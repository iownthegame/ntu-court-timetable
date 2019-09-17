<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">    
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
    	<!--link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.min.css" rel="stylesheet">
	<link href="bootstrap-responsive.css" rel="stylesheet"-->
        <link href="bootstrap.css" rel="stylesheet">
        <link href="bootstrap.min.css" rel="stylesheet">
        <link href="court.css" rel="stylesheet">
    	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script src="bootstrap.js"></script>
        <script src="bootstrap.min.js"></script>
</head>

<body>
<div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
<?php
            header("Content-Type:text/html; charset=utf-8");
            error_reporting(E_ERROR | E_PARSE);
            //date_default_timezone_set("Asia/Taipei");
            if(isset($_GET['mon'])){
                $month = $_GET['mon'];
            }
            else $month = date("Y-m");
          ?>
            <a class="brand" href="#"><?php echo $month;?> 場地預約一覽表</a>
            <div class="nav-collapse collapse">
            <ul class="nav">
              <li class="newcourt">
                <a href="#">新生球場</a>
              </li>
              <li class="girlcourt">
                <a href="#">女生球場</a>
              </li>
              <li class="centralcourt">
                <a href="#">中央球場</a>
              </li>
              <li class="halfcourt">
                <a href="#">那個半場</a>
              </li>
              <li class="earthcourt">
                <a href="#">地震球場</a>
              </li>
              <li class="csiecourt">
                <a href="#">資訊女籃</a>
              </li>
              <li class="friendcourt">
                <a href="#">約友誼賽</a>
              </li>
              <li class="nextmonth">
                <a id="next" href="lookup.php?mon=<?php echo date('Y-m',strtotime('+1 months',strtotime($month)));?>"><?php echo date('Y-m',strtotime('+1 months',strtotime($month)));?></a>
              </li>
              <li class="prevmonth">
                <a id="prev" href="lookup.php?mon=<?php echo date('Y-m',strtotime('-1 months',strtotime($month)));?>"><?php echo date('Y-m',strtotime('-1 months',strtotime($month)));?></a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <?php 
            $fup = file_get_contents('last_update');            
    ?>
        <div id="icon"><span>last update: <?php echo $fup;?> <img src="icon7.gif"> 已預約 <img src="actn010_2.gif"> 已出借/已繳費 <img src="icon4.gif"> 逾期 <img src="star.gif"> 抽籤登記 <img src="icon2.gif">  重疊場地   <a href="http://ntusportscenter.ntu.edu.tw/ntu/front/image/map.png"> 場地位置圖 </a> <a href="map2.jpg"> 涓版場地圖<a href="#today"> 跳至今天 </a></span></div>
   <table class="table table-hover table-bordered table1">
