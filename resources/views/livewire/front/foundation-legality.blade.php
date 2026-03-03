<div>
    <x-page-header title="Legalitas Yayasan" :showBack="true" backUrl="{{ route('profile.index') }}" />

    <main id="main-content" class="pb-6">
        <section id="intro-section" class="bg-white px-4 py-5">
            <div class="flex items-start gap-3">
                <div class="flex-1">
                    <h2 class="text-base font-bold text-dark mb-1">Dokumen Legalitas</h2>
                    <p class="text-sm text-gray-600 leading-relaxed">Berikut adalah dokumen resmi yang menunjukkan legalitas dan kredibilitas {{ $foundation->name ?? 'yayasan kami' }}.</p>
                </div>
            </div>
        </section>

        <section id="legal-documents" class="mt-2 bg-white px-4 py-4">
            <div class="space-y-3">
                @forelse($documents as $doc)
                <div class="border border-gray-200 rounded-xl p-4">
                    <div class="flex items-start gap-3">
                        <div class="w-12 h-12 bg-orange-50 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-file-lines text-primary text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-sm font-bold text-dark mb-1">{{ $doc->title }}</h3>
                            <p class="text-xs text-gray-600 mb-1">{{ $doc->document_number }}</p>
                            @if($doc->issuing_authority)
                                <p class="text-xs text-gray-500 mb-1">Penerbit: {{ $doc->issuing_authority }}</p>
                            @endif
                            @if($doc->expiry_date)
                                <p class="text-xs text-gray-500 mb-1">Berlaku s.d: {{ $doc->expiry_date->format('d M Y') }}</p>
                            @endif
                            <div class="flex items-center gap-2 mt-1">
                                <span class="inline-flex items-center gap-1 text-xs text-green-700 bg-green-50 px-2 py-1 rounded-full">
                                    <i class="fa-solid fa-circle-check text-[10px]"></i>
                                    {{ $doc->status }}
                                </span>
                            </div>
                        </div>
                        @if($doc->file_url)
                        <a href="{{ $doc->file_url }}" target="_blank"
                            class="w-9 h-9 flex items-center justify-center bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <i class="fa-solid fa-eye text-gray-600"></i>
                        </a>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fa-regular fa-file-lines text-gray-400 text-2xl"></i>
                    </div>
                    <p class="text-sm text-gray-500">Belum ada dokumen legalitas.</p>
                </div>
                @endforelse
            </div>
        </section>

        @if($documents->count() > 0)
        <section id="verification-info" class="mt-2 bg-white px-4 py-5">
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
                <div class="flex items-start gap-3">
                    <i class="fa-solid fa-circle-info text-blue-600 text-lg mt-0.5"></i>
                    <div class="flex-1">
                        <h3 class="text-sm font-semibold text-dark mb-1">Verifikasi Dokumen</h3>
                        <p class="text-xs text-gray-700 leading-relaxed">Semua dokumen legalitas telah diverifikasi dan terdaftar resmi pada instansi berwenang. Anda dapat mengunduh dokumen untuk keperluan verifikasi independen.</p>
                    </div>
                </div>
            </div>
        </section>
        @endif

        @if($foundation && $foundation->phone)
        <section id="contact-section" class="mt-2 bg-white px-4 py-5">
            <h3 class="text-sm font-bold text-dark mb-3">Butuh Informasi Lebih Lanjut?</h3>
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $foundation->phone) }}"
                target="_blank"
                class="w-full flex items-center justify-center gap-2 bg-green-600 text-white py-3 rounded-xl font-semibold hover:bg-green-700 transition-colors shadow-lg active:scale-[0.98] transition-transform">
                <i class="fa-brands fa-whatsapp text-lg"></i>
                <span class="text-sm">Hubungi Tim Legal Kami</span>
            </a>
        </section>
        @endif
    </main>
</div>
