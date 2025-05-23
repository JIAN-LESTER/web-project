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
    animation: fadeIn 0.4s ease-in-out;
  }

  .slide-up {
    animation: slideUp 0.3s ease-out;
  }

  .scale-in {
    animation: scaleIn 0.2s ease-out;
  }

  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: translateY(8px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  @keyframes slideUp {
    from {
      opacity: 0;
      transform: translateY(20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  @keyframes scaleIn {
    from {
      opacity: 0;
      transform: scale(0.95);
    }
    to {
      opacity: 1;
      transform: scale(1);
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
    animation: fadeIn 0.5s ease-in-out;
  }

  /* Logo and title section */
  .chat-header-section {
    text-align: center;
    margin-bottom: 48px;
    animation: slideUp 0.4s ease-out;
  }

  .chat-logo {
    width: 56px;
    height: 56px;
    margin: 0 auto 20px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
  }

  .chat-logo:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
  }

  .chat-logo img {
    width: 100%;
    height: 100%;
    object-fit: contain;
  }

  .chat-title {
    font-size: 36px;
    font-weight: 700;
    color: #202123;
    margin: 0;
    letter-spacing: -0.02em;
    background: linear-gradient(135deg, #202123 0%, #10a37f 100%);
    background-clip: text;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
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
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 28px;
    max-width: 1100px;
    width: 100%;
    margin-bottom: 52px;
  }

  .category-card {
    background: white;
    border-radius: 16px;
    padding: 28px;
    border: 1px solid #e5e5e5;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    position: relative;
    overflow: hidden;
    animation: slideUp 0.5s ease-out;
  }

  .category-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #10a37f, #4ecdc4);
    transform: scaleX(0);
    transition: transform 0.3s ease;
  }

  .category-card:hover {
    border-color: #10a37f;
    box-shadow: 0 8px 32px rgba(16, 163, 127, 0.15);
    transform: translateY(-4px);
  }

  .category-card:hover::before {
    transform: scaleX(1);
  }

  .category-card:nth-child(1) {
    animation-delay: 0.1s;
  }

  .category-card:nth-child(2) {
    animation-delay: 0.2s;
  }

  .category-card:nth-child(3) {
    animation-delay: 0.3s;
  }

  .category-icon {
    width: 28px;
    height: 28px;
    margin-bottom: 20px;
    color: #10a37f;
    transition: all 0.3s ease;
  }

  .category-card:hover .category-icon {
    transform: scale(1.1);
    color: #0d8a6b;
  }

  .category-title {
    font-size: 20px;
    font-weight: 700;
    color: #202123;
    margin-bottom: 16px;
    transition: color 0.3s ease;
  }

  .category-card:hover .category-title {
    color: #10a37f;
  }

  .category-examples {
    list-style: none;
    padding: 0;
    margin: 0;
  }

  .category-examples li {
    font-size: 14px;
    color: #6b7280;
    margin-bottom: 10px;
    padding: 10px 14px;
    background: #f8fafc;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid transparent;
  }

  .category-examples li:hover {
    background: linear-gradient(135deg, #e5f3f0, #f0f9f7);
    color: #10a37f;
    border-color: #10a37f;
    transform: translateX(4px);
  }

  .category-examples li:last-child {
    margin-bottom: 0;
  }

  /* Input section */
  .input-section {
    width: 100%;
    max-width: 800px;
    position: relative;
    animation: slideUp 0.6s ease-out;
  }

  .input-container {
    position: relative;
    background: white;
    border-radius: 20px;
    border: 2px solid #e5e7eb;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
    overflow: hidden;
  }

  .input-container:focus-within {
    border-color: #10a37f;
    box-shadow: 0 8px 32px rgba(16, 163, 127, 0.2);
  }

  .chat-input {
    width: 100%;
    border: none;
    outline: none;
    padding: 18px 24px;
    padding-right: 60px;
    font-size: 16px;
    font-weight: 500;
    border-radius: 20px;
    background: transparent;
    resize: none;
    min-height: 60px;
    max-height: 200px;
    line-height: 1.5;
    color: #374151;
  }

  .chat-input::placeholder {
    color: #9ca3af;
    font-weight: 400;
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
    background: #f3f4f6;
    color: #6b7280;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    position: relative;
    overflow: hidden;
  }

  .input-btn:hover {
    background: #e5e7eb;
    transform: scale(1.05);
  }

  .send-btn {
    background: #10a37f !important;
    color: white !important;
    box-shadow: 0 2px 8px rgba(16, 163, 127, 0.25);
  }

  .send-btn:hover {
    background: #0d8a6b !important;
    box-shadow: 0 4px 12px rgba(16, 163, 127, 0.35);
    transform: scale(1.05);
  }

  .send-btn:disabled {
    background: #e5e7eb !important;
    color: #9ca3af !important;
    cursor: not-allowed;
    box-shadow: none;
    transform: none;
  }

  /* Chat container (when chat is active) - With navbar space */
  #chat-container {
    position: fixed;
    top: 56px;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    z-index: 999;
    display: none;
    flex-direction: column;
    margin-left: 0;
    animation: fadeIn 0.4s ease-in-out;
  }

  #chat-container.active {
    display: flex;
  }

  /* Adjust chat container for large screens with sidebar */
  @media (min-width: 992px) {
    #chat-container {
      left: 240px;
      width: calc(100% - 240px);
    }

    body.sidebar-hidden #chat-container {
      left: 0;
      width: 100%;
    }
  }

  .chat-header {
    background: white;
    border-bottom: 1px solid #e5e7eb;
    padding: 12px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
    animation: slideUp 0.3s ease-out;
    min-height: 48px;
  }

  .chat-header-left {
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .chat-header-logo {
    width: 28px;
    height: 28px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
  }

  .chat-header-logo img {
    width: 100%;
    height: 100%;
    object-fit: contain;
  }

  .chat-header-title {
    font-size: 16px;
    font-weight: 600;
    color: #202123;
    margin: 0;
  }

  .chat-back-btn {
    background: #f3f4f6;
    border: none;
    color: #6b7280;
    font-size: 16px;
    cursor: pointer;
    padding: 6px;
    border-radius: 6px;
    transition: all 0.2s ease;
  }

  .chat-back-btn:hover {
    background: #e5e7eb;
    color: #374151;
  }

  /* Chat messages area - More compact */
  #chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
    background: transparent;
    display: flex;
    flex-direction: column;
    gap: 16px;
  }

  /* FAQ container positioned above input */
  #faq-quick-buttons-container {
    position: absolute;
    bottom: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #e5e7eb;
    border-bottom: none;
    border-radius: 16px 16px 0 0;
    padding: 20px;
    box-shadow: 0 -8px 32px rgba(0, 0, 0, 0.1);
    max-height: 240px;
    overflow-y: auto;
    z-index: 10;
    display: none;
    animation: slideUp 0.3s ease-out;
  }

  #faq-quick-buttons-container.show {
    display: block;
  }

  .faq-header {
    font-size: 16px;
    font-weight: 600;
    color: #202123;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .faq-close {
    background: #f3f4f6;
    border: none;
    color: #6b7280;
    cursor: pointer;
    font-size: 18px;
    padding: 6px;
    border-radius: 6px;
    transition: all 0.2s ease;
  }

  .faq-close:hover {
    background: #e5e7eb;
    color: #374151;
  }

  .faq-quick-buttons-container .faq-button {
    display: block;
    width: 100%;
    text-align: left;
    padding: 12px 16px;
    margin-bottom: 10px;
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    color: #374151;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
  }

  .faq-quick-buttons-container .faq-button:hover {
    background: linear-gradient(135deg, #e5f3f0, #f0f9f7);
    border-color: #10a37f;
    color: #10a37f;
    transform: translateY(-1px);
  }

  .faq-quick-buttons-container .faq-button:last-child {
    margin-bottom: 0;
  }

  /* Chat input container - More compact */
  .chat-input-container {
    padding: 16px 20px 20px 20px;
    background: white;
    border-top: 1px solid #e5e7eb;
    position: relative;
    animation: slideUp 0.4s ease-out;
  }

  #chat-container .input-container {
    background: white;
    border: 2px solid #10a37f;
    border-radius: 20px;
    box-shadow: 0 2px 8px rgba(16, 163, 127, 0.1);
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
    max-width: 100%;
    margin: 0;
  }

  #chat-container .input-container:focus-within {
    border-color: #10a37f;
    box-shadow: 0 4px 16px rgba(16, 163, 127, 0.2);
  }

  #chat-container .chat-input {
    padding: 16px 60px 16px 20px;
    border-radius: 20px;
    font-size: 15px;
    font-weight: 500;
    min-height: 52px;
    max-height: 200px;
    line-height: 1.4;
  }

  #chat-container .input-actions {
    right: 12px;
    bottom: 12px;
    top: auto;
    transform: none;
  }

  #chat-container .send-btn {
    width: 28px;
    height: 28px;
    border-radius: 6px;
    background: #10a37f !important;
    color: white !important;
    box-shadow: none;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  #chat-container .send-btn:hover {
    background: #0d8a6b !important;
    transform: none;
  }

  #chat-container .send-btn:disabled {
    background: #e5e7eb !important;
    color: #9ca3af !important;
    box-shadow: none;
    transform: none;
  }

  #chat-container .send-btn svg {
    width: 14px;
    height: 14px;
  }

  /* Message bubbles - Enhanced with better styling */
  .message-bubble {
    max-width: 75%;
    padding: 16px 20px;
    border-radius: 20px;
    position: relative;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    animation: fadeIn 0.3s ease-out;
    word-wrap: break-word;
    line-height: 1.5;
  }

  .message-bubble.user {
    background: linear-gradient(135deg, #10a37f, #0d8a6b);
    color: white;
    margin-left: auto;
    border-bottom-right-radius: 6px;
    box-shadow: 0 4px 12px rgba(16, 163, 127, 0.3);
  }

  .message-bubble.bot {
    background: white;
    border: 1px solid #e5e7eb;
    color: #374151;
    align-self: flex-start;
    border-bottom-left-radius: 6px;
  }

  .message-bubble.error {
    background: linear-gradient(135deg, #fef2f2, #fee2e2);
    border: 1px solid #fca5a5;
    color: #dc2626;
    align-self: flex-start;
    font-size: 14px;
    padding: 12px 16px;
  }

  .message-time {
    font-size: 11px;
    opacity: 0.8;
    margin-top: 6px;
    text-align: right;
    font-weight: 500;
    letter-spacing: 0.5px;
  }

  .message-bubble.bot .message-time {
    text-align: left;
    color: #6b7280;
  }

  .message-bubble.user .message-time {
    color: rgba(255, 255, 255, 0.8);
  }

  /* Toggle FAQ button */
  #toggle-faq-btn {
    position: absolute;
    right: 60px;
    bottom: 12px;
    width: 36px;
    height: 36px;
    border-radius: 12px;
    border: none;
    background: #f3f4f6;
    color: #6b7280;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    font-size: 14px;
  }

  #toggle-faq-btn:hover {
    background: #e5e7eb;
    transform: scale(1.05);
  }

  #toggle-faq-btn.active {
    background: linear-gradient(135deg, #10a37f, #0d8a6b);
    color: white;
    box-shadow: 0 4px 12px rgba(16, 163, 127, 0.3);
  }

  /* Responsive design */
  @media (max-width: 768px) {
    .categories-section {
      grid-template-columns: 1fr;
      gap: 20px;
    }

    .chat-main-container {
      padding: 16px;
    }

    .chat-header-section {
      margin-bottom: 36px;
    }

    .chat-title {
      font-size: 30px;
    }

    .chat-logo {
      width: 48px;
      height: 48px;
      margin-bottom: 16px;
    }

    .category-card {
      padding: 24px;
    }

    .input-section {
      max-width: 100%;
    }

    .chat-input {
      font-size: 16px;
      min-height: 56px;
    }

    #chat-messages {
      padding: 16px;
      gap: 14px;
    }

    .chat-input-container {
      padding: 12px 16px 16px 16px;
    }

    .chat-header {
      padding: 10px 16px;
      min-height: 44px;
    }

    .chat-header-title {
      font-size: 15px;
    }

    .chat-header-logo {
      width: 24px;
      height: 24px;
    }

    .message-bubble {
      max-width: 85%;
      padding: 14px 18px;
    }
  }

  /* Scrollbar styling - Enhanced */
  #chat-messages::-webkit-scrollbar,
  #faq-quick-buttons-container::-webkit-scrollbar {
    width: 6px;
  }

  #chat-messages::-webkit-scrollbar-track,
  #faq-quick-buttons-container::-webkit-scrollbar-track {
    background: transparent;
  }

  #chat-messages::-webkit-scrollbar-thumb,
  #faq-quick-buttons-container::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
    transition: background 0.2s ease;
  }

  #chat-messages::-webkit-scrollbar-thumb:hover,
  #faq-quick-buttons-container::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
  }

  /* Typing indicator - Enhanced design */
  .typing-indicator {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 20px;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 20px;
    max-width: 75%;
    align-self: flex-start;
    border-bottom-left-radius: 6px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    animation: fadeIn 0.3s ease-out;
  }

  .typing-indicator span {
    font-size: 14px;
    color: #6b7280;
    font-weight: 500;
  }

  .typing-dots {
    display: flex;
    gap: 6px;
  }

  .typing-dot {
    width: 8px;
    height: 8px;
    background: #10a37f;
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

  /* Enhanced hover effects */
  .chat-input:focus {
    animation: scaleIn 0.2s ease-out;
  }

  /* Additional animations for smooth interactions */
  .input-container:focus-within .input-actions {
    animation: scaleIn 0.2s ease-out;
  }

  /* Smooth transitions for all interactive elements */
  * {
    transition-property: transform, opacity, color, background-color, border-color, box-shadow;
    transition-duration: 0.2s;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  }
</style>

<div class="chat-main-container" id="welcome-section">
  <!-- Header with Logo and Title -->
  <div class="chat-header-section">
    <div class="chat-logo">
      <!-- Updated to use oasp.png logo only -->
      <img src="{{ asset('oasp.png') }}" alt="OASP Logo">
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
      <div class="chat-header-logo">
        <!-- Updated to use oasp.png logo only -->
        <img src="{{ asset('oasp.png') }}" alt="OASP Logo">
      </div>
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
      <div class="input-container" style="width: 750px; margin-left:25%">
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
          {{-- <button type="button" class="input-btn" id="chat-attach-btn" title="Attach file">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"/>
            </svg>
          </button> --}}
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
// Enhanced script to handle chat interactions with animations and improved UX
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

        // Animation helper functions
        function addAnimation(element, animationClass) {
            if (element) {
                element.classList.add(animationClass);
                // Remove animation class after animation completes
                setTimeout(() => {
                    element.classList.remove(animationClass);
                }, 500);
            }
        }

        // Function to adjust chat container for sidebar
        function adjustChatContainerForSidebar() {
            if (window.innerWidth >= 992) {
                if (document.body.classList.contains('sidebar-hidden')) {
                    chatContainer.style.left = '0';
                    chatContainer.style.width = '100%';
                } else {
                    chatContainer.style.left = '240px';
                    chatContainer.style.width = 'calc(100% - 240px)';
                }
            } else {
                chatContainer.style.left = '0';
                chatContainer.style.width = '100%';
            }
        }

        // Enhanced auto-resize textarea without animations
        function autoResize(textarea) {
            if (!textarea) return;

            const minHeight = textarea === mainInput ? 60 : 52;
            const maxHeight = 200;

            textarea.style.height = 'auto';
            const scrollHeight = textarea.scrollHeight;
            const newHeight = Math.min(Math.max(scrollHeight, minHeight), maxHeight);

            textarea.style.height = newHeight + 'px';
        }

        // Reset textarea without animation
        function resetTextarea(textarea) {
            if (textarea) {
                textarea.value = '';
                textarea.style.height = 'auto';
                const minHeight = textarea === mainInput ? 60 : 52;
                textarea.style.height = minHeight + 'px';
            }
        }

        // Handle textarea input without animations
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

        // Utilities with enhanced animations
        function scrollToBottom(smooth = true) {
            if (messagesDiv) {
                messagesDiv.scrollTo({
                    top: messagesDiv.scrollHeight,
                    behavior: smooth ? 'smooth' : 'auto'
                });
            }
        }

        function createMessageBubble(content, sender = 'bot') {
            const bubble = document.createElement('div');
            bubble.className = `message-bubble ${sender}`;

            const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            bubble.innerHTML = `
                ${content}
                <div class="message-time">${time}</div>
            `;

            // Add fade-in animation
            setTimeout(() => {
                bubble.classList.add('fade-in');
            }, 10);

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

            // Add fade-in animation
            setTimeout(() => {
                indicator.classList.add('fade-in');
            }, 10);

            return indicator;
        }

        function disableInputs() {
            [mainInput, userInput].forEach(input => {
                if (input) {
                    input.disabled = true;
                    input.style.opacity = '0.6';
                }
            });
            [sendBtn, chatSendBtn, toggleFaqBtn].forEach(btn => {
                if (btn) {
                    btn.disabled = true;
                    btn.style.opacity = '0.6';
                }
            });
        }

        function enableInputs() {
            [mainInput, userInput].forEach(input => {
                if (input) {
                    input.disabled = false;
                    input.style.opacity = '1';
                }
            });
            [sendBtn, chatSendBtn, toggleFaqBtn].forEach(btn => {
                if (btn) {
                    btn.disabled = false;
                    btn.style.opacity = '1';
                }
            });
        }

        // Enhanced start chat function with animations
        function startChat() {
            const message = mainInput.value.trim();
            if (!message) return;

            // Add send animation to button
            addAnimation(sendBtn, 'scale-in');

            // Switch to chat view with fade transition
            welcomeSection.style.opacity = '0';
            welcomeSection.style.transform = 'translateY(-20px)';

            setTimeout(() => {
                welcomeSection.style.display = 'none';
                chatContainer.classList.add('active');
                adjustChatContainerForSidebar();

                // Reset welcome section styles for future use
                welcomeSection.style.opacity = '1';
                welcomeSection.style.transform = 'translateY(0)';
            }, 300);

            // Add message to chat with animation
            const messageBubble = createMessageBubble(message, 'user');
            messagesDiv.appendChild(messageBubble);
            scrollToBottom();

            // Clear and reset main input
            resetTextarea(mainInput);

            // Send message
            sendMessage(message);
        }

        // Enhanced submit message function
        function submitMessage() {
            const message = userInput.value.trim();
            if (!message) return;

            // Add send animation to button
            addAnimation(chatSendBtn, 'scale-in');

            const messageBubble = createMessageBubble(message, 'user');
            messagesDiv.appendChild(messageBubble);
            scrollToBottom();

            // Clear and reset user input
            resetTextarea(userInput);

            sendMessage(message);
        }

        // Enhanced send message to server
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

                // Remove typing indicator with fade out
                typingIndicator.style.opacity = '0';
                setTimeout(() => {
                    typingIndicator.remove();
                }, 200);

                // Add bot response with animation
                setTimeout(() => {
                    const botMessage = createMessageBubble(data.message || "Sorry, I couldn't understand that.", 'bot');
                    messagesDiv.appendChild(botMessage);
                    scrollToBottom();
                }, 300);

            } catch (err) {
                console.error(err);
                typingIndicator.style.opacity = '0';
                setTimeout(() => {
                    typingIndicator.remove();
                    const errorMessage = createMessageBubble('Error processing message.', 'error');
                    messagesDiv.appendChild(errorMessage);
                    scrollToBottom();
                }, 200);
            } finally {
                setTimeout(() => {
                    enableInputs();
                    if (userInput) userInput.focus();
                }, 400);
            }
        }

        // Enhanced FAQ functionality
        function toggleFaq() {
            if (faqContainer) {
                const isShowing = faqContainer.classList.contains('show');
                if (isShowing) {
                    faqContainer.style.opacity = '0';
                    faqContainer.style.transform = 'translateY(10px)';
                    setTimeout(() => {
                        faqContainer.classList.remove('show');
                        faqContainer.style.opacity = '1';
                        faqContainer.style.transform = 'translateY(0)';
                    }, 200);
                } else {
                    faqContainer.classList.add('show');
                    addAnimation(faqContainer, 'slide-up');
                }

                if (toggleFaqBtn) {
                    toggleFaqBtn.classList.toggle('active');
                    addAnimation(toggleFaqBtn, 'scale-in');
                }
            }
        }

        function closeFaq() {
            if (faqContainer && faqContainer.classList.contains('show')) {
                faqContainer.style.opacity = '0';
                faqContainer.style.transform = 'translateY(10px)';
                setTimeout(() => {
                    faqContainer.classList.remove('show');
                    faqContainer.style.opacity = '1';
                    faqContainer.style.transform = 'translateY(0)';
                }, 200);
            }
            if (toggleFaqBtn) {
                toggleFaqBtn.classList.remove('active');
            }
        }

        // Enhanced send FAQ question
        async function sendFaqQuestion(faqId, questionText) {
            if (!faqId) return;

            // Switch to chat view if not already there
            if (welcomeSection.style.display !== 'none') {
                welcomeSection.style.opacity = '0';
                setTimeout(() => {
                    welcomeSection.style.display = 'none';
                    chatContainer.classList.add('active');
                    adjustChatContainerForSidebar();
                    welcomeSection.style.opacity = '1';
                }, 300);
            }

            disableInputs();
            const messageBubble = createMessageBubble(questionText, 'user');
            messagesDiv.appendChild(messageBubble);
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

                typingIndicator.style.opacity = '0';
                setTimeout(() => {
                    typingIndicator.remove();
                }, 200);

                setTimeout(() => {
                    const reply = data.bot_message?.content || "Sorry, no answer available.";
                    const botMessage = createMessageBubble(reply, 'bot');
                    messagesDiv.appendChild(botMessage);
                    scrollToBottom();
                }, 300);

            } catch (err) {
                console.error(err);
                typingIndicator.style.opacity = '0';
                setTimeout(() => {
                    typingIndicator.remove();
                    const errorMessage = createMessageBubble('Error processing FAQ question.', 'error');
                    messagesDiv.appendChild(errorMessage);
                    scrollToBottom();
                }, 200);
            } finally {
                setTimeout(() => {
                    enableInputs();
                    if (userInput) userInput.focus();
                }, 400);
            }
        }

        // Event listeners with enhanced interactions
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
                // Add animation for back transition
                addAnimation(chatBackBtn, 'scale-in');

                chatContainer.style.opacity = '0';
                setTimeout(() => {
                    chatContainer.classList.remove('active');
                    welcomeSection.style.display = 'flex';
                    chatContainer.style.opacity = '1';
                    addAnimation(welcomeSection, 'fade-in');
                }, 300);

                closeFaq();

                // Reset main input
                if (mainInput) {
                    resetTextarea(mainInput);
                    sendBtn.disabled = true;
                }
            });
        }

        // Enhanced category card clicks without input animations
        document.querySelectorAll('.category-examples li').forEach(item => {
            item.addEventListener('click', () => {
                const question = item.getAttribute('data-question') || item.textContent.trim();
                if (mainInput) {
                    mainInput.value = question;
                    autoResize(mainInput);
                    sendBtn.disabled = false;
                    mainInput.focus();
                }
            });

            // Add hover animation
            item.addEventListener('mouseenter', () => {
                addAnimation(item, 'scale-in');
            });
        });

        // Enhanced FAQ button clicks
        document.querySelectorAll('.faq-button').forEach(button => {
            button.addEventListener('click', () => {
                const faqId = button.getAttribute('data-id');
                const question = button.getAttribute('data-question') || button.textContent.trim();
                addAnimation(button, 'scale-in');
                sendFaqQuestion(faqId, question);
            });
        });

        // Enhanced load existing conversation messages
        document.querySelectorAll('.load-conversation').forEach(link => {
            link.addEventListener('click', async (e) => {
                e.preventDefault();
                const id = link.dataset.id;
                if (!id) return;

                // Add loading animation
                addAnimation(link, 'scale-in');

                try {
                    const res = await fetch(`/chat/conversation/${id}/messages`);
                    const data = await res.json();

                    welcomeSection.style.opacity = '0';
                    setTimeout(() => {
                        welcomeSection.style.display = 'none';
                        chatContainer.classList.add('active');
                        adjustChatContainerForSidebar();
                        messagesDiv.innerHTML = '';
                        welcomeSection.style.opacity = '1';

                        if (data.messages?.length) {
                            data.messages.forEach((msg, index) => {
                                setTimeout(() => {
                                    const bubble = createMessageBubble(msg.content, msg.sender);
                                    const timeElem = bubble.querySelector('.message-time');
                                    if (timeElem) {
                                        timeElem.textContent = new Date(msg.created_at).toLocaleTimeString([], {
                                            hour: '2-digit',
                                            minute: '2-digit'
                                        });
                                    }
                                    messagesDiv.appendChild(bubble);
                                    scrollToBottom(false);
                                }, index * 100); // Stagger message loading
                            });
                        } else {
                            messagesDiv.innerHTML = '<div class="text-center text-muted py-4">No messages yet in this conversation.</div>';
                        }

                        setTimeout(() => {
                            scrollToBottom();
                            if (userInput) userInput.focus();
                        }, data.messages?.length * 100 + 200);
                    }, 300);
                } catch (err) {
                    console.error(err);
                    messagesDiv.innerHTML = '<div class="text-center text-danger py-4">Failed to load messages.</div>';
                }
            });
        });

        // Enhanced click outside FAQ detection
        document.addEventListener('click', (e) => {
            if (faqContainer && faqContainer.classList.contains('show')) {
                if (!faqContainer.contains(e.target) && e.target !== toggleFaqBtn) {
                    closeFaq();
                }
            }
        });

        // Enhanced sidebar toggle events
        window.addEventListener('resize', () => {
            if (chatContainer.classList.contains('active')) {
                adjustChatContainerForSidebar();
            }
        });

        document.addEventListener('sidebarToggled', () => {
            if (chatContainer.classList.contains('active')) {
                adjustChatContainerForSidebar();
            }
        });

        // Initialize without input animations
        if (mainInput) {
            mainInput.focus();
            sendBtn.disabled = true;
        }

        if (chatSendBtn) {
            chatSendBtn.disabled = true;
        }

        // Handle existing messages on load with animations
        if (messagesDiv && messagesDiv.children.length > 0) {
            welcomeSection.style.display = 'none';
            chatContainer.classList.add('active');
            adjustChatContainerForSidebar();

            // Add animations to existing messages
            Array.from(messagesDiv.children).forEach((child, index) => {
                setTimeout(() => {
                    addAnimation(child, 'fade-in');
                }, index * 100);
            });

            setTimeout(() => {
                scrollToBottom();
                if (userInput) userInput.focus();
            }, 500);
        }

        // Initial sidebar adjustment
        adjustChatContainerForSidebar();

        // Add entrance animations to category cards
        setTimeout(() => {
            document.querySelectorAll('.category-card').forEach((card, index) => {
                setTimeout(() => {
                    addAnimation(card, 'slide-up');
                }, index * 100);
            });
        }, 200);
    });
})();
</script>

@endsection
