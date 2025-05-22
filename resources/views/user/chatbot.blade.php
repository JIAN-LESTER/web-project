@extends('layouts.app')

@section('content')

<style>
  html {
    overflow: hidden;
  }

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

  #chat-container {
    position: relative;
    max-width: 1300px;
    height: 600px;
    display: flex;
    flex-direction: column;
  }

  /* Scrollable messages with padding-bottom for FAQ + footer */
  #chat-messages {
    flex-grow: 1;
    overflow-y: auto;
    padding-bottom: 160px; /* enough space for FAQ + footer */
    background: #f8f9fa;
  }

  /* FAQ fixed just above footer */
  #faq-quick-buttons-container {
  position: absolute;
  bottom: 60px;
  left: 0;
  right: 0;
  background: #ffffffd9; /* slightly transparent white */
  border-top: 1px solid #dee2e6;
  padding: 16px 20px;
  box-shadow: 0 -6px 20px rgba(0, 0, 0, 0.08);
  border-radius: 0 0 12px 12px;
  max-height: 180px;
  overflow-y: auto;
  z-index: 20;
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  backdrop-filter: blur(5px); /* subtle blur for modern feel */
}

#faq-quick-buttons-container .faq-button {
  font-size: 0.9rem;
  font-weight: 500;
  background-color: #f8f9fa;
  border: 1px solid #ced4da;
  color: #495057;
  transition: all 0.2s ease;
  padding: 8px 16px;
  border-radius: 9999px;
  box-shadow: 0 1px 2px rgba(0,0,0,0.05);
}

#faq-quick-buttons-container .faq-button:hover {
  background-color: #e9ecef;
  border-color: #adb5bd;
  color: #212529;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);

}
  /* Footer fixed at bottom inside container */
  .card-footer {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: white;
    padding: 10px 16px;
    box-shadow: 0 -2px 8px rgba(0, 0, 0, 0.1);
    border-radius: 0 0 8px 8px;
    z-index: 25; /* above FAQ */
    display: flex;
  }

  #chat-form {
    flex-grow: 1;
    display: flex;
    gap: 8px;
  }

  #user-input {
    flex-grow: 1;
    border-radius: 9999px;
    padding: 10px 16px;
    border: 1px solid #ced4da;
  }

  #chat-form button {
    border-radius: 9999px;
    padding: 10px 20px;
  }

  /* Message styling, unchanged from your original */
  .d-flex.mb-3 {
    margin-bottom: 1rem !important;
  }
  .justify-content-end {
    justify-content: flex-end !important;
  }
  .justify-content-start {
    justify-content: flex-start !important;
  }
  .bg-primary {
    background-color: #0d6efd !important;
  }
  .text-white {
    color: white !important;
  }
  .bg-white {
    background-color: white !important;
  }
  .border {
    border: 1px solid #dee2e6 !important;
  }
  .rounded-4 {
    border-radius: 1.5rem !important;
  }
  .shadow-sm {
    box-shadow: 0 .125rem .25rem rgba(0,0,0,.075)!important;
  }
  .text-end {
    text-align: right !important;
  }
  .small {
    font-size: .875em;
  }
  .text-light {
    color: #f8f9fa !important;
  }
  .text-muted {
    color: #6c757d !important;
  }
</style>

<div class="container-fluid py-5 mt-1">
  <div id="chat-container" class="card shadow-lg w-100 mx-auto mt-3" style="max-width: 1500px;height:780px;" role="region"
    aria-live="polite" aria-label="Chat messages container">

    <!-- Header -->
    <div class="card-header bg-white d-flex justify-content-between align-items-center border-bottom px-4 py-3 shadow-sm">
  <h5 class="mb-0 fw-semibold text-primary" style="font-size: 1.25rem;">OASP Assist</h5>
  <button id="toggle-faq-btn"
    aria-label="Toggle FAQ quick questions"
    title="Toggle FAQ Quick Questions"
    class="btn btn-outline-primary btn-sm rounded-circle d-flex align-items-center justify-content-center shadow"
    style="width: 35px; height: 35px; font-size: 1.2rem;">
    ❓
  </button>
</div>

    <!-- Chat Messages -->
    <div id="chat-messages" class="card-body bg-light" tabindex="0" aria-label="Chat messages">

      @isset($messages)
      @foreach($messages as $msg)
      <div class="d-flex mb-3 {{ $msg->sender === 'user' ? 'justify-content-end' : 'justify-content-start' }}">
        <div class="px-3 py-2 rounded-4 shadow-sm
                            {{ $msg->sender === 'user' ? 'bg-primary text-white' : 'bg-white border' }}"
          style="max-width: 70%;">
          {{ $msg->content }}
          <div
            class="text-end small
                                {{ $msg->sender === 'user' ? 'text-light' : 'text-muted' }} mt-1">
            {{ \Carbon\Carbon::parse($msg->created_at)->format('h:i A') }}
          </div>
        </div>
      </div>
      @endforeach
      @endisset

    </div>

    <!-- FAQ Buttons (fixed above footer) -->
    <div id="faq-quick-buttons-container" aria-label="Frequently Asked Questions quick select">
      @foreach($faqs as $faq)
      <button type="button"
        class="btn btn-outline-secondary faq-button px-3 py-2 rounded-pill shadow-sm"
        data-id="{{ $faq->faqID }}"
        data-question="{{ $faq->question }}">
        {{ $faq->question }}
      </button>
      @endforeach
    </div>

    <!-- Footer Form (fixed at bottom) -->
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
          aria-autocomplete="list" />
        <button type="submit" class="btn btn-primary px-4 rounded-pill"
          aria-label="Send chat message">Send</button>
      </form>
    </div>

  </div>
