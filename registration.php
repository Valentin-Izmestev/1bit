<?
include_once './main/classes/User.php';
if (User::isAuth()) {
    header('Location: personal.php');
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
        <form action="" method="POST" class="form form-reg">
            <h1>Форма регистрации</h1>
            <input type="hidden" name="form" value="form-reg">
            <div class="form__grid form__grid--3col">
                <label class="label name">
                    <span class="db caption">Имя*</span>
                    <input type="text" name="name" class="db inputbox " autocomplete="off" placeholder="" value="">
                    <div class="error-message"></div>
                </label>
                <label class="label patronymic">
                    <span class="db caption ">Отчество*</span>
                    <input type="text" name="patronymic" class="db inputbox " autocomplete="off" placeholder="" value="">
                    <div class="error-message"></div>
                </label>
                <label class="label surname">
                    <span class="db caption">Фамилия*</span>
                    <input type="text" name="surname" class="db inputbox " autocomplete="off" placeholder="" value="">
                    <div class="error-message"></div>
                </label>
            </div>
            
            <div class="form__grid form__grid--2col">
                <label class="label email">
                    <span class="db caption">Email*</span>
                    <input type="email" name="email" class="db inputbox " autocomplete="off" placeholder="" value="">
                    <div class="error-message"></div>
                </label>
                <label class="label login">
                    <span class="db caption">Логин*</span>
                    <input type="text" name="login" class="db inputbox " autocomplete="off" placeholder="" value="">
                    <div class="error-message"></div>
                </label>
            </div>

            <div class="form__grid form__grid--2col password">
                <label class="label">
                    <span class="db caption">Пароль*</span>
                    <input type="password" name="password"  class="db inputbox password-input" autocomplete="off" placeholder="" value=""> 
                </label>
                <label class="label">
                    <span class="db caption">Повторите пароль*</span>
                    <input type="password" name="check_password" class="db inputbox check-password-input" autocomplete="off" placeholder="" value=""> 
                </label>
                <div class="pass-error-message error-message"></div>
            </div>
            <div class="form__grid form__grid--2col">
                <label class="label tel">
                    <span class="db caption">Телефон*</span>
                    <input type="tel" name="tel" class="db inputbox " autocomplete="off" placeholder="" value="">
                    <div class="error-message"></div>
                </label>
                <div class="label">
                    <span class="db caption">Укажите пол*</span>
                    <div class="form__radio-box gender">
                        <label class="form__radio-label">
                            <span class="caption">Мужской</span>
                            <input type="radio" name="gender" value="m">
                            <i></i>
                        </label>
                        <label class="form__radio-label">
                            <span class="caption">Женский</span>
                            <input type="radio" name="gender" value="f">
                            <i></i>
                        </label>
                        <div class="error-message"></div>
                    </div>
                </div>
            </div>
            <div class="form__grid form__grid--2col">
                <label class="label date_of_birth">
                    <span class="db caption">Дата рождения*</span>
                    <input type="date" name="date_of_birth" class="db inputbox" autocomplete="off" placeholder="" value="">
                    <div class="error-message"></div>
                </label>
                <label class="label nickname">
                    <span class="db caption">Никнейм*</span>
                    <input type="text" name="nickname" class="db inputbox" autocomplete="off" placeholder="" value="">
                    <div class="error-message"></div>
                </label>
            </div>
            <div class="form__grid form__grid--2col">
                <input type="submit" class="btn" name="submit" value="Зарегистрироваться"> 
            </div>

        </form>
    </div>
</body>
<script src="js/script.js"></script>

</html>