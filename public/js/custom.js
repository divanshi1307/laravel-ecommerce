$(document).ready(function() {
    $ = jQuery.noConflict();
	// Override summernotes image manager
	$('[data-toggle=\'summernote\']').each(function() {
	    var element = this;
		var attr = $(this).attr("data-height");
		var height=350;
		if (typeof attr !== typeof undefined && attr !== false) {
			height=attr;
		}
		$(element).summernote({
			height: height,
			callbacks: {
				onPaste: function (e) {
					var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
					e.preventDefault();
					document.execCommand('insertText', false, bufferText);
				}
			},
			toolbar: [
				['style', ['style']],
				['font', ['bold', 'underline', 'clear']],
				['fontname', ['fontname']],
				['fontsize', ['fontsize']],
				['color', ['color']],
				['para', ['ul', 'ol', 'paragraph']],
				['table', ['table']],
				['insert', ['link', 'image', 'video']],
				['view', ['fullscreen', 'codeview', 'help']],
				['mybutton', ['file']]
			],
			buttons: {
				file: function (context) {
                    return $.summernote.ui.button({
                        contents: '<i class="fa fa-file"/>',
                        tooltip: 'Add PDF/Doc Link',
                        click: function () {
        					$('#modal-image').remove();
        					$.ajax({
        					    type: "get",
        						url: siteurl+"/admin/filemanager",
        						data: {
        						    file_type : 'files'
        						},
        						dataType: 'html',
        						success: function(html) {
        							$('body').append('<div id="modal-image" class="modal">' + html + '</div>');
        							$('#modal-image').modal('show');
        							$('#modal-image').delegate('a.thumbnail', 'click', function(e) {
        								e.preventDefault();
        								$(element).summernote('createLink', {
                                            text: "Download File",
                                            url: $(this).attr('href'),
                                            isNewWindow: true
                                        });
        								$('#modal-image').modal('hide');
        							});
        						}
        					});						
        				}
                    }).render();
                },
    			image: function() {
					var ui = $.summernote.ui;
					// create button
					return ui.button({
						contents: '<i class="note-icon-picture" />',
						tooltip: 'Select Image',
						click: function () {
							$('#modal-image').remove();
							$.ajax({
							    type: "get",
								url: siteurl+"/admin/filemanager",
								data: {
        						    file_type : 'images'
        						},
								dataType: 'html',
								success: function(html) {
									$('body').append('<div id="modal-image" class="modal">' + html + '</div>');
									$('#modal-image').modal('show');
									$('#modal-image').delegate('a.thumbnail, .insertAll', 'click', function(e) {
										e.preventDefault();
										if( $(this).hasClass('thumbnail') ){
										    $(element).summernote('insertImage', $(this).attr('href'));
										}else{
										    var html = "<div class='gallery-div'>";
    										$('input[name^=\'path\']:checked').each(function(){
    										    html += "<img src='"+$(this).attr('data-val')+"' width='260px'/>";
    										});
    										html += "</div>";
    										$(element).summernote('pasteHTML', html);
										}
										$('#modal-image').modal('hide');
									});
								}
							});						
						}
					}).render();
				}
  			}
		});
	});
	
	$('select').select2({placeholder: "Select", theme: 'bootstrap'});
	
	$('[data-toggle="tooltip"]').tooltip();
	$('.title > a').tooltip();
		
	$('[data-toggle=\'datepicker\']').each(function() {
	    var start_date = $(this).attr('start-date');
		$(this).datepicker({
			format: "dd-mm-yyyy",
			autoclose: true,
			startDate: (typeof start_date !== typeof undefined && start_date !== false) ? new Date(start_date) : ""
		});
	});
	
	$('.close-nav a').click(function(){
	    $('#mainMenu').hide();
	});
	
	$('.navbar-toggler').click(function(){
	    $('#mainMenu').show();
	});
	
	// Quantity
	/*$('.qty').change(function(){
		if( $(this).val()<1 ){
			$(this).val("1");
		}
	});*/
	$('.qty-plus').click(function(){
		var max_qty = $(this).attr("data-max");
		var input = $(this).parent('.qty-btn').parent('.qty-div').children('.qty')
		var oldval = parseInt(input.val());
		if( max_qty!="" ){
			if( oldval >= max_qty){
				input.val(oldval);
				input.trigger('change');
				alert("Max. "+max_qty);
				return false;
			}else{
				var newval = oldval + 1;
				input.val(newval);
				input.trigger('change');
			}
		}else{
			var newval = oldval + 1;
			input.val(newval);
			input.trigger('change');
		}
	});
	$('.qty-minus').click(function(){
		var input = $(this).parent('.qty-btn').parent('.qty-div').children('.qty')
		var oldval = parseInt(input.val());
		if(oldval>1){
			var newval = oldval - 1;
			input.val(newval);
		}else{
			var newval = oldval;
			$(this).parent('.qty-btn').parent('.qty-div').children('.edit-qty').val(oldval - 1);
		}
		input.trigger('change');
	});
	
});

$(function () {
	$('[data-toggle=\'datetimepicker\']').each(function() {
	    var start_date = $(this).attr('start-date');
		var end_date = $(this).attr('end-date');
		$(this).datetimepicker({
			format: "D-M-Y H:m",
			collapse: false,
			allowInputToggle: true,
			minDate: (typeof start_date !== typeof undefined && start_date !== false) ? new Date(start_date) : false,
			maxDate: (typeof end_date !== typeof undefined && end_date !== false) ? new Date(end_date) : false,
			disabledHours: false
		});
	});
});
function filter(element) {
	var value = $(element).val();

	$(".list > li").each(function() {
		if ($(this).text().search(value) > -1) {
			$(this).show();
		}
		else {
			$(this).hide();
		}
	});
}
/*$(document).keydown(function(e){ 
	if(e.which === 123){ return false; } 
	if(e.ctrlKey && e.keyCode == 'E'.charCodeAt(0)){ return false; } 
	if(e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)){ return false; } 
	if(e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)){ return false; } 
	if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)){ return false; } 
	if(e.ctrlKey && e.keyCode == 'S'.charCodeAt(0)){ return false; } 
	if(e.ctrlKey && e.keyCode == 'H'.charCodeAt(0)){ return false; } 
	if(e.ctrlKey && e.keyCode == 'A'.charCodeAt(0)){ return false; } 
	if(e.ctrlKey && e.keyCode == 'F'.charCodeAt(0)){ return false; } 
	if(e.ctrlKey && e.keyCode == 'E'.charCodeAt(0)){ return false; } 
});
$(document).bind("contextmenu",function(e){ e.preventDefault(); });*/

function numbersonly(myfield, e, dec) { 
	var key; 
	var keychar; 
	if (window.event) key = window.event.keyCode; else if (e) key = e.which; else return true; 
	keychar = String.fromCharCode(key);
	// control keys 
	if ((key==null) || (key==0) || (key==8) || (key==9) || (key==13) || (key==27) ) return true; 
	// numbers 
	else if ((("0123456789").indexOf(keychar) > -1)) return true; 
	// decimal point jump 
	else if (dec && (keychar == ".")) { myfield.form.elements[dec].focus(); return false; } else return false;
}
