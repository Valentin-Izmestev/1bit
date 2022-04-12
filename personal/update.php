<?
session_start();
include_once '../main/classes/User.php';
include_once '../main/header.php';
if (!isAuth()) {
    header('Location: auth.php');
    die();
}
$dataUser = User::getData();
?>

<h1>Данные пользователя</h1>

<div class="user-info--edit">
    <form class="formUpdate" method="POST">
        <button type="submit" class="btn btn-save fright">Сохранить</button>
        <input type="hidden" name="form" value="form-update">
        <input type="hidden" name="id" value="<?= $dataUser['id']; ?>">
        <ul class="user-info__list">
            <li>
                <label>
                    <b>Имя:</b> <input type="text" name="name" class="formUpdate__input" autocomplete="off" value="<?= $dataUser['name']; ?>">
                </label>
                <span class="error-message error-message__name"></span>
            </li>
            <li>
                <label>
                    <b>Отчество:</b> <input type="text" name="patronymic" class="formUpdate__input" autocomplete="off" value="<?= $dataUser['patronymic']; ?>">
                </label>
                <span class="error-message error-message__patronymic"></span>
            </li>
            <li>
                <label>
                    <b>Фамилия:</b> <input type="text" name="surname" class="formUpdate__input" autocomplete="off" value="<?= $dataUser['surname']; ?>">
                </label>
                <span class="error-message error-message__surname"></span>
            </li>
            <li>
                <label>
                    <b>Логин:</b> <input type="text" name="login" class="formUpdate__input" autocomplete="off" value="<?= $dataUser['login']; ?>">
                </label>
                <span class="error-message error-message__login"></span>
            </li>
            <li>
                <label>
                    <b>Email:</b> <input type="email" name="email" class="formUpdate__input" autocomplete="off" value="<?= $dataUser['email']; ?>">
                </label>
                <span class="error-message error-message__email"></span>
            </li>
            <li>
                <label>
                    <b>Телефон:</b> <input type="tel" name="tel" class="formUpdate__input" autocomplete="off" value="<?= $dataUser['tel']; ?>">
                </label>
                <span class="error-message error-message__tel"></span>
            </li>
            <li class="flex">
                <b>Укажите пол:</b>
                <div class="form__radio-box">
                    <label class="form__radio-label">
                        <span class="caption">Мужской</span>
                        <input type="radio" name="gender" value="m" <? if ($dataUser['gender'] === 'мужской') echo 'checked'; ?>>
                        <i></i>
                    </label>
                    <label class="form__radio-label">
                        <span class="caption">Женский</span>
                        <input type="radio" name="gender" value="f" <? if ($dataUser['gender'] === 'женский') echo 'checked'; ?>>
                        <i></i>
                    </label>
                    <span class="error-message error-message__gender"></span>
                </div>
            </li>
            <li>
                <label>
                    <b>Дата рождения:</b> <input type="date" name="date_of_birth" class="formUpdate__input" autocomplete="off" value="<?= $_SESSION['date_of_birth']; ?>">
                </label>
                <span class="error-message error-message__date"></span>
            </li>
            <li>
                <label>
                    <b>Никнейм:</b> <input type="text" name="nickname" class="formUpdate__input" autocomplete="off" value="<?= $dataUser['nickname']; ?>">
                </label>
                <span class="error-message error-message__nickname"></span>
            </li>
        </ul>
    </form>
</div>


<template id="successful-message-template">
    <div class="successful-message"> 
    </div>
</template>


<?
include_once '../main/footer.php';
?>