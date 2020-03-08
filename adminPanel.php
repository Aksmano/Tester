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
    <!-- <script>
        confirmation = false
        document.getElementById("formButton").value == "Zapisz" ? confirmation = confirm("Czy chcesz wyzerować ranking dobrych i złych odpowiedzi tego pytania?") : null
    </script> -->
</head>
<body>

<?php session_start();
?>
<div id="main">
  <h1><span><?php echo $_SESSION['user'] ?></span>, witaj w Testerze</h1>
<?php
$servername = 'localhost';
$username = 'root';
$password = '';
$database = 'tester';
if (!isset($_SESSION['whichTable'])) {
    $whichTable = "users";
} else {
    $whichTable = $_SESSION['whichTable'];
}
$id = "";
$question = "";
$answer = "";
$button = "Dodaj";
// Create connection
$conn = @new mysqli($servername, $username, $password, $database);
if ($conn->connect_errno) {
    die("Brak połączaenia");
}
$conn->query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");
$rs = $conn->query("SELECT isAdmin FROM users WHERE id='" . $_SESSION["user"] . "'");
$rec = $rs->fetch_array();
if (isset($_SESSION['user']) && $rec['isAdmin'] == 1) {
    echo "<form method='post'><input type='submit' value='Baza z pytaniami' name='adminOptions'>
    <input type='submit' value='Baza z userami' name='adminOptions'>
    <input type='submit' value='Ranking 10 najtrudniejszych pytań' name='adminOptions'>
    <input type='submit' value='Ranking 10 najlepszych użytkowników' name='adminOptions'>
    <input type='submit' value='Rozpocznij Test' name='adminOptions'>
    <input type='submit' value='Login' name='adminOptions'>
    <input type='submit' value='Register' name='adminOptions'>
    <input type='submit' value='Logout' name='adminOptions'>
    </form>";
    if (isset($_POST['adminOptions'])) {
        switch ($_POST['adminOptions']) {
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
            case 'Home':
                header("Location:home.php");
                break;
            case "Baza z pytaniami":
                $whichTable = "questions";
                $_SESSION['whichTable'] = "questions";
                break;
            case "Baza z userami":
                $whichTable = "users";
                $_SESSION['whichTable'] = "users";
                break;
            case 'Ranking 10 najtrudniejszych pytań':
                $whichTable = "top10q";
                $_SESSION['whichTable'] = "users";
                break;
            case 'Ranking 10 najlepszych użytkowników':
                $whichTable = "top10u";
                $_SESSION['whichTable'] = "users";
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
            case "Dodaj":
                $question = $_POST['question'];
                $answer = $_POST['answer'];
                $conn->query("INSERT INTO questions(question, answer, good, allAnswers) VALUES ('$question','$answer',0,0)") or die("INSERT nie działa");
                $question = "";
                $answer = "";
                break;
            case "Edytuj":
                $rs = $conn->query("SELECT id, question, answer FROM questions WHERE id=" . $_POST['id']);
                $rec = $rs->fetch_array();
                $question = $rec['question'];
                $answer = $rec['answer'];
                $id = $rec["id"];
                $button = "Zapisz";
                break;
            case "Zapisz":
                $question = $_POST['question'];
                $answer = $_POST['answer'];
                $id = $_POST['id'];
                if (!empty($_POST['resetAns'])) {
                    $conn->query("UPDATE questions SET question='$question', answer='$answer', good=0, allAnswers=0  WHERE id=$id") or die("UPDATE nie działa");
                } else {
                    $conn->query("UPDATE questions SET question='$question', answer='$answer' WHERE id=$id") or die("UPDATE nie działa");
                }
                $question = "";
                $answer = "";
                $id = "";
                $button = "Dodaj";
                break;
            case "Usuń":
                if ($whichTable == "users") {
                    $id = $_POST['id'];
                    $conn->query("DELETE FROM users WHERE id='$id'");
                    $_SESSION['whichTable'] = "users";
                } else {
                    $conn->query("DELETE FROM questions WHERE id=" . $_POST['id']);
                    $_SESSION['whichTable'] = "questions";
                }
                break;
        }
    }
} else {
    if (isset($_SESSION['user'])) {
        header("Location:home.php");
    } else {
        header("Location:login.php");
    }
}
if ($whichTable == "users") {
    $rs = $conn->query("SELECT id, isAdmin, questions, goodAnswers, email FROM users") or die("SELECT nie działa");
    if ($rs->num_rows > 0) {
        echo ("<table>");
        echo ("<tr><th>ID</th><th>Czy jest adminem</th><th>Ilość udzielonych odpowiedzi</th><th>Ilość poprawnych odpowiedzi</th><th>Ilość błędnych odpowiedzi</th><th>Email</th><th>Usuń</th><tr>");
        while ($rec = $rs->fetch_array()) {
            echo ("<form method='post'><tr><td><input type='hidden' name='id' value='" . $rec["id"] . "'>" . $rec["id"] . "</td><td>" . ($rec["isAdmin"] ? "TAK" : "NIE") . "</td><td>" . $rec["questions"] . "</td><td>" . $rec["goodAnswers"] . "</td><td>" . ($rec["questions"] - $rec["goodAnswers"]) . "</td><td>" . $rec["email"] . "</td><td><input type='submit' name='adminOptions' value='Usuń'></td></tr></form>");
        }
        echo ("</table>");
    }
} else if ($whichTable == "questions") {
    $rs = $conn->query("SELECT id, question, answer, good, allAnswers FROM questions ORDER BY id DESC") or die("SELECT nie działa");
    if ($rs->num_rows > 0) {
        echo "<form method='post'>
            <input type='hidden' name='id' value='$id'>
            <label>Pytanie:</label> <input type='text' name='question' value='$question'>
            <label>Odpowiedź:</label> <input type='text' name='answer' value='$answer'>";
        if ($button == "Zapisz") {
            echo "<label style='display: inline;'>Wyzerowanie dobrych odpowiedzi i wszystkich pojawień się pytania</label> <input style='display: inline;' type='checkbox' name='resetAns'>";
        }
        echo "<input type='submit' value='$button' name='adminOptions' id='formButton'>
            </form>";
        echo ("<table>");
        echo ("<tr><th>ID</th><th>Pytanie</th><th>Odpowiedź</th><th>Odpowiedzi dobre</th><th>Odpowiedzi złe</th><th>Wszystkie odpowiedzi</th><th>Usuń</th><th>Edytuj</th><tr>");
        while ($rec = $rs->fetch_array()) {
            echo ("<form method='post'><tr><td><input type='hidden' name='id' value='" . $rec["id"] . "'>" . $rec["id"] . "</td><td>" . $rec["question"] . "</td><td>" . $rec["answer"] . "</td><td>" . $rec["good"] . "</td><td>" . ($rec["allAnswers"] - $rec["good"]) . "</td><td>" . $rec["allAnswers"] . "</td><td><input type='submit' name='adminOptions' value='Usuń'></td><td><input type='submit' name='adminOptions' value='Edytuj'></td></tr></form>");
        }
        echo ("</table>");

    }
} else if ($whichTable == "top10q") {
    $rs = $conn->query("SELECT id, question, answer, good, allAnswers FROM questions WHERE allAnswers>=8 ORDER BY good / allAnswers ASC LIMIT 10 ") or die("SELECT nie działa");
    if ($rs->num_rows > 0) {
        echo ("<table>");
        echo ("<tr><th>ID</th><th>Pytanie</th><th>Odpowiedź</th><th>Odpowiedzi dobre</th><th>Odpowiedzi złe</th><th>Wszystkie odpowiedzi</th><th>Usuń</th><th>Edytuj</th><tr>");
        while ($rec = $rs->fetch_array()) {
            echo ("<form method='post'><tr><td><input type='hidden' name='id' value='" . $rec["id"] . "'>" . $rec["id"] . "</td><td>" . $rec["question"] . "</td><td>" . $rec["answer"] . "</td><td>" . $rec["good"] . "</td><td>" . ($rec["allAnswers"] - $rec["good"]) . "</td><td>" . $rec["allAnswers"] . "</td><td><input type='submit' name='adminOptions' value='Usuń'></td><td><input type='submit' name='adminOptions' value='Edytuj'></td></tr></form>");
        }
        echo ("</table>");

    }
} else if ($whichTable == "top10u") {
    $rs = $conn->query("SELECT id, isAdmin, questions, goodAnswers, email FROM users ORDER BY goodAnswers / questions DESC LIMIT 10") or die("SELECT nie działa");
    if ($rs->num_rows > 0) {
        echo ("<table>");
        echo ("<tr><th>ID</th><th>Czy jest adminem</th><th>Ilość udzielonych odpowiedzi</th><th>Ilość poprawnych odpowiedzi</th><th>Ilość błędnych odpowiedzi</th><th>Email</th><th>Usuń</th><tr>");
        while ($rec = $rs->fetch_array()) {
            echo ("<form method='post'><tr><td><input type='hidden' name='id' value='" . $rec["id"] . "'>" . $rec["id"] . "</td><td>" . ($rec["isAdmin"] ? "TAK" : "NIE") . "</td><td>" . $rec["questions"] . "</td><td>" . $rec["goodAnswers"] . "</td><td>" . ($rec["questions"] - $rec["goodAnswers"]) . "</td><td>" . $rec["email"] . "</td><td><input type='submit' name='adminOptions' value='Usuń'></td></tr></form>");
        }
        echo ("</table>");
    }
}
$conn->close();
?>
</div>
</body>
</html>