</div>

{{-- Include the refactored JavaScript --}}
<script>
// Script to handle chat interactions
(() => {
    document.addEventListener('DOMContentLoaded', function () {
        const chatContainer = document.getElementById('chat-container');
        const messagesDiv = document.getElementById('chat-messages');
        const chatForm = document.getElementById('chat-form');
        const mainInput = document.getElementById('user-input');
        const toggleFaqBtn = document.getElementById('toggle-faq-btn');

        // Move the FAQ quick buttons container inside messagesDiv at the bottom
        let faqQuickContainer = document.getElementById('faq-quick-buttons-container');
        if (faqQuickContainer && messagesDiv) {
            messagesDiv.appendChild(faqQuickContainer);
        }

        // Get all FAQ buttons (outside FAQ container)
        const faqButtons = document.querySelectorAll('.faq-button');

        // Create an array of FAQ data from the buttons
        const faqData = [];
        faqButtons.forEach(button => {
            faqData.push({
                id: button.getAttribute('data-id'),
                question: button.getAttribute('data-question') || button.textContent.trim()
            });
        });

        // ---------------- Utilities ----------------
        function scrollToBottom() {
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        }

        function createMessageBubble(content, sender = 'bot') {
            const wrapper = document.createElement('div');
            wrapper.className = `d-flex mb-3 fade-in ${sender === 'user' ? 'justify-content-end' : 'justify-content-start'}`;

            const bubble = document.createElement('div');
            bubble.className = 'px-3 py-2 rounded';
            bubble.style.maxWidth = '70%';

            if (sender === 'user') bubble.classList.add('bg-primary', 'text-white');
            else if (sender === 'error') bubble.classList.add('bg-danger', 'text-white');
            else bubble.classList.add('bg-white', 'border');

            const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

            bubble.innerHTML = `${content}
                <div class="text-end small ${sender === 'user' ? 'text-light' : 'text-muted'} mt-1">${time}</div>`;

            wrapper.appendChild(bubble);
            return wrapper;
        }

        function createTypingIndicator() {
            const wrapper = document.createElement('div');
            wrapper.className = 'd-flex justify-content-start mb-3';

            const bubble = document.createElement('div');
            bubble.className = 'px-3 py-2 rounded bg-light border';
            bubble.style.maxWidth = '70%';
            bubble.innerHTML = `<em>OASP Assist is typing...</em>`;

            wrapper.appendChild(bubble);
            return wrapper;
        }

        function disableInputs() {
            mainInput.disabled = true;
            if (toggleFaqBtn) toggleFaqBtn.disabled = true;
            faqQuickContainer.querySelectorAll('button').forEach(b => b.disabled = true);
            document.querySelectorAll('.faq-button').forEach(b => b.disabled = true);
        }

        function enableInputs() {
            mainInput.disabled = false;
            if (toggleFaqBtn) toggleFaqBtn.disabled = false;
            faqQuickContainer.querySelectorAll('button').forEach(b => b.disabled = false);
            document.querySelectorAll('.faq-button').forEach(b => b.disabled = false);
        }

        function hideFaqQuickButtons() {
            faqQuickContainer.classList.add('d-none');
        }

        // Toggle FAQ quick buttons visibility
        function toggleFaqQuickButtons() {
            if (faqQuickContainer.classList.contains('d-none')) {
                populateFaqQuickButtons();
                faqQuickContainer.classList.remove('d-none');
                scrollToBottom();
            } else {
                hideFaqQuickButtons();
            }
        }

        // ---------------- Chat Message Submission ----------------
        if (chatForm) {
            chatForm.addEventListener('submit', async e => {
                e.preventDefault();
                const userMessage = mainInput.value.trim();
                if (!userMessage) return;

                disableInputs();
                messagesDiv.appendChild(createMessageBubble(userMessage, 'user'));
                scrollToBottom();
                mainInput.value = '';
                hideFaqQuickButtons(); // Hide FAQ after sending a message

                const typingIndicator = createTypingIndicator();
                messagesDiv.appendChild(typingIndicator);
                scrollToBottom();

                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                                    document.querySelector('input[name="_token"]')?.value;

                    const response = await fetch('/chatbot/message', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({ message: userMessage }),
                    });

                    const data = await response.json();
                    typingIndicator.remove();
                    messagesDiv.appendChild(createMessageBubble(data.message || "Sorry, I couldn't understand that.", 'bot'));
                } catch (err) {
                    console.error(err);
                    typingIndicator.remove();
                    messagesDiv.appendChild(createMessageBubble('Error processing message.', 'error'));
                } finally {
                    enableInputs();
                    scrollToBottom();
                    mainInput.focus();
                }
            });
        }

        // ---------------- FAQ Buttons Handling ----------------
        // Handle main FAQ buttons in the FAQ section (outside messagesDiv)
        document.querySelectorAll('.faq-button').forEach(button => {
            button.addEventListener('click', () => {
                const faqId = button.getAttribute('data-id');
                const question = button.textContent.trim();
                sendFaqQuestion(faqId, question);
            });
        });

        // ---------------- FAQ Quick Buttons Handling ----------------
        function populateFaqQuickButtons() {
            faqQuickContainer.innerHTML = '<p>FAQs Section:</p>';

            // Use faqData or query existing buttons
            const buttons = faqData.length > 0 ? faqData : Array.from(document.querySelectorAll('.faq-button'))
                .map(btn => ({
                    id: btn.getAttribute('data-id'),
                    question: btn.getAttribute('data-question') || btn.textContent.trim()
                }));

            buttons.forEach(item => {
                const btn = document.createElement('button');
                btn.className = 'btn btn-outline-primary btn-sm mb-1';
                btn.textContent = item.question;
                btn.style.whiteSpace = 'normal';
                btn.style.textAlign = 'left';
                btn.style.maxWidth = '100%';
                btn.addEventListener('click', () => sendFaqQuestion(item.id, item.question));
                faqQuickContainer.appendChild(btn);
            });
        }

        async function sendFaqQuestion(faqId, questionText) {
            if (!faqId) return;

            disableInputs();
            messagesDiv.appendChild(createMessageBubble(questionText, 'user'));
            hideFaqQuickButtons(); // Hide FAQ after selecting a question

            const typingIndicator = createTypingIndicator();
            messagesDiv.appendChild(typingIndicator);
            scrollToBottom();

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                               document.querySelector('input[name="_token"]')?.value;

                const response = await fetch('/faq/question', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ faq_id: faqId }),
                });

                const data = await response.json();
                typingIndicator.remove();
                const reply = data.bot_message?.content || "Sorry, no answer available.";
                messagesDiv.appendChild(createMessageBubble(reply, 'bot'));
            } catch (err) {
                console.error(err);
                typingIndicator.remove();
                messagesDiv.appendChild(createMessageBubble('Error processing FAQ quick question.', 'error'));
            } finally {
                enableInputs();
                scrollToBottom();
                mainInput.focus();
            }
        }

        // ---------------- Other UI Handling ----------------
        if (toggleFaqBtn) {
            toggleFaqBtn.addEventListener('click', toggleFaqQuickButtons);
        }

        if (messagesDiv) {
            messagesDiv.addEventListener('keydown', e => {
                if (e.key === 'Escape' && !faqQuickContainer.classList.contains('d-none')) {
                    hideFaqQuickButtons();
                    if (toggleFaqBtn) toggleFaqBtn.focus();
                }
            });
        }

        // Load existing conversation messages if any
        document.querySelectorAll('.load-conversation').forEach(link => {
            link.addEventListener('click', async e => {
                e.preventDefault();
                const id = link.dataset.id;
                if (!id) return;

                try {
                    const res = await fetch(`/chat/conversation/${id}/messages`);
                    const data = await res.json();

                    document.getElementById('welcome-section')?.classList.add('d-none');
                    chatContainer.classList.remove('d-none');
                    messagesDiv.innerHTML = '';

                    if (data.messages?.length) {
                        data.messages.forEach(msg => {
                            const bubble = createMessageBubble(msg.content, msg.sender);
                            const timeElem = bubble.querySelector('.text-end.small');
                            if (timeElem) timeElem.textContent = new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                            messagesDiv.appendChild(bubble);
                        });
                    } else {
                        messagesDiv.innerHTML = '<p class="text-muted">No messages yet in this conversation.</p>';
                    }

                    scrollToBottom();
                    mainInput.focus();
                } catch (err) {
                    console.error(err);
                    messagesDiv.innerHTML = '<p class="text-danger">Failed to load messages.</p>';
                }
            });
        });

        // ---------------- On Load ----------------
        if (mainInput) {
            mainInput.focus();
        }

        // Initialize FAQ container - populate it initially
        populateFaqQuickButtons();

        // Hide FAQ container if there are messages on load, else show it
        if (messagesDiv.querySelectorAll('.d-flex.mb-3').length > 0) {
            hideFaqQuickButtons();
        } else {
            faqQuickContainer.classList.remove('d-none');
        }
    });
})();
</script>


@endsection