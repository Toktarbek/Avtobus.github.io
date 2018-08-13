<?php
//qatelikti shigaru ushin
function display_errors($errors){
	$display='<ul class="bg-danger">';
	foreach ($errors as $error) {
		$display.='<li class="text-danger">'.$error.'</li>';
	}
	$display.='</ul>';
	return $display;
}

//html kodtardi orindamau ushin 
function sanitize($dirty){
	return htmlentities($dirty,ENT_QUOTES,"UTF-8");
}

//tg pormatyn koiu ushin
function money($number){
	return number_format($number,2).' тг';
}


?>