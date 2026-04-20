<div wire:poll.3s="checkStatus" class="min-h-screen flex items-center justify-center px-6 py-20 bg-slate-50 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full -z-10">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-primary-100/30 rounded-full blur-[120px]"></div>
    </div>

    <div class="max-w-xl w-full bg-white rounded-[3rem] shadow-2xl shadow-slate-200 p-12 text-center border border-slate-100 relative">
        <div class="w-24 h-24 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-4xl mx-auto mb-8 animate-bounce">
            <i class="fas fa-check-circle"></i>
        </div>
        
        <h1 class="text-3xl font-extrabold text-slate-900 mb-4">{{ $name }}</h1>
        <p class="text-slate-600 mb-10 leading-relaxed">
            @if($status === 'active')
                Selamat! Dashboard digital untuk yayasan Anda sudah aktif. Anda akan segera diarahkan ke dashboard Anda.
            @else
                Dashboard digital untuk yayasan Anda sedang disiapkan. Kami sedang menunggu konfirmasi pembayaran dari sistem. 
                <strong>Halaman ini akan otomatis berpindah saat pembayaran terverifikasi.</strong>
            @endif
        </p>
        
        <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100 mb-10 text-left">
            <div class="flex items-center justify-between mb-4 pb-4 border-b border-slate-200">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Subdomain Anda</span>
                <span class="font-bold text-slate-900">{{ $domain }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Email Admin</span>
                <span class="font-bold text-slate-900">{{ $email }}</span>
            </div>
        </div>
        
        <div class="space-y-4">
            <a href="http://{{ $domain }}/admin/login" target="_blank" class="block w-full bg-primary-600 hover:bg-primary-700 text-white font-extrabold py-4 px-8 rounded-2xl shadow-xl shadow-primary-200 transition-all hover:-translate-y-1">
                Buka Dashboard Admin <i class="fas fa-external-link-alt ml-2"></i>
            </a>
            <a href="{{ route('central.landing') }}" class="block w-full py-4 text-slate-500 font-bold hover:text-slate-800 transition-colors">
                Kembali ke Beranda
            </a>
        </div>
        
        <div class="mt-12 p-4 bg-blue-50 rounded-xl flex gap-3 text-left">
            <i class="fas fa-info-circle text-blue-500 mt-1"></i>
            <p class="text-xs text-blue-700 leading-relaxed">
                Kami juga telah mengirimkan detail akses dan panduan penggunaan ke email <strong>{{ $email }}</strong>. Silakan periksa kotak masuk atau folder spam Anda.
            </p>
        </div>
    </div>
</div>
