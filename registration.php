<?php
header("Content-Type: text/html; charset=utf-8");
require('include/func.inc.php');
$link = mysqli_connect('localhost', 'root', '', 'task');

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    
    $error = 1;
    
    $login = filter($_POST['login']);
    if($login != ''){
        $login_query = mysqli_query($link, "SELECT login FROM users WHERE login='$login'");
        $login_query = mysqli_fetch_array($login_query);
        if($login_query != ''){
            $err_login = "Логин занят";
            $error = 0;
        }
    }else{
        $err_login = "Введите логин";
        $error = 0;
    }
    
    $pass = filter($_POST['pass']);
    if($pass != ''){
        $pass_query = mysqli_query($link, "SELECT password FROM users WHERE password='$pass'");
        $pass_query = mysqli_fetch_array($pass_query);
        if($pass_query != ''){
            $err_pass = "Пароль занят";
            $error = 0;
        }
    }else{
        $err_pass = "Введите пароль";
        $error = 0;
    }
    
    $year = (int) filter($_POST['year']);
    if($year == 0 or $year > 2016){
        $err_year = "Неверное значение года";
        $error = 0;
    }elseif($year > 2011 and $year <= 2016){
        $err_year = "Too young!"; 
        $error = 0;   
    }elseif($year < 1866){
        $err_year = "Too old!";
        $error = 0;
    }
    if($error == 1){
        $insert = mysqli_query($link, "INSERT INTO users (login, password, bday) VALUES ('$login', '$pass', $year)");
        session_start();
        $id = mysqli_query($link, "SELECT id FROM users WHERE login='$login'");
        $row = mysqli_fetch_array($id);
        $_SESSION['id'] = $row['id'];
        $_SESSION['auth'] = 1;
        $host  = $_SERVER['HTTP_HOST'];
        $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'counter.php';
        header("Location: http://$host$uri/$extra");
        exit;
    }
}
?>
<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="content-type" content="text/html" />
    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <link href='css/bootstrap.css' rel='stylesheet' type='text/css' media='all'>
	<title>Registration</title>
</head>

<body>

<div id="form">
<form action="registration.php" method="POST" role="form">
  <div class="form-group">
    <label for="login">Логин</label>
    <input type="text" name="login" value="<?=$login?>" maxlength="255" type="login" class="form-control" id="login" placeholder="Введите логин" />
    <p class="help-block"><?=$err_login?></p>    
  </div>

  <div class="form-group">
    <label for="pass">Пароль</label>
    <input type="password" name="pass" value="<?=$pass?>" maxlength="255" class="form-control" id="pass" placeholder="Пароль" />
    <p class="help-block"><?=$err_pass?></p>    
  </div>
  
  <div class="form-group">
    <label for="year">Год рождения</label>
    <input type="text" name="year" id="year" value="<?=$year?>" maxlength="4" class="form-control" />
    <p class="help-block"><?=$err_year?></p>   
  </div>

<input type="submit" value="Зарегистрироваться" class="btn btn-success" />
<a href="index.php" class="btn btn-link">Войти</a>
</form>
</div>

</body>
</html>
<?php
	mysqli_close($link);
?>