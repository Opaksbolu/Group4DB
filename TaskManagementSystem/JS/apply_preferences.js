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
});
    function applyLanguageNav() {
        const language = localStorage.getItem("userLanguage");
        console.log(language);
        if (language) {
            const translations = {
                English: {

                    createTaskLink: "Create Task",
                    viewTasksLink: "View Tasks",
                    settingsLink: "Settings",
                    logoutLink: "Logout"
                },
                Spanish: {

                    createTaskLink: "Crear Tarea",
                    viewTasksLink: "Ver Tareas",
                    settingsLink: "Ajustes",
                    logoutLink: "Cerrar Sesión"

                },
                French: {
                        
                    createTaskLink: "Créer une tâche",
                    viewTasksLink: "Voir les tâches",
                    settingsLink: "Paramètres",
                    logoutLink: "Se déconnecter"
                }
            };
            
            const textElements = translations[language];
            console.log(textElements);
            if (textElements) {
                document.getElementById("createTaskLink").textContent = textElements.createTaskLink;
                document.getElementById("viewTasksLink").textContent = textElements.viewTasksLink;
                document.getElementById("settingsLink").textContent = textElements.settingsLink;
                document.getElementById("logoutLink").textContent = textElements.logoutLink;

                console.log(textElements.createTaskLink);

            }
        }
    }
