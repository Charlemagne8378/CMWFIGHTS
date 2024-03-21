document.addEventListener('DOMContentLoaded', () => {
    const darkModeBtn = document.querySelector('#darkModeBtn');
    const lightModeBtn = document.querySelector('#lightModeBtn');

    if (darkModeBtn && lightModeBtn) {
        darkModeBtn.addEventListener('click', () => {
            document.body.classList.add('dark-mode');
            localStorage.setItem('theme', 'dark');
        });

        lightModeBtn.addEventListener('click', () => {
            document.body.classList.remove('dark-mode');
            localStorage.setItem('theme', 'light');
        });

        const storedTheme = localStorage.getItem('theme');
        if (storedTheme) {
            if (storedTheme === 'dark') {
                darkModeBtn.click();
            } else {
                lightModeBtn.click();
            }
        }
    }
});
