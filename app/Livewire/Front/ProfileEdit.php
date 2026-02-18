<?php

namespace App\Livewire\Front;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class ProfileEdit extends Component
{
    use WithFileUploads;

    public $name;
    public $phone;
    public $email;
    public $avatar; // Current avatar URL
    public $photo; // New uploaded photo

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->phone = $user->phone;
        $this->email = $user->email;
        $this->avatar = $user->avatar;
    }

    public function updatedPhoto()
    {
        $this->validate([
            'photo' => 'image|max:1024', // 1MB Max
        ]);
    }

    public function save()
    {
        $user = Auth::user();

        $this->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'photo' => 'nullable|image|max:1024',
        ]);

        $data = [
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
        ];

        if ($this->photo) {
            // Store in public/avatars
            $path = $this->photo->store('avatars', 'public');
            $data['avatar'] = $path; // Store relative path, not URL
        }

        $user->update($data);

        session()->flash('success', 'Profil berhasil diperbarui');
        return redirect()->route('profile.index');
    }

    #[Layout('layouts.front')]
    #[Title('Edit Profil')]
    public function render()
    {
        return view('livewire.front.profile-edit');
    }
}
