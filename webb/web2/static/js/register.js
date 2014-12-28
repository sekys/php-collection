function register(objekt) {
	/* Skryvame  ukazky*/	
	$('.hint').hide();
	/* Iba jedno ukazeme  */
	$(objekt).parent().find('.hint').show();
	/* Skry ostatne focusi */
	$("#signup input.inputtext").each(function() {
		this.css( { 
			backgroundColor:'', 
			borderColor:'', 
			color:'', 
			fontWeight:'' 
		});;	
	});
	/* focus zobrazyme */
	$(objekt).css({ 
		backgroundColor:'#FFE679', 
		borderColor:'#E08332',
		color:'#523E36', 
		fontWeight:'bold' 
	});	
}