<?
//подключаюсь к БД
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = '1bit';
 
$connection = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
$varTest = 'test';
//сделать $connection глобальной переменной. 
if(!$connection)
{
    echo 'Проблемы с подключением к БД';
    die();
}else{
    // echo 'Подключение к БД успешно';
}
mysqli_query($connection, "SET NAMES 'utf8'"); 