@extends('layouts.app')

@section('content')

<style>
    .fade-in {
        animation: fadeIn 0.3s ease-in-out;
    }
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(5px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<div class="container-fluid py-5">

    {{-- Welcome Section: Shows only if no messages --}}
    <div id="welcome-section" 
         class="text-center mb-4 {{ isset($messages) && $messages->count() > 0 ? 'd-none' : '' }}">
        @php $user = Auth::user(); @endphp
        <h4 class="mb-3">
            Hi {{ $user->name }}, Welcome to <strong>OASP Assist</strong>
        </h4>
        <form id="chat-form-initial" class="d-flex justify-content-center gap-2" aria-label="Start chat form">
            @csrf
            <input type="text" id="user-input-initial" 
                   class="form-control w-50 rounded-pill px-4 py-2"
                   placeholder="Ask something..." 
                   required autofocus
                   aria-required="true"
                   aria-describedby="initial-input-help">
            <button type="submit" class="btn btn-primary px-4 rounded-pill" aria-label="Send initial message">Send</button>
        </form>
    </div>

    {{-- Chat UI: Hidden initially if no prior messages --}}
    <div id="chat-container" class="card d-none shadow-sm w-100" role="region" aria-live="polite" aria-label="Chat messages container">

        <div class="card-header text-center bg-white">
            <h5 class="mb-0 fw-semibold">OASP Assist</h5>
        </div>

        <div id="chat-messages" class="card-body bg-light" style="height: 600px; overflow-y: auto;" tabindex="0" aria-label="Chat messages">
            @isset($messages)
                @foreach($messages as $msg)
                    <div class="d-flex mb-3 {{ $msg->sender === 'user' ? 'justify-content-end' : 'justify-content-start' }}">
                        <div class="px-3 py-2 rounded 
                            {{ $msg->sender === 'user' ? 'bg-primary text-white' : 'bg-white border' }}" 
                            style="max-width: 70%;">
                            {{ $msg->content }}
                            <div class="text-end small 
                                {{ $msg->sender === 'user' ? 'text-light' : 'text-muted' }} mt-1">
                                {{ \Carbon\Carbon::parse($msg->created_at)->format('h:i A') }}
                            </div>
                        </div>
                    </div>
                @endforeach
            @endisset
        </div>

        <div class="card-footer bg-white">
            <form id="chat-form" class="d-flex gap-2" aria-label="Send chat message form">
                @csrf
                <input type="text" id="user-input" 
                       class="form-control rounded-pill px-4 py-2" 
                       placeholder="Type your message..." 
                       required
                       aria-required="true"
                       aria-describedby="chat-input-help"
                       autocomplete="off"
                       aria-autocomplete="list">
                <button type="submit" class="btn btn-primary px-4 rounded-pill" aria-label="Send chat message">Send</button>
            </form>
        </div>
    </div>
</div>

<script>
    (() => {
        const welcomeForm = document.getElementById('chat-form-initial');
        const initialInput = document.getElementById('user-input-initial');
        const chatContainer = document.getElementById('chat-container');
        const messagesDiv = document.getElementById('chat-messages');
        const chatForm = document.getElementById('chat-form');
        const mainInput = document.getElementById('user-input');

        // Utility: Scroll chat to bottom
        function scrollToBottom() {
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        }

        // Utility: Create message bubble DOM element
        function createMessageBubble(content, sender = 'bot') {
            const bubbleWrapper = document.createElement('div');
            bubbleWrapper.className = `d-flex mb-3 fade-in ${sender === 'user' ? 'justify-content-end' : 'justify-content-start'}`;

            const bubble = document.createElement('div');
            bubble.style.maxWidth = '70%';
            bubble.className = 'px-3 py-2 rounded';

            if (sender === 'user') {
                bubble.classList.add('bg-primary', 'text-white');
            } else if (sender === 'error') {
                bubble.classList.add('bg-danger', 'text-white');
            } else {
                bubble.classList.add('bg-white', 'border');
            }

            const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            bubble.innerHTML = `
                ${content}
                <div class="text-end small ${sender === 'user' ? 'text-light' : 'text-muted'} mt-1">${time}</div>
            `;

            bubbleWrapper.appendChild(bubble);
            return bubbleWrapper;
        }

        // Show typing indicator bubble
        function createTypingIndicator() {
            const typingWrapper = document.createElement('div');
            typingWrapper.className = 'd-flex justify-content-start mb-3';

            const typingBubble = document.createElement('div');
            typingBubble.style.maxWidth = '70%';
            typingBubble.className = 'px-3 py-2 rounded bg-light border';
            typingBubble.innerHTML = `<em>OASP Assist is typing...</em>`;

            typingWrapper.appendChild(typingBubble);
            return typingWrapper;
        }

        // Handle welcome form submission: transition to chat
        welcomeForm.addEventListener('submit', e => {
            e.preventDefault();
            const firstMessage = initialInput.value.trim();
            if (!firstMessage) return;

            // Hide welcome section, show chat container
            document.getElementById('welcome-section').classList.add('d-none');
            chatContainer.classList.remove('d-none');

            // Pre-fill main chat input and submit automatically
            mainInput.value = firstMessage;
            chatForm.dispatchEvent(new Event('submit'));
        });

        // Handle main chat form submission
        chatForm.addEventListener('submit', async e => {
            e.preventDefault();

            const userMessage = mainInput.value.trim();
            if (!userMessage) return;

            // Display user's message instantly
            messagesDiv.appendChild(createMessageBubble(userMessage, 'user'));
            scrollToBottom();

            // Clear input for next message
            mainInput.value = '';
            mainInput.disabled = true;

            // Show typing indicator
            const typingIndicator = createTypingIndicator();
            messagesDiv.appendChild(typingIndicator);
            scrollToBottom();

            try {
                const response = await fetch('{{ route("chatbot.handle") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({ message: userMessage }),
                });

                const data = await response.json();
                typingIndicator.remove();

                // Display bot's response
                messagesDiv.appendChild(createMessageBubble(data.message || "Sorry, I couldn't understand that.", 'bot'));
                scrollToBottom();
            } catch (error) {
                console.error('Chat error:', error);
                typingIndicator.remove();

                messagesDiv.appendChild(createMessageBubble('Error processing message.', 'error'));
                scrollToBottom();
            } finally {
                mainInput.disabled = false;
                mainInput.focus();
            }
        });

        // Load previous conversation on clicking conversation links (if any exist)
        document.querySelectorAll('.load-conversation').forEach(link => {
            link.addEventListener('click', async e => {
                e.preventDefault();
                const conversationID = link.dataset.id;
                if (!conversationID) return;

                try {
                    const response = await fetch(`/chat/conversation/${conversationID}/messages`);
                    const data = await response.json();

                    // Switch to chat view and clear previous messages
                    document.getElementById('welcome-section').classList.add('d-none');
                    chatContainer.classList.remove('d-none');
                    messagesDiv.innerHTML = '';

                    if (data.messages && data.messages.length > 0) {
                        data.messages.forEach(msg => {
                            const bubble = createMessageBubble(msg.content, msg.sender);
                            // Overwrite timestamp since createMessageBubble uses current time, here we use actual message time
                            const timeElem = bubble.querySelector('.text-end.small');
                            if (timeElem) {
                                timeElem.textContent = new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                            }
                            messagesDiv.appendChild(bubble);
                        });
                    } else {
                        messagesDiv.innerHTML = '<p class="text-muted">No messages yet in this conversation.</p>';
                    }
                    scrollToBottom();
                    mainInput.focus();
                } catch (err) {
                    console.error('Failed to load conversation:', err);
                    messagesDiv.innerHTML = '<p class="text-danger">Failed to load messages.</p>';
                }
            });
        });
    })();
</script>

@endsection
