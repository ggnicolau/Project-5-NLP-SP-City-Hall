<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function file_get_contents_curl($url1) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
    curl_setopt($ch, CURLOPT_URL, $url1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);       

    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}


$news = new DOMDocument;
$url= file_get_contents_curl('https://www.defesa.gov.br/noticias?start=10');
//echo $url;
@$news->loadHTML($url);
//echo $news->saveHTML();

$xpath = new DOMXPath($news);
$newsList = array();
$newsList = $xpath->query('//h2[@class="tileHeadline"]/a'); 

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
	//echo $href;
	$title= $news->nodeValue;
	$html = file_get_contents_curl ('https://www.defesa.gov.br'.$href);
	@$tmp_news->loadHTML($html);
	$xpath2 = new DOMXPath($tmp_news);
	$parList = array();
	$parList = $xpath2->query('//div[@class="item-page"]/p'); 
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
