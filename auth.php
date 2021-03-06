<? 
session_start();
 include_once './main/classes/User.php';
 
    if(User::isAuth()){
        header('Location: ./personal/');
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Страница авторизации</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="wrapper"> 
        <form action="" method="POST" class="form-auth form">
            <h1>Форма авторизации</h1>
            <input type="hidden" name="form" value="form-auth">
            <label class="label">
                <span class="db caption">Логин</span>
                <input type="text" name="login" class="db inputbox login" autocomplete="off" placeholder="" value="">
            </label>
            <label class="label">
                <span class="db caption">Пароль</span>
                <input type="password" name="password" class="db inputbox password" autocomplete="off" placeholder="" value="">
            </label>
            <input type="submit" class="btn" name="submit" value="Авторизоваться">
            <div class="form-auth__box alCenter">
                <a href="registration.php" class="form-auth__link inb">Зарегистрироваться</a>
            </div>
            
        </form> 
    </div> 
</body>
<script src="js/script.js"></script>

</html>