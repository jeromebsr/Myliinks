jQuery(function ($){
	let flash = $('#flash');
	console.log('FLASH:');
	console.log(flash);
	if(flash.length > 0)
	{
		$( ".flash" ).addClass( "animate--drop-in-fade-out" );
		setTimeout(function(){
			$( ".flash" ).removeClass( "animate--drop-in-fade-out" );
		}, 3500);
	}
});