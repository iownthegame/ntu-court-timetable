<?php
ini_set('display_errors', 'On');
$path = realpath('simplehtmldom.php');
require_once($path); 
$court_id = array(76,77,78,63,64,65,38,39,40,41,42,79,80,81,82,69,70);
$last_update = date("Y-m-d H:i:s");

$mon = date("Y-m");
$month = explode("-",$mon);
$year = (int)$month[0];
$month = (int)$month[1];
$arr = array();
$date_arr = array();
$enddate = date("t");
foreach($court_id as $id){
	echo 'id = '.$id.' ';
	$i=1;
	while(1){
		$start = 0;

		if($i<$enddate)
			$url = 'http://ntusportscenter.ntu.edu.tw/ntu/front/order.aspx?d=y&acp2id='.$id.'&yearno='.$year.'&monthno='.$month.'&dayno='.$i;
		else
			$url = 'http://ntusportscenter.ntu.edu.tw/ntu/front/order.aspx?d=y&acp2id='.$id.'&yearno='.$year.'&monthno='.$month.'&dayno='.$enddate;

		$url = htmlspecialchars_decode($url);
		//echo "url=".$url."\n";
		$html1 = file_get_html($url);
		if (method_exists($html1,"find")) {
			if($res1 = $html1->find('#cal tbody tr')){
				foreach($res1 as $key=>$value){
					$tmp = $value->find('td');
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
									//echo "date  = ".$date."\n";
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
								//echo ($k+$i-1-1)."\n";
								$tt = $v->find('a');
								$mm = $v->find('img');
								foreach($tt as $kk=>$vv)
									if($vv->plaintext)	
										$arr[$k+$i-1-1][$idx][$id][$kk] = array("team"=>$vv->plaintext,"img"=>$mm[$kk]->src); //team

							}	
						}
					}
				}
			}
		}
		if($i >= $enddate) break;
		$i += 7;
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
$year = (int)$month[0];
$month = (int)$month[1];
$arr = array();
$date_arr = array();
$enddate = cal_days_in_month(CAL_GREGORIAN, $month, $year) ; 
foreach($court_id as $id){
	echo 'id = '.$id.' ';
	$i = 1;
	while(1){
		$start = 0;
		if($i<$enddate)
			$url = 'http://ntusportscenter.ntu.edu.tw/ntu/front/order.aspx?d=y&acp2id='.$id.'&yearno='.$year.'&monthno='.$month.'&dayno='.$i;
		else
			$url = 'http://ntusportscenter.ntu.edu.tw/ntu/front/order.aspx?d=y&acp2id='.$id.'&yearno='.$year.'&monthno='.$month.'&dayno='.$enddate;
		$url = htmlspecialchars_decode($url);
		$html1 = file_get_html($url);
		if (method_exists($html1,"find")) {
			if($res1 = $html1->find('#cal tbody tr')){
				foreach($res1 as $key=>$value){
					$tmp = $value->find('td');
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
								$tt = $v->find('a');
								$mm = $v->find('img');
								foreach($tt as $kk=>$vv)
									if($vv->plaintext)	
										$arr[$k+$i-1-1][$idx][$id][$kk] = array("team"=>$vv->plaintext,"img"=>$mm[$kk]->src); //team

							}	
						}
					}
				}
			}
		}
		if($i >= $enddate) break;
		$i += 7;
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
$last_update = date("Y-m-d H:i:s");
echo 'last update: '.$last_update;
//fwrite($f,"finish:");
fwrite($f,"~");
fwrite($f,$last_update);
echo 'last update finish: '.$last_update;
fclose($f);
?>
