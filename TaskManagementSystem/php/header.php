<?php
session_start();

// Default preferences if none are set
$theme = $_SESSION['theme'] ?? 'Light';
$language = $_SESSION['language'] ?? 'English';
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($language) ?>">
<head>
    <meta charset="UTF-8">
    <title>Page Title</title>
    <style>
        body {
            transition: background-color 0.3s ease;
            color: black;
        }

        .dark-theme {
            background-color: black;
            color: white;
        }

        nav a {
            margin: 0 10px;
            text-decoration: none;
            color: inherit;
        }

        nav a:hover {
            text-decoration: underline;
        }
    </style>
    <script>
        const translations = {
            English: {
                welcome: "Welcome to the Website",
                createTask: "Create Task",
                viewTasks: "View Tasks",
                settings: "Settings",
                logout: "Logout"
            },
            Spanish: {
                welcome: "Bienvenido al Sitio Web",
                createTask: "Crear Tarea",
                viewTasks: "Ver Tareas",
                settings: "Configuración",
                logout: "Cerrar Sesión"
            },
            French: {
                welcome: "Bienvenue sur le site",
                createTask: "Créer une Tâche",
                viewTasks: "Voir les Tâches",
                settings: "Paramètres",
                logout: "Se Déconnecter"
            }
        };

        document.addEventListener('DOMContentLoaded', () => {
            const selectedLanguage = '<?= $language ?>';
            const texts = translations[selectedLanguage];
            if (texts) {
                document.getElementById('welcome').textContent = texts.welcome;
                document.getElementById('navCreateTask').textContent = texts.createTask;
                document.getElementById('navViewTasks').textContent = texts.viewTasks;
                document.getElementById('navSettings').textContent = texts.settings;
                document.getElementById('navLogout').textContent = texts.logout;
            }
        });
    </script>
</head>
<body class="<?= $theme === 'Dark' ? 'dark-theme' : '' ?>">
<nav>
    <a href="../HTML/create_task.html">Create Task</a>
    <a href="../HTML/view_tasks.html">View Tasks</a>
    <a href="../HTML/settings.html">Settings</a>
    <a href="../HTML/logout.html">Logout</a>
</nav>
</body>

