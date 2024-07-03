function toggleAccountBox() {
    var accountBox = document.querySelector('.account-box');
    accountBox.classList.toggle('show');
}

document.addEventListener('DOMContentLoaded', function() {
    var accountBtn = document.querySelector('.account-btn');
    accountBtn.addEventListener('click', toggleAccountBox);
});

const darkModeToggle = document.getElementById('darkModeToggle');
const applyTheme = (theme) => {
    document.documentElement.setAttribute('data-bs-theme', theme);
    if (theme === 'dark') {
        darkModeToggle.checked = true;
    } else {
        darkModeToggle.checked = false;
    }
};
const savedTheme = localStorage.getItem('theme') || 'light';
applyTheme(savedTheme);

darkModeToggle.addEventListener('change', () => {
    const theme = darkModeToggle.checked ? 'dark' : 'light';
    applyTheme(theme);
    localStorage.setItem('theme', theme);
});