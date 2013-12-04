jQuery(document).ready(function($) {
	$( "#ci_cpt_discography_date" ).datepicker({
		dateFormat: 'yy-mm-dd'
	});

	//
	// Events
	//
	$( "#ci_cpt_events_date" ).datepicker({
		dateFormat: 'yy-mm-dd'
	});

	$( "#ci_cpt_events_time" ).timepicker({
		ampm: false,
		timeFormat: 'HH:mm',
		stepMinute: 5
	});

	var isEnabled = $('#ci_cpt_event_recurrent').prop('checked');
	var datetime = $('#event_datetime');
	var recurrent = $('#event_recurrent');

	if (isEnabled) { 
		datetime.hide();
		recurrent.show(); 
	} 
	else { 
		datetime.show();
		recurrent.hide(); 
	}

	$('#ci_cpt_event_recurrent').click(function(){
		var datetime = $('#event_datetime');
		var recurrent = $('#event_recurrent');
		if ($(this).attr('checked')=="checked") {
			datetime.hide();
			recurrent.show(); 
		}
		else {
			datetime.show();
			recurrent.hide(); 
		}
	});


	//
	// Discography tracks (repeating fields)
	//
	function renumberTracks() {
		var $i = 1,
			$tbody = $("table.tracks").find("tbody");
		$tbody.each(function() {
			$(this).find(".track-num").text($i);
			$i++;
		});
	}

	// Repeating fields
	if($('#ci_repeating_tracks').length > 0) {
		$('#ci_repeating_tracks .tracks').sortable({
			update: renumberTracks
		});
		$('.tracks-add-field').on('click', function(e) {
			if ( $("table.tracks tbody").length ) {
				var trackNo = $("table.tracks tbody:last-child").find('.track-num').text();
			} else {
				var trackNo = 0;
			}
			var field = '<td class="tracks-field"><input type="text" name="ci_cpt_discography_tracks[]" value="" /></td>';
			var play_field = '<td class="tracks-field"><div class="wp-media-buttons"><input type="text" name="ci_cpt_discography_tracks[]" value="" class="uploaded with-button" /><a href="#" class="ci-upload ci-upload-track button add_media"><span class="wp-media-buttons-icon"></span></a></div></td>';

			var textfield = '<td class="tracks-field" colspan="6"><textarea placeholder="Song Lyrics" name="ci_cpt_discography_tracks[]" /></textarea></td>';
					html = '<tbody class="track-group"><tr><td class="cell-centered" rowspan="2">Track #<span class="track-num">'+ (parseInt(trackNo,10)+1) +'</span></td>' + field + field + field + play_field + field + '<td class="cell-centered"><a href="#" class="tracks-remove button insert-media add-media">Remove me</a></td>' + '<tr>' + textfield + '</tr></tbody>';

			$(html).hide().appendTo('.tracks').fadeIn().css('display','table-row-group');

			e.preventDefault();
		});
		$('#ci_repeating_tracks').on('click', '.tracks-remove', function() {
			$(this).parents('tbody.track-group').fadeOut(300, function() {
				$(this).remove();
				renumberTracks();
			});
			return false;
		});
	}


});