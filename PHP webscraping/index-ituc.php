<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('max_execution_time', 0);

$k=1;
$p_length=10;
$p_lastnumber=210;

for ($i = 0; $i <=$p_lastnumber; $i++) {
$news = new DOMDocument;
$url= file_get_contents ('https://www.ituc-csi.org/spip.php?page=more&type=news&debut_news='.strval($i*$p_length).'#pagination_news');


//echo $url;
@$news->loadHTML($url);
//echo $news->saveHTML();

$xpath = new DOMXPath($news);
$newsList = array();
$newsList = $xpath->query('//div[@class="news-text"]//a'); 

?>
<html>
<head>
<title>PHP Article Extractor</title>
</head>
<body>
<?php

$tmp_news = new DOMDocument(); 

foreach ($newsList as $news) {
	$href = 'https://www.ituc-csi.org/'.$news->getAttribute('href');	
	$title= $news->nodeValue;
	$html = file_get_contents ($href);
	@$tmp_news->loadHTML($html);
	$xpath2 = new DOMXPath($tmp_news);
	$parList = array();
	$parList = $xpath2->query('//div[@class="main"]//p'); 
	$data_html = $xpath2->query('//p[@class="date"]');
	$content= $title.' '.$data_html[0]->nodeValue;
	foreach ($parList as $par) {
		$content= $content.' '.$par->nodeValue; 
	}
	
	$filename = "ituc/".strval($i+1)."-".$k.".txt";
	file_put_contents($filename, $content);
	echo $filename." islem tamam! <br>";
	$k=$k+1;

}
}


?>

</body>

</html>
