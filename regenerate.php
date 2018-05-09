<?php
$yml_path=$argv[1];
$hass_url=$argv[2];
$delete=false;
if(isset($argv[3]) && $argv[3]=='delete'){
    $delete=true;
}
$states=json_decode(file_get_contents($hass_url),true);

$used=array();
foreach($states as $k=>$v){
    if(isset($v['entity_id'])){
        $used[$v['entity_id']]=true;
    }
}
//echo "## current entities (".count($used)."):".PHP_EOL;
//echo "## ".implode(', ',array_keys($used)).PHP_EOL;

$data=file_get_contents($yml_path);
$data=str_replace("\r\n","\n",$data);
$data=explode("\n",$data);

$found=array();

$ent=false;
$piece="";
foreach($data as $i=>$line){
    if(strlen(trim($line))>0){
        if( 
            ($line[0]>='a' && $line[0]<='z') 
            || ($line[0]>='A' && $line[0]<='Z') 
            || ($line[0]>='0' && $line[0]<='9') 
        ){
            if(is_string($ent)){
                process_piece($ent,$piece);
                $ent=false;
                $piece='';
            }
            $ent=trim(trim(trim($line),':'));
        }else{
            if(strlen($piece)>0){
                $piece.="\n";
            }
            $piece.=$line;
        }
    }
}
process_piece($ent,$piece);

function process_piece($entity,$data){
    global $found;
    $found[$entity]=$data;
}
//echo PHP_EOL;
//echo "## found in yml (".count($found)."):".PHP_EOL;
//echo "## ".implode(', ',array_keys($found)).PHP_EOL;
foreach($found as $name=>$data){
    
    if(isset($used[$name])){
        $prefix='';
        echo writeprefix($prefix,$name.':');
        echo writeprefix($prefix,$data);
    }else{
        if($delete==false){
            $prefix='#';
            echo writeprefix($prefix,$name.':');
            echo writeprefix($prefix,$data);
        }
    }
}

function writeprefix($prefix,$lines){
    $lines=explode("\n",$lines);
    $index=0;
    $string='';
    foreach($lines as $i=>$line){
        if($index>0){ $string.=PHP_EOL; }
        if($prefix!=='' &&  isset($line[0]) && $line[0]!='#'){
            $string.=$prefix;
        }
        $string.=$line;    
        $index++;
    }
    return $string.PHP_EOL;
}
