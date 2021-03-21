$(document).ready(function() {
	$("#link_name").keyup(function() {
		setTimeout(function(){
			let recup = $("#link_name").val();
			if (recup.length > 255){
				$(".link_name").html('Too many caracters. 255 max.');
				$(".btn-validate-add-link").attr('disabled', 'disabled');
			}else {
				$(".btn-validate-add-link").Removeattr('disabled', 'disabled');
				$(".link_name").html('');
			}
		},100);
	});

	$("#url").keyup(function() {
		setTimeout(function(){
			let recup = $("#url").val();
			if (recup.length > 255){
				$(".url").html('URL too longer. 255 max.');
				$(".btn-validate-add-link").attr('disabled', 'disabled');
			}else {
				$(".url").html('');
				$(".btn-validate-add-link").Removeattr('disabled', 'disabled');
			}
		},100);
	});

	$("#link_name_edit").keyup(function() {
		setTimeout(function(){
			let recup = $("#link_name_edit").val();
			if (recup.length > 255){
				$(".link_name_edit").html('Too many caracters. 255 max.');
				$(".btn-validate-edit-title").attr('disabled', 'disabled');
			}else {
				$(".btn-validate-edit-title").Removeattr('disabled', 'disabled');
				$(".link_name_edit").html('');
			}
		},100);
	});

	$( "#btn-add-link" ).on( "click", function()
	{
		$("#card-add-link").removeClass("display-none");
	});

	$( ".close" ).on( "click", function()
	{
		$("#card-add-link").addClass("display-none");
	});

	$( ".form-edit-link-url").on( "click", function()
	{
		$("#card-remove-link").removeClass("display-none");
		$("#trash").removeClass("display-none");
	});

	let id = $("[id^=edit-link-name-]").data("id");
	let a = $("[id^=edit-link-name-]").attr('data-id', id);

	$(a).on('click', function () {
		$(".edit-link-name").addClass('display-none');
		$(".form-edit-link-name").removeClass('display-none');

		$( ".close-form").on( "click", function() {
			$(".edit-link-name").removeClass('display-none');
			$(".form-edit-link-name").addClass('display-none');
		});
	});

	let id2 = $("[id^=edit-link-url-]").data("id");
	let b = $("[id^=edit-link-url-]").attr('data-id', id2);


	$(b).on('click', function () {
		$(".edit-link-url").addClass('display-none');
		$(".form-edit-link-url").removeClass('display-none');

		$( ".close-form").on( "click", function() {
			$(".edit-link-url").removeClass('display-none');
			$(".form-edit-link-url").addClass('display-none');
		});
	});

	$(".deleteLinkId").on('click', function () {
		let id = $(this).data('id');
		$(".hidden_delete_id").val(id);
	});
});