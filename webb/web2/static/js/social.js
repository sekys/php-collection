function fav(obj) { img_hover(obj, IMAGES+'tool/fav_off.png', IMAGES+'tool/fav_on.png'); }
function addfav(url, name) { 
    url = bcheckaddress(url);
    name = bcheckname(name);
    if (window.sidebar) { // Mozilla Firefox Bookmark
        window.sidebar.addPanel(name, url, "");
    } else if( window.external ) { // IE Favorite
        window.external.AddFavorite( url, name); }
    else if(window.opera && window.print) { // Opera Hotlist
        return true; 
    }
}
function fb(url, name) { 
    url = bcheckaddress(url);
    name = bcheckname(name);
    window.open('http://www.facebook.com/sharer.php?u='
    +encodeURIComponent(url)
    +'&t='+encodeURIComponent(name),
    name,'toolbar=0,status=0,width=626,height=436');
}
function twitter(url, name) { 
    url = bcheckaddress(url);
    window.open('http://twitter.com/share?url='+encodeURIComponent(url),
    'Sharer','toolbar=0,status=0,width=626,height=436');
}
function bcheckname(name) { return (!name) ? document.title : name; }  
function bcheckaddress(url) { return (!url) ? window.location : url; }
function like(url) {
	// Posle log s adresou, treba ?
}
function likec(type, cid) {
	// Posle konkretny log, s ID a TYPE 
	wmini(action(15, cid)+'&type='+type);
}
function Tips() {	
	$(".tipaction").live("click", function() { 
		var id = $(this).parent().find(".tipaction").attr("id");
		if(!id) return;
		jQuery.get(action('16', 0)+"&mid="+id); 
		$(this).parent().parent().hide("drop"); 
	});
	/* Hide */
	$(".tiphide").live("click", function() { 
	    var id = $(this).parent().find(".tipaction").attr("id");
	    if(!id) return;
	    jQuery.get(action('16', 0)+"&mid="+id+"&hidden=1"); 
		$(this).parent().parent().hide("drop"); 
	});
}