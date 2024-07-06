document.addEventListener('DOMContentLoaded', () => {
    const messageInput = document.getElementById('message-input');
    const sendButton = document.getElementById('send-button');

    sendButton.addEventListener('click', () => {
        const message = messageInput.value;
        if (message.trim() !== '') {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'send_message.php';

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'message';
            input.value = message;

            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }
    });
});
