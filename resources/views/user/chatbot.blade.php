@extends('layouts.app')

@section('content')

<style>
  html {
    overflow: hidden;
  }

  body {
    background: #f7f7f8;
    font-family: 'Poppins', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
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

  /* Main container with centered layout */
  .chat-main-container {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 20px;
    background: #f7f7f8;
  }

  /* Logo and title section */
  .chat-header-section {
    text-align: center;
    margin-bottom: 48px;
  }

  .chat-logo {
    width: 48px;
    height: 48px;
    margin: 0 auto 16px;
    background: #10a37f;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 24px;
  }

  .chat-title {
    font-size: 32px;
    font-weight: 600;
    color: #202123;
    margin: 0;
    letter-spacing: -0.02em;
  }

  .chat-title-plus {
    background: linear-gradient(90deg, #ff6b6b, #4ecdc4);
    background-clip: text;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    font-size: 18px;
    font-weight: 500;
    margin-left: 8px;
    vertical-align: super;
  }

  /* Categories section */
  .categories-section {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 24px;
    max-width: 1000px;
    width: 100%;
    margin-bottom: 48px;
  }

  .category-card {
    background: white;
    border-radius: 12px;
    padding: 24px;
    border: 1px solid #e5e5e5;
    transition: all 0.2s ease;
    cursor: pointer;
  }

  .category-card:hover {
    border-color: #10a37f;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  }

  .category-icon {
    width: 24px;
    height: 24px;
    margin-bottom: 16px;
    color: #10a37f;
  }

  .category-title {
    font-size: 18px;
    font-weight: 600;
    color: #202123;
    margin-bottom: 12px;
  }

  .category-examples {
    list-style: none;
    padding: 0;
    margin: 0;
  }

  .category-examples li {
    font-size: 14px;
    color: #8e8ea0;
    margin-bottom: 8px;
    padding: 8px 12px;
    background: #f7f7f8;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
  }

  .category-examples li:hover {
    background: #e5f3f0;
    color: #10a37f;
  }

  .category-examples li:last-child {
    margin-bottom: 0;
  }

  /* Input section */
  .input-section {
    width: 100%;
    max-width: 768px;
    position: relative;
  }

  .input-container {
    position: relative;
    background: white;
    border-radius: 12px;
    border: 1px solid #e5e5e5;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: all 0.2s ease;
  }

  .input-container:focus-within {
    border-color: #10a37f;
    box-shadow: 0 4px 12px rgba(16, 163, 127, 0.2);
  }

  .chat-input {
    width: 100%;
    border: none;
    outline: none;
    padding: 16px 20px;
    font-size: 16px;
    border-radius: 12px;
    background: transparent;
    resize: none;
    min-height: 52px;
    max-height: 200px;
  }

  .chat-input::placeholder {
    color: #8e8ea0;
  }

  .input-actions {
    position: absolute;
    right: 12px;
    bottom: 12px;
    display: flex;
    gap: 8px;
    align-items: center;
  }

  .input-btn {
    width: 32px;
    height: 32px;
    border-radius: 6px;
    border: none;
    background: #f7f7f8;
    color: #8e8ea0;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
  }

  .input-btn:hover {
    background: #e5e5e5;
  }

  .send-btn {
    background: #10a37f !important;
    color: white !important;
  }

  .send-btn:hover {
    background: #0d8a6b !important;
  }

  .send-btn:disabled {
    background: #e5e5e5 !important;
    color: #8e8ea0 !important;
    cursor: not-allowed;
  }

  /* Chat container (when chat is active) */
  #chat-container {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: white;
    z-index: 1000;
    display: none;
    flex-direction: column;
  }

  #chat-container.active {
    display: flex;
  }

  .chat-header {
    background: white;
    border-bottom: 1px solid #e5e5e5;
    padding: 16px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .chat-header-left {
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .chat-header-logo {
    width: 32px;
    height: 32px;
    background: #10a37f;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 16px;
  }

  .chat-header-title {
    font-size: 18px;
    font-weight: 600;
    color: #202123;
    margin: 0;
  }

  .chat-back-btn {
    background: none;
    border: none;
    color: #8e8ea0;
    font-size: 20px;
    cursor: pointer;
    padding: 8px;
    border-radius: 6px;
    transition: all 0.2s ease;
  }

  .chat-back-btn:hover {
    background: #f7f7f8;
    color: #202123;
  }

  /* Chat messages area */
  #chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 24px;
    background: #f7f7f8;
  }

  /* FAQ container positioned above input */
  #faq-quick-buttons-container {
    position: absolute;
    bottom: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #e5e5e5;
    border-bottom: none;
    border-radius: 12px 12px 0 0;
    padding: 16px;
    box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.1);
    max-height: 200px;
    overflow-y: auto;
    z-index: 10;
    display: none;
  }

  #faq-quick-buttons-container.show {
    display: block;
  }

  .faq-header {
    font-size: 14px;
    font-weight: 600;
    color: #202123;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .faq-close {
    background: none;
    border: none;
    color: #8e8ea0;
    cursor: pointer;
    font-size: 16px;
    padding: 4px;
  }

  .faq-quick-buttons-container .faq-button {
    display: block;
    width: 100%;
    text-align: left;
    padding: 8px 12px;
    margin-bottom: 8px;
    background: #f7f7f8;
    border: 1px solid #e5e5e5;
    border-radius: 8px;
    color: #202123;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s ease;
  }

  .faq-quick-buttons-container .faq-button:hover {
    background: #e5f3f0;
    border-color: #10a37f;
    color: #10a37f;
  }

  .faq-quick-buttons-container .faq-button:last-child {
    margin-bottom: 0;
  }

  /* Chat input container */
  .chat-input-container {
    padding: 24px;
    background: white;
    border-top: 1px solid #e5e5e5;
    position: relative;
  }

  /* Message bubbles */
  .message-bubble {
    max-width: 70%;
    padding: 12px 16px;
    border-radius: 18px;
    margin-bottom: 16px;
    position: relative;
  }

  .message-bubble.user {
    background: #10a37f;
    color: white;
    margin-left: auto;
  }

  .message-bubble.bot {
    background: white;
    border: 1px solid #e5e5e5;
    color: #202123;
  }

  .message-bubble.error {
    background: #fee;
    border: 1px solid #fcc;
    color: #c33;
  }

  .message-time {
    font-size: 12px;
    opacity: 0.7;
    margin-top: 4px;
    text-align: right;
  }

  /* Toggle FAQ button */
  #toggle-faq-btn {
    position: absolute;
    right: 52px;
    bottom: 12px;
    width: 32px;
    height: 32px;
    border-radius: 6px;
    border: none;
    background: #f7f7f8;
    color: #8e8ea0;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 14px;
  }

  #toggle-faq-btn:hover {
    background: #e5e5e5;
  }

  #toggle-faq-btn.active {
    background: #10a37f;
    color: white;
  }

  /* Responsive design */
  @media (max-width: 768px) {
    .categories-section {
      grid-template-columns: 1fr;
      gap: 16px;
    }

    .chat-main-container {
      padding: 16px;
    }

    .chat-header-section {
      margin-bottom: 32px;
    }

    .chat-title {
      font-size: 28px;
    }

    .category-card {
      padding: 20px;
    }

    .input-section {
      max-width: 100%;
    }

    #chat-messages {
      padding: 16px;
    }

    .chat-input-container {
      padding: 16px;
    }
  }

  /* Scrollbar styling */
  #chat-messages::-webkit-scrollbar,
  #faq-quick-buttons-container::-webkit-scrollbar {
    width: 4px;
  }

  #chat-messages::-webkit-scrollbar-track,
  #faq-quick-buttons-container::-webkit-scrollbar-track {
    background: transparent;
  }

  #chat-messages::-webkit-scrollbar-thumb,
  #faq-quick-buttons-container::-webkit-scrollbar-thumb {
    background: #e5e5e5;
    border-radius: 2px;
  }

  #chat-messages::-webkit-scrollbar-thumb:hover,
  #faq-quick-buttons-container::-webkit-scrollbar-thumb:hover {
    background: #d0d0d0;
  }

  /* Typing indicator */
  .typing-indicator {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 16px;
    background: white;
    border: 1px solid #e5e5e5;
    border-radius: 18px;
    max-width: 70%;
    margin-bottom: 16px;
  }

  .typing-dots {
    display: flex;
    gap: 4px;
  }

  .typing-dot {
    width: 8px;
    height: 8px;
    background: #8e8ea0;
    border-radius: 50%;
    animation: typing 1.4s infinite ease-in-out;
  }

  .typing-dot:nth-child(1) { animation-delay: -0.32s; }
  .typing-dot:nth-child(2) { animation-delay: -0.16s; }

  @keyframes typing {
    0%, 80%, 100% {
      opacity: 0.3;
      transform: scale(1);
    }
    40% {
      opacity: 1;
      transform: scale(1.2);
    }
  }
