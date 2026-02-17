@section('title', 'Profile')
@section('header', 'Profile Saya')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Profile Card -->
    <div class="lg:col-span-1">
        <div class="bg-white overflow-hidden shadow-soft rounded-2xl border border-gray-100 p-6 text-center">
            <div class="relative inline-block mb-4">
                <img class="h-32 w-32 rounded-full object-cover border-4 border-gray-50 shadow-md mx-auto" 
                     src="{{ $newAvatar ? $newAvatar->temporaryUrl() : ($avatar ? Storage::url($avatar) : 'https://ui-avatars.com/api/?name='.urlencode($name).'&background=random') }}" 
                     alt="{{ $name }}">
                
                <label for="avatar-upload" class="absolute bottom-0 right-0 bg-primary hover:bg-primary-hover text-white p-2 rounded-full cursor-pointer shadow-lg transition-colors duration-200">
                    <i class="fa-solid fa-camera"></i>
                    <input type="file" wire:model="newAvatar" id="avatar-upload" class="hidden" accept="image/*">
                </label>
            </div>

            <h3 class="text-xl font-bold text-gray-900">{{ $name }}</h3>
            <p class="text-gray-500 text-sm mb-3">{{ $email }}</p>
            
            <div class="flex justify-center gap-2 mb-4">
                <span class="px-3 py-1 bg-purple-100 text-purple-700 text-xs font-semibold rounded-full uppercase tracking-wide">
                    {{ auth()->user()->role }}
                </span>
            </div>

            <div class="border-t border-gray-100 pt-4 text-left">
                <div class="flex items-center justify-between text-sm py-2">
                    <span class="text-gray-500">Bergabung</span>
                    <span class="font-medium text-gray-900">{{ auth()->user()->created_at->format('d M Y') }}</span>
                </div>
                <div class="flex items-center justify-between text-sm py-2">
                    <span class="text-gray-500">Status Email</span>
                    <span class="font-medium {{ auth()->user()->email_verified_at ? 'text-green-600' : 'text-yellow-600' }}">
                        {{ auth()->user()->email_verified_at ? 'Terverifikasi' : 'Belum Verifikasi' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Forms -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Edit Profile Info -->
        <div class="bg-white overflow-hidden shadow-soft rounded-2xl border border-gray-100 p-6">
            <h4 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">Informasi Pribadi</h4>
            
            @if (session('success'))
                <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-4 rounded-r shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fa-solid fa-circle-check text-green-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <form wire:submit.prevent="updateProfile" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                        <input wire:model="name" type="text" class="w-full rounded-xl border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-sm transition-shadow bg-gray-50 focus:bg-white transition-colors">
                        @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">No. Telepon</label>
                        <input wire:model="phone" type="text" class="w-full rounded-xl border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-sm transition-shadow bg-gray-50 focus:bg-white transition-colors">
                        @error('phone') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                    <input wire:model="email" type="email" class="w-full rounded-xl border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-sm transition-shadow bg-gray-50 focus:bg-white transition-colors">
                    @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="pt-2 text-right">
                    <button type="submit" class="bg-primary hover:bg-primary-hover text-white font-semibold py-2.5 px-6 rounded-xl inline-flex items-center justify-center transition-all duration-200 shadow-lg shadow-primary/30 hover:shadow-primary/50 hover:-translate-y-0.5">
                        <i class="fa-solid fa-save mr-2"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <!-- Change Password -->
        <div class="bg-white overflow-hidden shadow-soft rounded-2xl border border-gray-100 p-6">
            <h4 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">Ubah Password</h4>

            @if (session('success_password'))
                <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-4 rounded-r shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fa-solid fa-circle-check text-green-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700 font-medium">{{ session('success_password') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <form wire:submit.prevent="updatePassword" class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Password Saat Ini</label>
                    <input wire:model="current_password" type="password" class="w-full rounded-xl border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-sm transition-shadow bg-gray-50 focus:bg-white transition-colors">
                    @error('current_password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Password Baru</label>
                        <input wire:model="password" type="password" class="w-full rounded-xl border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-sm transition-shadow bg-gray-50 focus:bg-white transition-colors">
                        @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Konfirmasi Password</label>
                        <input wire:model="password_confirmation" type="password" class="w-full rounded-xl border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-sm transition-shadow bg-gray-50 focus:bg-white transition-colors">
                    </div>
                </div>

                <div class="pt-2 text-right">
                    <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white font-semibold py-2.5 px-6 rounded-xl inline-flex items-center justify-center transition-all duration-200 shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                        <i class="fa-solid fa-key mr-2"></i> Ubah Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
