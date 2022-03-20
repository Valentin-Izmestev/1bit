<?
session_start();
include_once '../../main/header.php';

if (!isAuth()) {
    header('Location: auth.php');
    die();
}  
?>


<?
include_once '../../main/footer.php';
?>