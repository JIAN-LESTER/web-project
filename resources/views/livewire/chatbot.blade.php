<div class="d-flex flex-column vh-100 max-w-md mx-auto bg-dark text-white rounded shadow-sm" style="top: -50%">

    <!-- Message Container -->
    <div id="chat-container" class="flex-grow-1 overflow-auto p-3">
        @foreach ($messages as $msg)
            <div class="mb-3 d-flex {{ $msg->sender === 'user' ? 'justify-content-end' : 'justify-content-start' }}">
                <div class="p-3 rounded bg-light text-dark"
                     style="max-width: 75%;">
                    <div class="small text-muted mb-1">{{ ucfirst($msg->sender) }}</div>
                    <div>{{ $msg->content }}</div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Input Box -->
    <div class="border-top p-3 d-flex">
        <input wire:model="input" wire:keydown.enter="sendMessage" type="text"
               class="form-control me-2 rounded-start"
               placeholder="Type your message..." />
        <button wire:click="sendMessage"
                class="btn btn-primary rounded-end">
            Send
        </button>
    </div>

    <!-- Scroll to Bottom Script -->
    <script>
        window.addEventListener('scroll-bottom', () => {
            const container = document.getElementById('chat-container');
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        });
    </script>
</div>
