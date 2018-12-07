$j = jQuery.noConflict();

$j(function(){
	webpLazyload();
});
function webpLazyload(){
	var img = $j('img');
	var div = $j('div');
	img.each(function()
	{
		var origsrc = $j(this).attr('src');
		var srcval = origsrc.replace(/(png|jpg|jpeg)/i, "webp");
		$j(this)
		.error(function()
		{
			$j(this).attr('src', origsrc);
		})
		.attr('src', srcval);
	});	
}