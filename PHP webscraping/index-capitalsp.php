<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('max_execution_time', 0);

$k=1;
$p_length=10;
$p_lastnumber=689;

for ($i =350; $i <=$p_lastnumber; $i++) {
$news = new DOMDocument;
$url= file_get_contents ('http://www.capital.sp.gov.br/@@busca?portal_type:list=News%20Item&sort_order=descending&b_start:int='.strval($i*$p_length).'&sort_on=effective');
//echo $url;
@$news->loadHTML($url);
//echo $news->saveHTML();

$xpath = new DOMXPath($news);
$newsList = array();
$newsList = $xpath->query('//a[@class="state-published"]'); 

?>
<html>
<head>
<title>PHP Article Extractor</title>
</head>
<body>
<?php

$tmp_news = new DOMDocument(); 

foreach ($newsList as $news) {
	$href =$news->getAttribute('href');	
	$title= $news->nodeValue;
	$html = file_get_contents ($href);
	@$tmp_news->loadHTML($html);
	$xpath2 = new DOMXPath($tmp_news);
	$parList = array();
	$parList = $xpath2->query('//div[@class="contentBody"]'); 
	$data_html = $xpath2->query('//div[@class="noticias_media"]/time');
	$data=$data_html[0]->nodeValue;
	$data= substr($data, -10);
	$content= $title.' '.$data.' ';
	if (count($parList)>0) {
	foreach ($parList as $par) {$content= $content.' '.$par->nodeValue; }
	$date = DateTime::createFromFormat('d/m/Y', trim($data));
	$filename = "prefeitura/capitalsp/".$date->format('Y-m-d')."-".strval($i+1)."-".$k.".txt";
	file_put_contents($filename, $content);
	echo $filename." islem tamam! <br>";
	$content='';
	}
	$k=$k+1;
	

}
}


?>

</body>

</html>
