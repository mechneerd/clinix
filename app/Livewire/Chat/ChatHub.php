<?php

namespace App\Livewire\Chat;

use Livewire\Component;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;

class ChatHub extends Component
{
    public $conversations = [];
    public $selectedConversation = null;
    public $messages = [];
    public $newMessage = '';
    public $search = '';

    public function mount($conversationId = null)
    {
        \Log::info('ChatHub Mounting', ['user_id' => Auth::id(), 'conversation_id' => $conversationId]);
        $this->loadConversations();
        if ($conversationId) {
            $this->selectConversation($conversationId);
        }
    }

    public function getListeners()
    {
        $userId = Auth::id();
        return [
            "echo-private:App.Models.User.{$userId},.MessageSent" => 'handleNewMessage',
            "echo-private:App.Models.User.{$userId},.SystemAlertSent" => 'loadConversations',
        ];
    }

    public function loadConversations()
    {
        $this->conversations = Auth::user()->conversations()
            ->with(['users', 'messages' => function($q) { $q->latest()->limit(1); }])
            ->orderByDesc('last_message_at')
            ->get();
    }

    public function selectConversation($id)
    {
        \Log::info('Selecting Conversation', ['id' => $id]);
        $this->selectedConversation = Conversation::with('users')->find($id);
        $this->loadMessages();
        
        // Mark as read
        $this->selectedConversation->messages()
            ->where('sender_id', '!=', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function loadMessages()
    {
        if ($this->selectedConversation) {
            $this->messages = $this->selectedConversation->messages()
                ->with('sender')
                ->oldest()
                ->get()
                ->toArray();
            
            $this->dispatch('chat-scrolled-to-bottom');
        }
    }

    public function sendMessage()
    {
        \Log::info('sendMessage called', ['body' => $this->newMessage, 'has_conv' => (bool)$this->selectedConversation]);
        if (trim($this->newMessage) == '' || !$this->selectedConversation) {
            \Log::warning('sendMessage validation failed');
            return;
        }

        $msg = $this->selectedConversation->messages()->create([
            'sender_id' => Auth::id(),
            'body' => $this->newMessage,
            'type' => 'text'
        ]);

        $this->selectedConversation->update(['last_message_at' => now()]);
        
        \Log::info('Attempting to broadcast message', ['message_id' => $msg->id]);
        broadcast(new MessageSent($msg))->toOthers();

        $this->newMessage = '';
        $this->loadMessages();
        $this->loadConversations();
    }

    public function handleNewMessage($event)
    {
        \Log::info('ChatHub received event', ['event' => $event]);
        
        if ($this->selectedConversation && $event['message']['conversation_id'] == $this->selectedConversation->id) {
            $this->loadMessages();
            
            // Mark as read if it's the current conversation
            Message::find($event['message']['id'])->update(['read_at' => now()]);
        } else {
            $this->loadConversations();
        }
    }

    public function render()
    {
        return view('livewire.chat.chat-hub');
    }
}
