var page = require('webpage').create();
var system = require('system');
var fs= require('fs');
var url = system.args[1];
    
page.open(url);
console.log(url);
page.onLoadFinished = function()
{
    // Output Results Immediately
    page.evaluate(function () {
        var html = document.getElementsByTagName('section')[1].textContent;	
		var tarih = document.querySelectorAll('h1.chapeu2')[0].innerHTML;	
    });
	
	//var tari = tarih.substr(tarih.indexOf(",")+1, 11); 
	 
	console.log(html);
	//fs.write("qhxpZ.txt", html, 'w'); 
    phantom.exit();
};
