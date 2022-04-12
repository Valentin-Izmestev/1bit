<?
session_start();

// функция проверки авторизова ли пользователь
function isAuth()
{
    if($_SESSION['auth'])
    {
        return true;
    }
    else
    {
        return false;
    }
}

// функция выдает данные авторизованного пользователя
function getUserInfo()
{
    //преобразовываю пол пользоваетля в вид
    $gender = '';
    if($_SESSION['gender'] === 'm')
    {
        $gender = 'мужской';
    }
    else
    {
        $gender = 'женский';
    }

    //преобразую дату в ДД.ММ.ГГГГ
    $date = date_create($_SESSION['date_of_birth']);
    $modDate = date_format($date, 'd.m.Y');

    return [
        "login" => $_SESSION['login'],
        "name" => $_SESSION['name'],
        "patronymic" => $_SESSION['patronymic'],
        "surname" => $_SESSION['surname'],
        "email" => $_SESSION['email'],
        "tel" => $_SESSION['tel'],
        "gender" => $gender,
        "date_of_birth" => $modDate
    ] ;
}

//более простая функция дампа массива.
function dump($arr)
{
    if(isAuth()){
        echo '<pre>';
        var_dump($arr);
        echo '</pre>';
    } 
}


 