</style>

<div class="chat-main-container" id="welcome-section">
  <!-- Header with Logo and Title -->
  <div class="chat-header-section">
    <div class="chat-logo">
      <!-- You can replace this with your custom logo -->
      <span>OA</span>
    </div>
    <h1 class="chat-title">
      OASP Assist
      {{-- <span class="chat-title-plus">Plus</span> --}}
    </h1>
  </div>

  <!-- Categories Section -->
  <div class="categories-section">
    <!-- Admissions Category -->
    <div class="category-card" data-category="admissions">
      <div class="category-icon">
        <svg fill="currentColor" viewBox="0 0 20 20">
          <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
      </div>
      <h3 class="category-title">Admissions</h3>
      <ul class="category-examples">
        <li data-question="What are the admission requirements?">What are the admission requirements?</li>
        <li data-question="How do I apply for admission?">How do I apply for admission?</li>
        <li data-question="When is the admission deadline?">When is the admission deadline?</li>
      </ul>
    </div>

    <!-- Scholarships Category -->
    <div class="category-card" data-category="scholarships">
      <div class="category-icon">
        <svg fill="currentColor" viewBox="0 0 20 20">
          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
        </svg>
      </div>
      <h3 class="category-title">Scholarships</h3>
      <ul class="category-examples">
        <li data-question="What scholarships are available?">What scholarships are available?</li>
        <li data-question="How do I apply for scholarships?">How do I apply for scholarships?</li>
        <li data-question="What are the scholarship requirements?">What are the scholarship requirements?</li>
      </ul>
    </div>

    <!-- Placements Category -->
    <div class="category-card" data-category="placements">
      <div class="category-icon">
        <svg fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M3 6a3 3 0 013-3h10a1 1 0 01.8 1.6L14.25 8l2.55 3.4A1 1 0 0116 13H6a1 1 0 00-1 1v3a1 1 0 11-2 0V6z" clip-rule="evenodd"/>
        </svg>
      </div>
      <h3 class="category-title">Placements</h3>
      <ul class="category-examples">
        <li data-question="What placement opportunities are available?">What placement opportunities are available?</li>
        <li data-question="How can I prepare for placements?">How can I prepare for placements?</li>
        <li data-question="What companies visit for placements?">What companies visit for placements?</li>
      </ul>
    </div>
  </div>

  <!-- Input Section -->
  <div class="input-section">
    <div class="input-container">
      <textarea
        id="main-input"
        class="chat-input"
        placeholder="Type your message..."
        rows="1"
      ></textarea>
      <div class="input-actions">
        {{-- <button type="button" class="input-btn" id="attach-btn" title="Attach file">
          <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"/>
          </svg>
        </button> --}}
        {{-- <button type="button" class="input-btn" id="toggle-faq-btn" title="Quick questions">
          <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
          </svg>
        </button> --}}
        <button type="submit" class="input-btn send-btn" id="send-btn" title="Send message">
          <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/>
          </svg>
        </button>
      </div>

      <!-- FAQ Quick Buttons Container -->
      <div id="faq-quick-buttons-container">
        <div class="faq-header">
          <span>Quick Questions</span>
          <button class="faq-close" id="faq-close">&times;</button>
        </div>
        <div class="faq-quick-buttons">
          @foreach($faqs as $faq)
          <button type="button"
            class="faq-button"
            data-id="{{ $faq->faqID }}"
            data-question="{{ $faq->question }}">
            {{ $faq->question }}
          </button>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Chat Container (Hidden by default) -->
