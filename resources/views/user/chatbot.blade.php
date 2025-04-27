@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card">
        <div class="card-header text-center">
            <h4>Welcome to OASP Assist Chatbot</h4>
        </div>

        <div class="card-body" style="height: 400px; overflow-y: auto;" id="chat-messages">
            <!-- Messages appear here -->
        </div>

        <div class="card-footer">
            <form id="chat-form" class="d-flex">
                @csrf
                <input type="text" id="user-input" class="form-control me-2" placeholder="Type your message..." required>
                <button type="submit" class="btn btn-primary">Send</button>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('chat-form').addEventListener('submit', async function(event) {
    event.preventDefault();

    const inputField = document.getElementById('user-input');
    const userMessage = inputField.value.trim();
    const messagesDiv = document.getElementById('chat-messages');

    if (!userMessage) return;

    // Display user's message
    const userBubble = document.createElement('div');
    userBubble.classList.add('text-end', 'mb-2');
    userBubble.innerHTML = `<span class="badge bg-primary">${userMessage}</span>`;
    messagesDiv.appendChild(userBubble);
    messagesDiv.scrollTop = messagesDiv.scrollHeight;

    inputField.value = '';

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

        // Display bot's response
        const botBubble = document.createElement('div');
        botBubble.classList.add('text-start', 'mb-2');
        botBubble.innerHTML = `<span class="badge bg-secondary">${data.message}</span>`;
        messagesDiv.appendChild(botBubble);
        messagesDiv.scrollTop = messagesDiv.scrollHeight;

    } catch (error) {
        console.error('Error:', error);
    }
});
</script>
@endsection
