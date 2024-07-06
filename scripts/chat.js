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

function deleteMessage(index) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce message ?')) {
        fetch('delete_message.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'index=' + index
        }).then(response => {
            if (response.ok) {
                location.reload();
            }
        });
    }
}

function muteUser(pseudo) {
    if (confirm('Êtes-vous sûr de vouloir muter cet utilisateur ?')) {
        fetch('mute_user.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'pseudo=' + encodeURIComponent(pseudo)
        }).then(response => {
            if (response.ok) {
                location.reload();
            }
        });
    }
}