<html>
<header>
    <script src="js/Chart.js"></script>
    <link rel="stylesheet" href="css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</header>
<body>
<?php
    //$f = fopen("1Solar20171009.txt", "r") or die("Unable to open file!");

    // read text to 2d array
    $data = array();
    $f = fopen("1Solar20171009.txt", "r");
    if ($f) {
        $i=0;
        while (($line = fgets($f)) !== false) {
            $part = explode(",", $line);
            $data[$i] = array(
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
            $i++;
            //print_r($data);
        }
        fclose($f);
    } else {
        echo "error opening the file.";
    }

    /*
    echo '<table border="1">';
    for($i=0; $i<count($data); $i++){
        echo '<tr>';
        for($j=0; $j<13; $j++){
            echo '<td>' . $data[$i][$j] . '</td>';
        }
        echo '</tr>';
    }
    echo '</table>';
    */
?>

<script>
<?php
    $select = $_GET["select"];
    $data = json_encode($data);

    echo "\tlet select=$select\n";
    echo "\tlet solarData=$data\n";
?>

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


    let time = new Array(901)
    for(let i=0; i<time.length; i++)
        time[i] = solarData[i][2];
   
    let data = new Array(901)
    for(let i=0; i<data.length; i++)
        data[i] = solarData[i][select]
     
    document.write(`
        <h2 style="display:flex;justify-content:space-around">
            <div>${solarData[0][0].replace("_"," ")}</div>
            <div>${solarData[0][1].substring(0,4)}年${solarData[0][1].substring(4,6)}月${solarData[0][1].substring(6,8)}日</div>
            <div>${names[select-3]}</div>
        </h2>
        <div class="chart"><canvas id="myChart"></canvas></div>`
    )
    
    /* screen width > 600px */
    document.write(`<div class="oneline">`)
    for(let i=0; i<10; i++){
        document.write(`
            <button class="button" type="button" onclick="window.location.href='?select=${i+3}'">${names[i]}</button>
        `)
    }
    document.write(`</div>`)

    /* screen width < 599px */
    document.write(`<div class="twoline">`)
    for(let i=0; i<5; i++){
        document.write(`
            <button class="button" type="button" onclick="window.location.href='?select=${i+3}'">${names[i]}</button>
        `)
    }
    document.write(`</div>`)
    document.write(`<div class="twoline">`)
    for(let i=5; i<10; i++){
        document.write(`
            <button class="button" type="button" onclick="window.location.href='?select=${i+3}'">${names[i]}</button>
        `)
    }
    document.write(`</div>`)

    var ctx = document.getElementById("myChart").getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
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