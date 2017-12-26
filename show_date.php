<html>
<header>
    <script src="js/Chart.js"></script>
    <link rel="stylesheet" href="css/header_bar.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <style>
    body{
        font-family: '微軟正黑體', sans-serif;
        overflow-y:scroll;
        //background-color: #1F2739;
    }
    h2{
        text-align: center;
        display:flex;
        justify-content:space-around;
    }
    div.chart{
        width:90%;
        margin: auto;
    }
    .button {
        background-color: #555555;;
        border: none;
        color: white;
        padding: 15px 32px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 18px;
        margin: 4px 2px;
        cursor: pointer;
        border-radius: 6px;
        white-space: nowrap;
    }
    a:link{
        text-decoration: none;
        color: black;
    }
    a:visited {
        text-decoration: none;
    }
    a:hover {
        text-decoration: underline;
    }
    a:active {
        text-decoration: underline;
    }
    div .oneline{
        display:flex;
        justify-content:space-around;
        flex-wrap:wrap;
    }
    div .twoline{
        display:flex;
        justify-content:space-around;
        flex-wrap:wrap;
    }
    canvas{
        width:100% !important;
        height:22% !important;
    }
    .month {
        display:flex;
        justify-content:space-around;
    }
    @media (max-width:200px){
        .oneline{
        display:none;
        }
    }
    @media (min-width:200px){
        .twoline{
            display:none;
        }
    }
    </style>
</header>
<body>
<?php include_once("header_bar.php"); ?>
<?php
    $year = $_GET["year"];
    $month = $_GET["month"];
    $date = $_GET["date"];
    $select = 3;
    if(isset($_GET["select"])){
        $select = $_GET["select"];
    }

    if(strlen($month) == 1)
        $month = "0" . $month;
    if(strlen($date) == 1)
        $date = "0" . $date;  

    $dirname = $year . "年" . $month . "月" . $date . "日";
    //echo $dirname;

    /* read text to 2d array */
    for($i=1; $i<4; $i++){  // 1Solar - 3Solar
        $data = array();
        //$f = fopen("Solar/2016年02月04日/1Solar20160204.txt", "r");
        $f = fopen("Solar/" . $dirname . "/" . $i ."Solar" . $year . $month . $date .".txt", "r");
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
            $f2 = fopen("default.txt","r");
            $j=0;
            while (($line = fgets($f2)) !== false) {
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
            fclose($f2);
        }
    }//end for
    

?>

<script>
<?php
    echo "\tlet year=$year\n";
    echo "\tlet month=$month\n";
    echo "\tlet date=$date\n";
    echo "\tlet select=$select\n";
    echo "\tlet solarData1=$data_json1\n";
    echo "\tlet solarData2=$data_json2\n";
    echo "\tlet solarData3=$data_json3\n";
