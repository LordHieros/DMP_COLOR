$(document).ready(function(){ 
	$('legend').click(function() { 
		$(this).parent().contents().filter(
			function() {  return this.nodeType == 3; }).wrap('<span></span>');//wrap any stray text nodes
		$(this).siblings().toggle(); 
	}); 
}) ;