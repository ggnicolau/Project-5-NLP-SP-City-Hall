<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('max_execution_time', 0);

$k=1;
$p_length=10;
$p_lastnumber=3;
$phantom_script='bin/get-website.js'; 

for ($i = 3; $i <=$p_lastnumber; $i++) {
$news = new DOMDocument;
$url= file_get_contents ('http://www.ssp.sp.gov.br/noticia/UltimasNoticias.aspx?pag='.strval($i));
//echo $url;
@$news->loadHTML($url);
//echo $news->saveHTML();

$xpath = new DOMXPath($news);
$newsList = array();
$newsList = $xpath->query('//a[contains(@id, "conteudo_repData_repNoticias")]'); 


$tmp_news = new DOMDocument(); 

foreach ($newsList as $news) {
	$href = "http://www.ssp.sp.gov.br".$news->getAttribute('href');	
	$title= $news->nodeValue;
	$response = shell_exec ('phantomjs '.$phantom_script.' '.$href );
	//echo $response." <br>";
	//echo $href." <br>";
	if (@$tmp_news->loadHTML($response)) { 
	$xpath2 = new DOMXPath($tmp_news);
	//$parList = array();
	//$dataList = array();
	$parList = $xpath2->query('//section[@ng-controller= "ctrlNoticia"]'); 
	$dataList = $xpath2->query('//h1[@ng-bind= "DataFormatada"]'); 
	//echo var_dump($parList->nodeValue);
	$data=$dataList[0]->nodeValue;
	//echo $data." <br>";
	$data = substr($data, (strpos($data,',')+1),11);
	echo $parList[0]->nodeValue." <br>";
	$content= trim(preg_replace('/\s+/', ' ', $parList[0]->nodeValue));
	$content=utf8_encode($content);
	if (DateTime::createFromFormat('d/m/Y', trim($data))) {
	$date = DateTime::createFromFormat('d/m/Y', trim($data));
	$filename = "governodoestado/ssp-ultimas-noticias/".$date->format('Y-m-d')."-".strval($i)."-".$k.".txt";
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
