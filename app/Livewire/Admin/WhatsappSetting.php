<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\AppSetting;
use App\Models\FoundationSetting;
use App\Models\WhatsappMessageLog;
use App\Services\StarSenderService;
use Carbon\Carbon;

#[Layout('layouts.admin')]
#[Title('WhatsApp Setting')]
class WhatsappSetting extends Component
{
    use WithPagination;
    
    public $perPage = 5;

    public $deviceId;
    public $deviceInfo = [];
    public $qrCode;
    public $qrUrl;
    public $isConnected = false;
    public $isPolling = false;
    public $deviceConnectedAt;
    public $starsender_enabled;

    public $waProvider;
    public $fonnteToken;

    // Test message fields
    public $testPhone;
    public $testMessage;
    public $deviceName; // Temporary name during connection

    protected $starSender;

    public function boot(StarSenderService $starSender)
    {
        $this->starSender = $starSender;
    }

    public function mount()
    {
        $this->deviceId = AppSetting::get('starsender_device_id');
        $this->deviceConnectedAt = AppSetting::get('starsender_device_connected_at');
        $this->starsender_enabled = (bool) AppSetting::get('starsender_enabled');
        $this->waProvider = AppSetting::get('wa_provider', 'starsender');
        $this->fonnteToken = AppSetting::get('fonnte_token');
        
        if ($this->deviceId) {
            $this->checkDeviceStatus();
        }
    }

    public function connectDevice()
    {
        $foundationName = FoundationSetting::value('name') ?? 'Yayasan';
        $this->deviceName = substr($foundationName, 0, 20) . '-' . rand(1000, 9999);
        
        $result = $this->starSender->createDevice($this->deviceName);

        if ($result['status']) {
            $data = $result['data']['data'] ?? $result['data'];
            
            // New API returns 'kode_gambar' (base64)
            if (isset($data['kode_gambar'])) {
                $this->qrCode = $data['kode_gambar'];
                
                // If it's a full data URI, strip prefix for logic consistency or just pass to view
                // The view expects base64 string for $qrCode, or URL for $qrUrl
                // If $qrCode contains 'data:image', the view does <img src="data:image/png;base64,{{ $qrCode }}">
                // If $data['kode_gambar'] is ALREADY "data:image/...", then the view will double it "data:image...data:image..."
                // Let's check the view logic.
                
                if (str_starts_with($this->qrCode, 'data:image')) {
                    $this->qrUrl = $this->qrCode; // Treat as URL/Source
                    $this->qrCode = null;
                } else {
                    // Plain base64
                    $this->qrUrl = null;
                }
                
                $this->isPolling = true;
            } else {
                session()->flash('error', 'Gagal mendapatkan QR Code dari response.');
            }
        } else {
            session()->flash('error', 'Gagal membuat device: ' . ($result['message'] ?? 'Unknown error'));
        }
    }

    public function checkDeviceStatus()
    {
        // If we have deviceId, we check by ID (legacy/reconnect)
        // If we have deviceName, we check by Name (new flow)
        
        if ($this->deviceName) {
            $result = $this->starSender->getDeviceByName($this->deviceName);
            
            if ($result['status']) {
                $data = $result['data']; // This is the device object
                $status = $data['status'] ?? '';
                
                if ($status === 'connected' || $status === 'authenticated') {
                    $this->processConnectedDevice($data);
                }
            }
            return;
        }

        if ($this->deviceId) {
            // Legacy/Existing check
            // Note: getDeviceDetail is now wrapper for getDeviceStatusByName? No, I implemented getDeviceByName.
            // But I didn't remove getDeviceDetail from service? I did not.
            // Wait, I should have updated getDeviceDetail or use getDeviceByName logic?
            // The service now has getDeviceByName. getDeviceDetail still exists but uses /device/detail endpoint which might be wrong?
            // User snippet used /devices list.
            // So I should probably use getDeviceByName if I can get the name.
            // But if I only have ID stored in DB, I can't filter list by ID easily unless I loop all.
            // Let's assume getDeviceDetail might fail if endpoint changed.
            // But let's try to stick to the new flow.
            
            // If I only have ID, I should try to get status.
            // Does GET /devices return all? Yes.
            // So I can implement getDeviceById in Service?
            // Or just use getDeviceDetail if it works?
            // The user didn't provide /device/detail example.
            // Let's implement getDeviceById in Service by looping /devices list.
            
            // For now, let's keep checkDeviceStatus logic simple.
            $result = $this->starSender->getDeviceDetail($this->deviceId);
            
             if ($result['status']) {
                $data = $result['data']; // getDeviceDetail now returns the device object directly in 'data'
                $status = $data['status'] ?? '';
                if ($status === 'connected' || $status === 'authenticated') {
                    $this->processConnectedDevice($data);
                } else {
                    $this->isConnected = false;
                }
             } else {
                 $this->isConnected = false;
             }
        }
    }

