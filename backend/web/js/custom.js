$( document ).ready(function() {
	$.fn.modal.Constructor.prototype.enforceFocus = function() {};
		  $('tbody').sortable();
		  //alert(1);
		 
		  $(document).on('change', ".package", function() {
			var id = $(this).val();
			//alert(id);	
			});
		$(document).on('blur', ".receipt:first", function() {
			//alert(this.value); 
		var receipt = $(this).val();
		$('.receipt').val(receipt);
		});
		$(document).on('blur', ".amount:first", function() {
			//alert(this.value); 
		var amount = $(this).val();
		$('.amount').val(amount);
		});
		 
		}());		