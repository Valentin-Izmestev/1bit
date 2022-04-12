<?php
include_once './main/functions.php';
include_once './main/db.php';
include_once './main/classes/User.php';
include_once './main/classes/Post.php';
 $arrPost = [
     "title" => "one",  
 ];

$arPost = [
    "title" =>"Заголовок2",
    "preview_img" =>"",
    "create_date" =>"2022-04-06",
    "preview" =>"Анонс новости",
    "content" =>"<p>Текст новости</p>",
    "author_id" =>1
    ];
$arFile = [

];

echo $_SESSION['current_post_id'];
?>
