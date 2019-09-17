<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">    
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
	<link href="bootstrap.css" rel="stylesheet">
	<link href="bootstrap.min.css" rel="stylesheet">
	<link href="courtm.css" rel="stylesheet">
    	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    	<script src="bootstrap.js"></script>
    	<script src="bootstrap.min.js"></script>

</head>

<body>
  <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <!--button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button-->
<?php
            header("Content-Type:text/html; charset=utf-8");
            error_reporting(E_ERROR | E_PARSE);
            //date_default_timezone_set("Asia/Taipei");
	    $fup = file_get_contents('last_update');
            $yy = date("Y");
            $mm = (int)date("m");
            $dd = date("d");
 	    if(count($_POST)>0){
                  $month = $_POST['month'];
                  $year = $_POST['year'];
                  $day = $_POST['day'];
		  $court = $_POST['court'];
             }
             else{
		$month = $mm;
		$year = $yy;
		$day = $dd;
		$court = "allcourt_所有場地";
	     }
	     $court_tmp = explode("_",$court);
	     $court_chi = $court_tmp[1];
	     $court_eng = $court_tmp[0];
	     if(strcmp($court_eng,"newcourt")==0)
		$c_id_arr=array(76,77);
	     else if(strcmp($court_eng,"girlcourt")==0)
		$c_id_arr=array(78);
	     else if(strcmp($court_eng,"centralcourt")==0)
		$c_id_arr=array(63,64,65);
	     else if(strcmp($court_eng,"halfcourt")==0)
		$c_id_arr=array(38,39,40,41,42,79,80,81,82);
	     else if(strcmp($court_eng,"earthcourt")==0)
		$c_id_arr=array(69,70);
             else //allcourt,csiecourt
		$c_id_arr=array(76,77,78,63,64,65,38,39,40,41,42,79,80,81,82,69,70);
		
          ?>
            <a class="brand" href="#">台大資訊女籃 場地預約查詢</a>

        </div>
      </div>
    </div>
<form action="" method="post">
<select name="year" style="width:80px">
　<option value=<?php echo$yy+1; ?>><?php echo$yy+1; ?></option>
　<option selected='true' value=<?php echo$yy; ?>><?php echo$yy; ?></option>
　<option value=<?php echo$yy-1; ?>><?php echo$yy-1; ?></option>
</select>年
<select name="month" style="width:60px">
	<?php
		for($i=1;$i<=12;$i++){
			if((int)$mm==$i)
				echo"<option selected='true' value=".$i.">".$i."</option>";
			else 
				echo"<option value=".$i.">".$i."</option>";
		}
	?>
</select>月
<select name="day" style="width: 60px">
	<?php
		for($i=1;$i<=31;$i++){
			if((int)$dd==$i)
				echo"<option selected='true' value=".$i.">".$i."</option>";
			else 
				echo"<option value=".$i.">".$i."</option>";
		}
	?>
</select>日
<br>
<select name="court" style="width:130px">
　<option value="allcourt_所有場地">所有場地</option>
　<option value="newcourt_新生球場">新生球場</option>
　<option value="centralcourt_中央球場">中央球場</option>
　<option value="girlcourt_女生球場">女生球場</option>
　<option value="halfcourt_那個半場">那個半場</option>
　<option value="earthcourt_地震球場">地震球場</option>
　<option value="csiecourt_資訊女籃">資訊女籃</option>
</select>
&nbsp;&nbsp;&nbsp;
<input type="submit" name="submit" value="Submit" id="submit">
</form>

<div id="icon">
	<span>last update: <?php echo $fup;?> 
	<img src="icon7.gif"> 已預約 
	<img src="actn010_2.gif"> 已出借/已繳費 
	<img src="icon4.gif"> 逾期 
	<img src="star.gif"> 抽籤登記 
	<img src="icon2.gif"> 重疊場地   
	<a href="http://ntusportscenter.ntu.edu.tw/ntu/front/image/map.png" target="_blank"> 場地位置圖 </a> 
	<a href="map2.jpg" target="_blank"> 涓版場地圖 </a> 
	</span>
</div>
<?php
	echo"<h4 id='alert'><font style=background:yellow>您選擇".$year."年".$month."月".$day."日的".$court_chi."</font></h4>";
?>

