<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot Interface</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        /* Styling the chatbot interface */
        .chat-container {
            width: 100%;
            max-width: 600px;
            height: 80vh;
            margin: auto;
            display: flex;
            flex-direction: column;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
            padding: 10px;
        }
        .chat-box {
            flex-grow: 1;
            overflow-y: auto;
            padding: 10px;
            background-color: white;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        .user-message, .bot-message {
            margin-bottom: 10px;
        }
        .user-message {
            text-align: right;
        }
        .bot-message {
            text-align: left;
        }
        .message-input {
            display: flex;
            justify-content: space-between;
        }
        .message-input input {
            width: 85%;
            padding: 10px;
            border-radius: 20px;
            border: 1px solid #ddd;
        }
        .message-input button {
            width: 12%;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
        }
        .message-input button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="chat-container">
            <div class="chat-box" id="chat-box">
                <!-- Messages will appear here -->
            </div>
            <div class="message-input">
                <input type="text" id="user-input" placeholder="Type your message..." />
                <button id="send-btn"><i class="bi bi-send"></i></button>
            </div>
        </div>
    </div>

    <script>
        // Function to append user and bot messages to the chat box
        function appendMessage(message, sender) {
            const chatBox = document.getElementById('chat-box');
            const messageDiv = document.createElement('div');
            messageDiv.classList.add(sender + '-message');
            messageDiv.textContent = message;
            chatBox.appendChild(messageDiv);
            chatBox.scrollTop = chatBox.scrollHeight; // Scroll to bottom
        }

        // Function to handle user input and simulate bot response
        document.getElementById('send-btn').addEventListener('click', function () {
            const userInput = document.getElementById('user-input').value;
            if (userInput.trim() !== "") {
                // Append user message
                appendMessage(userInput, 'user');
                document.getElementById('user-input').value = ''; // Clear input box

                // Simulate bot response after a delay
                setTimeout(function () {
                    const botResponse = "This is a bot response: " + userInput;
                    appendMessage(botResponse, 'bot');
                }, 1000);
            }
        });

        // Allow pressing Enter to send message
        document.getElementById('user-input').addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                document.getElementById('send-btn').click();
            }
        });
    </script>
</body>
</html>
