<div class="w-full max-w-md bg-white p-8 rounded-xl shadow-lg border border-slate-100">
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-slate-800">SuperAdmin Login</h2>
        <p class="text-slate-500 mt-2 text-sm">Masuk ke platform pengelolaan tenant.</p>
    </div>

    @if (session()->has('error'))
        <div class="bg-red-50 text-red-600 p-3 rounded mb-4 text-sm border border-red-100">
            {{ session('error') }}
        </div>
    @endif

    <form wire:submit="login" class="space-y-5">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
            <input type="email" wire:model="email" class="w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Password</label>
            <input type="password" wire:model="password" class="w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="w-full bg-indigo-600 text-white font-medium py-2.5 rounded-md hover:bg-indigo-700 transition duration-200">
            Masuk Sekarang
        </button>
    </form>
</div>
