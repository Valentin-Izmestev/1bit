<?
session_start();
include_once './main/header.php';
if(!isAuth())
{
    header('Location: auth.php');
    die();
}
$dataUser = getUserInfo();

echo '<b>Имя:</b> '.$dataUser['name'].'<br>';
echo '<b>Отчество:</b> '.$dataUser['patronymic'].'<br>';
echo '<b>Фамилия:</b> '.$dataUser['surname'].'<br>';
echo '<b>Email:</b> '.$dataUser['email'].'<br>';
echo '<b>Телефон:</b> '.$dataUser['tel'].'<br>';
echo '<b>Пол:</b> '. $dataUser['gender'].'<br>';
echo '<b>Дата рождения:</b> '.$dataUser['date_of_birth'].'<br>';



