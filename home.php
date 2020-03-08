<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <link rel="stylesheet" href="css/style.css">
    <title>Document</title>
    <script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
    </script>
</head>
<body>
<?php session_start();?>
<div id="main">
  <h1><span><?php echo $_SESSION['user'] ?></span>, witaj w Testerze</h1>
<?php
$servername = 'localhost';
$username = 'root';
$password = '';
$database = 'tester';

// Create connection
$conn = @new mysqli($servername, $username, $password, $database);
if ($conn->connect_errno) {
    die("Brak połączaenia");
}
$rs = $conn->query("SELECT isAdmin FROM users WHERE id='" . $_SESSION["user"] . "'");
$rec = $rs->fetch_array();
if (isset($_SESSION['user']) && $rec['isAdmin'] != 1) {
    echo "<form method='post'>";
    echo "<input type='submit' value='Login' name='userOptions'>
    <input type='submit' value='Logout' name='userOptions'>
    <input type='submit' value='Register' name='userOptions'>
    <input type='submit' value='Rozpocznij Test' name='userOptions'>
    </form>";
    if (isset($_POST['userOptions'])) {
        switch ($_POST['userOptions']) {
            case 'Logout':
                session_destroy();
                header("Location:login.php");
                break;
            case 'Login':
                header("Location:login.php");
                break;
            case 'Register':
                header("Location:register.php");
                break;
            case 'Admin Panel':
                header("Location:adminPanel.php");
                break;
            case 'Rozpocznij Test':
                $_SESSION['test'] = false;
                $_SESSION['rAns'] = array();
                $_SESSION['rQues'] = array();
                $_SESSION['randAns'] = array();
                $_SESSION['randAnsInd'] = array();
                $_SESSION['chosAns'] = array();
                $_SESSION['k'] = -1;
                $_SESSION['quesId'] = array();
                header("Location:test.php");
                break;
        }
    }
    $user = $_SESSION['user'];
    $rs = $conn->query("SELECT questions, goodAnswers FROM users WHERE id='$user'");
    $rec = $rs->fetch_array();
    echo "<h2>Odpowiedziałeś na <span>" . $rec['questions'] . "</span> pytań</h2>";
    echo "<h2>Ilość poprawnych odpwiedzi to <span>" . $rec['goodAnswers'] . "</span> pytań</h2>";
    echo "<h2>Ilość błędnych odpwiedzi to <span>" . ($rec['questions'] - $rec['goodAnswers']) . "</span> pytań</h2>";
    echo "<h2>Procent poprawnych odpowiedzi to <span>" . round(($rec['goodAnswers'] / $rec['questions']) * 100) . "</span>%</h2>";
    echo "<h2>Użytkownicy, którzy osiągneli najlepsze wyniki</h2>";
    $rs = $conn->query("SELECT id, isAdmin, questions, goodAnswers, email FROM users ORDER BY goodAnswers / questions DESC LIMIT 10") or die("SELECT nie działa");
    if ($rs->num_rows > 0) {
        echo ("<table>");
        echo ("<tr><th>ID</th><th>Ilość udzielonych odpowiedzi</th><th>Ilość poprawnych odpowiedzi</th><th>Ilość błędnych odpowiedzi</th><tr>");
        while ($rec = $rs->fetch_array()) {
            echo ("<tr><td><input type='hidden' name='id' value='" . $rec["id"] . "'>" . $rec["id"] . "</td><td>" . $rec["questions"] . "</td><td>" . $rec["goodAnswers"] . "</td><td>" . ($rec["questions"] - $rec["goodAnswers"]) . "</td></tr>");
        }
        echo ("</table>");
    }
} else if ($rec['isAdmin'] == 1) {
    header("Location:adminPanel.php");
} else {
    header("Location:login.php");
}
$conn->close();
?>
</div>
</body>
</html>
