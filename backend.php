<?php 
session_start();
include_once './main/classes/User.php';
include_once './main/functions.php';
include_once './main/db.php'; //подключаю файл в котором устанавливается соединение с базой данных
include_once './main/classes/Post.php';
//если пользователь авторизован, то перевожу на указанную страницу и прекращаю выполнение скрипта.
if(User::isAuth())
{
    // header('Location: ./personal/');
    // die();
}
 
    
$_POST_JSON = json_decode(file_get_contents("php://input"), true);
 
//код записывает id статьи, которую нужно редактировать в сессию.
if(isset($_POST['current_post_id']))
{
    $_SESSION['current_post_id'] = $_POST['current_post_id'];
    echo true;
}
else
{
    echo false;
}
 
 
if($_POST_JSON['form'] === 'form-auth')
{
    echo User::login($_POST_JSON, $connection);
}
if($_POST_JSON['form'] === 'form-reg')
{
    echo User::registeration($_POST_JSON, $connection);
}
if($_POST_JSON['form'] === 'form-update')
{
    echo User::update($_POST_JSON, $connection);
}
if($_POST['form'] === 'post_add')
{
    echo Post::add($_POST, $_FILES,  $connection);  
}
if($_POST['mission'] === 'delete')
{
    $postId = (int) $_POST['post_id'];
    echo Post::delete($postId, $connection);
}
if($_POST['form'] === 'post_edit'){ 
    $postId = (int) $_POST['post_id'];
    
    if(!empty($_FILES)){ 
        $migo =  Post::update($_POST, $_FILES, $connection, $postId); 
        print_r($migo); 
    }
    else
    {
        $arFiles = [
            "no_files" => true
        ];
        $migo =  Post::update($_POST, $arFiles, $connection, $postId);
        print_r($migo); 
    }
}

 
?> 
