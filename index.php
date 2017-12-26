<html>
<head>
    <script src="js/Chart.js"></script>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/header_bar.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
    <?php include_once("header_bar.php"); ?>
    <div class="slideshow-container">
    <div class="mySlides fade">
        <div class="numbertext">1 / 3</div>
        <img src="pics/pic1.jpg" class="slideshow">
        <div class="text">圖源:https://www.flickr.com/photos/yy_flickr/8211322868/in/photostream/</div>
    </div>

    <div class="mySlides fade">
        <div class="numbertext">2 / 3</div>
        <img src="pics/pic2.jpg" class="slideshow">
        <div class="text">圖源:https://www.flickr.com/photos/yy_flickr/8210421589/in/photostream/</div>
    </div>

    <div class="mySlides fade">
        <div class="numbertext">3 / 3</div>
        <img src="pics/pic3.jpg" class="slideshow">
        <div class="text">圖源:http://gitlci.ccu.edu.tw/ccutw/teacher/teacherPartTime.aspx</div>
    </div>
    
    </div>
    <br>
    <!--
    <div style="text-align:center">
        <span class="dot" onclick="currentSlide(1)"></span> 
        <span class="dot" onclick="currentSlide(2)"></span> 
        <span class="dot" onclick="currentSlide(3)"></span> 
    </div>
    -->
<?php
    $dir = "Solar";
    $dir_array= array_diff(scandir($dir), array('..', '.'));
    
    $years = array();
    $temp_year = substr($dir_array[2], 0, 4);
    array_push($years, $temp_year);
    
    for($i=2; $i<count($dir_array); $i++){
        if(substr($dir_array[$i], 0, 4) != $temp_year){
            $temp_year = substr($dir_array[$i], 0, 4);
            array_push($years, $temp_year);
        }
    }
    //print_r($dir_array);
?>
<script>
<?php
    $years_json = json_encode($years);
    echo "\tlet years=$years_json\n";
?>

    document.write(`<div class="oneline">`)
    for(let i=0; i<years.length; i++){
        document.write(`
            <button class="button" type="button" onclick="location.href='show.php?year=${years[i]}'">${years[i]}</button>
        `)
    }
    document.write(`</div>`)

    /** 幻燈片 */
    var slideIndex = 0;
    showSlides();

    function showSlides() {
        var slides = document.getElementsByClassName("mySlides");
        for (let i = 0; i < slides.length; i++) {
            slides[i].style.display = "none"; 
        }
        slideIndex++;
        if (slideIndex> slides.length) {slideIndex = 1} 
        slides[slideIndex-1].style.display = "block"; 
        setTimeout(showSlides, 2500);
    }

    /*
    var slideIndex = 1;
    showSlides(slideIndex);
    
    function plusSlides(n) {
        showSlides(slideIndex += n);
    }
    
    function currentSlide(n) {
        showSlides(slideIndex = n);
    }
    
    function showSlides(n) {
        var i;
        var slides = document.getElementsByClassName("mySlides");
        var dots = document.getElementsByClassName("dot");
        if (n > slides.length) {slideIndex = 1} 
        if (n < 1) {slideIndex = slides.length}
        for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none"; 
        }
        for (i = 0; i < dots.length; i++) {
            dots[i].className = dots[i].className.replace(" active", "");
        }
        slides[slideIndex-1].style.display = "block"; 
        dots[slideIndex-1].className += " active";
    }*/
</script>
</body>
</html>