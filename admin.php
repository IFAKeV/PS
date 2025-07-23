<?php
$cfg = include __DIR__ . '/config.php';
$campaigns = json_decode(file_get_contents(__DIR__.'/campaigns.json'), true);

function safeName($name) {
    return preg_replace('/[^a-zA-Z0-9_-]/','_', $name);
}

$sendReport = '';
if (isset($_GET['send'])) {
    $id = (int)$_GET['send'];
    if (!isset($campaigns[$id])) die('Unknown campaign');
    $camp = $campaigns[$id];
    $csvPath = __DIR__.'/data/'.$camp['csv'];
    $tplPath = __DIR__.'/templates/'.$camp['email_template'];
    if (!file_exists($csvPath) || !file_exists($tplPath)) die('Missing files');
    require __DIR__.'/PHPMailer/src/PHPMailer.php';
    require __DIR__.'/PHPMailer/src/SMTP.php';
    require __DIR__.'/PHPMailer/src/Exception.php';
    $tpl = file_get_contents($tplPath);
    $handle = fopen($csvPath,'r');
    $header = fgetcsv($handle, 0, ',', '"', '\\');
    $logFile = __DIR__.'/logs/'.safeName($camp['name']).'-'.date('Ymd-His').'.jsonl';
    $success = 0;
    $total = 0;
    $errors = [];
    while(($row=fgetcsv($handle, 0, ',', '"', '\\'))!==false){
        if(count($row) !== count($header)) {
            $event = [
                'event' => 'malformed_row',
                'row'   => $row,
                'time'  => time()
            ];
            file_put_contents($logFile, json_encode($event) . "\n", FILE_APPEND);
            continue;
        }
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
        try {
            $total++;
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
                $success++;
            } else {
                $event = [
                    'event' => 'send_error',
                    'email' => $data['email'],
                    'error' => $mail->ErrorInfo,
                    'time'  => time()
                ];
                file_put_contents($logFile, json_encode($event) . "\n", FILE_APPEND);
                $errors[] = $mail->ErrorInfo;
            }
        } catch(Exception $e) {
            $event = [
                'event' => 'send_exception',
                'email' => $data['email'],
                'error' => $e->getMessage(),
                'time'  => time()
            ];
            file_put_contents($logFile, json_encode($event) . "\n", FILE_APPEND);
            $errors[] = $e->getMessage();
        }
        sleep(10);
    }
    fclose($handle);
    ob_start();
    echo "<h2>Versand abgeschlossen</h2>";
    echo "<p>Erfolgreich versendet: {$success} von {$total}</p>";
    if (!empty($errors)) {
        echo "<p>Fehler beim Versand:</p><ul>";
        foreach ($errors as $msg) {
            echo '<li>'.htmlspecialchars($msg).'</li>';
        }
        echo '</ul>';
    }
    $sendReport = ob_get_clean();
}
?>
<!DOCTYPE html>
<html><body>
<h1>Phishing Simulation Admin</h1>
<?php if($sendReport) echo $sendReport; ?>
<ul>
<?php foreach($campaigns as $i=>$c): ?>
<li><?php echo htmlspecialchars($c['name']); ?> - <a href="?send=<?php echo $i; ?>">Versand starten</a></li>
<?php endforeach; ?>
</ul>
<div id="stats"></div>
<script>
function load(){
    fetch('stats.php').then(r=>r.json()).then(d=>{
        let html='';
        Object.keys(d).forEach(name=>{
            const s=d[name];
            html+=`<h3>${name}</h3>`+
                  `<p>Versendet: ${s.total_sent} | `+
                  `Geklickt: ${s.clicked} | `+
                  `Abgesendet: ${s.submitted} | `+
                  `Fehler: ${s.errors}</p>`;
            if(s.entries.length){
                html+='<ul>';
                s.entries.forEach(e=>{
                    let status=[];
                    if(e.click_time) status.push('geklickt');
                    if(e.submit_time) status.push('Form gesendet');
                    html+=`<li>${e.email}`;
                    if(status.length) html+=` - ${status.join(', ')}`;
                    html+='</li>';
                });
                html+='</ul>';
            }
        });
        document.getElementById('stats').innerHTML=html;
    });
}
setInterval(load,5000);load();
</script>
</body></html>
