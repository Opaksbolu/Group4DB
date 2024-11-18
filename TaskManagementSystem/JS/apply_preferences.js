document.addEventListener("DOMContentLoaded", function () {
    // Check if user preferences are stored in localStorage
    const theme = localStorage.getItem("theme");
    const language = localStorage.getItem("language");

    if (theme) {
        document.body.className = theme; // Apply the theme class to the body
    }

    if (language) {
        // Logic to apply language (e.g., change text content or load translations)
        applyLanguage(language);
    }

    function applyLanguage(language) {
        // Example logic to change the language (e.g., setting innerText or translating)
        console.log(`Applying language: ${language}`);
        // Implement your language change logic here
    }
});
