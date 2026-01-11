<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true || 
    (isset($_SESSION['admin_ip']) && $_SESSION['admin_ip'] !== $_SERVER['REMOTE_ADDR'])) {
    session_destroy();
    header("Location: ../");
    exit;
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konzole | Kira Server</title>
    <link rel="stylesheet" href="../../style.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="dark">
    <nav class="navbar">
        <a href="../../" class="logo">Admin Panel</a>
        <div class="nav-links">
            <span style="margin-right:15px; opacity:0.7; color: var(--primary-color);">Online</span>
            <a href="../logout.php" style="color:#ff5555;"><i class="fas fa-sign-out-alt"></i> Odhlásit</a>
        </div>
    </nav>
    <main class="container">
        <div class="console-container">
            <div id="consoleOutput" class="console-output">
                <div class="log-entry"><span class="log-time">[System]</span> Připraveno.</div>
            </div>
            <div class="console-input-area">
                <input type="text" id="cmdInput" class="cmd-input" placeholder="Zadej příkaz..." autocomplete="off">
                <button onclick="sendCmd()" class="cmd-btn"><i class="fas fa-paper-plane"></i></button>
            </div>
        </div>
    </main>
    <script>
        const output = document.getElementById('consoleOutput');
        const input = document.getElementById('cmdInput');
        input.addEventListener("keypress", function(e) { if(e.key === "Enter") sendCmd(); });
        async function sendCmd() {
            const cmd = input.value;
            if(!cmd) return;
            addLog("Ty", cmd, "#fff");
            input.value = "";
            try {
                const res = await fetch('../api.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({ command: cmd })
                });
                const data = await res.json();
                if(data.status === 'success') addLog("Server", "Příkaz odeslán.", "#4CAF50");
                else addLog("Chyba", data.message, "#ff5555");
            } catch(e) { addLog("Chyba", "Spojení selhalo.", "#ff5555"); }
        }
        function addLog(who, msg, color) {
            const time = new Date().toLocaleTimeString();
            const div = document.createElement('div');
            div.className = 'log-entry';
            div.innerHTML = `<span class="log-time">[${time}]</span> <strong style="color:${color}">${who}:</strong> ${msg}`;
            output.appendChild(div);
            output.scrollTop = output.scrollHeight;
        }
    </script>
</body>
</html>