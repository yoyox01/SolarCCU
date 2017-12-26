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
    $date = $_GET["date"];
    $select = 3;
    if(isset($_GET["select"])){
        $select = $_GET["select"];
    }
    $station = $_GET["station"];

    if(strlen($month) == 1)
        $month = "0" . $month;
    if(strlen($date) == 1)
        $date = "0" . $date;  

    $dirname = $year . "年" . $month . "月" . $date . "日";
    //echo $dirname;

    /* read text to 2d array */
    $data = array();
    //$f = fopen("Solar/2016年02月04日/1Solar20160204.txt", "r");
    $f = fopen("Solar/" . $dirname . "/" . $station ."Solar" . $year . $month . $date .".txt", "r");
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
        fclose($f2);
    }
    

?>

<script>
<?php
    $data_json = json_encode($data);

    echo "\tlet year=$year\n";
    echo "\tlet month=$month\n";
    echo "\tlet date=$date\n";
    echo "\tlet select=$select\n";
    echo "\tlet station=$station\n";
    echo "\tlet solarData=$data_json\n";
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


    let time = new Array(solarData.length)
    for(let i=0; i<time.length; i++){
        time[i] = solarData[i][2];
    }
   
    let data = new Array(solarData.length)   
    for(let i=0; i<data.length; i++){
        data[i] = solarData[i][select]
    }
    
    let station_name
    if(station == 1){
        station_name = "電機館"
    }else if(station == 2){
        station_name = "圖書館"
    }else if(station == 3){
        station_name = "機車棚"
    }else{
        station_name = "Unknown"
    }
    document.write(`
        <div>
            <h2>
                <div>${station_name}</div>
                <div>${solarData[0][1].substring(0,4)}年${solarData[0][1].substring(4,6)}月${solarData[0][1].substring(6,8)}日</div>
                <div>${names[select-3]}</div>
            </h2>
            <div class="chart"><canvas class="detail" id="myChart"></canvas></div>
        
    `)
    
    /* screen width > 600px */
    document.write(`<div class="oneline">`)
    for(let i=0; i<10; i++){
        document.write(`
            <button class="button" type="button" onclick="window.location.href='?year=${year}&month=${month}&date=${date}&select=${i+3}&station=${station}'">${names[i]}</button>
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

    var ctx = document.getElementById("myChart").getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: time,
            datasets: [{
                label: names[select-3],
                data: data,
                backgroundColor: backgroundColors[select-3],
                borderColor: borderColors[select-3],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true
                    }
                }]
            }
        }
    });
</script>
</body>
</html>