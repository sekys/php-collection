function wChange(obj) {
    objekt = wFind(obj);
    wDestroy(objekt);
}
function wFind(objekt) {
    /* Najde okno podla vnutorneho objektu    */
    return $(objekt).parents(".ui-dialog").dialog("close");
}
function wDestroy(objekt) {
    /* Znic objekt  */
    $(objekt).dialog("close");
    $(objekt).dialog("destroy");
}
function wajaxform(title, adresa, x, y, formid) {
    formid = formid ? formid : "#form";
    x = x ? x : 500;
    y = y ? y : 300;
    objekt = engine_new();
    objekt.html( Preloader(x-(x/2), y-(y/2)) );    
    objekt.css({ 'background-color' : 'white' });
    wwindow(objekt, title, 
                    function(obj) { 
                        postdata = obj.find(formid).serialize();
                        obj.html( Preloader(x-(x/2), y-(y/2)) ); 
                        $.post(adresa, postdata, 
                            function(data) { obj.html(data); }
                        );    
                    }, 
                    x, y );                
    ajax(objekt, adresa);
}
function wajax(title, callback, x, y) {
    x = x ? x : 500;
    y = y ? y : 300;
    objekt = engine_new();    
    objekt.html( Preloader(x-(x/2), y-(y/2)) );
    objekt.css({ 'background-color' : 'white' });
    return wwindow(objekt, title, 
                    function(obj) { 
                        obj.html( Preloader(x-(x/2), y-(y/2)) ); 
                        callback(obj); 
                    }, 
                    x, y );
}
function wwindow(objekt, nazov, callback, x, y) {
    $(objekt).dialog({
        bgiframe: true,
        autoOpen: true,
        width: x,
        height: y,
        modal: true,
        title: nazov, 
        show: 'blind',
        hide: 'explode',
        buttons: {
            Ok : function() { 
                callback(this); 
            },
            /* Ak vypol okno  */
            Cancel: function() { 
                $(this).dialog('close'); 
            }
        }
    });
    /* V dalsej funkcii sa nacitaju udaje ktore chceme ...  */
    return objekt;
}
function walert(nazov, text) {
    if(!walert.created) {  
        $(function() { /* najprv musime okno vytvorit.....  */
            $('#dialog_error').dialog({
                bgiframe: true,
                height: 140,
                title: nazov,
                modal: true, 
                autoOpen: false
            });
        });
        walert.created = engine_new();
    }
    /* Uz len pri viacerom pouziti .... */
    walert.created.dialog('option', 'width', 140);
    walert.created.dialog('option', 'title', nazov);
    walert.created.html(text);
    walert.created.dialog('open');
    return walert.created;
}
function wmini(link, x, y, click, time, z) 
{  /* Nastavovacky - alias BeeperBox */
    if(x == null) x = 7;
    if(y == null) y = 19;
    if(time == null) time = 50;
    if(z == null) z = 101;
    if(z == -1) z = wmini.z = wmini.z+1 || 2;  /* automaticky posun dopredu */
    /* Neskor automatickym posun po Y suradnici ? A nasklada to ak oriadky. */
 
    /* Vytvor */
    objekt = engine_new();
    objekt.css({'left': x+'%', 'top': y+'%', 'z-index': z});
    objekt.addClass("miniwindow");
    if(click == null) { 
        objekt.bind("click", function(){ 
            $(this).hide("drop"); 
        }); 
    }
    $.get(link, function(data) { 
        /* + Cas */
        if(time != null) { 
            setTimeout(function() {
                if(objekt.css("display") != "none") {
                    objekt.hide("drop");
                }
            }, time*100); 
        }
        objekt.html(data);
    });
    return objekt;
}
function wclose(objekt)  {
    $(objekt).dialog('close');
}