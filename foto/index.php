<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galerie | Kira Server</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <nav class="navbar">
        <a href="../" class="logo">Kira Server</a>
        <div class="nav-links">
            <a href="../">Domů</a>
            <a href="../status/">Status</a>
            <a href="#" class="active">Galerie</a>
            <a href="http://ms48.hicoria.cloud:20693/" target="_blank">Dynmapa</a>
            <label class="switch-container">
                <input type="checkbox" id="themeToggle">
                <i class="fas fa-moon"></i>
            </label>
        </div>
    </nav>

    <header class="hero-section" style="min-height: 40vh;">
        <div class="hero-content">
            <h1><i class="fas fa-camera"></i> Galerie</h1>
            <p class="subtitle">Podívej se na momentky z našeho serveru.</p>
        </div>
    </header>

    <main class="container overlap-top">
        <div class="card">
            <div class="gallery-grid">
                <?php
                // Nastavení složky
                $dir = "galerie/";

                if (is_dir($dir)) {
                    $files = scandir($dir);
                    foreach ($files as $file) {
                        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                        // Podporované formáty
                        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                            echo '<div class="gallery-item">';
                            echo '<a href="' . $dir . $file . '" target="_blank">';
                            echo '<img src="' . $dir . $file . '" alt="Foto ze serveru" loading="lazy">';
                            echo '</a>';
                            echo '</div>';
                        }
                    }
                } else {
                    echo "<p>Složka 'galerie' nebyla nalezena.</p>";
                }
                ?>
            </div>
            
            <?php
            // Hláška, pokud je prázdno
            if (is_dir($dir) && count(scandir($dir)) <= 2) {
                echo "<p style='text-align:center; opacity:0.7;'>Zatím tu nejsou žádné fotky. Brzy přidáme!</p>";
            }
            ?>
        </div>
    </main>

    <footer>
        <p>
            &copy; <span id="year"></span> Kira Server. Vytvořeno pro naši komunitu. <br>
            <span style="font-size: 0.8rem; opacity: 0.6;">Nejsme spojeni s Mojang Studios.</span>
        </p>
    </footer>

    <script>
        document.getElementById('year').textContent = new Date().getFullYear();

        const themeToggle = document.getElementById('themeToggle');
        const body = document.body;
        const savedTheme = localStorage.getItem('theme');

        if (savedTheme) {
            body.classList.add(savedTheme);
            themeToggle.checked = savedTheme === 'dark';
        }

        themeToggle.addEventListener('change', () => {
            if (themeToggle.checked) {
                body.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            } else {
                body.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            }
        });
    </script>
</body>
</html>