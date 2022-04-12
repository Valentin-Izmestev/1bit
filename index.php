<? 
session_start();
include_once './main/header.php';
include_once './main/db.php';
include_once './main/classes/Post.php';
$arResult = Post::get($connection);
$postCount = 0;
?>

<h1>Последние статьи наших авторов</h1>
<div class="posts-box clear"> 
    <??>
    <? foreach ($arResult as $item) : ?>
        <?
        $postCount++;
            if($postCount > 6) break; 
        ?> 
        
        <article class="post" >
            <header class="post__header">
                <? if ($item["preview_img"]) : ?>
                    <div class="post__img">
                        <img src="<?=$item["preview_img"]?>" width="350" height="233" alt="<?= $item["title"] ?>">
                    </div>
                <? endif; ?> 
                <div class="post__info"> 
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
include_once './main/footer.php';
?>