<?php

namespace App\Livewire\Admin;

use App\Models\AdminNotification;
use Livewire\Component;

class AdminNotificationBell extends Component
{
    public $showDropdown = false;
    public $unreadCount = 0;
    public $notifications = [];
    public $lastCheckedId = 0;

    public function mount()
    {
        $this->unreadCount = AdminNotification::where('is_read', false)->count();
        $this->notifications = AdminNotification::latest()->take(10)->get()->toArray();
        $this->lastCheckedId = AdminNotification::max('id') ?? 0;
    }

    /**
     * Called every 3 seconds by Livewire polling.
     * Checks for new notifications and dispatches sound events.
     */
    public function checkNewNotifications()
    {
        $newNotifications = AdminNotification::where('id', '>', $this->lastCheckedId)
            ->orderBy('id', 'asc')
            ->get();

        if ($newNotifications->isNotEmpty()) {
            foreach ($newNotifications as $notification) {
                // Determine which sound to play
                $soundType = $notification->type === 'new_transaction' ? '1' : '2';
                $this->dispatch('play-notification-sound', sound: $soundType);
            }

            $this->lastCheckedId = $newNotifications->last()->id;
        }

        // Refresh counts and list
        $this->unreadCount = AdminNotification::where('is_read', false)->count();
        $this->notifications = AdminNotification::latest()->take(10)->get()->toArray();
    }

    public function toggleDropdown()
    {
        $this->showDropdown = !$this->showDropdown;
    }

    public function markAsRead($id)
    {
        AdminNotification::where('id', $id)->update(['is_read' => true]);
        $this->unreadCount = AdminNotification::where('is_read', false)->count();
        $this->notifications = AdminNotification::latest()->take(10)->get()->toArray();
    }

    public function markAllAsRead()
    {
        AdminNotification::where('is_read', false)->update(['is_read' => true]);
        $this->unreadCount = 0;
        $this->notifications = AdminNotification::latest()->take(10)->get()->toArray();
    }

    public function render()
    {
        return view('livewire.admin.admin-notification-bell');
    }
}
