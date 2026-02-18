@section('title', 'Manajemen Pengguna')
@section('header', 'Data Pengguna')

<div class="space-y-6">
    <div class="bg-white overflow-hidden shadow-soft rounded-2xl border border-gray-100">
        <div class="p-6">
            <!-- Top Controls -->
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-6">
                <div class="relative w-full sm:w-1/3 group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-search text-gray-400 group-focus-within:text-primary transition-colors"></i>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama, email..." class="pl-10 w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-primary focus:ring focus:ring-primary/20 transition-all duration-200 py-2.5 px-4 text-base">
                </div>
                
                <div class="w-full sm:w-auto flex gap-2">
                    <select wire:model.live="roleFilter" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-primary focus:ring focus:ring-primary/20 cursor-pointer py-2.5 px-4 text-base">
                        <option value="">Semua Role</option>
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>
                    
                    <button wire:click="createUser" class="bg-primary hover:bg-primary-hover text-white font-semibold py-2.5 px-4 rounded-xl inline-flex items-center justify-center transition-all duration-200 shadow-lg shadow-primary/30 hover:shadow-primary/50 hover:-translate-y-0.5 whitespace-nowrap">
                        <i class="fa-solid fa-plus mr-2"></i> Tambah User
                    </button>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-hidden rounded-xl border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50/80">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">User</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kontak</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Bergabung</th>
                                <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($users as $user)
                            <tr class="hover:bg-orange-50/30 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-primary text-white flex items-center justify-center text-sm font-bold border border-gray-100 shadow-sm overflow-hidden">
                                                @if($user->avatar)
                                                    <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->initials }}" class="w-full h-full object-cover">
                                                @else
                                                    {{ $user->initials }}
                                                @endif
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-semibold text-gray-900">{{ $user->name }}</div>
                                            <div class="text-xs text-gray-400">ID: #{{ $user->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $user->email }}</div>
                                    <div class="text-xs text-gray-500">{{ $user->phone ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->role === 'admin' ? 'bg-purple-50 text-purple-700 border border-purple-100' : 'bg-gray-50 text-gray-600 border border-gray-100' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $user->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <button wire:click="editUser({{ $user->id }})" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <button wire:click="toggleRole({{ $user->id }})" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Ganti Role">
                                            <i class="fa-solid fa-user-shield"></i>
                                        </button>
                                        <button wire:click="showDetail({{ $user->id }})" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Detail">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        @if(auth()->id() !== $user->id)
                                        <button wire:click="deleteUser({{ $user->id }})" onclick="return confirm('Yakin hapus user ini?') || event.stopImmediatePropagation()" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4 text-gray-400 text-2xl">
                                            <i class="fa-solid fa-users-slash"></i>
                                        </div>
                                        <p class="text-lg font-medium text-gray-900">Belum ada pengguna</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    <!-- Form Modal -->
    <div x-data="{ show: $wire.entangle('isFormOpen') }"
         x-show="show"
         x-transition:enter="transition ease-out duration-300"
         class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="show = false"></div>
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
                <form wire:submit.prevent="saveUser">
                    <div class="bg-white px-6 pt-6 pb-4 sm:p-8">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-xl font-bold text-gray-900">{{ $userId ? 'Edit Pengguna' : 'Tambah Pengguna' }}</h3>
                            <button type="button" wire:click="closeFormModal" class="text-gray-400 hover:text-gray-500"><i class="fa-solid fa-xmark text-xl"></i></button>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                                <input wire:model="name" type="text" class="w-full rounded-xl border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-sm bg-gray-50 focus:bg-white transition-colors" placeholder="Masukkan nama lengkap">
                                @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                                <input wire:model="email" type="email" class="w-full rounded-xl border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-sm bg-gray-50 focus:bg-white transition-colors" placeholder="email@contoh.com">
                                @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">No. Telepon <span class="text-gray-400 font-normal">(Opsional)</span></label>
                                <input wire:model="phone" type="text" class="w-full rounded-xl border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-sm bg-gray-50 focus:bg-white transition-colors" placeholder="08xxxxxxxxxx">
                                @error('phone') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Role</label>
                                <select wire:model="role" class="w-full rounded-xl border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-sm bg-gray-50 focus:bg-white transition-colors">
                                    <option value="user">User</option>
                                    <option value="admin">Admin</option>
                                </select>
                                @error('role') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="border-t border-gray-100 pt-4 mt-4">
                                <h4 class="text-sm font-bold text-gray-900 mb-3">Keamanan</h4>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">Password {{ $userId ? '(Opsional)' : '' }}</label>
                                        <input wire:model="password" type="password" class="w-full rounded-xl border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-sm bg-gray-50 focus:bg-white transition-colors" placeholder="{{ $userId ? 'Kosongkan jika tidak ingin mengubah' : 'Minimal 8 karakter' }}">
                                        @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">Konfirmasi Password</label>
                                        <input wire:model="password_confirmation" type="password" class="w-full rounded-xl border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-sm bg-gray-50 focus:bg-white transition-colors" placeholder="Ulangi password">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 sm:flex sm:flex-row-reverse border-t border-gray-100 rounded-b-2xl gap-3">
                        <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-lg shadow-primary/30 px-5 py-2.5 bg-primary text-base font-semibold text-white hover:bg-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:w-auto sm:text-sm transition-all hover:-translate-y-0.5">
                            Simpan
                        </button>
                        <button type="button" wire:click="closeFormModal" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-5 py-2.5 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:mt-0 sm:w-auto sm:text-sm transition-all">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Detail Modal -->
    <div x-data="{ show: $wire.entangle('isOpen') }"
         x-show="show"
         x-transition:enter="transition ease-out duration-300"
         class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="show = false"></div>
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
                @if($selectedUser)
                <div class="bg-white px-6 pt-6 pb-4 sm:p-8">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-gray-900">Detail Pengguna</h3>
                        <button type="button" wire:click="closeModal" class="text-gray-400 hover:text-gray-500"><i class="fa-solid fa-xmark text-xl"></i></button>
                    </div>
                    
                    <div class="flex items-center mb-8">
                        <div class="h-20 w-20 rounded-full bg-primary text-white flex items-center justify-center text-xl font-bold border-2 border-white shadow-md overflow-hidden mr-5">
                            @if($selectedUser->avatar)
                                <img src="{{ Storage::url($selectedUser->avatar) }}" alt="{{ $selectedUser->initials }}" class="w-full h-full object-cover">
                            @else
                                {{ $selectedUser->initials }}
                            @endif
                        </div>
                        <div>
                            <h4 class="text-2xl font-bold text-gray-900">{{ $selectedUser->name }}</h4>
                            <p class="text-gray-500 mb-1">{{ $selectedUser->email }}</p>
                            <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full {{ $selectedUser->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($selectedUser->role) }}
                            </span>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 mb-6">
                        <div class="grid grid-cols-2 gap-6 text-sm">
                            <div>
                                <p class="text-xs text-gray-400 uppercase font-bold mb-1">Telepon</p>
                                <p class="font-medium text-gray-900">{{ $selectedUser->phone ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 uppercase font-bold mb-1">Bergabung</p>
                                <p class="font-medium text-gray-900">{{ $selectedUser->created_at->format('d M Y') }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 uppercase font-bold mb-1">Status Email</p>
                                <p class="font-medium {{ $selectedUser->email_verified_at ? 'text-green-600' : 'text-yellow-600' }}">
                                    {{ $selectedUser->email_verified_at ? 'Terverifikasi' : 'Belum Verifikasi' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-sm font-bold text-gray-900 mb-3">Transaksi Terakhir</h4>
                        <div class="border border-gray-100 rounded-xl overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-100">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500">Tanggal</th>
                                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500">Tipe</th>
                                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500">Nominal</th>
                                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @forelse($selectedUser->payments ?? [] as $payment)
                                    <tr>
                                        <td class="px-4 py-2 text-xs text-gray-500">{{ $payment->created_at->format('d M Y') }}</td>
                                        <td class="px-4 py-2 text-xs text-gray-500 capitalize">{{ str_replace('_', ' ', $payment->transaction_type) }}</td>
                                        <td class="px-4 py-2 text-xs font-medium text-gray-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                        <td class="px-4 py-2 text-xs">
                                            <span class="px-1.5 py-0.5 rounded-md text-[10px] font-bold uppercase {{ $payment->status == 'paid' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">{{ $payment->status }}</span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-4 text-center text-xs text-gray-400 italic">Belum ada riwayat transaksi.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 sm:flex sm:flex-row-reverse border-t border-gray-100 rounded-b-2xl">
                    <button wire:click="closeModal" type="button" class="w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-5 py-2.5 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:ml-3 sm:w-auto sm:text-sm transition-all">
                        Tutup
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
