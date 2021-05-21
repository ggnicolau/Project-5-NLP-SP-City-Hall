<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('max_execution_time', 0);

$k=1;
$p_length=10;
$p_lastnumber=1389;

for ($i = 1; $i <=$p_lastnumber; $i++) {
$news = new DOMDocument;
$url= file_get_contents ('https://www.educacao.sp.gov.br/noticias/page/'.strval($i));
//echo $url;
@$news->loadHTML($url);
//echo $news->saveHTML();

$xpath = new DOMXPath($news);
$newsList = array();
$newsList = $xpath->query('//h2[@class="title"]/a'); 


$tmp_news = new DOMDocument(); 

foreach ($newsList as $news) {
	$href = $news->getAttribute('href');	
	$title= $news->nodeValue;
	//echo $href." <br>";
	if (file_get_contents ($href)) {
	$html = file_get_contents ($href);
	@$tmp_news->loadHTML($html);
	$xpath2 = new DOMXPath($tmp_news);
	// $parList = array();
	$parList = $xpath2->query('//div[@class="entry-content"]'); 
	$data_html = $xpath2->query('//div[@class="date w-100"]/span');
	if ($data_html->length > 0) {$data=$data_html[0]->nodeValue;} else {continue;}
	$data= substr($data, -10);
	//echo $data." <br>";
	$content= $title.' '.$data.' ';
	foreach ($parList as $par) {$content= $content.' '.$par->nodeValue; }
	if (DateTime::createFromFormat('d/m/Y', trim($data))) {
	$date = DateTime::createFromFormat('d/m/Y', trim($data));
	$filename = "governodoestado/educacao/".$date->format('Y-m-d')."-".strval($i)."-".$k.".txt";
	file_put_contents($filename, $content);
	echo $filename." <br>";
	$k=$k+1;
	} else {echo "The date cannot be retrieved ! <br>";}
	}
	else {echo "The page cannot be retrieved ! <br>";}
	

}
}


?>

</body>

</html>
