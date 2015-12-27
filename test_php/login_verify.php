<?php

function printInfo($cur_sessionkey, $last_sessionkey) {
        echo "<br><br>";
        echo " CUR  SESSION KEY:".$cur_sessionkey."<br>";
        echo " LAST SESSION KEY:".$last_sessionkey."<br>";
        echo " CUR  REMOTE ADDR:".$_SERVER['REMOTE_ADDR']."<br>";
        echo"  LAST REMOTE ADDR:".$_SESSION['LAST_REMOTE_ADDR']."<br>";
        echo " CUR  AGENT      :".$_SERVER['HTTP_USER_AGENT']."<br>";
        echo " LAST AGENT      :".$_SESSION['HTTP_USER_AGENT']."<br>";
}


//获取表单信息
$username = $_POST['username'];
$password = md5($_POST['password']);

//连接数据库
@mysql_connect("localhost","root","")
or die("connect to db fail!<br>");
@mysql_select_db("php_db")
or die("select to db php_db fail!<br>");

//获取db数据
$query = @mysql_query("select username, password, sessionkey from users where username = '$username'")
or die("process query select fail!");

//判断用户以及密码
if($row = mysql_fetch_array($query))
{
    session_start();
    $sessionkey = session_id();

    //验证用户名密码
    if($username!==$row['username'] || $password !== md5($row['password']))
    {
        echo "verify password fail!<br>";
        return ;
    }
    

    echo "verify password success!<br>";
    //session存在
    if(isset($_SESSION['LAST_REMOTE_ADDR']))
    {
        //与上次同一个device
        if(($sessionkey == $row['sessionkey'])&&
           ($_SESSION['LAST_REMOTE_ADDR'] == $_SERVER['REMOTE_ADDR'])&&
           ($_SESSION['HTTP_USER_AGENT']  == $_SERVER['HTTP_USER_AGENT'])) 
        {

            echo "you come from same IP!<br>";
            printInfo($sessionkey, $row['sessionkey']);
        }
        //与上次不同设备
        else
        {

            //更新数据
            $_SESSION['LAST_REMOTE_ADDR']   = $_SERVER['REMOTE_ADDR'];
            $_SESSION['HTTP_USER_AGENT']    = $_SERVER['HTTP_USER_AGENT'];
            @mysql_query("update users set sessionkey='$sessionkey' where username = '$username'")
            or die("process query update fail!");

            echo "you come from diffent IP, session destroyed!<br>";
            printInfo($sessionkey, $row['sessionkey']);
            session_destroy();
        }
    }
    //session不存在
    else
    {
            //更新数据
            $_SESSION['LAST_REMOTE_ADDR']   = $_SERVER['REMOTE_ADDR'];
            $_SESSION['HTTP_USER_AGENT']    = $_SERVER['HTTP_USER_AGENT'];
            @mysql_query("update users set sessionkey='$sessionkey' where username = '$username'")
            or die("process query update fail!");

            echo "you come for the first time!<br>";
    }

}
else
{

    echo "verify user fail!<br>";
}
?>