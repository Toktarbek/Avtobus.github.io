<?php 
$db=mysqli_connect('127.0.0.1','root','','avtobus');
if (mysqli_connect_errno()) {
	echo 'Дерек қорға қосылу келесі қателермен сәтсіз болды: '.mysqli_connect_error();
	die();
}
mysqli_set_charset($db, 'utf8');

session_start();
require_once $_SERVER['DOCUMENT_ROOT'].'/avtobus/config.php';
require_once BASEURL.'helpers/helpers.php';

if (isset($_SESSION['success_flash'])) {
	echo '<div class="bg-success"><p class="text-success text-center">'.$_SESSION['success_flash'].'</p></div>';
	unset($_SESSION['success_flash']);
}
if (isset($_SESSION['error_flash'])) {
	echo '<div class="bg-danger"><p class="text-danger text-center">'.$_SESSION['error_flash'].'</p></div>';
	unset($_SESSION['error_flash']);
}
