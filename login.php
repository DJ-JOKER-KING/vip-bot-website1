<?php
session_start();
if($_POST && $_POST["p"]=="admin123"){$_SESSION["a"]=1;header("Location: panel.php");}
?>
<form method="post"><input name="p"><button>Login</button></form>