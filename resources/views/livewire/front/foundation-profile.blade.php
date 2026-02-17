<div>
    <x-page-header title="Profil Yayasan" :showBack="true" backUrl="{{ route('profile.index') }}" />

    <main id="main-content" class="pb-20">
        <section id="foundation-header" class="bg-white px-4 py-6">
            <div class="text-center">
                <div class="w-20 h-20 mx-auto rounded-full overflow-hidden bg-gray-200 mb-4">
                    <img src="{{ $foundation->logo }}" alt="Foundation Logo" class="w-full h-full object-cover">
                </div>
                <h2 class="text-xl font-bold text-dark mb-2">{{ $foundation->name }}</h2>
                <p class="text-sm text-gray-600">{{ $foundation->tagline }}</p>
            </div>
        </section>

        <section id="about-foundation" class="bg-white px-4 py-6 mt-2">
            <h3 class="text-sm font-bold text-dark mb-3">Tentang Kami</h3>
            <div class="text-sm text-gray-700 leading-relaxed mb-4 rich-text-content">
                {!! $foundation->about !!}
            </div>
        </section>

        <section id="vision-mission" class="bg-white px-4 py-6 mt-2">
            <h3 class="text-sm font-bold text-dark mb-4">Visi &amp; Misi</h3>
            <div class="space-y-4">
                <div>
                    <h4 class="text-sm font-semibold text-primary mb-2">Visi</h4>
                    <div class="text-sm text-gray-700 leading-relaxed rich-text-content">
                        {!! $foundation->vision !!}
                    </div>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-primary mb-2">Misi</h4>
                    <ul class="text-sm text-gray-700 space-y-1">
                        @php
                            $missions = is_string($foundation->mission) ? json_decode($foundation->mission, true) : $foundation->mission;
                            $missions = is_array($missions) ? $missions : [];
                        @endphp
                        @foreach($missions as $mission)
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-check text-primary text-xs mt-0.5"></i>
                            <span>{{ $mission }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </section>

        <section id="program-focus" class="bg-white px-4 py-6 mt-2">
            <h3 class="text-sm font-bold text-dark mb-4">Fokus Program</h3>
            <div class="grid grid-cols-2 gap-3">
                @php
                    $icons = [
                        'Pendidikan' => 'fa-graduation-cap',
                        'Kesehatan' => 'fa-heart-pulse',
                        'Dakwah' => 'fa-mosque',
                        'Kemanusiaan' => 'fa-hand-holding-heart',
                    ];
                @endphp
                @foreach($foundation->focus_areas ?? [] as $area)
                <div class="p-3 border border-gray-200 rounded-lg text-center">
                    <div class="w-10 h-10 bg-primary/10 rounded-lg mx-auto mb-2 flex items-center justify-center">
                        <i class="fa-solid {{ $icons[$area] ?? 'fa-star' }} text-primary"></i>
                    </div>
                    <div class="text-xs font-medium text-dark">{{ $area }}</div>
                </div>
                @endforeach
            </div>
        </section>

        <section id="contact-info" class="bg-white px-4 py-6 mt-2">
            <h3 class="text-sm font-bold text-dark mb-4">Kontak &amp; Alamat</h3>
            <div class="space-y-3">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="fa-solid fa-map-marker-alt text-gray-600 text-sm"></i>
                    </div>
                    <div>
                        <div class="text-xs text-gray-600 mb-1">Alamat</div>
                        <div class="text-sm text-dark">{{ $foundation->address }}</div>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="fa-solid fa-phone text-gray-600 text-sm"></i>
                    </div>
                    <div>
                        <div class="text-xs text-gray-600 mb-1">Telepon</div>
                        <div class="text-sm text-dark">{{ $foundation->phone }}</div>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="fa-solid fa-envelope text-gray-600 text-sm"></i>
                    </div>
                    <div>
                        <div class="text-xs text-gray-600 mb-1">Email</div>
                        <div class="text-sm text-dark">{{ $foundation->email }}</div>
                    </div>
                </div>
            </div>
        </section>

        @if($foundation->social_media)
        <section id="social-media" class="bg-white px-4 py-6 mt-2 mb-4">
            <h3 class="text-sm font-bold text-dark mb-4">Media Sosial</h3>
            <div class="flex gap-3">
                @if(isset($foundation->social_media['facebook']))
                <a href="{{ $foundation->social_media['facebook'] }}" class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center hover:opacity-90 transition-opacity">
                    <i class="fa-brands fa-facebook text-white text-lg"></i>
                </a>
                @endif
                @if(isset($foundation->social_media['instagram']))
                <a href="{{ $foundation->social_media['instagram'] }}" class="w-12 h-12 bg-pink-500 rounded-lg flex items-center justify-center hover:opacity-90 transition-opacity">
                    <i class="fa-brands fa-instagram text-white text-lg"></i>
                </a>
                @endif
                @if(isset($foundation->social_media['whatsapp']))
                <a href="{{ $foundation->social_media['whatsapp'] }}" class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center hover:opacity-90 transition-opacity">
                    <i class="fa-brands fa-whatsapp text-white text-lg"></i>
                </a>
                @endif
                @if(isset($foundation->social_media['youtube']))
                <a href="{{ $foundation->social_media['youtube'] }}" class="w-12 h-12 bg-red-600 rounded-lg flex items-center justify-center hover:opacity-90 transition-opacity">
                    <i class="fa-brands fa-youtube text-white text-lg"></i>
                </a>
                @endif
            </div>
        </section>
        @endif
    </main>
</div>
