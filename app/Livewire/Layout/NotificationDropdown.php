<?php

namespace App\Livewire\Layout;

use Livewire\Component;

use Illuminate\Support\Facades\Auth;
use App\Models\User;

class NotificationDropdown extends Component
{
    public $notifications = [];
    public $unreadCount = 0;

    public function mount()
    {
        $this->loadNotifications();
    }

    public function getListeners()
    {
        $userId = Auth::id();
        return [
            "echo-private:App.Models.User.{$userId},.SystemAlertSent" => 'handleNewNotification',
        ];
    }

    public function loadNotifications()
    {
        $user = Auth::user();
        if ($user) {
            $this->notifications = $user->unreadNotifications()->take(5)->get()->toArray();
            $this->unreadCount = $user->unreadNotifications()->count();
        }
    }

    public function handleNewNotification($event)
    {
        // The notification will be persisted in database via Notification class,
        // but we trigger a refresh here to show the new one.
        $this->loadNotifications();
        
        // Show toast via Alpine
        $this->dispatch('show-toast', [
            'title' => $event['title'],
            'message' => $event['message'],
            'type' => $event['type'] ?? 'info'
        ]);
    }

    public function markAsRead($notificationId)
    {
        $notification = Auth::user()->notifications()->find($notificationId);
        if ($notification) {
            $notification->markAsRead();
            $this->loadNotifications();
        }
    }

    public function render()
    {
        return view('livewire.layout.notification-dropdown');
    }
}
