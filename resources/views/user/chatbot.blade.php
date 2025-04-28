@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card">
        <div class="card-header text-center">
            <h4>Welcome to OASP Assist Chatbot</h4>
        </div>

        <div class="card-body" style="height: 400px; overflow-y: auto;" id="chat-messages">
            <!-- Messages will appear here dynamically -->
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
        // Send message to Laravel route
        const response = await fetch('{{ route("chatbot.handle") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({ message: userMessage })
        });

        const data = await response.json();

        // Display the bot's response (from KB or OpenAI)
        const botBubble = document.createElement('div');
        botBubble.classList.add('text-start', 'mb-2');
        
        // If the response comes from OpenAI (fallback), display appropriately
        const responseMessage = data.message || "Sorry, I couldn't understand that.";
        
        botBubble.innerHTML = `<span class="badge bg-secondary">${responseMessage}</span>`;
        messagesDiv.appendChild(botBubble);
        messagesDiv.scrollTop = messagesDiv.scrollHeight;

    } catch (error) {
        console.error('Error:', error);
        const errorBubble = document.createElement('div');
        errorBubble.classList.add('text-start', 'mb-2');
        errorBubble.innerHTML = `<span class="badge bg-danger">Sorry, there was an error processing your message.</span>`;
        messagesDiv.appendChild(errorBubble);
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    }
});
</script>
@endsection
