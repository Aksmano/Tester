<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv='X-UA-Compatible' content='ie=edge'>
    <title>Document</title>
    <link rel="stylesheet" href="css/style.css">
    <script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
    </script>
</head>

<body>
    <!-- <pre> -->

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

// Create connection
$conn = @new mysqli($servername, $username, $password, $database);
if ($conn->connect_errno) {
    die("Brak połączaenia");
}

// Check connection

// if (!$conn) {
//     die('Connection failed: ' . mysqli_connect_error());
// }
// $id = $_POST['id'];
// $pass = $_POST['pass'];
// $passRep = $_POST['passRep'];
// $email = $_POST['email'];
$id = "";
$pass = "";
$passRep = "";
$email = "";
$loginSpan = "*";
$emailSpan = "*";
$passSpan = "*";
$passRepSpan = "*";
$isInDB = false;
$canBeRegistered = true;

if (isset($_POST['regForm'])) {
    if (empty($_POST['id'])) {
        $canBeRegistered = false;
        $loginSpan = "* to pole nie może być puste";
    } else if (strlen($_POST['id']) < 6) {
        $loginSpan = "* login musi posiadać przynajmniej 6 znaków";
        $canBeRegistered = false;
    }
    if (empty($_POST['email'])) {
        $canBeRegistered = false;
        $emailSpan = "* to pole nie może być puste";
    }
    if (empty($_POST['pass'])) {
        $canBeRegistered = false;
        $passSpan = "* to pole nie może być puste";
    } else if (strlen($_POST['pass']) < 6) {
        $passSpan = "* pass musi posiadać przynajmniej 6 znaków";
        $canBeRegistered = false;
    }
    if (empty($_POST['passRep'])) {
        $canBeRegistered = false;
        $passRepSpan = "* to pole nie może być puste";
    }
    $rs = $conn->query("SELECT id FROM users");
    while ($rec = $rs->fetch_array()) {
        if ($rec['id'] == $_POST['id']) {
            $canBeRegistered = false;
            $isInDB = true;
            $loginSpan = '* login jest już zajęty';
            break;
        }
    }
    $rs = $conn->query("SELECT email FROM users");
    while ($rec = $rs->fetch_array()) {
        if ($rec['email'] == $_POST['email']) {
            $canBeRegistered = false;
            $isInDB = true;
            $emailSpan = '* email jest już zajęty';
            break;
        }
    }

    switch ($_POST['regForm']) {
        case 'Register':
            if ($canBeRegistered && $_POST['id'] != '' && $_POST['pass'] != '' && $_POST['passRep'] != '' && $_POST['email'] != '') {
                $id = $_POST['id'];
                $pass = $_POST['pass'];
                $email = $_POST['email'];
                $passRep = $_POST['passRep'];
                if ($_POST['pass'] == $_POST['passRep']) {
                    $hash = password_hash($pass, PASSWORD_DEFAULT);
                    $rs = $conn->query("SELECT id FROM users");
                    while ($rec = $rs->fetch_array()) {
                        if ($rec['id'] == $id) {
                            $isInDB = true;
                            $loginSpan = '* login jest już zajęty';
                            break;
                        }
                    }
                    if (!$isInDB) {
                        $conn->query("INSERT INTO users(id, pass, isAdmin, questions, goodAnswers, email) VALUES ('$id', '$hash', 0, 0, 0, '$email')") or die("INSERT nie działa");
                        if (isset($_SESSION['user'])) {
                            session_destroy();
                            session_start();
                        }
                        $_SESSION['user'] = $_POST['id'];
                        header("Location: http://localhost/tester/home.php");
                    }
                    // echo $hash;
                    // $name = "";
                    // $pass = "";
                    // $passRep = "";
                    // $email = "";
                    // $loginSpan = "*";
                } else {
                    $passRepSpan = "* hasła muszą być identyczne";
                }
            }
            break;
        case "Logout":
            session_destroy();
            header("Location:register.php");
            break;
        case "Home":
            header("Location:home.php");
            break;
    }

}
$conn->close();
echo "<form method='post'>
<h1>Register for tester</h1>
<label>Login <span>$loginSpan</span></label>
<input type='text' name='id' id='id' value='$id' placeholder='login'>
<label>Email <span>$emailSpan</span></label>
<input type='email' name='email' id='email' value='$email' placeholder='email'>
<label>Password <span>$passSpan</span></label>
<input type='password' name='pass' id='pass' value='$pass' placeholder='password'>
<label>Repeat password <span>$passRepSpan</span></label>
<input type='password' name='passRep' id='passRep' value='$passRep' placeholder='repeat password'>
<input type='submit' value='Register' name='regForm'>";
if (isset($_SESSION['user'])) {
    echo "<input type='submit' value='Logout' name='regForm'>
    <input type='submit' value='Home' name='regForm'>";
}
echo "<p>Posiadasz już konto? <a href='http://localhost/tester/login.php'>Zaloguj się!</a></p>
</form>";
$conn->close();
?>
</div>
<!-- </pre> -->
</body>

</html>
