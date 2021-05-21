<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('max_execution_time', 0);

$k=1;
$news = new DOMDocument;
$url= file_get_contents ('http://www.saopaulo.sp.leg.br/sala-de-imprensa/banco-de-releases/');
//echo $url;
@$news->loadHTML($url);
//echo $news->saveHTML();

$xpath = new DOMXPath($news);
$newsList = array();
$newsList = $xpath->query('//h1[@class="h2 entry-title"]/a'); 

?>
<html>
<head>
<title>PHP Article Extractor</title>
</head>
<body>
<?php

$tmp_news = new DOMDocument(); 

foreach ($newsList as $news) {
	$href = $news->getAttribute('href');	
	$title= $news->nodeValue;
	$html = file_get_contents ($href);
	@$tmp_news->loadHTML($html);
	$xpath2 = new DOMXPath($tmp_news);
	$parList = $xpath2->query('//section[@itemprop="articleBody"]/p'); 
	$data_html = $xpath2->query('//time[@class="updated"]');
	$data=$data_html[0]->nodeValue;
	//echo $data." <br>";
	$data= substr($data, 0, 10);
	$content= $title.' '.$data. ' ';
	foreach ($parList as $par) {$content= $content.' '.$par->nodeValue; }
	$date = DateTime::createFromFormat('d/m/Y', trim($data));
	$filename = "camara/banco-de-releases/".$date->format('Y-m-d')."-".$k.".txt";
	file_put_contents($filename, $content);
	echo $filename." <br>";
	$content='';
	$k=$k+1;

}


?>

</body>

</html>
