/* Opet globalne  */
var zobrazeny = true;
var tickety_id = new String();
var ticketov = 0;
var celkovy_kurz;

function stavky_vyhra() { txt_set('stavky_vyhra', roundfloat( val_get('stavky_vklad') * celkovy_kurz , 2)); }
function stavky_update() { txt_set("stavky_kurz", roundfloat(celkovy_kurz, 2)); set_cookie("tickety", tickety_id); stavky_vyhra(); }
function stavka(id, clany, kurz) { return '<table width="100%" cellpadding="0" cellspacing="0" class="malepismo" id="ticket-'+id+'"><tr><td width="10"><a href="javascript:stavky_vymaz('+id+');"><img align="absmiddle" border="0" onmouseout="this.src=\'/webb/web2/images/delete.gif\'" onmouseover="this.src=\''+IMAGES+'tool/delete_hover.gif\'" src="'+IMAGES+'tool/delete.gif" title="Zmazať" alt="X"/></a></td><td align="right">'+clany+'<br>Kurz: <span id="kurz-'+id+'">'+kurz+'</span></td></tr></table>'; }
function stavky_toggle() {
	if(zobrazeny) {
		txt_set("stavky_close") = 'Otvoriť';
		document.getElementById("stavky_inner").style.width = '10%';
	} else {
		txt_set("stavky_close") = 'Zavrieť';
		document.getElementById("stavky_inner").style.width = '100%';
	}
	zobrazeny = !zobrazeny;
}
function stavky_pridaj(id, clan) {
	/* Pripadne zapiname panel  */
	if(!zobrazeny) {
		stavky_toggle();
	}
	/* Vytvareme tabulku; */
	if(clan == 1) {
		clany  = "<strong>"+txt_get("zapas-"+id+"-1-meno")+"</strong> - "+" "+txt_get("zapas-"+id+"-2-meno");
	} else {
		clany  = txt_get("zapas-"+id+"-1-meno")+" - <strong>"+txt_get("zapas-"+id+"-2-meno")+"</strong>";
	}
	kurz = txt_get("zapas-"+id+"-"+clan+"-kurz");
	/* Kontrolujeme duplicitne data */
	if(tickety_id.search("."+id) > -1) {
		error_dialog("St&aacute;vky", "<p>Tento z&aacute;pas u&#382; m&aacute;&scaron; v zozname.</p>");
		return false;
	}
	/* Velky ticketov ticketov */
	if(ticketov > 12) {
		document.getElementById("stavky_p").style.position = 'absolute';
	}
	/* prvy krat a pri ziadnom ... */
	txt_set("stavky_tip", '');	
	/* Updatujeme data    */
	if(celkovy_kurz == 0 ) { celkovy_kurz = 1; }
	txt_add("stavky_tickety", "", stavka(id, clany, kurz));
	tickety_id = tickety_id+"."+id+"-"+clan;
	celkovy_kurz = celkovy_kurz * parseFloat(kurz);	/* float funkcie v javascripte su dost ubohe.... */
	stavky_update();
	ticketov++;
}
function stavky_vymaz(id) {
	ticketov--;
	if(ticketov < 12) {
		document.getElementById("stavky_p").style.position = 'fixed';
	}
	celkovy_kurz = celkovy_kurz / parseFloat(txt_get("kurz-"+id));
	tickety_id = tickety_id.replace("."+id+"-2", "");
	tickety_id = tickety_id.replace("."+id+"-1", "");
	stavky_update();
	txt_set("ticket-"+id, '');
	$('#ticket-'+id).toggle("drop", 500);
}
function stavky_vymaz_all() {
	celkovy_kurz = 0.0;
	ticketov = 0;
	tickety_id = '';
	stavky_update();
	
	txt_set("stavky_tickety", '');
	document.getElementById("stavky_p").style.position = 'fixed';
}	