<?php
ini_set('display_errors', 'On');
$path = realpath('simplehtmldom.php');
require_once($path); 
$court_id = [76,77,78,63,64,65];
$month = 8;
$arr = array();
$date_arr = array();
foreach($court_id as $id){
    for($i=1;$i<=31;$i+=7){
        $start = 0;
        $html1 = file_get_html('http://ntusportscenter.ntu.edu.tw/ntu/front/order.aspx?d=y&acp2id='.$id.'&yearno=2013&monthno='.$month.'&dayno='.$i);
        $res1 = $html1->find("#cal tbody tr");
        foreach($res1 as $key=>$value){
            $tmp = $value->find("td");
            $tmparr = array();
            if($key == 0 ){ //add date into array, only do once
                if($start == 0){
                    $start = 1;
                    foreach($tmp as $k=>$v){
                        if($k != 0){
                            $new = array();
                            $date = $v->plaintext; //date
                            if(in_array($date,$date_arr))
                                break;
                            $date_arr[] = $date;
                            $new['date'] = $date; 
                            $arr[] = $new;
                            
                        }
                    }
                }
                continue;
            }
            else{
                foreach($tmp as $k=>$v){
                    if($k == 0)
                        $idx = $v->plaintext; //time index
                    else
                        $arr[$k+$i-1-1][$idx][$id] = $v->plaintext; //team
                }
            }
        }
    }
}

$f = fopen('court.json_'.$month,'w');
fwrite($f,json_encode($arr));
fclose($f);
print_r($arr);

?>
