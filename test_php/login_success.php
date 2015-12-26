<?php
session_start();
//数据库中有盖用户
if(isset($_SESSION['USERNAME']))
{
	//判断是否是同一个IP，近似判断是否是同一个设备
	if($_SERVER['REMOTE_ADDR']     !== $_SESSION['LAST_REMOTE_ADDR'] ||
	   $_SERVER['HTTP_USER_AGENT'] !== $_SESSION['LAST_USER_AGENT']) 
	{

		echo "you come from diffent IP, session destroyed! ";
   		session_destroy();
	}
	else
	{
		echo "you come from same IP, welcome ".$_SESSION['USERNAME']." to login.";
	}
}
//数据库中没有该用户
else
{
    echo "verify failed!";
}
?>