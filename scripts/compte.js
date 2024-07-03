function toggleAccountBox() {
    var accountBox = document.querySelector('.account-box');
    accountBox.classList.toggle('show');
}

document.addEventListener('DOMContentLoaded', function() {
    var accountBtn = document.querySelector('.account-btn');
    accountBtn.addEventListener('click', toggleAccountBox);
});
