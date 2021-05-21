<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$url='http://www2.planalto.gov.br/acompanhe-o-planalto/noticias?b_start:int=30';
$phantom_script='bin/get-website.js'; 
$response = shell_exec ('phantomjs ' .$phantom_script.' '.$url );
//echo $response;

$news = new DOMDocument;
@$news->loadHTML($response);

$xpath = new DOMXPath($news);
$newsList = array();
$newsList = $xpath->query('//a[@class="summary url"]'); 


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
	$html = shell_exec ('phantomjs ' .$phantom_script.' '.$href );
	@$tmp_news->loadHTML($html);
	$xpath2 = new DOMXPath($tmp_news);
	$parList = array();
	$parList = $xpath2->query('//div[@property="rnews:articleBody"]/p'); 
	$data = $xpath2->query('//span[@property="rnews:documentPublished"]');
	
	echo "<h1>".$title."</h1>";
	echo "<br>";
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
