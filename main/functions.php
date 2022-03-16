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
    return [
        "login" => $_SESSION['login'],
        "name" => $_SESSION['name'],
        "patronymic" => $_SESSION['patronymic'],
        "surname" => $_SESSION['surname'],
        "email" => $_SESSION['email'],
        "tel" => $_SESSION['tel'],
        "gender" => $_SESSION['gender'],
        "date_of_birth" => $_SESSION['date_of_birth']
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







