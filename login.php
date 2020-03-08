<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv='X-UA-Compatible' content='ie=edge'>
    <link rel='stylesheet' href='css/style.css'>
    <title>Document</title>
    <script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
    </script>
</head>

<body>
<div id="main">
<?php session_start();?>
<?php
// if (isset($_SESSION['user'])) {
//     header("Location:home.php");
// }
$servername = 'localhost';
$username = 'root';
$password = '';
$database = 'tester';
// $id = "";
// $pass = "";
$loginSpan = "*";
$passSpan = "*";
$isInDB = false;
$conn = @new mysqli($servername, $username, $password, $database);
if ($conn->connect_errno) {
    die("Brak połączaenia");
}
$rs = $conn->query("SELECT id FROM users");
if (!empty($_POST['id'])) {
    while ($rec = $rs->fetch_array()) {
        if ($rec['id'] == $_POST['id']) {
            $isInDB = true;
            break;
        }
    }
}

if (!$isInDB) {
    $loginSpan = "* Nie ma takiego użytkownika";
}
if (empty($_POST['id'])) {
    $loginSpan = "* to pole nie może być puste";
}
if (empty($_POST['pass'])) {
    $passSpan = "* to pole nie może być puste";
}
if (isset($_POST['loginForm'])) {
    switch ($_POST['loginForm']) {
        case 'Login':
            if ($_POST['id'] != '' && $_POST['pass'] != '') {
                $id = $_POST['id'];
                $pass = $_POST['pass'];
                $rs = $conn->query("SELECT pass FROM users WHERE id='" . $id . "'");
                // echo $rs;
                $rec = $rs->fetch_array();
                if ($rec['pass'] == password_verify($_POST['pass'], $rec['pass']) && $isInDB) {
                    $_SESSION['user'] = $_POST['id'];
                    header("Location:home.php");
                } else {
                    $passSpan = "* Hasło jest nieprawidłowe";
                }
            }
            break;
        case "Logout":
            session_destroy();
            header("Location:login.php");
            break;
        case "Home":
            header("Location:home.php");
            break;
    }
}
echo "<form method='post'>
<h1>Login for tester </h1>
<label>Login <span>$loginSpan</span></label>
<input type='text' name='id' id='id' value='' placeholder='login'>
<label>Password <span>$passSpan</span></label>
<input type='password' name='pass' id='pass' value='' placeholder='password'>
<input type='submit' value='Login' name='loginForm'>";
if (isset($_SESSION['user'])) {
    echo "<input type='submit' value='Logout' name='loginForm'>
    <input type='submit' value='Home' name='loginForm'>";
}
echo "<p>Nie masz jeszcze konta? <a href='http://localhost/tester/register.php'>Zarejestruj się!</a></p></form>";
$conn->close();
?>
</div>
</body>

</html>
