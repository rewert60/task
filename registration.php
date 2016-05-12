<?php
header("Content-Type: text/html; charset=utf-8");
require('include/db.inc.php');
require('include/func.inc.php');

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    
    $error = 1;
    
    $login = filter($_POST['login']);
    if($login != '' and preg_match("/^[A-Za-z0-9_-]{3,16}$/",$login)){
        $login_query = mysqli_query($link, "SELECT login FROM users WHERE login='$login'");
        $login_query = mysqli_fetch_array($login_query);
        if($login_query != ''){
            $err_login = "Логин занят";            
        }
    }else{
        $err_login = "Введите корректный логин";        
    }
    
    $pass = filter($_POST['pass']);
    if($pass != '' and preg_match("/^[A-Za-z0-9_-]{3,16}$/",$pass)){
        $pass_query = mysqli_query($link, "SELECT password FROM users WHERE password='$pass'");
        $pass_query = mysqli_fetch_array($pass_query);
        if($pass_query != ''){
            $err_pass = "Пароль занят";
        }
    }else{
        $err_pass = "Введите корректный пароль";
    }
    
    $day = (int) filter($_POST['day']);
    $mounth = (int) filter($_POST['mounth']);
    $year = (int) filter($_POST['year']);
    
    $d = getdate();
    $yearn = $d['year'];  
    $mounthn = $d['mon']; 
    $dayn = $d['mday'];    
    
        if(checkdate( $mounth, $day, $year )){
        if($year > $yearn){
            $err_year = "Неверная дата";
        }elseif($yearn - $year > 151){
            $err_year = "Too old!";
        }elseif($yearn - $year < 5){
            $err_year = "Too young!";
        }elseif($yearn - $year == 151){
            if($mounthn - $mounth > 0){
                $err_year = "Too old!";
            }elseif($mounthn - $mounth == 0){
                if($dayn - $day > 0){
                    $err_year = "Too old!";
                }elseif($dayn - $day == 0){
                    $err_year = "Happy birthday! But, too old :(";
                }
            }
        }elseif($yearn - $year == 5){
            if($mounthn - $mounth < 0){
                $err_year = "Too young!";
            }elseif($mounthn - $mounth == 0){
                if($dayn - $day < 0){
                    $err_year = "Too young!";
                }
            }
        }
    }else{
        $err_year = "Неверная дата";
    }
    
    if($err_login or $err_pass){
        $red = "color: red;";
    }
    if($err_login or $err_pass or $err_year){
        $error = 0;
    }
    
    if($error == 1){
        $bday = $day."-".$mounth."-".$year;
        $pass = md5(strrev($pass));
        mysqli_query($link, "INSERT INTO users (login, password, bday) VALUES ('$login', '$pass', '$bday')") or die(mysqli_error($link));
        session_start();
        $id = mysqli_query($link, "SELECT id FROM users WHERE login='$login'");
        $row = mysqli_fetch_array($id);
        $_SESSION['id'] = $row['id'];
        $_SESSION['auth'] = 1;
        $host  = $_SERVER['HTTP_HOST'];
        $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'counter.php';
        if(!mysqli_error($link)){
            header("Location: http://$host$uri/$extra");
            exit;           
        }else{
            echo mysqli_error($link);
        }
        
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
    <p class="login" style="<?=$red?>">Логин и пароль может состоять из латинских букв, дефиса или нижнего подчеркивания. Длинна 3-16 символов.</p>
    <input type="text" name="login" value="<?=$login?>" maxlength="255" type="login" class="form-control" id="login" placeholder="Введите логин" />
    <p class="help-block"><?=$err_login?></p>    
  </div>

  <div class="form-group">
    <label for="pass">Пароль</label>
    <input type="password" name="pass" value="<?=$pass?>" maxlength="255" class="form-control" id="pass" placeholder="Пароль" />
    <p class="help-block"><?=$err_pass?></p>    
  </div>
  
  <div class="form-group form-inline">
    <p>Введите дату рождения числами</p>    
    <input type="text" name="day" id="day" value="<?=$day?>" maxlength="2" class="form-control" placeholder="день" /> - 
    <input type="text" name="mounth" id="mounth" value="<?=$mounth?>" maxlength="2" class="form-control" placeholder="месяц" /> - 
    <input type="text" name="year" id="year" value="<?=$year?>" maxlength="4" class="form-control" placeholder="год" />
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
