<div class="flex h-[calc(100vh-8rem)] bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-2xl overflow-hidden border border-slate-200 dark:border-slate-800"
     x-data="{ mobileOpen: false }">
    
    <!-- Sidebar -->
    <div class="w-full md:w-80 border-r border-slate-100 dark:border-slate-800 flex flex-col"
         :class="{ 'hidden md:flex': selectedConversation && !mobileOpen }">
        
        <div class="p-6 border-b border-slate-100 dark:border-slate-800">
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-4">Messages</h2>
            <div class="relative">
                <input type="text" wire:model.live="search" placeholder="Search conversations..."
                       class="w-full pl-10 pr-4 py-3 bg-slate-50 dark:bg-slate-800/50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-brand-teal transition-all">
                <svg class="w-5 h-5 absolute left-3 top-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto p-4 space-y-2">
            @foreach($conversations as $conv)
                @php $otherUser = $conv->otherUser(auth()->user()); @endphp
                <button wire:click="selectConversation({{ $conv->id }})"
                        class="w-full p-4 rounded-3xl flex items-center gap-4 transition-all {{ $selectedConversation && $selectedConversation->id == $conv->id ? 'bg-brand-teal text-white shadow-lg shadow-brand-teal/20' : 'hover:bg-slate-50 dark:hover:bg-slate-800/50 text-slate-600 dark:text-slate-400' }}">
                    <div class="relative">
                        <img src="{{ $otherUser->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($otherUser->name).'&color=7F9CF5&background=EBF4FF' }}" 
                             class="w-12 h-12 rounded-2xl object-cover">
                        <span class="absolute -bottom-1 -right-1 block h-3 w-3 rounded-full bg-green-500 ring-2 ring-white dark:ring-slate-900"></span>
                    </div>
                    <div class="flex-1 text-left">
                        <div class="flex justify-between items-center">
                            <h4 class="font-bold truncate {{ $selectedConversation && $selectedConversation->id == $conv->id ? 'text-white' : 'text-slate-900 dark:text-white' }}">
                                @if($otherUser->user_type === 'clinic_admin' && $conv->clinic_id)
                                    {{ \App\Models\Clinic::find($conv->clinic_id)->name }} (Clinic)
                                @else
                                    {{ $otherUser->name }}
                                @endif
                            </h4>
                            <span class="text-[10px] opacity-70">{{ $conv->last_message_at ? \Carbon\Carbon::parse($conv->last_message_at)->format('H:i') : '' }}</span>
                        </div>
                        <p class="text-xs truncate opacity-80">{{ $conv->messages->first()->body ?? 'No messages yet' }}</p>
                    </div>
                </button>
            @endforeach
        </div>
    </div>

    <!-- Chat Area -->
    <div class="flex-1 flex flex-col relative bg-slate-50/50 dark:bg-slate-900/50"
         :class="{ 'hidden md:flex': !selectedConversation && !mobileOpen }">
        
        @if($selectedConversation)
            @php $otherUser = $selectedConversation->otherUser(auth()->user()); @endphp
            <!-- Chat Header -->
            <div class="p-6 bg-white dark:bg-slate-900 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <button @click="mobileOpen = true" class="md:hidden text-slate-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <div class="relative">
                        <img src="{{ $otherUser->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($otherUser->name).'&color=7F9CF5&background=EBF4FF' }}" 
                             class="w-10 h-10 rounded-xl object-cover">
                        <span class="absolute -bottom-1 -right-1 block h-2.5 w-2.5 rounded-full bg-green-500 ring-2 ring-white dark:ring-slate-900"></span>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 dark:text-white">
                            @if($otherUser->user_type === 'clinic_admin' && $selectedConversation->clinic_id)
                                {{ \App\Models\Clinic::find($selectedConversation->clinic_id)->name }} (Clinic)
                            @else
                                {{ $otherUser->name }}
                            @endif
                        </h3>
                        <p class="text-[10px] text-green-500 font-medium uppercase tracking-wider">Online</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button class="p-2 text-slate-400 hover:text-brand-teal transition-colors rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Messages List -->
            <div class="flex-1 overflow-y-auto p-6 space-y-6 scroll-smooth" id="chat-messages"
                 x-on:chat-scrolled-to-bottom.window="$el.scrollTop = $el.scrollHeight">
                @foreach($messages as $msg)
                    <div class="flex {{ $msg['sender_id'] == auth()->id() ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[70%] group">
                            <div class="flex items-end gap-2 {{ $msg['sender_id'] == auth()->id() ? 'flex-row-reverse' : '' }}">
                                <div class="p-4 rounded-3xl shadow-sm {{ $msg['sender_id'] == auth()->id() ? 'bg-brand-teal text-white rounded-br-none' : 'bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-bl-none' }}">
                                    <p class="text-sm leading-relaxed">{{ $msg['body'] }}</p>
                                </div>
                                <span class="text-[9px] text-slate-400 opacity-0 group-hover:opacity-100 transition-opacity">
                                    {{ \Carbon\Carbon::parse($msg['created_at'])->format('H:i') }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Message Input -->
            <div class="p-6 bg-white dark:bg-slate-900 border-t border-slate-100 dark:border-slate-800">
                <form wire:submit.prevent="sendMessage" class="flex gap-4">
                    <div class="flex-1 relative">
                        <input type="text" wire:model="newMessage" placeholder="Type your message..."
                               class="w-full pl-6 pr-12 py-4 bg-slate-50 dark:bg-slate-800/50 border-none rounded-[1.8rem] text-sm focus:ring-2 focus:ring-brand-teal transition-all">
                        <button type="button" class="absolute right-4 top-3.5 text-slate-400 hover:text-brand-teal">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                            </svg>
                        </button>
                    </div>
                    <button type="submit" 
                            class="p-4 bg-brand-teal text-white rounded-[1.5rem] hover:bg-brand-teal-dark transition-all transform hover:scale-105 active:scale-95 shadow-lg shadow-brand-teal/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </button>
                </form>
            </div>
        @else
            <div class="flex-1 flex items-center justify-center text-center p-12">
                <div class="max-w-md">
                    <div class="w-32 h-32 bg-brand-teal/10 rounded-full flex items-center justify-center text-brand-teal mx-auto mb-8 animate-pulse">
                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-4">Your Inbox</h3>
                    <p class="text-slate-500 dark:text-slate-400">Select a conversation from the sidebar to start chatting with your clinic team or patients.</p>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('livewire:navigated', () => {
        console.log('ChatHub: Livewire Navigated');
        setupChatDebug();
    });

    function setupChatDebug() {
        if (!window.Echo) {
            console.error('ChatHub: window.Echo is not defined!');
            return;
        }

        console.log('ChatHub: Echo is ready. Listening for connection status...');
        
        // Check current subscriptions
        const channels = Object.keys(window.Echo.connector.channels);
        console.log('ChatHub: Active Channels:', channels);

        window.Echo.connector.pusher.connection.bind('state_change', (states) => {
            console.log('ChatHub: Connection State Changed:', states);
        });

        // Form submit debug
        const form = document.querySelector('form[wire\\:submit]');
        if (form) {
            form.addEventListener('submit', (e) => {
                console.log('ChatHub: Form SUBMIT event caught by JS');
            });
        }
    }

    // Initial load
    document.addEventListener('DOMContentLoaded', setupChatDebug);
</script>
