function empty (mixed_var) { // http://kevin.vanzonneveld.net
	var key;
	if (mixed_var === "" ||
		mixed_var === " " ||
		mixed_var === 0 ||
		mixed_var === "0" ||
		mixed_var === null ||
		mixed_var === false ||
		typeof mixed_var === 'undefined'
	){ return true; }	
	if (typeof mixed_var === 'object') {
		for (key in mixed_var) { return false; }
		return true;
	}
	return false;
}

jQuery(document).ready(function($) {
	$(".datepicker").live('focus', function() {
	  	$(this).datepicker({
			changeMonth: true,
			changeYear: true,
			showAnim: "slideDown",
			yearRange: "c-600:c",
			showOn:'focus'
		}); 
	});
	
	$('.clone span.genealogy_add').live('click', function(e) {
		//e.preventDefault();
		var $div = $(this).parents('div.clone');
		var $clone = $div.clone();
		var rel = $div.attr('rel');
		$('input,select',$clone).val('').attr('checked', false).change();
		$('div.timespan', $clone).hide();
		$clone.hide().insertAfter($div).slideDown(function() { $('input.hasDatepicker', $clone).removeClass('hasDatepicker'); }).find('input:not(:checkbox)').focus();
		
		updateCloneNumbering(rel);
		return false;
	});
	
	$('.timespan_to input.datepicker').live('focus click blur change', function(e) {
		if(empty($(this).val())) {
			$(this).parents('.timespan').find('.timespan_reason').hide();
			return;
		}
		$(this).parents('.timespan').find('.timespan_reason').show(); 
		return;
	});
	
	$('label.living input[name="family_member_data[living]"]').bind('click change load', function(e) {
		if($(this).val() == 'Deceased') {
			$('fieldset.death').slideDown();
		} else {
			$('fieldset.death').slideUp();
		}
		return;
	});
	
	$('input[name="family_member_data[living]"]:checked').trigger('change');
	
	$('.clone span.genealogy_remove').live('click', function(e) {
		e.preventDefault();
		var rel = $(this).parents('.clone').attr('rel');
		if($('.clone[rel='+rel+'] span.genealogy_remove').length > 1) {
			$(this).parents('div.clone').slideUp(function() { $(this).remove(); });
		}
		updateCloneNumbering(rel);
		return false;
	});
	
	function numberIt(rel, name, i) {
		if(!name) { return '';}
		name = name.replace(/\_[0-9]+/i, '');
		name = name.replace(rel, rel+'_'+i);
		return name;
	}
			
	function updateCloneNumbering(rel) {
		if(!rel) {
			$('div.clone').each(function() {
				if($('div.clone[rel="'+$(this).attr('rel')+'"]').length > 1) {
					$('.genealogy_remove', $(this)).show();
				} else {
					$('div.clone[rel="'+$(this).attr('rel')+'"]').length
					$('.genealogy_remove', $(this)).hide();
				}
			});	
			return;
		}
		
		$('div.clone[rel='+rel+']').each(function(index) {
			i = index+1;
			//console.log('i= '+i);
			$('select,input,label', $(this)).each(function(a) {
				if($(this).attr('name')) {
					$(this).attr('name', numberIt(rel, $(this).attr('name'), i));
				}
				if($(this).attr('id')) {
					$(this).attr('id', numberIt(rel,$(this).attr('id'), i));
				}
				if($(this).attr('for')) {
					$(this).attr('for', numberIt(rel,$(this).attr('for'), i));
				}
			});
			
			if($('div.clone[rel='+rel+']').length > 1) {
				//alert($('div.clone[rel='+rel+']').length + ' - rel:'+rel);
				$('.genealogy_remove', $(this)).show();
			} else {
				//alert($('div.clone[rel='+rel+']').length + ' ELSE - rel:'+rel);
				$('.genealogy_remove', $(this)).hide();
			}
		});
		
		triggerTimeSpanLoad();

		return;
	}
	
	function triggerTimeSpanLoad() {
		$('input[type=checkbox][name*="_checked"]').each(function() { $(this).trigger('load'); });
	}
	
	$('input[type=checkbox][name*="_checked"]').live('load click', function() {
		if($(this).attr('checked')) {
			$(this).parent().next('div.timespan').slideDown();
		} else {
			$(this).parent().next('div.timespan').slideUp();
		}
	}).trigger('load');
	
	updateCloneNumbering();
});