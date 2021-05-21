<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('max_execution_time', 0);

$k=1;
$p_length=10;
$p_lastnumber=8000;

for ($i = 6681; $i <=$p_lastnumber; $i++) {
$news = new DOMDocument;
$url= file_get_contents ('http://www.saopaulo.sp.gov.br/spnoticias/ultimas-noticias/page/'.strval($i));
//echo $url;
@$news->loadHTML($url);
//echo $news->saveHTML();

$xpath = new DOMXPath($news);
$newsList = array();
$newsList = $xpath->query('//h3[@class="title"]/a'); 


$tmp_news = new DOMDocument(); 

foreach ($newsList as $news) {
	$href = $news->getAttribute('href');	
	//echo $href." <br>";
	$title= $news->nodeValue;
	if (file_get_contents ($href)) {
	$html = file_get_contents ($href);
	@$tmp_news->loadHTML($html);
	$xpath2 = new DOMXPath($tmp_news);
	$parList = $xpath2->query('//article[@class="article-main"]/p'); 
	$data_html = $xpath2->query('//div[@class="meta"]/span[@class="date"]');
	if ($data_html->length > 0) {$data=$data_html[0]->nodeValue;} else {continue;}
	$data= substr($data, 5, 11);
	//echo $data." <br>";
	$content= $title.' '.$data.' ';
	foreach ($parList as $par) {$content= $content.' '.$par->nodeValue; }
	$date = DateTime::createFromFormat('d/m/Y', trim($data));
	$filename = "governodoestado/ultimas-noticias/".$date->format('Y-m-d')."-".strval($i)."-".$k.".txt";
	file_put_contents($filename, $content);
	echo $filename." <br>";
	$k=$k+1;
	}
	else {echo "The page cannot be retrieved ! <br>";}
	

}
}


?>

</body>

</html>
