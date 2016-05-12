<?php
header("Content-Type: text/html; charset=utf-8");
require('include/db.inc.php');
require('include/func.inc.php');
require('include/visit.inc.php');

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $login = filter($_POST['login']);
        if($login != '' and preg_match("/^[A-Za-z0-9_-]{3,16}$/",$login)){
            $login_query = mysqli_query($link, "SELECT login FROM users WHERE login='$login'");
            $login_query = mysqli_fetch_array($login_query);
            if($login_query == ''){
                $err = "Неверный логин или пароль";                
            }
        }else{
            $err = "Введите логин и пароль";            
        }
        
        $pass = filter($_POST['pass']);
        if($pass != '' and preg_match("/^[A-Za-z0-9_-]{3,16}$/",$pass)){
            $pass = md5(strrev($pass));
            $pass_query = mysqli_query($link, "SELECT password FROM users WHERE password='$pass'");            
            $pass_query = mysqli_fetch_array($pass_query);
            if($pass_query == ''){
                $err = "Неверный логин или пароль";                
            }
        }else{
            $err = "Введите логин и пароль";            
        }
        if(!$err){        
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
	<title>Login</title>
</head>

<body>
<pre>
visitors: <?=$visitors?> 
  visits: <?=$visit_sum[0]?>

</pre>
<div id="form">
<form action="index.php" method="POST" role="form">
  <p class="help-block"><?=$err?></p>
  <div class="form-group">
    <label for="login">Логин</label><br />
    <input type="text" name="login" type="login" class="form-control" id="login" placeholder="Введите логин" value="<?=$login?>" /><br />    
  </div>
  <div class="form-group">
    <label for="pass">Пароль</label><br />
    <input type="password" name="pass" class="form-control" id="pass" placeholder="Пароль" /><br />
  </div>
  <input type="submit" value="Войти" class="btn btn-success" />
  <a href="registration.php" class="btn btn-link">Регистрация</a>
</form>
</div>

</body>
</html>
<?php
	mysqli_close($link);
?>