<?php
    $f = @file_get_contents('court.json_'.$month);
    if($f == true){ //json file exists

    $arr = json_decode($f,'true');
    //print_r($arr);
    echo"<thead><tr><th>Date</th>";
    foreach($arr as $key=>$value){
        $keys = array_keys($value);
        foreach($keys as $k=>$v){
            if($k!=0){
                echo"<th>".$v."</th>";
            }
        }
        break;
    }
    echo "</tr></thread>";
	echo"</table>";
			
    echo"<div class='table-body-box'><table class='table table-hover table-bordered table2'>";
    echo "<tbody>";
    foreach($arr as $key=>$value){
        echo"<tr>";
        foreach($keys as $k=>$v){
            if($k!=0){ //time=>court
                if($k<=3) 
                    echo "<td id='day'>";
                else if($k<=6) 
                    echo "<td id='noon'>";
                else
                    echo "<td id='night'>";
                $time = $value[$v];
                foreach($time as $c_id=>$teamlist){
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
                            echo "<div class='GIRLS'>";
                        }
                        else if(strcmp(mb_substr($team, -1,1,"UTF-8"), "男")==0){
                            echo "<div class='BOYS'>";
                        }
                        else{
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
                echo"</td>";

            }
            else{ //date
                $mon = explode("/",$value[$v]);
                $mm = explode("-",$month);
                if((int)$mon[0]!=(int)$mm[1])
                   break;
		$mmm = date('m'); //current
		$ddd = date('d'); //current
		$day = explode("(",$mon[1]);
		if(((int)$mmm == (int)$mon[0])&&((int)$ddd == (int)$day[0]))//today
                	echo"<td id='today' name='today'>".$value[$v]."</td>";
		else
                	echo"<td>".$value[$v]."</td>";
            }
        }
        echo"</tr>";
    }
    echo "</tbody>";
    }
    else{
        echo"no data";
    }
    function isCSIEGB($team){
        $team_name = array("資工系女","02台大資工系女","台大資工系女","資工所女","台大資工所女","02台大資工所女","104資訊工程學系女","104資訊工程學研究所女","104資訊工程所女","105資訊工程所女","105資訊工程學系女");
        if (in_array($team, $team_name)){
            return true;
        }
        return false;
    }
?>
</table></div>

<script type="text/javascript">
    $(function() {
        $(".new").prepend("<button class='btn btn-mini btn-primary' type='button'>新</button>&nbsp;");
        $(".girl").prepend("<button class='btn btn-mini btn-danger' type='button'>女</button>&nbsp;");
        $(".central").prepend("<button class='btn btn-mini btn-success' type='button'>中</button>&nbsp;");
        $(".half").prepend("<button class='btn btn-mini btn-warning' type='button'>半</button>&nbsp;");
        $(".earth").prepend("<button class='btn btn-mini btn-inverse' type='button'>地</button>&nbsp;");
    });
    $(".newcourt").click(function() {
        $(".GIRLS").show();
        $(".BOYS").show();
        $(".OTHERS").show();
        $(".CSIE").show();
        $(".new").show();
        $(".girl").hide();
        $(".central").hide();
	$(".half").hide();
	$(".earth").hide();
        console.log("shinshen");
    });
    $(".girlcourt").click(function() {
        $(".GIRLS").show();
        $(".BOYS").show();
        $(".OTHERS").show();
        $(".CSIE").show();
        $(".new").hide();
        $(".girl").show();
        $(".central").hide();
	$(".half").hide();
	$(".earth").hide();
        console.log("girl fisrt");
    });
    $(".centralcourt").click(function() {
        $(".GIRLS").show();
        $(".BOYS").show();
        $(".OTHERS").show();
        $(".CSIE").show();
        $(".new").hide();
        $(".girl").hide();
        $(".central").show();
	$(".half").hide();
	$(".earth").hide();
        console.log("chong yang");
    });
    $(".earthcourt").click(function() {
        $(".GIRLS").show();
        $(".BOYS").show();
        $(".OTHERS").show();
        $(".CSIE").show();
        $(".new").hide();
        $(".girl").hide();
        $(".central").hide();
	$(".half").hide();
	$(".earth").show();
        console.log("Di Chen");
    });
    $(".halfcourt").click(function() {
        $(".GIRLS").show();
        $(".BOYS").show();
        $(".OTHERS").show();
        $(".CSIE").show();
        $(".new").hide();
        $(".girl").hide();
        $(".central").hide();
	$(".earth").hide();
	$(".half").show();
        console.log("half");
    });
    $(".csiecourt").click(function() {
        $(".GIRLS").hide();
        $(".BOYS").hide();
        $(".OTHERS").hide();
        $(".CSIE").show();
        $(".new").show();
        $(".girl").show();
        $(".central").show();
	$(".half").show();
	$(".earth").show();
        console.log("CSIEGBASKET");
    });
    $(".friendcourt").click(function() {
        $(".new").show();
        $(".girl").show();
        $(".central").show();
	$(".half").show();
	$(".earth").show();
        $(".GIRLS").show();
        $(".BOYS").hide();
        $(".OTHERS").hide();
        $(".CSIE").show();
        console.log("friend ship game with girls");
    });
    $(".brand").click(function() {
        $(".GIRLS").show();
        $(".BOYS").show();
        $(".OTHERS").show();
        $(".CSIE").show();
        $(".new").show();
        $(".girl").show();
        $(".central").show();
	$(".half").show();
	$(".earth").show();
        console.log("brand");
    });
</script>
</body>

</html>