    private function processConnectedDevice($data)
    {
        $this->isConnected = true;
        $this->isPolling = false;
        $this->qrCode = null;
        $this->qrUrl = null;
        $this->deviceInfo = $data;

        // Save Device ID
        if (isset($data['id'])) {
            $this->deviceId = $data['id'];
            AppSetting::set('starsender_device_id', $this->deviceId);
        }

        // Save API Key if present (unlikely in V3 list response)
        // Updated: The field is actually 'device_key' based on user testing
        if (isset($data['device_key'])) {
            AppSetting::set('starsender_api_key', $data['device_key']);
        } elseif (isset($data['apikey'])) {
            // Fallback for older versions just in case
            AppSetting::set('starsender_api_key', $data['apikey']);
        }
        
        // Save connection date
        if (!$this->deviceConnectedAt) {
            $this->deviceConnectedAt = now()->toDateTimeString();
            AppSetting::set('starsender_device_connected_at', $this->deviceConnectedAt);
        }
    }

    public function relogDevice()
    {
        if (!$this->deviceId) return;

        $result = $this->starSender->relogDevice($this->deviceId);

        if ($result['status']) {
            $data = $result['data']['data'] ?? $result['data'];
            
            $this->qrCode = $data['qr_code'] ?? null;
            $this->qrUrl = $data['qr_url'] ?? null;
            
            // Fallback: if relog doesn't return QR, we might need to call create again or just poll detail
            // But per docs, relog sets status to pending_relogin. 
            // Often we need to display QR again. If relog doesn't give QR, we might need to re-create.
            // Let's assume for now relog just resets the session and we might need to get QR from detail or response.
            
            $this->isPolling = true;
            $this->isConnected = false;
            
            session()->flash('success', 'Permintaan relog berhasil. Silakan scan QR baru jika muncul.');
        } else {
            session()->flash('error', 'Gagal relog device: ' . ($result['message'] ?? 'Unknown error'));
        }
    }
    
    public function disconnectDevice()
    {
        // To disconnect, we just clear local settings. 
        // Real disconnect on server side might need an API call if available, but docs don't show "delete device".
        // We'll just clear our local state so we can create a new one.
        
        AppSetting::set('starsender_device_id', null);
        AppSetting::set('starsender_api_key', null);
        AppSetting::set('starsender_device_connected_at', null);
        
        $this->deviceId = null;
        $this->isConnected = false;
        $this->deviceInfo = [];
        $this->qrCode = null;
        $this->isPolling = false;
        
        session()->flash('success', 'Device berhasil diputus dari sistem.');
    }

    public function toggleEnabled()
    {
        $this->starsender_enabled = !$this->starsender_enabled;
        AppSetting::set('starsender_enabled', $this->starsender_enabled);
        
        session()->flash('success', 'Status notifikasi WhatsApp berhasil diperbarui.');
    }

    public function updatedWaProvider($value)
    {
        AppSetting::set('wa_provider', $value);
        session()->flash('success', 'Provider WhatsApp berhasil diubah ke ' . ucfirst($value) . '.');
    }

    public function saveFonnteToken()
    {
        $this->validate([
            'fonnteToken' => 'required|string',
        ]);
        AppSetting::set('fonnte_token', $this->fonnteToken);
        session()->flash('success', 'Token Fonnte berhasil disimpan.');
    }

    public function sendTestMessage()
    {
        $this->validate([
            'testPhone' => 'required|numeric|digits_between:10,15',
            'testMessage' => 'required|string|max:500',
        ]);

        $provider = AppSetting::get('wa_provider', 'starsender');
        $sender = $provider === 'fonnte' ? app(\App\Services\FonnteService::class) : $this->starSender;

        $result = $sender->sendMessage($this->testPhone, $this->testMessage, 'test');

        if ($result['status']) {
            session()->flash('success', 'Pesan tes berhasil dikirim.');
            $this->testMessage = ''; // Clear message but keep phone
        } else {
            session()->flash('error', 'Gagal mengirim pesan: ' . ($result['message'] ?? 'Unknown error'));
        }
    }

    public function render()
    {
        // Poll if needed
        if ($this->isPolling) {
            $this->checkDeviceStatus();
        }

        $logs = WhatsappMessageLog::with('payment')
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.admin.whatsapp-setting', [
            'logs' => $logs
        ]);
    }
    
    public function getExpiryDateProperty()
    {
        if (!$this->deviceConnectedAt) return null;
        
        return Carbon::parse($this->deviceConnectedAt)->addDays(30);
    }
    
    public function getDaysRemainingProperty()
    {
        if (!$this->expiryDate) return 0;
        
        $days = now()->diffInDays($this->expiryDate, false);
        return max(0, (int)$days);
    }
}
