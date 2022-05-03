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

$arAnswer = [
    "status" => false,
    "data" => [],
    "message" =>"" 
];
    
$_POST_JSON = json_decode(file_get_contents("php://input"), true);
// print_r($_POST_JSON);
// $res = User::validation($_POST_JSON);
//  print_r($res);


 
 
if($_POST_JSON['form'] === 'form-auth')
{
     //экспортируемый массив с информацией о прогрессе авторизации  
    if ($_POST_JSON['login']) {
        $login = htmlspecialchars($_POST_JSON['login']);
        $query = "SELECT * FROM `users` WHERE `login`=?";
        $stmt = mysqli_prepare($connection, $query);

        mysqli_stmt_bind_param($stmt, 's', $login);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $data = mysqli_fetch_assoc($result);

        if ($data) {
            $arAnswer["data"]["login"]["error"] = false;
            $arAnswer["data"]["login"]["error_message"] = "";

            $password = $_POST_JSON['password'];

            if ($data['password'] === $password) {

                $arAnswer["data"]["password"]["error"] = false;
                $arAnswer["data"]["password"]["error_message"] = "";

                $arAnswer["status"] = User::login($data);
                $arAnswer["message"] = "Добро пожаловать!";
            }
            else
            {
                $arAnswer["status"] = false;
                $arAnswer["data"]["password"]["error"] = true;
                $arAnswer["data"]["password"]["error_message"] = "Неправильно указан пароль";
            }
            
        }
        else
        {
            $arAnswer["status"] = false;
            $arAnswer["data"]["login"]["error"] = true;
            $arAnswer["data"]["login"]["error_message"] = "Неправильно указан логин";
            $arAnswer["data"]["password"]["error"] = true;
            $arAnswer["data"]["password"]["error_message"] = "Неправильно указан пароль";
        }
    }
    else
    {
        $arAnswer["status"] = false;
        $arAnswer["data"]["login"]["error"] = true;
        $arAnswer["data"]["login"]["error_message"] = "Неправильно указан логин";
        $arAnswer["data"]["password"]["error"] = true;
        $arAnswer["data"]["password"]["error_message"] = "Неправильно указан пароль";
    }

    
}


if($_POST_JSON['form'] === 'form-reg')
{
        $arAnswer = User::validation($_POST_JSON);
        
        if ($arAnswer['status']) {
            // Добавляю нового пользователя в базу данных
            $arAnswer["message"] = User::registeration($_POST_JSON);
        }
}


if($_POST_JSON['form'] === 'form-update')
{
    $arAnswer = User::validation($_POST_JSON);
    if($arAnswer["status"])
    {
        $arAnswer = User::update($_POST_JSON);
    }
    
}

// //работа со статьями 
if($_POST['form'] === 'post_add')
{
    if ($_POST['title']) {  
        $arAnswer = Post::add($_POST, $_FILES);    
    } else {
        $arAnswer["status"] = false;
        $arAnswer["data"]["title"]["error"] = true;
        $arAnswer["data"]["title"]["error_message"] = 'Поле Заголовок не заполнено'; 
    }
    
}
if($_POST['mission'] === 'delete')
{
    $postId = (int) $_POST['post_id'];
    $arAnswer = Post::delete($postId);
}

//код записывает id статьи, которую нужно редактировать в сессию.
if(isset($_POST['current_post_id']))
{
    $_SESSION['current_post_id'] = $_POST['current_post_id']; 
} 

if($_POST['form'] === 'post_edit'){ 
    $postId = (int) $_POST['post_id'];
    
    if(!empty($_FILES)){ 
        $migo =  Post::update($_POST, $_FILES, $postId); 
        // print_r($migo); 
    }
    else
    {
        $arFiles = [
            "no_files" => true
        ];
        $migo =  Post::update($_POST, $arFiles,  $postId);
        // print_r($migo); 
    }
}


// ***************************************
$jsonToFront = json_encode($arAnswer);
echo $jsonToFront;
// echo $arAnswer;
    
?> 
