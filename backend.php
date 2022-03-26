<?php 
session_start();
include_once './main/functions.php';
include_once './main/db.php'; //подключаю файл в котором устанавливается соединение с базой данных
//если пользователь авторизован, то перевожу на указанную страницу и прекращаю выполнение скрипта.
if(isAuth())
{
    header('Location: personal.php');
    die();
}

    
$_POST = json_decode(file_get_contents("php://input"), true);
 
//экспортируемый массив с информацией о прогрессе авторизации 
$arAnswer =[
    "error" =>[
        "errorStatus" => true,
        "loginError" => "Y",
        "loginErrorMessage" => "Неправильно указан логин",
        "passwordError" => "Y",
        "passwordErrorMessage" => "Неправильно указан пароль",
    ],  
];
 
if($_POST['login'])
{
    $login = htmlspecialchars($_POST['login']);
    $query = "SELECT * FROM `users` WHERE `login`=?";
    $stmt = mysqli_prepare($connection, $query);

    mysqli_stmt_bind_param($stmt, 's', $login);  
    mysqli_stmt_execute($stmt); 

    $result = mysqli_stmt_get_result($stmt);  
    $data = mysqli_fetch_assoc($result);  

    if($data)
    {
        $arAnswer['error']['loginError'] = 'N';
        $password = $_POST['password'];

        if($data['password'] === $password)
        {
            $arAnswer['error']['errorStatus'] = false;
            $arAnswer['error']['passwordError'] = 'N';

            //заполняю сессию данными о пользователе.
            $_SESSION['auth'] = true;
            $_SESSION['login'] = $data["login"];
            $_SESSION['name'] = $data["name"];
            $_SESSION['patronymic'] = $data["patronymic"];
            $_SESSION['surname'] = $data["surname"];
            $_SESSION['email'] = $data["email"];
            $_SESSION['tel'] = $data["tel"];
            $_SESSION['gender'] = $data["gender"];
            $_SESSION['date_of_birth'] = $data["date_of_birth"]; 
        }
    }
 
}

    $json = json_encode($arAnswer);
    echo $json; 

?> 
