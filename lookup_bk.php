<html>
<head>
    <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.min.css" rel="stylesheet">
    <link href="court.css" rel="stylesheet">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
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
          <a class="brand" href="#">7月份場地預約一覽表</a>
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
              <li class="csiecourt">
                <a href="#">資訊女籃預場地</a>
              </li>
              <li class="friendcourt">
                <a href="#">約友誼賽吧</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <table class="table table-hover table-bordered">
<?php
    $f = file_get_contents('court.json_8');
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
                foreach($time as $c_id=>$team){
                    $team = explode("(",$team);
                    $team = $team[0];
                    $team = explode("子籃球隊",$team);
                    $team = $team[0];
                    $team = str_replace(' ','',$team);
                    if($team == '') continue;
                    if (strcmp($team,"資工系女")==0){
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

                    if($c_id>=76){ 
                        if($c_id==78)
                            echo"<div class='girl'>".$team."</div>";
                        else{
                            $tmpid = $c_id-75;
                            echo"<div class='new'>[".$tmpid."]".$team."</div>";
                        }
                    }
                    else{
                        $tmpid = $c_id-62;
                        echo"<div class='central'>[".$tmpid."]".$team."</div>";
                    }
                    echo "</div>";
                }
                echo"</td>";
            }
            else{ //date
                echo"<td>".$value[$v]."</td>";
            }
        }
        echo"</tr>";
    }
    echo "</tbody>";
?>
</table>

<script type="text/javascript">
    $(function() {
        $(".new").prepend("<button class='btn btn-mini btn-primary' type='button'>新</button>&nbsp;");
        $(".girl").prepend("<button class='btn btn-mini btn-danger' type='button'>女</button>&nbsp;");
        $(".central").prepend("<button class='btn btn-mini btn-success' type='button'>中</button>&nbsp;");
    });
    $(".newcourt").click(function() {
        $(".GIRLS").show();
        $(".BOYS").show();
        $(".OTHERS").show();
        $(".CSIE").show();
        $(".new").show();
        $(".girl").hide();
        $(".central").hide();
    });
    $(".girlcourt").click(function() {
        $(".GIRLS").show();
        $(".BOYS").show();
        $(".OTHERS").show();
        $(".CSIE").show();
        $(".new").hide();
        $(".girl").show();
        $(".central").hide();
    });
    $(".centralcourt").click(function() {
        $(".GIRLS").show();
        $(".BOYS").show();
        $(".OTHERS").show();
        $(".CSIE").show();
        $(".new").hide();
        $(".girl").hide();
        $(".central").show();
    });
    $(".csiecourt").click(function() {
        $(".new").show();
        $(".girl").show();
        $(".central").show();
        $(".GIRLS").hide();
        $(".BOYS").hide();
        $(".OTHERS").hide();
        $(".CSIE").show();
    });
    $(".friendcourt").click(function() {
        $(".new").show();
        $(".girl").show();
        $(".central").show();
        $(".GIRLS").show();
        $(".BOYS").hide();
        $(".OTHERS").hide();
        $(".CSIE").show();
    });
    $(".brand").click(function() {
        $(".GIRLS").show();
        $(".BOYS").show();
        $(".OTHERS").show();
        $(".CSIE").show();
        $(".new").show();
        $(".girl").show();
        $(".central").show();
    });
</script>
</body>

</html>
