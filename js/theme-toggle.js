document.addEventListener('DOMContentLoaded', () => {
    const themeToggles = document.querySelectorAll('.theme-toggle');
    const html = document.documentElement;

    // Check for saved theme
    const savedTheme = localStorage.getItem('theme') || 'dark';
    html.setAttribute('data-theme', savedTheme);
    updateIcons(savedTheme);

    themeToggles.forEach(toggle => {
        toggle.addEventListener('click', () => {
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateIcons(newTheme);
        });
    });

    function updateIcons(theme) {
        themeToggles.forEach(toggle => {
            const icon = toggle.querySelector('i');
            if (icon) {
                if (theme === 'light') {
                    icon.className = 'fas fa-moon';
                } else {
                    icon.className = 'fas fa-sun';
                }
            }
        });
    }
});
