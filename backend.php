<?php 

//подключаюсь к БД
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = '1bit';
 
$connection = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);

if(!$connection)
{
    echo 'Проблемы с подключением к БД';
    die();
}else{
    // echo 'Подключение к БД успешно';
}
mysqli_query($connection, "SET NAMES 'utf8'"); 
    
$_POST = json_decode(file_get_contents("php://input"), true);
 
//экспортируемый массив
$arAnswer =[
    "error" =>[
        "errorStatus" => true,
        "loginError" => "Y",
        "loginErrorMessage" => "Неправильно указан логин",
        "passwordError" => "Y",
        "passwordErrorMessage" => "Неправильно указан пароль",
    ], 
    // "name" => "",
    // "patronymic" => "",
    // "surname" => "",
    // "email" => "",
    // "tel" => "",
    // "gender" => "",
    // "date_of_birth" => ""
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

            //заполняю экспортирумый массив данными пользователя из БД
            // $arAnswer["name"] = $data["name"];
            // $arAnswer["patronymic"] = $data["patronymic"];
            // $arAnswer["surname"] = $data["surname"];
            // $arAnswer["email"] = $data["email"];
            // $arAnswer["tel"] = $data["tel"];
            // $arAnswer["gender"] = $data["gender"];
            // $arAnswer["date_of_birth"] = $data["date_of_birth"]; 

            session_start();
            $_SESSION['auth'] = true;
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
