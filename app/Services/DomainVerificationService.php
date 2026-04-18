<?php

namespace App\Services;

use App\Models\TenantDomain;

class DomainVerificationService
{
    /**
     * Verify a custom domain via DNS records.
     * We support two methods:
     * 1. CNAME record for subdomains (e.g. donasi.yayasan.com -> fundrize.com)
     * 2. A record for root domains (e.g. yayasan.com -> SERVER_IP)
     *
     * @param TenantDomain $domain
     * @return array
     */
    public function verify(TenantDomain $domain): array
    {
        if ($domain->type !== 'custom') {
            return ['success' => false, 'message' => 'Hanya custom domain yang memerlukan verifikasi DNS.'];
        }

        $hostName = $domain->domain;
        
        // CNAME target is ideally what we configured, otherwise fallback to our config or base domain
        $expectedCname = $domain->dns_target ?? config('tenancy.base_domain'); 
        
        // For testing locally without real DNS, bypass or mock
        if (app()->environment('local', 'testing')) {
            // Uncomment to test local pass
            // $this->markVerified($domain);
            // return ['success' => true, 'message' => 'Simulated verification success.'];
        }

        // 1. Check CNAME record
        if ($this->checkCname($hostName, $expectedCname)) {
            $this->markVerified($domain);
            return ['success' => true, 'message' => 'Domain terverifikasi via CNAME record.'];
        }

        // 2. Fallback check A record for naked domains
        $expectedIp = env('SERVER_IP'); // Add this to production .env 
        if ($expectedIp && $this->checkARecord($hostName, $expectedIp)) {
            $this->markVerified($domain);
            return ['success' => true, 'message' => 'Domain terverifikasi via A record.'];
        }

        // Verification failed
        $domain->update(['last_checked_at' => now()]);

        return [
            'success' => false,
            'message' => 'DNS Record belum ditemukan. Pastikan Anda telah mengatur CNAME atau A record dengan benar. Propagasi DNS bisa membutuhkan waktu 1-48 jam.'
        ];
    }

    protected function checkCname(string $domain, string $expectedTarget): bool
    {
        try {
            $records = dns_get_record($domain, DNS_CNAME);
            if (!$records) {
                return false;
            }

            foreach ($records as $record) {
                if (isset($record['target']) && str_ends_with(strtolower($record['target']), strtolower($expectedTarget))) {
                    return true;
                }
            }
        } catch (\Exception $e) {
            // DNS lookup failed
            return false;
        }

        return false;
    }

    protected function checkARecord(string $domain, string $expectedIp): bool
    {
        try {
            $records = dns_get_record($domain, DNS_A);
            if (!$records) {
                return false;
            }

            foreach ($records as $record) {
                if (isset($record['ip']) && $record['ip'] === $expectedIp) {
                    return true;
                }
            }
        } catch (\Exception $e) {
            return false;
        }

        return false;
    }

    protected function markVerified(TenantDomain $domain): void
    {
        $domain->update([
            'dns_verified' => true,
            'verified_at' => now(),
            'last_checked_at' => now(),
            'ssl_status' => 'pending', // Triggers SSL provisioning if we build that later
        ]);
    }
}