<?php
	$num_padded = sprintf("%02s", $month);
	$f_name = 'court.json_'.$year.'-'.$num_padded;
	$f = @file_get_contents($f_name);
	if($f == true){
		$arr = json_decode($f,'true');
		$tmpdate = $month."/".$day;
		foreach($arr as $key=>$value){
			$v = $value["date"];
			$vv = explode("(",$v);
             		if(strcmp($vv[0],$tmpdate)==0){ //match date!
				echo"<table class='table'>";
				//print_r($value);
				foreach($value as $time=>$courts){
					if(strcmp($time,"date")==0) continue;
					echo"<tr><td width=40%>".$time."</td><td>";
					foreach($courts as $court_id=>$teamlist){
						if(in_array((int)$court_id,$c_id_arr)){
							$c_id = (int)$court_id;
							foreach($teamlist as $teamkey=>$teamval){
                        					$team = $teamval["team"];
                        					$img = $teamval["img"];
					                        $img = explode("image/",$img);
					                        $img = end($img);
					                        $team = explode("(",$team);
					                        $team = $team[0];
					                        $team = explode("子籃球隊",$team);
					                        $team = $team[0];
					                        $team = str_replace(' ','',$team);
					                        if($team == '') continue;
                                            if (isCSIEGB($team)) {
					                            echo "<div class='CSIE'>";
					                        }
					                        else if(strcmp(mb_substr($team, -1,1,"UTF-8") ,"女")==0){
								    if(strcmp($court_eng,"csiecourt")==0) continue;
					                            echo "<div class='GIRLS'>";
					                        }
					                        else if(strcmp(mb_substr($team, -1,1,"UTF-8"), "男")==0){
								    if(strcmp($court_eng,"csiecourt")==0) continue;
					                            echo "<div class='BOYS'>";
					                        }
					                        else{
								    if(strcmp($court_eng,"csiecourt")==0) continue;
					                            echo "<div class='OTHERS'>";
					                        }
								$imgsrc = "<img src ='".$img."'>";
					                        if($c_id>=76 && $c_id<=78){
					                            if($c_id==78)
					                                echo"<div class='girl'>".$imgsrc.$team."</div>";
					                            else{
					                                $tmpid = $c_id-75;
					                                echo"<div class='new'>[".$tmpid."]".$imgsrc.$team."</div>";
                            					    }	
                        					}
					                        else if($c_id>=63 && $c_id<=65){
					                            $tmpid = $c_id-62;
					                            echo"<div class='central'>[".$tmpid."]".$imgsrc.$team."</div>";
					                        }
					                        else if($c_id>=69 && $c_id<=70){
					                            if($c_id == 69)
					                            	echo"<div class='earth'>[A]".$imgsrc.$team."</div>";
								    else
					                            	echo"<div class='earth'>[B]".$imgsrc.$team."</div>";
					                        }
					                        else{
					                                //half court38,39,40,41,42,79,80,81,82
					                                $cha_order = array("甲","乙","丙","丁","戊","己","庚","辛","壬");
					                                if($c_id<=42)
 					                                       $tmpid = $c_id-38; //0-4
					                                else
 					                                       $tmpid = $c_id-74;//5-8
					                                echo"<div class='half'>[".$cha_order[$tmpid]."]".$imgsrc.$team."</div>";
 					                       }
					                       echo "</div>";
							}			
						}	
					}
					echo"</td></tr>";
				}
				echo"</table>";
				break;
         		}
     		}
	}
	else{
		echo"<h4>查無資料 =(</h4>";
	}

    function isCSIEGB($team){
        $team_name = array("資工系女","02台大資工系女","台大資工系女","資工所女","台大資工所女","02台大資工所女","104資訊工程學系女","104資訊工程學研究所女","104資訊工程所女");
        if (in_array($team, $team_name)){
            return true;
        }
         return false;
    }
?>


<script type="text/javascript">
    $(function() {
        $(".new").prepend("<button class='btn btn-mini btn-primary' type='button'>新</button>&nbsp;");
        $(".girl").prepend("<button class='btn btn-mini btn-danger' type='button'>女</button>&nbsp;");
        $(".central").prepend("<button class='btn btn-mini btn-success' type='button'>中</button>&nbsp;");
        $(".half").prepend("<button class='btn btn-mini btn-warning' type='button'>半</button>&nbsp;");
        $(".earth").prepend("<button class='btn btn-mini btn-inverse' type='button'>地</button>&nbsp;");
    });
</script>
</body>
</html>
