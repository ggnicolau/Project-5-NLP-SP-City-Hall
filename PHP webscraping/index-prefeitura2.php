<?php

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
ini_set('max_execution_time', 0);

$k=1;
$p_length=10;
$p_lastnumber=1327;

for ($i = 1053; $i <=$p_lastnumber; $i++) {
$news = new DOMDocument;
$url= file_get_contents ('http://www.saopaulo.sp.leg.br/sala-de-imprensa/noticias/page/'.strval($i));
//echo $url;
@$news->loadHTML($url);
//echo $news->saveHTML();

$xpath = new DOMXPath($news);
$newsList = array();
$newsList = $xpath->query('//h1[@class="h2 entry-title"]/a'); 

$tmp_news = new DOMDocument(); 

foreach ($newsList as $news) {
	$href = $news->getAttribute('href');	
	$title= $news->nodeValue;
	//echo $href." <br>";
	if (file_get_contents ($href)) {
	$html = file_get_contents ($href);
	@$tmp_news->loadHTML($html);
	$xpath2 = new DOMXPath($tmp_news);
	$parList = array();
	$parList = $xpath2->query('//section[@itemprop="articleBody"]'); 
	$data_html = $xpath2->query('//time[@class="updated"]');
	$data=$data_html[0]->nodeValue;
	//echo $data." <br>";
	$data= substr($data, 0, 10);
	$date = DateTime::createFromFormat('d/m/Y', trim($data));
	$content= $title.' '.$data. ' '.$parList[0]->nodeValue;
	$filename = "camara/sala-de-imprensa/".$date->format('Y-m-d')."-".strval($i)."-".$k.".txt";
	file_put_contents($filename, $content);
	echo $filename." <br>";
	$content='';
	$k=$k+1;
	}
	else {echo "The page cannot be retrieved ! <br>";}
	

}
}


?>

</body>

</html>
