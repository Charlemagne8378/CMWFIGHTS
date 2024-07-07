document.addEventListener('DOMContentLoaded', function() {
    var sequence = [];
    var combo1 = ['ArrowUp', 'ArrowDown', 'ArrowLeft', 'ArrowRight']; // combo 1
    var combo2 = ['ArrowUp', 'ArrowDown', 'ArrowUp', 'ArrowDown']; // combo 2
    var combo3 = ['ArrowUp', 'ArrowLeft', 'ArrowDown', 'ArrowRight']; // combo 3

    document.addEventListener('keydown', function(event) {
        sequence.push(event.key);
        var maxComboLength = Math.max(combo1.length, combo2.length, combo3.length);
        if (sequence.length > maxComboLength) {
            sequence.shift();
        }
        
        // Test conditoon
        if (sequence.slice(-combo1.length).toString() === combo1.toString()) {
            window.location.href = 'https://www.cmwfight.fr/pages/compte/easter_egg';
        } else if (sequence.slice(-combo2.length).toString() === combo2.toString()) {
            window.location.href = 'https://www.cmwfight.fr/pages/compte/easter_egg2';
        } else if (sequence.slice(-combo3.length).toString() === combo3.toString()) {
            window.location.href = 'https://www.cmwfight.fr/pages/compte/easter_egg3';
        }
    });
});
