<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('max_execution_time', 0);

$k=1;
$p_length=10;
$p_lastnumber=105;

for ($i = 0; $i <=$p_lastnumber; $i++) {
$news = new DOMDocument;
$url= file_get_contents ('https://www.prefeitura.sp.gov.br/cidade/secretarias/subprefeituras/sao_miguel_paulista/noticias/index.php?page='.strval($i));
//echo $url;
@$news->loadHTML($url);
//echo $news->saveHTML();

$xpath = new DOMXPath($news);
$newsList = array();
$newsList = $xpath->query('//a[@class="media-list-link"]'); 

?>
<html>
<head>
<title>PHP Article Extractor</title>
</head>
<body>
<?php

$tmp_news = new DOMDocument(); 

foreach ($newsList as $news) {
	$href = "https://www.prefeitura.sp.gov.br/cidade/secretarias/subprefeituras/sao_miguel_paulista/noticias/".$news->getAttribute('href');	
	$title= $news->nodeValue;
	$html = file_get_contents ($href);
	@$tmp_news->loadHTML($html);
	$xpath2 = new DOMXPath($tmp_news);
	$parList = array();
	$parList = $xpath2->query('//div[@class="post-text"]/p'); 
	$data_html = $xpath2->query('//div[@class="col-md-6 hidden-xs"]/time');
	$data=$data_html[0]->nodeValue;
	$data= substr($data, -10);
	$content= $title.' '.$data;
	foreach ($parList as $par) {$content= $content.' '.$par->nodeValue; }
	$date = DateTime::createFromFormat('d/m/Y', trim($data));
	$filename = "subprefeitura/sao_miguel_paulista/".$date->format('Y-m-d')."-".strval($i+1)."-".$k.".txt";
	file_put_contents($filename, $content);
	echo $filename." <br>";
	$k=$k+1;
	

}
}


?>

</body>

</html>
