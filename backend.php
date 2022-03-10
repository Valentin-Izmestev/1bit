<?php 
 
const LOGIN = 'ivanos';
const PASSWORD = '12345';
const USER_NAME = 'Иванов Иван Иванович';
$userName = 'Иванов Иван Иванович';

$_POST = json_decode(file_get_contents("php://input"), true);

$arAnswer =[
    "error" =>[
        "errorStatus" => false,
        "loginError" => "N",
        "loginErrorMessage" => "Неправильно указан логин",
        "passwordError" => "N",
        "passwordErrorMessage" => "Неправильно указан пароль",
    ], 
    "userData" => ""
];

$loginError = '';
$loginErrorMessage = '';
$passwordError = '';
$passwordErrorMessage = ''; 
$hellowUser = '';


    if(isset($_POST['login']))
    {
        $login = trim($_POST['login']); 
    }

    if(isset($_POST['password']))
    {
        $password = trim($_POST['password']); 
    } 
    
    if($login !== LOGIN || $password !== PASSWORD)
    { 
        $arAnswer['error']['errorStatus'] = true;

        if($login !== LOGIN)
        {
            $login = '';
            $arAnswer['error']["loginError"] = "Y"; 
        }

        if($password !== PASSWORD)
        { 
            $arAnswer['error']["passwordError"] = "Y"; 
        }

        $json = json_encode($arAnswer);
        echo $json;

        die();
             
    }else{
        $arAnswer['error']['errorStatus'] = false;

        // $loginError = '';
        // $loginErrorMessage = '';
        // $passwordError = '';
        // $passwordErrorMessage = '';  
        $arAnswer["userData"] = "Приветствую, $userName";  
        $json = json_encode($arAnswer);
        echo $json;
        die();
    } 


?> 
