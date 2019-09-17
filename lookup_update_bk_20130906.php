<?php
ini_set('display_errors', 'On');
$path = realpath('simplehtmldom.php');
require_once($path); 
$court_id = array(76,77,78,63,64,65);
$last_update = date("Y-m-d H:i:s");

$mon = date("Y-m");
$month = explode("-",$mon);
$month = (int)$month[1];
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
                    else{
			$arr[$k+$i-1-1][$idx][$id] = array();
			$tt = $v->find("a");
			$mm = $v->find("img");
			foreach($tt as $kk=>$vv)
				if($vv->plaintext)	
                        		$arr[$k+$i-1-1][$idx][$id][$kk] = array("team"=>$vv->plaintext,"img"=>$mm[$kk]->src); //team
				
		    }	
                }
            }
        }
    }
}
$fname = 'court.json_'.$mon;
$f = fopen($fname.'_new','w');
fwrite($f,json_encode($arr));
fclose($f);
exec('mv '.$fname.'_new '.$fname);
echo $mon." updated!\n";
//print_r($arr);

$mon = date("Y-m",strtotime("+1 month"));
$month = explode("-",$mon);
$month = (int)$month[1];
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
                    else{
            $arr[$k+$i-1-1][$idx][$id] = array();
            $tt = $v->find("a");
            $mm = $v->find("img");
            foreach($tt as $kk=>$vv)
                if($vv->plaintext)	
                                $arr[$k+$i-1-1][$idx][$id][$kk] = array("team"=>$vv->plaintext,"img"=>$mm[$kk]->src); //team
                
            }	
                }
            }
        }
    }
}

$fname = 'court.json_'.$mon;
$f = fopen($fname.'_new','w');
fwrite($f,json_encode($arr));
fclose($f);
exec('mv '.$fname.'_new '.$fname);
echo $mon." updated!\n";

$f = fopen('last_update','w');
fwrite($f,$last_update);
fclose($f);
echo 'last update: '.$last_update;
?>
