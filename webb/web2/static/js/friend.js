function fd(obj, id) { 
	$(obj).parent().parent().hide("drop"); 
	jQuery.get(action('1', id)); 
} 
function fa(obj) { 
	jQuery.get(action('5', $(obj).parent().attr('id'))); 
	obj.src = IMAGES+"tool/redplus1.png"; 
}
function fu(obj) { 
	user( $(obj).parent().attr('id') ); 
}
function fto(obj) { 
	$(obj).find(".a").removeClass('b'); 
}