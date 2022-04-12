<?
include_once 'functions.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ПервыйБит Стажёры</title>
    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
    <div id="wrapper">
        <header class="header">
            <div class="inner">
                <a href="/" class="logo">
                    <img src="/img/logo.svg" alt="логотип Первый Бит">
                </a>
                <nav>
                    <ul class="main-menu">
                        <li>
                            <a href="/blog/">Все статьи</a>
                        </li>
                        <? if (isAuth()) : ?>
                            <li>
                                <a href="/personal/">Моя страница</a>
                            </li>
                            <li>
                                <a href="/personal/myblog">Мои статьи</a>
                            </li>
                        <?endif;?>

                    </ul>
                </nav>
                <div class="auth-box">
                    <? if (isAuth()) : ?>
                        <a href="/personal/" class="user-name">
                            <?
                            $arUserInfo = getUserInfo();
                            echo $arUserInfo['surname'] . ' ' . $arUserInfo['name'];
                            ?>
                        </a>
                    <? else : ?>
                        <a href="/auth.php" class="auth-box__reg-btn btn">Войти</a>
                    <? endif; ?>

                </div>
            </div>
        </header>
        <main class="main">
            <div class="inner">