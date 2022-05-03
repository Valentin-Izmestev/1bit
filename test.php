<?php
include_once './main/functions.php';
include_once './main/db.php';
include_once './main/classes/User.php';
include_once './main/classes/Post.php';
//  $arrPost = [
//      "title" => "one",  
//  ];

// $arPost = [
//     "title" =>"Заголовок2",
//     "preview_img" =>"",
//     "create_date" =>"2022-04-06",
//     "preview" =>"Анонс новости",
//     "content" =>"<p>Текст новости</p>",
//     "author_id" =>1
//     ];
// $arFile = [

// ];

// echo $_SESSION['current_post_id'];
$arRegTest = [
"check_password" => "",
"date_of_birth" => "1589-10-10",
"email" => "Ddof@mair.ru",
"form" => "form-reg",
"login" => "awd",
"gender" => "m",
"name" => "Влад",
"nickname" => "Влад",
"password" => "123",
"patronymic" => "",
"surname" => "",
"tel" => "",
];

$front = [
"form" => "form-reg",
"name" => "Bdfy",
"patronymic" => "asfdas",
"surname" => "sfsef",
"email" => "sdf@mail.ru",
"login" => "qwe",
"password" => "qwe",
"check_password" => "qwe",
"tel" => "123",
"gender" => "m",
"date_of_birth" => "2022-04-21",
"nickname" => "qwe",
];
// echo $varTest;
// echo '<pre>';
// var_dump($arAnswer);
// echo '</pre>';
echo '<hr>';
// echo $_SESSION['id'];
echo '<hr>';
// echo User::isAuth();
echo '<pre>';
// var_dump($_SESSION);
echo '</pre>';
global $connection;
 $arAnswer = User::validation($front);
echo '<pre>';
var_dump($arAnswer);
echo '</pre>';
?>
