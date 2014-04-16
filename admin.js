jQuery(document).ready(function(){
	var $ = jQuery; // sure does make the code cleaner
	// Check the review inputs on focus loss in case there browser doesn't support the number input type
	$('body').on('change blur','table.critique-review-inputs input',function(){
		var value = $(this).val();
		if(value.trim()!=''){// We reserve the right to leave sections blank and not review them
			// Check min and max
			if(value<parseInt($(this).attr('min'))){value=$(this).attr('min');}
			if(value>parseInt($(this).attr('max'))){value=$(this).attr('max');}
			// Check the rounding
			if($(this).attr('step')=='1'){
				value = Math.round(value);
			}else if($(this).attr('step')=='0.5'){
				value = Math.round(value*2)/2;
			}
			$(this).val(value);
		}
		critiqueSetDisplay(this);
		// Update the overall average (but only if this didn't fire from the overall average);
		if(!$(this).hasClass('critique-overall-average')){
			critiqueUpdateOverall();
		}
	})
	// This function will update the stars if the number in the input is changes
	function critiqueSetDisplay(input){
		var score = $(input).val();
		var scale = $(input).closest('tr').find('td.critique-metabox-scale');
		// Check for the various conditions so we know what type of scale were working with
		if($(scale).hasClass('type-5-stars')){
			// Star scale, add or remove the class "filled" from each i.star
			$(scale).find('i.star').each(function(){
				$(this).removeClass('full half')
				if($(this).index()<Math.floor(score)){
					$(this).addClass('full');
				}else if($(this).index()<score){
					$(this).addClass('half');
				}
			});
		}else if($(scale).hasClass('type-out-of-100')){
			$(scale).find('.critique-admin-of100-bar div').css('width',score+'%');
		}else{
			console.log('Unable to update display, could not determine critique scale type.');
		}
	}
	// This will update the "Overall" average if it is enabled
	function critiqueUpdateOverall(){
		if($('table.critique-review-inputs input[disabled]').length>0){
			var score = 0;
			var sections = 0;
			$('table.critique-review-inputs input:not([disabled])').each(function(){
				if($(this).val().trim()!=''){
					sections++
					score += parseInt($(this).val());
				}
			});
			$('table.critique-review-inputs input[disabled]').val(score/sections).trigger('blur');
		}
	}
	// Admin mouse actions, for easy selection without typing
	// First, the star items
	$('body').on('click','table.critique-review-inputs tr:not(.overall-average-line) div.critique-admin-star-container i',function(e){
		var hpos = ((e.pageX-$(this).offset().left)/$(this).width())*100;
		var value = $(this).index()+1;
		if(hpos<50){
			value -= 0.5;
		}
		$(this).closest('tr').find('input').val(value).trigger('blur');
	});
	// # out of 100
	$('body').on('click','table.critique-review-inputs tr:not(.overall-average-line) div.critique-admin-of100-bar',function(e){
		var hpos = ((e.pageX-$(this).offset().left)/$(this).width())*100;
		var value = Math.ceil(hpos);
		$(this).closest('tr').find('input').val(value).trigger('blur');
	});

});
