<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserList extends Component
{
    use WithPagination;

    public $search = '';
    public $roleFilter = '';
    public $perPage = 10;

    // Detail Modal
    public $selectedUser = null;
    public $isOpen = false;

    // Form Modal
    public $isFormOpen = false;
    public $userId = null;
    public $name, $email, $phone, $role = 'user', $password, $password_confirmation;

    protected $queryString = [
        'search' => ['except' => ''],
        'roleFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $users = User::where(function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('phone', 'like', '%' . $this->search . '%');
            })
            ->when($this->roleFilter, function($query) {
                $query->where('role', $this->roleFilter);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        $tenant = app('current_tenant');
        $canCreateUser = $tenant ? $tenant->canCreateMore('users') : true;
        $userQuota = $tenant ? $tenant->getRemainingQuota('users') : 99;
        $maxUsers = $tenant ? $tenant->getLimit('max_users', 99) : 99;

        return view('livewire.admin.user-list', [
            'users' => $users,
            'canCreateUser' => $canCreateUser,
            'userQuota' => $userQuota,
            'maxUsers' => $maxUsers,
        ])->layout('layouts.admin');
    }

    // Detail Methods
    public function showDetail($id)
    {
        $this->selectedUser = User::with(['payments' => function($query) {
            $query->latest()->take(5);
        }])->find($id);
        
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->selectedUser = null;
    }

    // Form Methods
    public function createUser()
    {
        $tenant = app('current_tenant');
        if ($tenant && !$tenant->canCreateMore('users')) {
            session()->flash('error', 'Batas pengguna untuk paket ' . $tenant->getPlanName() . ' telah tercapai (Maks: ' . $tenant->getLimit('max_users', 2) . '). Silakan upgrade paket Anda.');
            return;
        }

        $this->resetForm();
        $this->isFormOpen = true;
    }

    public function editUser($id)
    {
        $this->resetForm();
        $user = User::findOrFail($id);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->role = $user->role;
        $this->isFormOpen = true;
    }

    public function saveUser()
    {
        $rules = [
            'name' => 'required|min:3',
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->userId)],
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:admin,user',
        ];

        // Password required only for new users
        if (!$this->userId) {
            $rules['password'] = 'required|min:8|confirmed';
        } else {
            $rules['password'] = 'nullable|min:8|confirmed';
        }

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'role' => $this->role,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        if ($this->userId) {
            $user = User::findOrFail($this->userId);
            
            // Prevent changing own role via edit form
            if (auth()->id() == $user->id && $this->role !== auth()->user()->role) {
                $data['role'] = auth()->user()->role; // Revert role change
                session()->flash('error', 'Anda tidak dapat mengubah role akun sendiri.');
            }

            $user->update($data);
            session()->flash('success', 'Data pengguna berhasil diperbarui.');
        } else {
            // Enforce max_users limit on new users
            $tenant = app('current_tenant');
            if ($tenant && !$tenant->canCreateMore('users')) {
                session()->flash('error', 'Batas pengguna untuk paket ' . $tenant->getPlanName() . ' telah tercapai.');
                $this->isFormOpen = false;
                return;
            }

            $data['email_verified_at'] = now(); // Auto verify admin-created users
            User::create($data);
            session()->flash('success', 'Pengguna baru berhasil ditambahkan.');
        }

        $this->isFormOpen = false;
        $this->resetForm();
    }

    public function closeFormModal()
    {
        $this->isFormOpen = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->userId = null;
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->role = 'user';
        $this->password = '';
        $this->password_confirmation = '';
        $this->resetValidation();
    }

    // Action Methods
    public function toggleRole($id)
    {
        $user = User::findOrFail($id);
        
        if (auth()->id() == $user->id) {
            session()->flash('error', 'Anda tidak dapat mengubah role akun sendiri.');
            return;
        }

        $user->role = $user->role === 'admin' ? 'user' : 'admin';
        $user->save();
        
        session()->flash('success', "Role pengguna {$user->name} berhasil diubah menjadi {$user->role}.");
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        if (auth()->id() == $user->id) {
            session()->flash('error', 'Anda tidak dapat menghapus akun sendiri.');
            return;
        }

        try {
            $user->delete();
            session()->flash('success', 'Pengguna berhasil dihapus.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus pengguna. Kemungkinan ada data transaksi terkait.');
        }
    }
}
