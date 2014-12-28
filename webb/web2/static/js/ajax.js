function Preloader(x, y) { 
    return '<table align="center" width="'+x+'" height="'+y+'"><tr><td align=\'center\'><img src="'+IMAGES+'theme/loading.gif" /></td></tr></table>';
}
function ajax(objekt, url) { $(objekt).load(url); } 
function captcha(adresa)  {    
    return web2_ajaxwindow("Zadaj text z kontroln&eacute;ho obr&aacute;zka:", 
    	function(objekt) { 
	        var x = $("#captcha");
	        $.post(adresa, { 
	            captcha: x ? x.val() : ''
	        }, function(data) { 
	        	objekt.html(data); 
	        }); 
	}); 
    /* Potom sa prvykrat nacitajudata - staci len prvykrat...   */
}
function Rid() {
    /*
    To prevent caching with any browser when using any method, simply add a unique request id as a parameter.
    i.e. $.get('_ajax.php?rid=123456789');
    The browser will see this as a different file and request a fresh copy. 
    */
    cas = new Date().getTime().toString();
    return "?rid="+cas;
}
function CaptchaReload(obj, link) { 
    adresa = link+Rid();
    $(obj).find('.captcha-screen').attr("src", adresa); 
}
function ajaxpre(objekt, url, x, y) {
    obj = $(objekt);
    if(obj.length) {
        obj.html(Preloader(x, y));
        obj.load(url);
        return true;
    }
    return false;
}
