<?php
session_start(); if(!isset($_SESSION["a"])) exit;
$f="../users.json"; $u=json_decode(file_get_contents($f),true);
if(isset($_POST["approve"])) $u[$_POST["id"]]=["approved"=>1,"expire"=>time()+($_POST["days"]*86400),"device"=>null];
file_put_contents($f,json_encode($u,JSON_PRETTY_PRINT));
header("Location: panel.php");