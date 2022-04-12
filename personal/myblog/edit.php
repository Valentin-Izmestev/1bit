<?
session_start();
include_once '../../main/header.php';
include_once '../../main/db.php';
include_once '../../main/classes/Post.php';

if (!isAuth()) {
    header('Location: ../../auth.php');
    die();
}
if(empty($_SESSION['current_post_id'])){
    header('Location: /personal/myblog/');
    die();
}
$post = Post::get($connection, false, $_SESSION['current_post_id']);


?>
<h1>Редактировать статью</h1> 
<form class="post-edit post-form" method="POST">
    <input type="hidden" name="form" value="post_edit">
    <input type="hidden" name="post_id" value="<?= $post['id']?>">
    <label>
        <span class="caption">Заголовок</span>
        <input type="text" name="title" class="inputbox title" value="<?= $post['title'] ?>">
        <span class="error-message"></span>
    </label>
    <br>
    <? if ($post['preview_img']) : ?>
        <div class="img-box">
            <button class="img-box_remove"></button>
            <img src="<?= $post['preview_img'] ?>" alt="<?= $post['title'] ?>" class="spacer">
        </div>
        <label class="preview-img__label">
            <span class="caption">Изображение</span>
            <input type="file" name="preview_img" class="preview_img" disabled>
            <span class="error-message"></span>
        </label>
    <?else:?>
        <label class="preview-img__label">
            <span class="caption">Изображение</span>
            <input type="file" name="preview_img" class="preview_img">
            <span class="error-message"></span>
        </label>
    <? endif ?>

    <br>
    <br>
    <label>
        <span class="caption">Текст анонса</span>
        <textarea name="preview" class="inputbox preview"><?= $post['preview'] ?></textarea>
        <span class="error-message"></span>
    </label>
    <br>
    <label>
        <span class="caption">Текст статьи</span>
        <textarea name="content" class="inputbox content"><?= $post['content'] ?></textarea>
        <span class="error-message"></span>
    </label>
    <br>
    <input type="submit" class="btn btn_inb" value="Сохранить">
    <a href="/personal/myblog/" class="btn btn_inb btn__edit-cancel">Отмена</a>
</form>
<template id="successful-message-template">
    <div class="successful-message">
    </div>
</template>
<?
include_once '../../main/footer.php';
?>