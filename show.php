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

    $data = array();
    $f = fopen("2015-2017累計發電.txt", "r");
    if ($f) {
        $j=0;
        while (($line = fgets($f)) !== false) {
            $line = iconv("BIG5", "UTF-8", $line);
            $part = explode(" ", $line);
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
        echo    "file open error.";
    }
    //print_r($data);
?>
<script>
<?php
    $data_json = json_encode($data);

    echo "\tlet year=$year\n";
    echo "\tlet sumData=$data_json\n";
?>
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
    let color = Math.floor((Math.random()*10))

    let yearData = new Array(sumData.length-1)
    if(year >= 2015){
        for(let i=0; i<12; i++)
            yearData[i] = sumData[year-2015][i+1]
        
        document.write(`
            <div>
                <h2>
                    <div>${year}年</div>
                    <div>累積發電量(kWH)</div>
                </h2>
            </div>
        `)
    }else{
        document.write(`<h2>資料不完整</h2>`)
    }
    
    document.write(`
        <div class="chart"><canvas class="detail" id="myChart"></canvas></div>
    `)
    var ctx = document.getElementById("myChart").getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
            datasets: [{
                label: "累積發電量(kWH)",
                data: yearData,
                backgroundColor: backgroundColors[color],
                borderColor: borderColors[color],
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

    document.write(`<br><div id="oneline">`)
    for(let i=1; i<13; i++){
        document.write(`
            <button class="button" type="button"  onclick="location.href='show_month.php?year=${year}&month=${i}'">${i}月</button>
        `)
    }
    document.write(`</div>`)
    
</script>
</body>
</html>
