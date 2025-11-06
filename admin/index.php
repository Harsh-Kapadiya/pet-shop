<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}
require_once __DIR__ . '/../includes/db_connect.php';
?>

<?php
session_start();
require_once __DIR__ . '/../includes/db_connect.php';

if (isset($_SESSION['admin_id'])) {
    header('Location: pets.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare('SELECT admin_id, password_hash, fullname FROM admins WHERE username = ?');
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password_hash'])) {
        $_SESSION['admin_id'] = $admin['admin_id'];
        $_SESSION['admin_name'] = $admin['fullname'];
        header('Location: pets.php');
        exit;
    } else {
        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Admin Login</title></head>
<body>
<h2>Admin Login</h2>
<?php if ($error): ?><p style="color:red"><?php echo $error; ?></p><?php endif; ?>
<form method="post">
  <label>Username:<br><input name="username" required></label><br>
  <label>Password:<br><input type="password" name="password" required></label><br>
  <button type="submit">Login</button>
</form>
</body>
</html>
