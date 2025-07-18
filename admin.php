<?php
$cfg = include __DIR__ . '/config.php';
$campaigns = json_decode(file_get_contents(__DIR__.'/campaigns.json'), true);

function safeName($name) {
    return preg_replace('/[^a-zA-Z0-9_-]/','_', $name);
}

if (isset($_GET['send'])) {
    $id = (int)$_GET['send'];
    if (!isset($campaigns[$id])) die('Unknown campaign');
    $camp = $campaigns[$id];
    $csvPath = __DIR__.'/data/'.$camp['csv'];
    $tplPath = __DIR__.'/templates/'.$camp['template'];
    if (!file_exists($csvPath) || !file_exists($tplPath)) die('Missing files');
    require __DIR__.'/PHPMailer/src/PHPMailer.php';
    require __DIR__.'/PHPMailer/src/SMTP.php';
    require __DIR__.'/PHPMailer/src/Exception.php';
    $tpl = file_get_contents($tplPath);
    $handle = fopen($csvPath,'r');
    $header = fgetcsv($handle);
    $logFile = __DIR__.'/logs/'.safeName($camp['name']).'.jsonl';
    while(($row=fgetcsv($handle))!==false){
        $data = array_combine($header,$row);
        $hash = hash('sha256',$data['email']);
        $link = (isset($_SERVER['HTTPS'])?'https':'http').'://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/index.php?id='.$hash;
        $body = str_replace([
            '%Email%','%Vorname%','%Name%','%Link%'
        ],[
            $data['email'],$data['vorname'],$data['nachname'],$link
        ],$tpl);
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->isSMTP();
        $mail->Host = $cfg['smtp_host'];
        $mail->Port = $cfg['smtp_port'];
        $mail->SMTPAuth = true;
        $mail->Username = $cfg['smtp_user'];
        $mail->Password = $cfg['smtp_pass'];
        $mail->SMTPSecure = $cfg['smtp_secure'];
        $mail->setFrom($cfg['from_email'],$cfg['from_name']);
        $mail->addAddress($data['email']);
        $mail->isHTML(true);
        $mail->Subject = $camp['name'];
        $mail->Body = $body;
        if($mail->send()){
            $event = [
                'event' => 'sent',
                'email' => $data['email'],
                'first' => $data['vorname'],
                'last'  => $data['nachname'],
                'hash'  => $hash,
                'time'  => time()
            ];
            file_put_contents($logFile, json_encode($event) . "\n", FILE_APPEND);
        }
        sleep(10);
    }
    fclose($handle);
    echo "Versand abgeschlossen";
    exit;
}
?>
<!DOCTYPE html>
<html><body>
<h1>Phishing Simulation Admin</h1>
<ul>
<?php foreach($campaigns as $i=>$c): ?>
<li><?php echo htmlspecialchars($c['name']); ?> - <a href="?send=<?php echo $i; ?>">Versand starten</a></li>
<?php endforeach; ?>
</ul>
<div id="stats"></div>
<script>
function load(){
    fetch('stats.php').then(r=>r.json()).then(d=>{
        document.getElementById('stats').innerText=JSON.stringify(d,null,2);
    });
}
setInterval(load,5000);load();
</script>
</body></html>
