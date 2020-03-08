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
    <script>
        const signAnswer = (event) => {
            const array = [...event.target.parentElement.children]
            console.log(array);
            console.log(event.target.parentElement.children);


            array.forEach(element => {
             element.style.backgroundColor = "white"
            });
            event.target.parentElement.parentElement.firstElementChild.value = event.target.innerText
            event.target.style.backgroundColor= "gray"
        }
    </script>
</head>
<body>
<?php session_start();
$operationInfo = "";?>
<div id="main">
  <h1><span><?php echo $_SESSION['user'] ?></span>, witaj w Testerze</h1>
<?php
$servername = 'localhost';
$username = 'root';
$password = '';
$database = 'tester';
$control = true;
$k = ++$_SESSION['k'];

// Create connection
$conn = @new mysqli($servername, $username, $password, $database);
if ($conn->connect_errno) {
    die("Brak połączaenia");
}
$conn->query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");
$rs = $conn->query("SELECT id, question, answer FROM questions ORDER BY RAND() LIMIT 10");
if (isset($_SESSION['user'])) {
    if ($k > 10) {
        $chosenAnswers = $_SESSION['chosAns'];
        echo "<h2>Odpowiedziałeś poprawnie na <span>" . (100 - (10 * count($chosenAnswers))) . "%</span> pytań</h2>
        <form method='post'>
        <input type='submit' value='Home' name='testOptions'>
        <input type='submit' value='Login' name='testOptions'>
        <input type='submit' value='Register' name='testOptions'>
        <input type='submit' value='Logout' name='testOptions'>
        <input type='submit' value='Rozpocznij test ponownie' name='testOptions'>
        </form>";
    }
    if (!$_SESSION['test']) {
        while ($rec = $rs->fetch_array()) {
            $conn->query("UPDATE questions SET allAnswers=allAnswers + 1 WHERE id=" . $rec['id']);
            array_push($_SESSION['rQues'], $rec['question']);
            array_push($_SESSION['rAns'], $rec['answer']);
            array_push($_SESSION['quesId'], $rec['id']);
            while ($control) {
                $rs_ans = $conn->query("SELECT answer FROM questions ORDER BY RAND() LIMIT 3");
                $answers = array($rec['answer']);
                // $temp_ans = array();
                while ($rec_ans = $rs_ans->fetch_array()) {
                    if ($rec_ans['answer'] == $rec['answer']) {
                        $control = false;
                    } else {
                        // echo "11111" . var_dump($rec_ans['answer']);
                        array_push($answers, $rec_ans['answer']);
                    }
                }
                if (!$control) {
                    $control = true;
                } else {
                    $control = false;
                    // array_push($answers, $rec_ans);
                    // echo var_dump($answers);
                }
            }
            $control = true;
            $ans_indexes = array();
            $index_end = 4;
            for ($i = 0; $i < $index_end; $i++) {
                $rand = rand(0, 3);
                if (!in_array($rand, $ans_indexes)) {
                    array_push($ans_indexes, $rand);
                } else {
                    $index_end++;
                }
            }
            array_push($_SESSION['randAns'], $answers);
            array_push($_SESSION['randAnsInd'], $ans_indexes);
            $_SESSION['test'] = true;
        }
    }
    $rightAnswers = $_SESSION['rAns'];
    $rightQuestions = $_SESSION['rQues'];
    $randomizedAnswers = $_SESSION['randAns'];
    $randomizedAnswersIndexes = $_SESSION['randAnsInd'];
    $chosenAnswers = $_SESSION['chosAns'];
    if ($k < 10) {
        echo "<form method='post'>";
        echo "<input type='hidden' name='hidden_answer' value=''>";
        echo "<h3>" . $rightQuestions[$k] . "</h3>";
        echo "<div class='test-wrapper'>";
        echo "<div onclick='signAnswer(window.event)'>" . $randomizedAnswers[$k][$randomizedAnswersIndexes[$k][0]] . /*"<input type='radio' name='testOptions' value='A'></div>";*/"</div>"; //"' name='testOptions'>";
        echo "<div onclick='signAnswer(window.event)'>" . $randomizedAnswers[$k][$randomizedAnswersIndexes[$k][1]] . /*"<input type='radio' name='testOptions' value='B'></div>";*/"</div>"; //"' name='testOptions'>";
        echo "<div onclick='signAnswer(window.event)'>" . $randomizedAnswers[$k][$randomizedAnswersIndexes[$k][2]] . /*"<input type='radio' name='testOptions' value='C'></div>";*/"</div>"; //"' name='testOptions'>";
        echo "<div onclick='signAnswer(window.event)'>" . $randomizedAnswers[$k][$randomizedAnswersIndexes[$k][3]] . /*"<input type='radio' name='testOptions' value='D'></div>";*/"</div>"; //"' name='testOptions'>";
        echo "<input type='submit' value='Sprawdź' name='testOptions'><br><br>
        <input type='submit' value='Home' name='testOptions'>
        <input type='submit' value='Login' name='testOptions'>
        <input type='submit' value='Register' name='testOptions'>
        <input type='submit' value='Logout' name='testOptions'>
        <input type='submit' value='Rozpocznij test ponownie' name='testOptions'>";
        echo "</div>";
        echo "</form><br><br>";

    }
    if (isset($_POST['testOptions'])) {
        switch ($_POST['testOptions']) {
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
            case 'Home':
                header("Location:home.php");
                break;
            case 'Rozpocznij test ponownie':
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
            case 'Sprawdź':
                if (!empty($_POST['hidden_answer'] != 'empty')) {
                    array_push($_SESSION['chosAns'], $_POST['hidden_answer']);
                    $chosenAnswers = $_SESSION['chosAns'];
                }
                if ($k == 10) {
                    $questionIds = $_SESSION['quesId'];
                    $user = $_SESSION['user'];
                    for ($i = 0; $i < count($chosenAnswers); $i++) {
                        $conn->query("UPDATE users SET questions=questions + 1 WHERE id='$user'");
                        try {
                            if (($key = array_search($chosenAnswers[$i], $rightAnswers)) !== false) {
                                // echo "siup";
                                $conn->query("UPDATE questions SET good=good + 1 WHERE id=" . $questionIds[$key]);
                                $conn->query("UPDATE users SET goodAnswers=goodAnswers + 1 WHERE id='$user'");
                                unset($rightAnswers[$key]);
                                $rightAnswers = array_values($rightAnswers);
                                unset($chosenAnswers[$i]);
                                $chosenAnswers = array_values($chosenAnswers);
                                unset($questionIds[$key]);
                                $questionIds = array_values($questionIds);
                                $i = -1;
                                $k--;
                            }
                        } catch (Exception $ex) {

                        }
                    }
                    $_SESSION['chosAns'] = $chosenAnswers;
                    $k = 10;
                    // echo var_dump(count($rightAnswers));
                    echo "<h2>Odpowiedziałeś poprawnie na <span>" . (100 - (10 * count($chosenAnswers))) . "%</span> pytań</h2>
                    <form method='post'>
                    <input type='submit' value='Home' name='testOptions'>
                    <input type='submit' value='Login' name='testOptions'>
                    <input type='submit' value='Register' name='testOptions'>
                    <input type='submit' value='Logout' name='testOptions'>
                    <input type='submit' value='Rozpocznij test ponownie' name='testOptions'>
                    </form>";

                }
                break;
        }
    }
} else {
    header("Location:login.php");
}
$conn->close();
?>
</div>
</body>
</html>
