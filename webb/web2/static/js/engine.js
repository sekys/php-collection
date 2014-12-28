const engine_name = "#engine";
var engine;

function engine_add(id) { 
	engine.append(div_new(id, ''));
	return engine.find("#"+id); 
}
function engine_new() { 
	engine_new.id = engine_new.id+1 || 0;
	if(engine_new.id == 0) {
		engine = $(engine_name); 
	}
	a="engine_"+engine_new.id;
	return engine_add(a); 
}

/* Pomocne funkcie    */
function div_new(id, txt) { return "<div id='"+id+"'>"+txt+"</div>"; }
function presmeruj(cesta) { document.location.href= cesta; }
function random_to(x) { return Math.ceil(  Math.random() * x );}
function set_cookie(meno, x) { document.cookie = meno+"="+x+"; path=/"; }
function img_hover(obj, b, a, c) { c = (c) ? c : ''; img = ( $(obj).attr('src') == c+a ) ? b : a; $(obj).attr('src', c+img); }
function toNum(x) { return parseInt(x); }
function txt_add(id, pre, po) { 
	x = $(id);
	x.html( pre+x.html()+po );  
}

/* Dalsie */
function progressbar() { 
	progressbar.pozicia--; 
	document.getElementById("progressbar_green").style.backgroundPosition = pozicia+"px 50%"; 
}  
function roundfloat(cislo, pocetmiest) {
    buffer = cislo.toString(); /* float funkcie v javascripte su dost ubohe....riesenie cez retazec  */
    pozicia = buffer.indexOf(".");
    if(pozicia > -1) {
        return buffer.substring(0, buffer.indexOf(".") + 1 + pocetmiest);
    } return buffer;
}
function microtime() {
    if(!microtime.microcas) {
        microtime.microcas = new Date().getTime();
        return 0;
    } else {
        return (new Date().getTime() - microtime.microcas) / 10000;
    }
}
function precache_images() {
    var d=document; 
    if(d.images){ 
        if(!d.MM_p) d.MM_p=new Array();
        var i,j=d.MM_p.length,a=precache_images.arguments; for(i=0; i<a.length; i++)
        if (a[i].indexOf("#")!=0) { 
            d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}
    }
}
function web2_checkbox(id, farba) {
    var x = $("ch-i-"+id);
    if( x.value == "1" ) {
        x.value = "0";    
        document.getElementById("ch-s-"+id).src = IMAGES+"tool/"+farba+".png";    
    } else {
        x.value = "1";    
        document.getElementById("ch-s-"+id).src = IMAGES+"tool/"+farba+"-hilite.png";    
    }
}
function web2_checkbox_load() {
	precache_images(
	    IMAGES+"tool/green-hilite.png",
	    IMAGES+"tool/orange-hilite.png",
	    IMAGES+"tool/green.png",
	    IMAGES+"tool/orange.png",
	    IMAGES+"tool/default.png",
	    IMAGES+"tool/default-hilite.png"
	);
}
function web2_Tabs(data, x, y) {
    $('#tabs_'+data).tabs({
		cache:true,
		load: function (e, ui) {
			$(ui.panel).find(".tab-loading").remove();
		},
		select: function (e, ui) {
			var $panel = $(ui.panel);

			if ($panel.is(":empty")) {
				$panel.append(Preloader(x, y));
			}
		}
	});
}
function CSSMakeScrollFixed(name, y) {
	$(name).css('top', y);
	$(window).scroll(function(){
		var x = $(window).scrollTop();
		if(x > y) {
			$(name).css('top', 0);
		} else if( x < y) {
			x = y - x;
			$(name).css('top', x);
		}
	});	
}