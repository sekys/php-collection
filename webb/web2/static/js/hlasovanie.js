
/* Jquery handle - nefunguje na ajax oblasti :(   */
$(function() {     
    /* Hlasovanie */
    
    
    /*$(".hlasovanie").bind("mouseleave",function(){
        // Vycisti vsetko pre istotu ....
        $(this).each(function() {
            this.src = hlas_img+this.className+".gif";
         });
    });*/
    $(".hlasovanie img").bind("mouseenter",function(){
        var max = this.id;    
        $(this).parents(".hlasovanie").children("img").each(function() {
            if( this.id <= max) {
                if(this.className) {
                    this.src = hlas_img+"4.gif";
                }
            }
        });    
    }).bind("mouseleave",function(){
        $(this).parents(".hlasovanie").children("img").each(function() {
            if(this.className) {
                this.src = hlas_img+this.className+".gif";
            }
         });
    }).bind("click",function(){
        if(this.className) {
            var temp = $(this).parents(".hlasovanie").attr('id').split("-");
            this.html(Preloader(62, 31));
            ajax($(this).parents(".hlasovanie"), action(9, temp[1])+"&typ="+temp[0]+"&i="+this.id);
        }    
        /* 
            jQuery.get("/web2/web2/ajax/actions.php?akcia=9&typ="+temp[0]+"&id="+temp[1]+"&i="+this.id);
            $.get('/web2/web2/ajax/user.php?id='+id, function(data) {
                txt_set(user_zlozka, data);
                txt_add(cache_zlozka, "", '<div id="'+cache_zlozka+'-'+id+'">'+data+'</div>');
            });
        */
        /* Desktruktuj objekty uz netreba kedze mame ajax...  */
        /*var max = this.id;
        $(this).parents(".hlasovanie").children("img").each(function() {
            this.className = '';
        });*/
    });
});