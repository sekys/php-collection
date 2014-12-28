
function cq(obj) { 
    text = $(obj).parent().parent().find(".text").text();
    val_add(comment_textarea, "", "[Q]"+text+"[/Q]"); 
}
function cd(obj) { 
	var udaje = $(obj).parents(".comment").attr('id').split("-");
	adresa = action('15', udaje[0])+"&typ="+udaje[1];
	// return confirm(\"".$locale['414']."\");
	wajaxform("Vymaza&#357; koment&aacute;r", adresa);
}
function ce(obj) { 
	var udaje = $(obj).parents(".comment").attr('id').split("-");
	adresa = action('16', udaje[0])+"&typ="+udaje[1];
	wajaxform("Upravi&#357; koment&aacute;r", adresa);
}
function bbcode(txtarea, typ) {
	ta = $(txtarea);
	switch(typ) {
		case 0: /* Link  */
			var href = prompt("Odkaz:", ""); 
			if(!href) { return false; }
			var meno = prompt("Nazov odkazu:", "");
			if(!meno) { return false; }
			ta.value(ta.val() + "[URL="+meno+"]"+href+"[/URL]");	
			break;
		case 1: /* Image   -  Spravyme potom max sirka/vyska 50px a nahlad ako v galerii */
			data = prompt("Link na obrazok:", "");
			if(!data) { return false; }
			ta.val(ta.val() + "[IMG]"+data+"[/IMG]");
			break;
		case 2: /* GL  */
			/*callback = function() {
				var data = // v tvare 5,2 kategoria, id itemu ()potom neskor vyberieme ci je to video / image
				var meno = // vyberieme meno / nazov objektu
				val_add(textarea, "", "[GL="+data+"]"+meno+"[/GL]");

			};
			web2_window(objekt, callback, 200, 100);*/
			break;
		case 3: /*Youtobe */
			data = prompt("Link na YOUTOBE video:", "");
			if(!data) { return false; }
			ta.val(ta.val() + "[YOUTOBE]"+data+"[/YOUTOBE]"); 
			break;		
	}	
	return true;
}
function sendcomment(obj, ctyp, cid) {
	$.post(action(14, cid), { /* Pri captchi len prvykrat posielame udaje */
            ctype: ctyp, 
            //disable_smileys: val_get("#stena_sprava"),
            c_t: $(obj).val()
        }, function(data){ 
        	$(".comments").prepend(data);
    });
}

/* Jquery handle - nefunguje na ajax oblasti :(   */
$(function() { 
	/* Striedanie farby */
	$(".comment .right").each(function() {
		pasik = ( $(this).attr('class').search('tbl1')) ? 'qon' : 'qoff';	
		$(this).find('.text').find('.q').each(function() {
			$(this).addClass(pasik);
		});	
	});
	
    /*  Komentare akcie */
	$(".comment").live("mouseenter",function(){
	    /* Ak je ten uzivatel zobraz panel */
	    var udaje = $(this).attr('id').split("-");
	    if( (toNum(udaje[2]) == user_id) || admin == true) {
	        data = '<a onclick="ce(this);"><span class="ui-icon2 ui-icon-pencil"></span></a>';
	        data += '<a onclick="cd(this);"><span class="ui-icon2 ui-icon-close"></span></a>';
	        $(this).find(".more").html(data);
	    }    
	}).live("mouseleave",function(){
	    /* Vycisti panel */
	    if( (toNum($(this).attr('id')) == user_id) || admin == true) {
	        $(this).find(".more").html("");        
	    }
	});
	
	
	
});
