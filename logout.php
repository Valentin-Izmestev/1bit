<?php
session_start();
include_once './main/db.php';
include_once './main/classes/User.php';

User::logout();
header('Location: /');