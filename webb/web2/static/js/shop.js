/* Globalne */
kredit = 0;

function shop_load() {
	web2_Tabs('obchod', 510, 300);
}
function shopzmena() { 
	var x = $("#korun_zmena");
	x.val($('#to_kredit').val() / 30);
}
function shopzmenit() {
	kredit = $('korun_zmena').html();
	$('#tabs_obchod').tabs("select", 1);
}
function shopzmenaren() {
	/* Ziskane udaje a presmeruje na kontakt a tak doplnime udaje */
	typ = '';
	$("#payment_method").each(function() {
		if(this.checked) { typ = this.val(); }		
	});
	presmeruj(ROOT+"kontakt/?zmenaren="+typ+"&kredit="+kredit);	
}
function postava(id) { 
	ajax('#shop_ajax_panacikovia', 
	action(11, id)+'&avatar='+$("ch-i-panacikovia").val()); 
}
function obchod(id, act) { ajax("#obchod-"+id, action(act, id)); }