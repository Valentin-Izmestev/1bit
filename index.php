 
<?include './backend.php';?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>1 бит</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="wrapper">
        <?if($hellowUser):?>
            <div class="hellow">
                 <?=$hellowUser;?>
            </div>
        <?else:?>
            <form action="" method="POST" class="form-auth" class="form">
            <h1>Форма авторизации</h1> 
            <label>
                <span class="db caption">Логин</span>
                <input type="text" name="login" class="db inputbox <? if($loginError){echo $loginError;}?>" autocomplete="off" placeholder="<?if($loginErrorMessage){echo $loginErrorMessage;}?>" value="<? echo $login;?>">
            </label>
            <label>
                <span class="db caption">Пароль</span>
                <input type="password" name="password" class="db inputbox <? if($passwordError){echo $passwordError;}?>" autocomplete="off" placeholder="<? if($passwordErrorMessage){echo $passwordErrorMessage;}?>" value="<?echo $password;?>">
            </label>  
            <input  type="submit" name="submit" value="Авторизоваться">
        </form>
        <?endif;?>
        
    </div>
</body>

</html>
