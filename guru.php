<?php
function findLogFile($hash){
    foreach(glob(__DIR__.'/logs/*.jsonl') as $f){
        $h = fopen($f, 'r');
        while(($line = fgets($h)) !== false){
            $d = json_decode($line, true);
            if($d && isset($d['hash']) && $d['hash'] === $hash){
                fclose($h);
                return $f;
            }
        }
        fclose($h);
    }
    return null;
}

function findName($logFile, $hash){
    $h = fopen($logFile, 'r');
    while(($line = fgets($h)) !== false){
        $d = json_decode($line, true);
        if($d && isset($d['event']) && $d['event'] === 'sent' && $d['hash'] === $hash){
            fclose($h);
            $first = $d['first'] ?? '';
            $last  = $d['last'] ?? '';
            return trim($first.' '.$last);
        }
    }
    fclose($h);
    return '';
}

$hash = $_GET['id'] ?? '';
$name = '';
if($hash && ($logFile = findLogFile($hash))){
    $name = findName($logFile, $hash);
}
$guruCode = sprintf('#%08X.%08X', mt_rand(0, 0xFFFFFFFF), mt_rand(0, 0xFFFFFFFF));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Guru Meditation</title>
  <style>
    html, body {
      background-color: black;
      color: red;
      font-family: monospace;
      font-size: 20px;
      text-align: center;
      margin: 0;
      padding: 0;
      height: 100vh;
      overflow: hidden;
    }

    .hero-name { 
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 15vw;
    font-family: 'Arial Black', sans-serif;
    color: rgba(255, 255, 255, 0.5);
    text-align: center;
    line-height: 1;
    text-transform: uppercase;
    white-space: pre-line;
    z-index: 100;
    pointer-events: none;
    user-select: none;
    text-shadow: 0 0 10px rgba(255, 255, 255, 0.15);
    animation: pulse 3s infinite;
    }

    @keyframes pulse {
    0%, 100% { opacity: 0.5; }
    50% { opacity: 0.8; }
    }
    
    .frame {
      border: 10px solid red;
      margin: 50px auto 20px;
      padding: 30px;
      display: inline-block;
    }
    .line {
      margin: 20px 0;
    }
    .eyes {
      margin-top: 40px;
      z-index: 1;
    }
    .eyes img {
      display: block;
      margin: 0 auto;
      max-width: 100%;
      height: auto;
    }
    .footer {
      position: absolute;
      bottom: 20px;
      width: 100%;
      font-size: 18px;
      color: red;
      animation: blink 1s steps(1) infinite;
    }
    @keyframes blink {
      50% { opacity: 0; }
    }
  </style>
</head>
<body>
  <div class="frame">
    <div class="line">Software Failure. Press left mouse button to continue.</div>
    <div class="line">Guru Meditation <?= $guruCode ?></div>
  </div>
  <div class="hero-name">
    <?php
      if($name){
          echo nl2br(htmlspecialchars($name));
      } else {
          echo 'Ralf<br>Malz';
      }
    ?>
  </div>
  <div class="eyes">
    <img src="n.gif" alt="Ninja Eyes">
  </div>
  <div class="footer">Du bist auf Phishing hereingefallen. Gehe nicht über Los. Ziehe nicht 4000 Mark ein.</div>
</body>
</html>