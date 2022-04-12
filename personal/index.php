<?
session_start();
include_once '../main/classes/User.php';
include_once '../main/header.php';
if (!isAuth()) {
    header('Location: ../auth.php');
    die();
}
$dataUser = User::getData();
?>

<h1>Данные пользователя</h1>

<div class="user-info__wrapper">
    <div class="user-info">
        <a href="./update.php" class="btn btn-edit fright">Редактировать</a>
        <br>
        <br>
        <a href="/logout.php" class="btn btn-edit fright">Выйти</a>
        <ul class="user-info__list">
            <li><b>Имя:</b> <? echo $dataUser['name']; ?></li>
            <li><b>Отчество:</b> <?= $dataUser['patronymic']; ?></li>
            <li><b>Фамилия:</b> <?= $dataUser['surname']; ?></li>
            <li><b>Логин:</b> <?= $dataUser['login']; ?></li>
            <li><b>Email:</b> <?= $dataUser['email']; ?></li>
            <li><b>Телефон:</b> <?= $dataUser['tel']; ?></li>
            <li><b>Пол:</b> <?= $dataUser['gender']; ?></li>
            <li><b>Дата рождения:</b> <?= $dataUser['date_of_birth']; ?></li>
            <li><b>Никнейм:</b> <?= $dataUser['nickname']; ?></li>
        </ul>
    </div>  
</div>



<?
include_once '../main/footer.php';
?>