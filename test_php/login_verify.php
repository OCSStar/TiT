<?php
//获取表单信息
$username = $_POST['username'];
$password = md5($_POST['password']);

//连接数据库
@mysql_connect("localhost","","")
or die("connect to db fail!");
@mysql_select_db("mydb")
or die("connect to db fail!");

//获取db数据
$query = @mysql_query("select username, password, last_remote_addr， http_user_agent from users where username = '$username' and password = '$password'")
or die("SQL语句执行失败");

//判断用户以及密码
if($row = mysql_fetch_array($query))
{
    session_start();

    //初始化_SESSION
    $_SESSION['USERNAME']           = $row['username'];
    $_SESSION['PASSWORD']           = $row['password'];
    $_SESSION['LAST_REMOTE_ADDR']   = $row['last_remote_addr'];
    $_SESSION['HTTP_USER_AGENT']    = $row['http_user_agent'];

    echo "<a href='login_sucess.php'> welcome for tes php server!</a>";
}
else
{
    echo "<a href='login_fail.php'> verify error!";
}
?>