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

    <!-- Welcome Section -->
    <div id="welcome-section" class="text-center mb-4 {{ isset($messages) && $messages->count() > 0 ? 'd-none' : '' }}">
        <?php $user = Auth::user(); ?>
        <h4 class="mb-3">Hi {{ $user->name }}, Welcome to <strong>OASP Assist</strong></h4>
        <form id="chat-form-initial" class="d-flex justify-content-center gap-2">
            @csrf
            <input type="text" id="user-input-initial" class="form-control w-50 rounded-pill px-4 py-2"
                placeholder="Ask something..." required autofocus>
            <button type="submit" class="btn btn-primary px-4 rounded-pill">Send</button>
        </form>
    </div>

    <!-- Chat UI -->
    <div id="chat-container" class="card d-none shadow-sm w-100">

        <div class="card-header text-center bg-white">
            <h5 class="mb-0 fw-semibold">OASP Assist Chat</h5>
        </div>

        <div class="card-body bg-light" style="height: 600px; overflow-y: auto;" id="chat-messages">
            @isset($messages)
                @foreach($messages as $msg)
                    <div class="d-flex mb-3 {{ $msg->sender === 'user' ? 'justify-content-end' : 'justify-content-start' }}">
                        <div class="px-3 py-2 rounded {{ $msg->sender === 'user' ? 'bg-primary text-white' : 'bg-white border' }}"
                            style="max-width: 70%;">
                            {{ $msg->content }}
                            <div class="text-end small {{ $msg->sender === 'user' ? 'text-light' : 'text-muted' }} mt-1">
                                {{ \Carbon\Carbon::parse($msg->created_at)->format('h:i A') }}
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                
            @endisset
        </div>

        <div class="card-footer bg-white">
            <form id="chat-form" class="d-flex gap-2">
                @csrf
                <input type="text" id="user-input" class="form-control rounded-pill px-4 py-2"
                    placeholder="Type your message..." required>
                <button type="submit" class="btn btn-primary px-4 rounded-pill">Send</button>
            </form>
        </div>
    </div>
</div>

<script>
    const welcomeForm = document.getElementById('chat-form-initial');
    const initialInput = document.getElementById('user-input-initial');
    const chatContainer = document.getElementById('chat-container');
    const messagesDiv = document.getElementById('chat-messages');
    const chatForm = document.getElementById('chat-form');
    const mainInput = document.getElementById('user-input');

    // Handle the first form submission (welcome form)
    welcomeForm.addEventListener('submit', function (event) {
        event.preventDefault();
        const firstMessage = initialInput.value.trim();
        if (!firstMessage) return;

        // Hide welcome section and show chat container
        document.getElementById('welcome-section').classList.add('d-none');
        chatContainer.classList.remove('d-none');

        // Pre-fill the chat input with the first message
        mainInput.value = firstMessage;
        chatForm.dispatchEvent(new Event('submit')); // Automatically trigger the next message submission
    });

    // Handle the main chat form submission
    chatForm.addEventListener('submit', async function (event) {
        event.preventDefault();

        const userMessage = mainInput.value.trim();
        if (!userMessage) return;

        const now = new Date();
        const timeString = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

        // Display user's message
        const userBubble = document.createElement('div');
        userBubble.className = 'd-flex justify-content-end mb-3 fade-in';
        userBubble.innerHTML = `
            <div class="px-3 py-2 rounded bg-primary text-white" style="max-width: 70%;">
                ${userMessage}
                <div class="text-end small text-light mt-1">${timeString}</div>
            </div>`;
        messagesDiv.appendChild(userBubble);
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
        mainInput.value = '';

        // Show typing indicator
        const typingIndicator = document.createElement('div');
        typingIndicator.className = 'd-flex justify-content-start mb-3';
        typingIndicator.innerHTML = `
            <div class="px-3 py-2 rounded bg-light border" style="max-width: 70%;">
                <em>OASP Assist is typing...</em>
            </div>`;
        messagesDiv.appendChild(typingIndicator);
        messagesDiv.scrollTop = messagesDiv.scrollHeight;

        // Send the message to the server and handle response
        try {
            const response = await fetch('{{ route("chatbot.handle") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ message: userMessage })
            });

            const data = await response.json();
            typingIndicator.remove();

            // Display bot's response
            const botBubble = document.createElement('div');
            botBubble.className = 'd-flex justify-content-start mb-3 fade-in';
            botBubble.innerHTML = `
                <div class="px-3 py-2 rounded bg-white border" style="max-width: 70%;">
                    ${data.message || "Sorry, I couldn't understand that."}
                    <div class="text-end small text-muted mt-1">${new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</div>
                </div>`;
            messagesDiv.appendChild(botBubble);
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
            mainInput.focus();
        } catch (error) {
            console.error('Error:', error);
            typingIndicator.remove();

            // Show error bubble if something goes wrong
            const errorBubble = document.createElement('div');
            errorBubble.className = 'd-flex justify-content-start mb-3 fade-in';
            errorBubble.innerHTML = `
                <div class="px-3 py-2 rounded bg-danger text-white" style="max-width: 70%;">
                    Error processing message.
                    <div class="text-end small mt-1">Now</div>
                </div>`;
            messagesDiv.appendChild(errorBubble);
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
            mainInput.focus();
        }
    });

    // Load previous conversation on clicking a conversation link
    document.querySelectorAll('.load-conversation').forEach(link => {
        link.addEventListener('click', async function (e) {
            e.preventDefault();
            const conversationID = this.dataset.id;

            try {
                const response = await fetch(`/chat/conversation/${conversationID}/messages`);
                const data = await response.json();

                // Switch to chat view and load messages
                document.getElementById('welcome-section').classList.add('d-none');
                chatContainer.classList.remove('d-none');
                messagesDiv.innerHTML = '';

                if (data.messages.length > 0) {
                    data.messages.forEach(msg => {
                        const time = new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                        const bubble = document.createElement('div');
                        bubble.className = `d-flex ${msg.sender === 'user' ? 'justify-content-end' : 'justify-content-start'} mb-3 fade-in`;
                        bubble.innerHTML = `
                            <div class="px-3 py-2 rounded ${msg.sender === 'user' ? 'bg-primary text-white' : 'bg-white border'}" style="max-width: 70%;">
                                ${msg.content}
                                <div class="text-end small ${msg.sender === 'user' ? 'text-light' : 'text-muted'} mt-1">${time}</div>
                            </div>`;
                        messagesDiv.appendChild(bubble);
                    });
                } else {
                    messagesDiv.innerHTML = '<p class="text-muted">No messages yet in this conversation.</p>';
                }

                messagesDiv.scrollTop = messagesDiv.scrollHeight;
                mainInput.focus();
            } catch (err) {
                console.error('Failed to load conversation:', err);
                messagesDiv.innerHTML = '<p class="text-danger">Failed to load messages.</p>';
            }
        });
    });
</script>



@endsection
