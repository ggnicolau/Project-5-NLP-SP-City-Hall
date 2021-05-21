<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('max_execution_time', 0);

$news = new DOMDocument;
$url= file_get_contents ('http://www.saopaulo.sp.leg.br/sala-de-imprensa/notas-oficiais/');
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
	$parList = array();
	$parList = $xpath2->query('//div[@property="rnews:articleBody"]/p'); 
	$data = $xpath2->query('//span[@class="documentPublished"]');
	
	echo "<h1>".$title."</h1>";
	echo "<br>";
	echo $data[0]->nodeValue;
	echo "<br>";
	foreach ($parList as $par) {
		echo $par->nodeValue; 
		echo "<br>";
	}
	echo "<br>";

}


?>

</body>

</html>
