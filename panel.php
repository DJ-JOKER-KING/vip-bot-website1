<?php
session_start(); if(!isset($_SESSION["a"])) exit;
$u=json_decode(file_get_contents("../users.json"),true);
?>
<h2>VIP USERS</h2>
<?php foreach($u as $i=>$v) if(!empty($v["approved"])) echo "$i<br>"; ?>
<form action="action.php" method="post">
<input name="id"><input name="days">
<button name="approve">Approve</button>
</form>