<div id="chat-container">
  <!-- Chat Header -->
  <div class="chat-header">
    <div class="chat-header-left">
      <button class="chat-back-btn" id="chat-back-btn" title="Back to main">
        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
        </svg>
      </button>
      <div class="chat-header-logo">OA</div>
      <h2 class="chat-header-title">OASP Assist</h2>
    </div>
  </div>

  <!-- Chat Messages -->
  <div id="chat-messages" tabindex="0" aria-label="Chat messages">
    @isset($messages)
    @foreach($messages as $msg)
    <div class="message-bubble {{ $msg->sender === 'user' ? 'user' : 'bot' }} fade-in">
      {{ $msg->content }}
      <div class="message-time">
        {{ \Carbon\Carbon::parse($msg->created_at)->format('h:i A') }}
      </div>
    </div>
    @endforeach
    @endisset
  </div>

  <!-- Chat Input -->
  <div class="chat-input-container">
    <form id="chat-form" aria-label="Send chat message form">
      @csrf
      <div class="input-container">
        <textarea
          id="user-input"
          class="chat-input"
          placeholder="Type your message..."
          required
          aria-required="true"
          aria-describedby="chat-input-help"
          autocomplete="off"
          rows="1"
        ></textarea>
        <div class="input-actions">
          <button type="button" class="input-btn" id="chat-attach-btn" title="Attach file">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"/>
            </svg>
          </button>
          <button type="submit" class="input-btn send-btn" id="chat-send-btn" title="Send message">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
              <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/>
            </svg>
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
// Enhanced script to handle chat interactions with new UI
(() => {
    document.addEventListener('DOMContentLoaded', function () {
        // Elements
        const welcomeSection = document.getElementById('welcome-section');
        const chatContainer = document.getElementById('chat-container');
        const messagesDiv = document.getElementById('chat-messages');
        const chatForm = document.getElementById('chat-form');
        const userInput = document.getElementById('user-input');
        const mainInput = document.getElementById('main-input');
        const sendBtn = document.getElementById('send-btn');
        const chatSendBtn = document.getElementById('chat-send-btn');
        const toggleFaqBtn = document.getElementById('toggle-faq-btn');
        const faqContainer = document.getElementById('faq-quick-buttons-container');
        const faqClose = document.getElementById('faq-close');
        const chatBackBtn = document.getElementById('chat-back-btn');

        // Auto-resize textarea
        function autoResize(textarea) {
            textarea.style.height = 'auto';
            textarea.style.height = textarea.scrollHeight + 'px';
        }

        // Handle textarea input
        [mainInput, userInput].forEach(input => {
            if (input) {
                input.addEventListener('input', () => {
                    autoResize(input);
                    const hasContent = input.value.trim().length > 0;
                    const sendButton = input === mainInput ? sendBtn : chatSendBtn;
                    if (sendButton) {
                        sendButton.disabled = !hasContent;
                    }
                });

                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' && !e.shiftKey) {
                        e.preventDefault();
                        if (input === mainInput) {
                            startChat();
                        } else {
                            submitMessage();
                        }
                    }
                });
            }
        });

        // Utilities
        function scrollToBottom() {
            if (messagesDiv) {
                messagesDiv.scrollTop = messagesDiv.scrollHeight;
            }
        }

        function createMessageBubble(content, sender = 'bot') {
            const bubble = document.createElement('div');
            bubble.className = `message-bubble ${sender} fade-in`;

            const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            bubble.innerHTML = `
                ${content}
                <div class="message-time">${time}</div>
            `;

            return bubble;
        }

        function createTypingIndicator() {
            const indicator = document.createElement('div');
            indicator.className = 'typing-indicator';
            indicator.innerHTML = `
                <span>OASP Assist is typing</span>
                <div class="typing-dots">
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                </div>
            `;
            return indicator;
        }

        function disableInputs() {
            [mainInput, userInput].forEach(input => {
                if (input) input.disabled = true;
            });
            [sendBtn, chatSendBtn, toggleFaqBtn].forEach(btn => {
                if (btn) btn.disabled = true;
            });
        }

        function enableInputs() {
            [mainInput, userInput].forEach(input => {
                if (input) input.disabled = false;
            });
            [sendBtn, chatSendBtn, toggleFaqBtn].forEach(btn => {
                if (btn) btn.disabled = false;
            });
        }

        // Start chat function
        function startChat() {
            const message = mainInput.value.trim();
            if (!message) return;

            // Switch to chat view
            welcomeSection.style.display = 'none';
            chatContainer.classList.add('active');

            // Add message to chat
            messagesDiv.appendChild(createMessageBubble(message, 'user'));
            scrollToBottom();

            // Clear main input
            mainInput.value = '';
            autoResize(mainInput);

            // Send message
            sendMessage(message);
        }

        // Submit message function
        function submitMessage() {
            const message = userInput.value.trim();
            if (!message) return;

            messagesDiv.appendChild(createMessageBubble(message, 'user'));
            scrollToBottom();

            userInput.value = '';
            autoResize(userInput);

            sendMessage(message);
        }

        // Send message to server
        async function sendMessage(message) {
            disableInputs();

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
                    body: JSON.stringify({ message: message }),
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
                if (userInput) userInput.focus();
            }
        }

        // FAQ functionality
        function toggleFaq() {
            if (faqContainer) {
                faqContainer.classList.toggle('show');
                if (toggleFaqBtn) {
                    toggleFaqBtn.classList.toggle('active');
                }
            }
        }

        function closeFaq() {
            if (faqContainer) {
                faqContainer.classList.remove('show');
            }
            if (toggleFaqBtn) {
                toggleFaqBtn.classList.remove('active');
            }
        }

        // Send FAQ question
        async function sendFaqQuestion(faqId, questionText) {
            if (!faqId) return;

            // Switch to chat view if not already there
            if (welcomeSection.style.display !== 'none') {
                welcomeSection.style.display = 'none';
                chatContainer.classList.add('active');
            }

            disableInputs();
            messagesDiv.appendChild(createMessageBubble(questionText, 'user'));
            closeFaq();

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
                messagesDiv.appendChild(createMessageBubble('Error processing FAQ question.', 'error'));
            } finally {
                enableInputs();
                scrollToBottom();
                if (userInput) userInput.focus();
            }
        }

        // Event listeners
        if (sendBtn) {
            sendBtn.addEventListener('click', startChat);
        }

        if (chatForm) {
            chatForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                submitMessage();
            });
        }

        if (toggleFaqBtn) {
            toggleFaqBtn.addEventListener('click', toggleFaq);
        }

        if (faqClose) {
            faqClose.addEventListener('click', closeFaq);
        }

        if (chatBackBtn) {
            chatBackBtn.addEventListener('click', () => {
                chatContainer.classList.remove('active');
                welcomeSection.style.display = 'flex';
                closeFaq();
            });
        }

        // Category card clicks
        document.querySelectorAll('.category-examples li').forEach(item => {
            item.addEventListener('click', () => {
                const question = item.getAttribute('data-question') || item.textContent.trim();
                if (mainInput) {
                    mainInput.value = question;
                    autoResize(mainInput);
                    sendBtn.disabled = false;
                }
            });
        });

        // FAQ button clicks
        document.querySelectorAll('.faq-button').forEach(button => {
            button.addEventListener('click', () => {
                const faqId = button.getAttribute('data-id');
                const question = button.getAttribute('data-question') || button.textContent.trim();
                sendFaqQuestion(faqId, question);
            });
        });

        // Load existing conversation messages
        document.querySelectorAll('.load-conversation').forEach(link => {
            link.addEventListener('click', async (e) => {
                e.preventDefault();
                const id = link.dataset.id;
                if (!id) return;

                try {
                    const res = await fetch(`/chat/conversation/${id}/messages`);
                    const data = await res.json();

                    welcomeSection.style.display = 'none';
                    chatContainer.classList.add('active');
                    messagesDiv.innerHTML = '';

                    if (data.messages?.length) {
                        data.messages.forEach(msg => {
                            const bubble = createMessageBubble(msg.content, msg.sender);
                            const timeElem = bubble.querySelector('.message-time');
                            if (timeElem) {
                                timeElem.textContent = new Date(msg.created_at).toLocaleTimeString([], {
                                    hour: '2-digit',
                                    minute: '2-digit'
                                });
                            }
                            messagesDiv.appendChild(bubble);
                        });
                    } else {
                        messagesDiv.innerHTML = '<div class="text-center text-muted py-4">No messages yet in this conversation.</div>';
                    }

                    scrollToBottom();
                    if (userInput) userInput.focus();
                } catch (err) {
                    console.error(err);
                    messagesDiv.innerHTML = '<div class="text-center text-danger py-4">Failed to load messages.</div>';
                }
            });
        });

        // Close FAQ when clicking outside
        document.addEventListener('click', (e) => {
            if (faqContainer && faqContainer.classList.contains('show')) {
                if (!faqContainer.contains(e.target) && e.target !== toggleFaqBtn) {
                    closeFaq();
                }
            }
        });

        // Initialize
        if (mainInput) {
            mainInput.focus();
            sendBtn.disabled = true;
        }

        if (chatSendBtn) {
            chatSendBtn.disabled = true;
        }

        // Handle existing messages on load
        if (messagesDiv && messagesDiv.children.length > 0) {
            welcomeSection.style.display = 'none';
            chatContainer.classList.add('active');
            scrollToBottom();
            if (userInput) userInput.focus();
        }
    });
})();
</script>

@endsection