?>
    //console.log(solarData1)
    //document.write(`${solarData1[0][4]}`)
    //document.write(`${solarData1.length}`)
    let names = [
            "直流電壓(DV)",
            "直流電流(A)",
            "直流功率(kW)",
            "交流電壓(AV)",
            "交流電流(A)",
            "交流功率(kW)",
            "累積發電量(kWH)",
            "累計CO2減碳量(kg)",
            "太陽能板溫度(°C)",
            "太陽能板照度(W/m²)"
        ]
    let backgroundColors = [
        "rgba(54, 162, 235, 0.2)",      //blue
        "rgba(255, 159, 64, 0.2)",      //orange
        "rgba(75, 192, 192, 0.2)",      //green
        "rgba(225, 0, 45, 0.2)",        //red
        "rgba(255, 206, 86, 0.2)",      //yellow
        "rgba(153, 102, 255, 0.2)",     //purple
        "rgba(34, 191, 76, 0.2)",       //dark green
        "rgba(81, 77, 70, 0.2)",        //gray
        "rgba(255, 99, 132, 0.2)",      //pink
        "rgba(4, 109, 229, 0.2)",       //dark blue
    ]
    let borderColors = [        
        "rgba(54, 162, 235, 1)",        //blue
        "rgba(255, 159, 64, 1)",        //orange
        "rgba(75, 192, 192, 1)",        //green
        "rgba(225, 0, 45, 1)",          //red
        "rgba(255, 206, 86, 1)",        //yellow
        "rgba(153, 102, 255, 1)",       //purple
        "rgba(34, 191, 76, 1)",         //dark green
        "rgba(81, 77, 70, 1)",          //gray
        "rgba(255, 99, 132, 1)",        //pink
        "rgba(4, 109, 229, 1)",         //dark blue 
    ]


    let time1 = new Array(solarData1.length)
    let time2 = new Array(solarData2.length)
    let time3 = new Array(solarData3.length)
    for(let i=0; i<time1.length; i++){
        time1[i] = solarData1[i][2];
    }
    for(let i=0; i<time2.length; i++){
        time2[i] = solarData2[i][2];
    }
    for(let i=0; i<time3.length; i++){
        time3[i] = solarData3[i][2];
    }
   
    let data1 = new Array(solarData1.length)
    let data2 = new Array(solarData2.length)
    let data3 = new Array(solarData3.length)    
    for(let i=0; i<data1.length; i++){
        data1[i] = solarData1[i][select]
    }
    for(let i=0; i<data2.length; i++){
        data2[i] = solarData2[i][select]
    }
    for(let i=0; i<data3.length; i++){
        data3[i] = solarData3[i][select]
    }
       
    document.write(`
        <div>
            <a href="show_detail.php?year=${year}&month=${month}&date=${date}&station=1">
            <h2>
                <div>電機館</div>
                <div>${solarData1[0][1].substring(0,4)}年${solarData1[0][1].substring(4,6)}月${solarData1[0][1].substring(6,8)}日</div>
                <div>${names[select-3]}</div>
            </h2></a>
            <div class="chart"><canvas id="myChart1"></canvas></div>

            <a href="show_detail.php?year=${year}&month=${month}&date=${date}&station=2">
            <h2>
                <div>圖書館</div>
                <div>${solarData2[0][1].substring(0,4)}年${solarData2[0][1].substring(4,6)}月${solarData2[0][1].substring(6,8)}日</div>
                <div>${names[select-3]}</div>
            </h2></a>
            <div class="chart"><canvas id="myChart2"></canvas></div>

            <a href="show_detail.php?year=${year}&month=${month}&date=${date}&station=3">
            <h2>
                <div>機車棚</div>
                <div>${solarData3[0][1].substring(0,4)}年${solarData3[0][1].substring(4,6)}月${solarData3[0][1].substring(6,8)}日</div>
                <div>${names[select-3]}</div>
            </h2></a>
            <div class="chart"><canvas id="myChart3"></canvas></div>
        
    `)
    
    /* screen width > 600px */
    document.write(`<div class="oneline">`)
    for(let i=0; i<10; i++){
        document.write(`
            <button class="button" type="button" onclick="window.location.href='?year=${year}&month=${month}&date=${date}&select=${i+3}'">${names[i]}</button>
        `)
    }
    document.write(`</div>`)

    /* screen width < 599px 
    document.write(`<div id="twoline">`)
    for(let i=0; i<5; i++){
        document.write(`
            <button class="button" type="button" onclick="window.location.href='?select=${i+3}'">${names[i]}</button>
        `)
    }
    document.write(`</div>`)
    document.write(`<div id="twoline">`)
    for(let i=5; i<10; i++){
        document.write(`
            <button class="button" type="button" onclick="window.location.href='?select=${i+3}'">${names[i]}</button>
        `)
    }
    document.write(`</div>`)
    */

    var ctx1 = document.getElementById("myChart1").getContext('2d');
    var myChart1 = new Chart(ctx1, {
        type: 'line',
        data: {
            labels: time1,
            datasets: [{
                label: names[select-3],
                data: data1,
                backgroundColor: backgroundColors[select-3],
                borderColor: borderColors[select-3],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true,
                        //fontSize: 30
                    }
                }]
            }
        }
    });
    var ctx2 = document.getElementById("myChart2").getContext('2d');
    var myChart2 = new Chart(ctx2, {
        type: 'line',
        data: {
            labels: time2,
            datasets: [{
                label: names[select-3],
                data: data2,
                backgroundColor: backgroundColors[select-3],
                borderColor: borderColors[select-3],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true,
                        //fontSize: 40
                    }
                }]
            }
        }
    });
    var ctx3 = document.getElementById("myChart3").getContext('2d');
    var myChart3 = new Chart(ctx3, {
        type: 'line',
        data: {
            labels: time3,
            datasets: [{
                label: names[select-3],
                data: data3,
                backgroundColor: backgroundColors[select-3],
                borderColor: borderColors[select-3],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true,
                        //fontSize: 40
                    }
                }]
            }
        }
    });
</script>
</body>
</html>