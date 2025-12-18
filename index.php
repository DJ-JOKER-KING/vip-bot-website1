<?php
$BOT_TOKEN="YOUR_BOT_TOKEN";
$ADMIN_ID="123456789";
$usersFile="users.json";

function send($id,$msg){
 global $BOT_TOKEN;
 file_get_contents("https://api.telegram.org/bot$BOT_TOKEN/sendMessage?chat_id=$id&text=".urlencode($msg));
}
function saveU($u,$f){file_put_contents($f,json_encode($u,JSON_PRETTY_PRINT));}

$u=json_decode(file_get_contents("php://input"),true);
$chat=$u["message"]["chat"]["id"]??0;
$text=trim($u["message"]["text"]??"");
$users=file_exists($usersFile)?json_decode(file_get_contents($usersFile),true):[];

if($text=="/start"){
 if(!isset($users[$chat])){
  $users[$chat]=["approved"=>false];
  saveU($users,$usersFile);
  send($ADMIN_ID,"New User: $chat");
 }
 send($chat,"Waiting for approval");
 exit;
}

if($chat!=$ADMIN_ID){
 if(empty($users[$chat]["approved"])) exit;
 if($users[$chat]["expire"]<time()){
  unset($users[$chat]); saveU($users,$usersFile);
  send($chat,"VIP expired"); exit;
 }
 $d=$_SERVER["HTTP_USER_AGENT"];
 if(isset($users[$chat]["device"]) && $users[$chat]["device"]!=$d){
  send($chat,"Device locked"); exit;
 }
 if(empty($users[$chat]["device"])){
  $users[$chat]["device"]=$d; saveU($users,$usersFile);
 }
}

if($chat==$ADMIN_ID){
 if(preg_match('/\/approve (\d+) (\d+)/',$text,$m)){
  $users[$m[1]]=["approved"=>1,"expire"=>time()+($m[2]*86400),"device"=>null];
  saveU($users,$usersFile);
  send($m[1],"VIP approved");
 }
 if(preg_match('/\/remove (\d+)/',$text,$m)){
  unset($users[$m[1]]); saveU($users,$usersFile);
 }
 if($text=="/viplist"){
  $msg="VIP LIST\n";
  foreach($users as $i=>$v) if(!empty($v["approved"])) $msg.="$i\n";
  send($ADMIN_ID,$msg);
 }
}
