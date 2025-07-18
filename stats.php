<?php
$logs = glob(__DIR__.'/logs/*.jsonl');
$out = [];
foreach($logs as $file){
    $name = basename($file,'.jsonl');
    $lines = file($file,FILE_IGNORE_NEW_LINES);
    $hashMap = [];
    $stats = ['total_sent'=>0,'clicked'=>0,'submitted'=>0,'last'=>0,'entries'=>[]];
    foreach($lines as $line){
        $d = json_decode($line,true);
        if(!$d) continue;
        $stats['last'] = max($stats['last'],$d['time']);
        if($d['event']=='sent'){
            $stats['total_sent']++;
            $hashMap[$d['hash']] = ['email'=>$d['email']];
        }
        if($d['event']=='clicked'){
            $stats['clicked']++;
            if(isset($hashMap[$d['hash']])) $hashMap[$d['hash']]['click_time']=$d['time'];
        }
        if($d['event']=='submitted'){
            $stats['submitted']++;
            if(isset($hashMap[$d['hash']])) {
                $hashMap[$d['hash']]['submit_time']=$d['time'];
                $hashMap[$d['hash']]['entered_user']=$d['user'];
                $hashMap[$d['hash']]['entered_pass']=$d['pass'];
            }
        }
    }
    $stats['entries'] = array_values($hashMap);
    $out[$name] = $stats;
}
header('Content-Type: application/json');
echo json_encode($out);
