<?php 
 
const LOGIN = 'ivanos';
const PASSWORD = '12345';
const USER_NAME = 'Иванов Иван Иванович';
$userName = 'Иванов Иван Иванович';

$loginError = '';
$loginErrorMessage = '';
$passwordError = '';
$passwordErrorMessage = '';
 
$hellowUser = '';


if(isset($_POST['submit']))
{
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
        if($login !== LOGIN)
        {
            $login = '';
            $loginError = 'error';
            $loginErrorMessage = 'Неправильно указан логин';
        }
        if($password !== PASSWORD)
        {
            $password = ''; 
            $passwordError = 'error';
            $passwordErrorMessage = 'Неправильно указан пароль';
        }
        
             
    }else{
        $loginError = '';
        $loginErrorMessage = '';
        $passwordError = '';
        $passwordErrorMessage = '';  
        $hellowUser = '<h1>Приветствую, '.$userName.'</h1>';
    }
}


?> 
