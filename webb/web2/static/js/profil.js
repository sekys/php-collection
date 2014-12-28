var ProfilID = 0;
var PLogsTime = 0;

function profil_friends() {

}
function profil_lastgame() {
    
}
function stena_autoload() {
    $(document).ready(function(){ 
        $("#stena_sprava").bind("click",function() {
            if( $(this).attr('rows') == 2) {
                this.rows = 17;
            }    
        });
    });
}
function profil_autoload() {
    $(document).ready(function(){ 
        $('#tabs_profil').tabs( { tabTemplate: '<div><a href="#{href}"><span>#{label}</span></a></div>' });
    });
}
function stena(id) {
    adresa = action('10', id);        
    obj = captcha(adresa);
    $.post(adresa, { /* Pri captchi len prvykrat posielame udaje */
            stena_predmet: $("#stena_predmet").value, 
            stena_sprava: $("#stena_sprava").value 
        }, function(data){ 
        	obj.html(data);
    });
}
function stena_next(id) {
    /* Datum potrebujeme */
    stena_next.pocet = stena_next.pocet+1 || 0;

    /*+ Preloder */
    var x = $("#stena");
    x.value = x.value + '<span id="stena_'+stena_next.pocet+'">'+Preloader("100%", 200)+'</span>';
    ajax("#stena_"+stena_next.pocet, AJAX+'profil.php?akcia=stenanext&id='+ProfilID+"&cas="+PLogsTime);
}