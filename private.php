<?
session_start();

if (!$_SESSION['auth']) {
    header('Location: auth.php');
    die();
}
 
echo '<b>Имя:</b> '.$_SESSION['name'].'<br>';
echo '<b>Отчество:</b> '.$_SESSION['patronymic'].'<br>';
echo '<b>Фамилия:</b> '.$_SESSION['surname'].'<br>';
echo '<b>Email:</b> '.$_SESSION['email'].'<br>';
echo '<b>Телефон:</b> '.$_SESSION['tel'].'<br>';
echo '<b>Пол:</b> '. $_SESSION['gender'].'<br>';
echo '<b>Дата рождения:</b> '.$_SESSION['date_of_birth'].'<br>';
