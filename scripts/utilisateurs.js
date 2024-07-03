document.addEventListener('DOMContentLoaded', function() {
    const addUserBtn = document.getElementById('ajouter-utilisateur-btn');
    const addUserFormContainer = document.getElementById('ajouter-utilisateur-form-container');
    const table = document.getElementById('utilisateurs-table');
    const pagination = document.getElementById('pagination');

    addUserBtn.addEventListener('click', function() {
        addUserFormContainer.classList.toggle('hidden');
    });

    table.addEventListener('click', function(event) {
        const target = event.target;

        if (target.classList.contains('supprimer-btn')) {
            const email = target.getAttribute('data-email');
            showConfirmationDialog('SUPPRIMER', email, supprimerUtilisateur);
        }

        if (target.classList.contains('ban-btn')) {
            const email = target.getAttribute('data-email');
            showConfirmationDialog('BAN', email, banUtilisateur);
        }
    });

    function showConfirmationDialog(action, email, actionFunction) {
        const confirmationDialog = document.getElementById('confirmation-dialog');
        const confirmationInput = document.getElementById('confirmation-input');
        const confirmBtn = document.getElementById('confirm-btn');
        const cancelBtn = document.getElementById('cancel-btn');
        const instructionText = document.querySelector('.instruction-text');

        confirmationDialog.classList.remove('hidden');
        confirmationInput.value = '';
        confirmationInput.setAttribute('data-action', action.toLowerCase());
        instructionText.textContent = `Pour confirmer, veuillez entrer "${action}".`;

        confirmBtn.onclick = () => {
            const confirmationValue = confirmationInput.value.toUpperCase();
            if (confirmationValue === action) {
                actionFunction(email);
                confirmationDialog.classList.add('hidden');
            } else {
                alert(`Veuillez entrer "${action}" pour confirmer.`);
            }
        };

        cancelBtn.onclick = () => {
            confirmationDialog.classList.add('hidden');
        };
    }

    async function banUtilisateur(email) {
        try {
            const response = await fetch('../process/utilisateur/ban_utilisateur.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `Adresse_email=${encodeURIComponent(email)}`
            });

            if (response.ok) {
                alert('Utilisateur banni avec succès.');
                window.location.reload();
            } else {
                console.error('Une erreur est survenue lors du ban de l\'utilisateur.');
            }
        } catch (error) {
            console.error('Une erreur est survenue lors du ban de l\'utilisateur :', error);
        }
    }

    async function supprimerUtilisateur(email) {
        try {
            const response = await fetch('../process/utilisateur/supprimer_utilisateur.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `Adresse_email=${encodeURIComponent(email)}`
            });

            if (response.ok) {
                alert('Utilisateur supprimé avec succès.');
                window.location.reload();
            } else {
                console.error('Une erreur est survenue lors de la suppression de l\'utilisateur.');
            }
        } catch (error) {
            console.error('Une erreur est survenue lors de la suppression de l\'utilisateur :', error);
        }
    }

    const headers = table.querySelectorAll('th');
    const rows = Array.from(table.querySelectorAll('tbody tr'));

    headers.forEach(header => {
        header.addEventListener('click', () => {
            const headerIndex = Array.from(headers).indexOf(header);
            const currentDirection = header.getAttribute('data-sort-direction') || 'asc';

            const sortedRows = rows.sort((rowA, rowB) => {
                const cellA = rowA.cells[headerIndex].textContent.trim();
                const cellB = rowB.cells[headerIndex].textContent.trim();

                if (headerIndex === 4) { // Pour la colonne "Dernière connexion" (index 4)
                    const dateA = new Date(cellA).getTime() || 0;
                    const dateB = new Date(cellB).getTime() || 0;
                    return currentDirection === 'asc' ? dateA - dateB : dateB - dateA;
                } else {
                    return currentDirection === 'asc' ? cellA.localeCompare(cellB) : cellB.localeCompare(cellA);
                }
            });

            headers.forEach(header => {
                header.removeAttribute('data-sort-direction');
                header.classList.remove('sorted-asc', 'sorted-desc');
            });

            header.setAttribute('data-sort-direction', currentDirection === 'asc' ? 'desc' : 'asc');
            header.classList.toggle('sorted-asc', currentDirection === 'asc');
            header.classList.toggle('sorted-desc', currentDirection === 'desc');

            sortedRows.forEach(row => table.querySelector('tbody').appendChild(row));
        });
    });
});
