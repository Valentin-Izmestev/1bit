<?
session_start();
include_once './main/header.php';

if (!isAuth()) {
    header('Location: auth.php');
    die();
} 
session_destroy();
