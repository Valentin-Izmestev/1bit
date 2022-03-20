<?
session_start();
include_once '../main/header.php';
if(!isAuth())
{
    header('Location: auth.php');
    die();
} 
?>

<h1>ПервыйБит Стажёры</h1>
 
<?
$dataUser = getUserInfo();

echo '<b>Имя:</b> '.$dataUser['name'].'<br>';
echo '<b>Отчество:</b> '.$dataUser['patronymic'].'<br>';
echo '<b>Фамилия:</b> '.$dataUser['surname'].'<br>';
echo '<b>Email:</b> '.$dataUser['email'].'<br>';
echo '<b>Телефон:</b> '.$dataUser['tel'].'<br>';
echo '<b>Пол:</b> '. $dataUser['gender'].'<br>';
?>

<?
include_once '../main/footer.php';
?>

