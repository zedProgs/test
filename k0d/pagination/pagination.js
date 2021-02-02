jQuery(function($){
	var pagen = Number.parseInt($('#postsList').attr('pagen'));
	$('#postsList').buzinaPagination({
		itemsOnPage:pagen
	});
});