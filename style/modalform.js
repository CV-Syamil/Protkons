function view_form(el) {
	var href = $(el).data('href');
	var title = $(el).data('title');
	$('#modal_form .modal-title').html(title);
	$('#modal_form .modal-body').html('');
	$.ajax({
		url: href, type: 'GET',
		beforeSend: ()=>{
			$('#modal_form').modal('show');
			setTimeout(function() {
				blockUI('#modal_form .modal-dialog');
			}, 200);
		},error: (e)=>{
			setTimeout(function() {
				toastr.error(e.statusText,e.status);
				$('#modal_form').modal('hide');
				blockUI('#modal_form .modal-dialog',false);
			}, 500);
		},success: (r)=>{
			setTimeout(function() {
				$('#modal_form .modal-body').html(r);
				blockUI('#modal_form .modal-dialog',false);
			}, 500);
		}
	});
}
$('.btn_add').on('click', function(){view_form(this);});
$(document).on('click', '.btn_edit',function(){view_form(this);});
$(document).on('click', '.btn_hapus',function(){
	var href = $(this).data('href');
	Swal.fire({
	    title: 'Hapus Data ?',
	    text: "Data akan terhapus secara permanen.",
	    icon: 'warning',
	    showCancelButton: true,
	    confirmButtonText: 'Hapus Data',
	    showLoaderOnConfirm: true,
	    preConfirm: ()=>{
	      return new Promise(function(resolve, reject) {
	        window.location = href;
	      });
	    },allowOutsideClick:false
	}).then((result)=>{ });
});