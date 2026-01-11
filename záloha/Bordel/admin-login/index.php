<?php
session_start();

// jednoduché přihlášení – jen pro ukázku
if (isset($_POST['login'])) {
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';
    if ($user === 'admin' && $pass === 'heslo123') {
        $_SESSION['admin'] = $user;
        header("Location: index.php");
        exit;
    } else {
        $error = "Špatné jméno nebo heslo!";
    }
}

// logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

$logged_in = isset($_SESSION['admin']);
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <!-- tady je relativní cesta do rootu -->
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<?php if (!$logged_in): ?>
<div class="admin-login-wrapper">
    <div class="admin-login-box">
        <h1>Admin Login</h1>
        <?php if (!empty($error)) echo "<p style='color:red'>$error</p>"; ?>
        <form method="post">
            <input type="text" name="username" placeholder="Uživatelské jméno" class="admin-input" required>
            <input type="password" name="password" placeholder="Heslo" class="admin-input" required>
            <button type="submit" name="login" class="admin-btn">Přihlásit</button>
        </form>
    </div>
</div>
<?php else: ?>
<div class="admin-login-wrapper">
    <div class="admin-login-box">
        <h1>Vítej, <?php echo htmlspecialchars($_SESSION['admin']); ?></h1>
        <a href="?logout=1" class="admin-btn" style="background:#ff5555;">Odhlásit</a>
        <h2>Konzole</h2>
        <div class="admin-console" id="console">
            <!-- sem se budou vypisovat zprávy -->
            <p>Sem můžeš psát příkazy...</p>
        </div>
        <input type="text" id="console-input" class="admin-console-input" placeholder="Zadej příkaz">
    </div>
</div>
<script>
const input = document.getElementById('console-input');
const consoleDiv = document.getElementById('console');

input.addEventListener('keydown', function(e) {
    if(e.key === 'Enter'){
        const val = input.value.trim();
        if(val){
            const p = document.createElement('p');
            p.textContent = '> ' + val;
            consoleDiv.appendChild(p);
            consoleDiv.scrollTop = consoleDiv.scrollHeight;
            input.value = '';
        }
    }
});
</script>
<?php endif; ?>
</body>
</html>
