<?
session_start();
include_once '../../main/header.php';
include_once '../../main/db.php';
include_once '../../main/classes/Post.php';

if (!isAuth()) {
    header('Location: ../../auth.php');
    die();
}
$arResult = Post::get(true);
?>
<h1>Мои статьи</h1> 
<a href="/personal/myblog/add.php" class="btn btn_add-post">Добавить статью</a>
<div class="posts-box clear"> 
    <? foreach ($arResult as $item) : ?>
        <article class="post" id="<?= $item["id"] ?>">
            <header class="post__header">
                <? if ($item["preview_img"]) : ?>
                    <div class="post__img">
                        <img src="<?=$item["preview_img"]?>" width="350" height="233" alt="<?= $item["title"] ?>">
                    </div>
                <? endif; ?> 
                <div class="post__info">
                    <div class="post__control-block">

                        <a href="edit.php" class="btn post__btn-edit post__btn" title="Редактировать">
                            <svg version="1.1" id="pencil" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 56.8 56.5" style="enable-background:new 0 0 56.8 56.5;" xml:space="preserve">
                                <g>
                                    <rect x="11.5" y="10.3" shape-rendering="crispEdges" transform="matrix(0.7071 -0.7071 0.7071 0.7071 -9.7166 29.0927)" class="st0" width="37.5" height="32" />
                                </g>
                                <path class="st1" d="M27.8,52.5c0.5-0.1,0.9-0.6,0.8-1.1c-0.1-0.5-0.6-0.9-1.1-0.8L27.8,52.5z M1.2,55.4l-1-0.1L0,56.5l1.3-0.2L1.2,55.4z M6,29.1c0.1-0.5-0.3-1.1-0.8-1.1c-0.5-0.1-1.1,0.3-1.1,0.8L6,29.1z M27.5,50.6L1,54.4l0.3,2l26.4-3.8L27.5,50.6zM2.2,55.5L6,29.1l-2-0.3L0.2,55.2L2.2,55.5z" />
                                <path class="st1" d="M1.2,55.4L3,45c0,0,3.2,2,4.9,3.7c1.7,1.7,3.7,4.9,3.7,4.9L1.2,55.4z" />
                                <line class="st0" x1="21.3" y1="45.2" x2="48" y2="18.4" />
                                <line class="st0" x1="11.4" y1="35.3" x2="38.1" y2="8.5" />
                            </svg>
                        </a>
                        <a class="btn post__btn-remove post__btn" title="Удалить"></a>
                    </div>
                    <time datetime="<?= $item["create_date"] ?>"><?= $item["create_date"] ?></time>
                    <span class="post__info__author">
                        <b>Автор:</b> <?= $item["surname"] ?> <?= $item["name"] ?> <?= $item["patronymic"] ?> (<?= $item["nickname"] ?>)
                    </span>
                    <h2 class="post-title"><?= $item["title"] ?></h2>
                    <p class="post_preview">
                        <?= $item["preview"] ?>
                    </p>
                    <button class="btn btn__read-post">Подробнее</button>
                </div>
            </header>
            <div class="post__content">
                <?= $item["content"] ?>
            </div>
        </article>
    <? endforeach; ?>
</div>

<?
include_once '../../main/footer.php';
?>