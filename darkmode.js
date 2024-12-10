// darkmode.js

const DarkMode = {
    toggle: function() {
        const body = document.body;
        const darkModeToggle = document.getElementById('darkModeToggle');

        body.classList.toggle('dark-mode');
        const isDarkMode = body.classList.contains('dark-mode');
        localStorage.setItem('darkMode', isDarkMode ? 'true' : 'false'); // Armazena 'true' ou 'false'
        darkModeToggle.textContent = isDarkMode ? '☀️' : '🌙';
    },

    apply: function() {
        const isDarkMode = localStorage.getItem('darkMode') === 'true'; 
        const body = document.body;
        const darkModeToggle = document.getElementById('darkModeToggle');

        if (isDarkMode) {
            body.classList.add('dark-mode');
            darkModeToggle.textContent = '☀️';
        } else {
            body.classList.remove('dark-mode');
            darkModeToggle.textContent = '🌙';
        }
    }
};

// Aplicar o tema ao carregar a página
window.onload = DarkMode.apply;
