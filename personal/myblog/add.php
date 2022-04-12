<?
session_start();
include_once '../../main/header.php';

if (!isAuth()) {
    header('Location: ../../auth.php');
    die();
}
?>
<h1>Добавить статью</h1> 
<form class="post-add post-form" method="POST">
    <input type="hidden" name="form" value="post_add">
    <label >
        <span class="caption">Заголовок</span>
        <input type="text" name="title" class="inputbox title">
        <span class="error-message"></span>
    </label>
    <br>
    <label>
        <span class="caption">Изображение</span>
        <input type="file" name="preview_img"  class="preview_img">
        <span class="error-message"></span>
    </label>
    <br>
    <br>
    <label>
        <span class="caption">Текст анонса</span> 
        <textarea name="preview" class="inputbox preview"></textarea>
        <span class="error-message"></span>
    </label>
    <br>
    <label>
        <span class="caption">Текст статьи</span> 
        <textarea name="content" class="inputbox content"></textarea>
        <span class="error-message"></span>
    </label>
    <br>  
    <input type="submit" class="btn btn_inb" value="Сохранить">
    <a href="/personal/myblog/" class="btn btn_inb">Отмена</a>
</form>
<template id="successful-message-template">
    <div class="successful-message"> 
    </div>
</template>
<? 
include_once '../../main/footer.php';
?>