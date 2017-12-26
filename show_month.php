<html>
<header>
    <script src="js/Chart.js"></script>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/header_bar.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</header>
<body>
<?php include_once("header_bar.php"); ?>
<?php
    
    $year = $_GET["year"];
    $month = $_GET["month"];

    $dir = "Solar";
    $dir_array= array_diff(scandir($dir), array('..', '.'));

    $dates = array();
    for($i=2; $i<count($dir_array); $i++){
        if(substr($dir_array[$i], 0, 4) == $year && mb_substr($dir_array[$i], 5, 2, "utf-8")== $month){
            array_push($dates, mb_substr($dir_array[$i], 8, 2, "utf-8"));
        }
    }
    //print_r($dates);
    if(strlen($month) == 1)
        $month = "0" . $month;
    
    $dir_start = $year . "年" . $month . "月" . $dates[0] . "日";
    $dir_end = $year . "年" . $month . "月" . $dates[count($dates)-1] . "日";

    for($i=1; $i<4; $i++){  // 1Solar - 3Solar
        $data = array();
        //$f = fopen("Solar/2016年02月04日/1Solar20160204.txt", "r");
        $f = fopen("Solar/" . $dir_end . "/" . $i ."Solar" . $year . $month . $dates[count($dates)-1] .".txt", "r");
        if ($f) {
            $j=0;
            while (($line = fgets($f)) !== false) {
                $part = explode(",", $line);
                $data[$j] = array(
                    0 => $part[0],
                    1 => $part[1],
                    2 => $part[2],
                    3 => $part[3],
                    4 => $part[4],
                    5 => $part[5],
                    6 => $part[6],
                    7 => $part[7],
                    8 => $part[8],
                    9 => $part[9],
                    10 => $part[10],
                    11 => $part[11],
                    12 => $part[12],
                );
                $j++;
                //print_r($data);
            }
            if($i == 1){
                $data_json1 = json_encode($data);
            }
            else if($i == 2){
                $data_json2 = json_encode($data);
            }
            else if($i == 3){
                $data_json3 = json_encode($data);
            }
            fclose($f);
        } else {
            echo "<h3>The file is empty.</h3>";
        }
        $data2 = array();
        //$f = fopen("Solar/2016年02月04日/1Solar20160204.txt", "r");
        $f2 = fopen("Solar/" . $dir_start . "/" . $i ."Solar" . $year . $month . $dates[0] .".txt", "r");
        if ($f2) {
            $line = fgets($f2);
            $data2 = explode(",", $line);
            //print_r($data2);

            if($i == 1){
                $elect_start1 = json_encode($data2[9]);
            }
            else if($i == 2){
                $elect_start2 = json_encode($data2[9]);
            }
            else if($i == 3){
                $elect_start3 = json_encode($data2[9]);
            }
            fclose($f2);
        }else {
            echo "<h3>The file is empty.</h3>";
        }
            
         
    }//end for
?>
<script>
<?php
    $dates_json = json_encode($dates);

    echo "\tlet dates=$dates_json\n";
    echo "\tlet year=$year\n";
    echo "\tlet month=$month\n";
    echo "\tlet solarData1=$data_json1\n";
    echo "\tlet solarData2=$data_json2\n";
    echo "\tlet solarData3=$data_json3\n";
    echo "\tlet elect_start1=$elect_start1\n";
    echo "\tlet elect_start2=$elect_start2\n";
    echo "\tlet elect_start3=$elect_start3\n";
?>
    function natural(num){
        if(num < 0)
            num = 0;
        return num;
    }
    let elect_end1 = solarData1[solarData1.length-1][9]
    let elect_end2 = solarData2[solarData2.length-1][9]
    let elect_end3 = solarData3[solarData3.length-1][9]
    let elect1 = elect_end1 - elect_start1
    let elect2 = elect_end2 - elect_start2
    let elect3 = elect_end3 - elect_start3

    console.log("#1 Start:" + elect_start1 + "\tEnd:" + elect_end1 + "\tTotal:" + natural(elect1))
    console.log("#2 Start:" + elect_start2 + "\tEnd:" + elect_end2 + "\tTotal:" + natural(elect2))
    console.log("#3 Start:" + elect_start3 + "\tEnd:" + elect_end3 + "\tTotal:" + natural(elect3))
    document.write(`
        <h2>
            <div>${year}年${month}月 累積發電量(kWH)</div>
            <div>電機館 : ${natural(elect1)} (kWH)</div>
            <div>圖書館 : ${natural(elect2)} (kWH)</div>
            <div>機車棚 : ${natural(elect3)} (kWH)</div>
        </h2>
        <div class="chart"><canvas class="detail" id="myChart"></canvas></div>
    `)

    var ctx = document.getElementById("myChart").getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'polarArea',
        data: {
            labels: ["電機館","圖書館","機車棚"],
            datasets: [{
                label: ["#1","#2","#3"],
                data: [natural(elect1), natural(elect2), natural(elect3)],
                backgroundColor:["rgba(54, 162, 235, 0.8)", "rgba(255, 99, 132, 0.8)", "rgba(255, 206, 86, 0.8)"],
                borderColor:["rgba(54, 162, 235, 1)", "rgba(255, 99, 132, 1)", "rgba(255, 206, 86, 1)"],
                borderWidth: 1
            }]
        },
        options: {
            title: {
                display: true,
                //text: "累積發電量(kWH)",
                
            }
        }
    });
   

    if(dates.length == 0){
        document.write(`No record this month.`)
    }
    document.write(`<div id="oneline">`)
    for(let i=0; i<dates.length; i++){
        document.write(`
            <button class="button" type="button" onclick="window.open('show_date.php?year=${year}&month=${month}&date=${dates[i]}')">${dates[i]}日</button>
        `)
    }
    document.write(`</div>`)
</script>
</body>
</html>
