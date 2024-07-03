document.addEventListener('DOMContentLoaded', function() {
    var sequence = [];
    var combo = ['ArrowUp', 'ArrowDown', 'ArrowLeft', 'ArrowRight']; //combo de touches

    document.addEventListener('keydown', function(event) {
        sequence.push(event.key);
        if (sequence.length > combo.length) {
            sequence.shift();
        }
        // Verif la Condition
        if (sequence.toString() === combo.toString()) {
            window.location.href = 'https://www.cmwfight.fr/pages/compte/easter_egg'; 
        }
    });
});
