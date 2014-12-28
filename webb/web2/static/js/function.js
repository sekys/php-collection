/* Konstanty */
const ROOT = "/webb/";
const IMAGES = ROOT+"web2/images/";
const AJAX = ROOT+"web2/ajax/";

const cache_zlozka = "popup_user_cache";
const user_zlozka = "popup_user"; 
const comment_textarea = "#c_t";
const hlas_img = IMAGES+"aktivita/";

user_id = 0;
admin = false;

/* Experimentalne */
function fanusik(id) { jQuery.get(action('8', id)); }

/* Vsetko ostatne */
function galeria(id) { ajax("#shopgaleria", AJAX+"galeria.php?widget=shop&item="+id); } 
function showMe(it, box) { document.getElementById(it).style.display = (box.checked) ? "block" : "none"; }
function mShowMe(obj) { 
	$("#"+obj).toggle(); 
	var x = document.getElementById('toggle'+obj);
	if(x) {
		x.src = ( x.style.display == 'none' ) ? IMAGES+'tool/toggle.png' : IMAGES+'tool/toggle_collapse.png'; 
	}	
}
function stavka_toggle(id){ document.getElementById("stavka-"+id+"-row").style.display = (document.getElementById("stavka-"+id+"-row").style.display == 'none' ) ? "table-row" : "none"; }
function dennik_click(obj) { 
    dennik_click.bol = dennik_click.bol+1 || 0;
    if(!dennik_click.bol) {
        $("#dennik").val(''); 
        dennik_more(); 
    }
}
function img_hover2(obj, b, a) { img_hover(obj, b, a, IMAGES+'tool/'); }
function dennik_more() { 
	document.getElementById("dennik-text").className = '';
}
function dennik() { 
	dennik_more(); 
	var x = $("#dennik").val();
	if(x.indexOf("Nap&iacute;sa&#357; do denn&iacute;ka...") > -1 
	|| x.indexOf("Napísať do denníka...") > -1) {
		alert(0);
		return;
	}
	$.post(action('6', '1'), { sprava: x }, function(data){ 
		$("#dennik-zoznam").prepend(data);
	});
} 
function ftu(obj) { $(obj).find(".a").addClass("b"); } 
/* function ft(obj) { if($(obj).find(".a").hasClass("b")) { $(obj).find(".a").removeClass('b'); } else { $(obj).find(".a").addClass("b"); }} */
function del(obj) { img_hover2(obj, 'delete.gif', 'delete_hover.gif'); }

function anketa(id) {
	var deti = document.getElementById("anketa_voteoption").children;
	var vote= -1;
	for(var a=0; a < deti.length; a++) {
		if(deti[a].checked) { vote = deti[a].value; break; }
	}
	if(vote > -1) { wmini(action('7', id)+'&vote='+vote); }
}
function user(id) {
	/* Loading */
    gpe('text:<div id="'+user_zlozka+'">'+Preloader(100,150)+'</div>;'); 
	/* nacitavame data .... + cacher :) */
	if(!user_cache_search(id)) {
		$.get(AJAX+'user.php?id='+id, function(data) {
			$("#"+user_zlozka).html(data);
			$("#"+cache_zlozka).append( '<div id="'+cache_zlozka+'-'+id+'">'+data+'</div>' );
		});
	} else { 
		$("#"+user_zlozka).html( $("#"+cache_zlozka+'-'+id).html() );
	}   
}
function user_cache_search(id) {
	/* Alebo pouzijeme moj cachovaci system ?  */
	var upravene_id = cache_zlozka+'-'+id; 
	var deti = document.getElementById(cache_zlozka).children;
	for(var a=0; a < deti.length; a++) {
		if(deti[a].id == upravene_id) { return true; }
	}
	return false;
}
function login(obj) {
    $(obj).parent().parent().parent().dialog('close');
}
function kp(id) { wmini(action('13', id)); }
function kd(id) { wmini(action('12', id)); }
function action(akcia, id) { return AJAX+'actions.php?akcia='+akcia+'&id='+id; }
function la_reg(obj) { img_hover(obj, 'register.jpg', 'register_hov.jpg', IMAGES+'theme/'); }
function la_lost(obj) { img_hover(obj, 'lostpw.jpg', 'lostpw_hov.jpg', IMAGES+'theme/'); }
function lbh(obj, like) { 
    if(like == 1) {
        img_hover2(obj, 'thumb-up.gif', 'thumb-up_active.gif'); 
    } else { 
        img_hover2(obj, 'thumb-down.gif', 'thumb-down_active.gif'); 
    }
}
function lbc(kat, id, hlas) { wmini(action(kat, id)+'&hlas='+hlas); }

function Contact(id) { wajaxform('Kontakt', AJAX+'contact.php?id='+id, 600, 333); }
function report(url, type) { wajaxform('Report', action('14', type)+"&url="+bcheckaddress(url)); }
function Navod(id) { AjaxStred(AJAX+'navod.php?id='+id); }
function AjaxStred(url, x, y) { 
	return ajaxpre(".main-bg", url, (!x) ? '524' : x, (!y) ? '500' : y);	
}
