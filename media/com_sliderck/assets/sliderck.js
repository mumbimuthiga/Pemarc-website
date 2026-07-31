/**
 * @name		Slider CK
 * @package		com_sliderck
 * @copyright	Copyright (C) 2016. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */

 
var $ck = jQuery.noConflict();

$ck(document).ready(function(){
	// $ck('#addslide').click(function() {
		// ckAddSlide();
	// });
});

// function ckAddSlide() {
	// $ck('#slidescontainer #addslide').before('<div class="slidethumb">' + ($ck('.slidethumb').length+1) + '</div>');
// }
/*
customstyles = new Object();
	$ck('.menustylescustom').each(function() {
		$this = $ck(this);
		customstyles[$this.attr('data-prefix')] = $this.attr('data-rule');
	});
	customstyles = JSON.stringify(customstyles);
	var myurl = URIBASE + "/index.php?option=com_templateck&view=template&layout=ajaxrendercss&format=raw";
	$ck.ajax({
		type: "POST",
		url: myurl,
		data: {
			objclass: focus.prop('class'),
			ckobjid: focus.prop('id'),
			action: 'preview',
			customstyles: customstyles,
			
			
			
*/

function ckInitTooltips() {
	jQuery('.hasTip').tooltip({"html": true,"container": "body"});
}
/**
* Encode the fields id and value in json
*/
function ckMakeJsonFields() {
	var fields = new Object();
	$ck('#styleswizard_options input, #styleswizard_options select, #styleswizard_options textarea').each(function(i, el) {
		el = $ck(el);
		if (el.attr('type') == 'radio') {
			if (el.attr('checked')) {
				fields[el.attr('name')] = el.attr('value');
			} else {
				// fields[el.attr('id')] = '';
			}
		} else {
			fields[el.attr('name')] = el.attr('value');
		}
	});
	fields = JSON.stringify(fields);

	return fields;
	// return fields.replace(/"/g, "|qq|");
}

/**
* Render the styles from the module helper
*/
function ckPreviewStylesparams() {
	var button = '#ckpopupstyleswizard_makepreview';
	ckAddWaitIcon(button);
	var fields = ckMakeJsonFields();
	customstyles = new Object();
	$ck('.menustylescustom').each(function() {
		$this = $ck(this);
		customstyles[$this.attr('data-prefix')] = $this.attr('data-rule');
	});
	customstyles = JSON.stringify(customstyles);
	var myurl = URIBASE + "/index.php?option=com_sliderck&task=ajaxRenderCss";
	$ck.ajax({
		type: "POST",
		url: myurl,
		data: {
			customstyles: customstyles,
			customcss: $ck('#customcss').val(),
			fields: fields
		}
	}).done(function(code) {
		$ck('#layoutcss').val(code);
		var csscode = '<style>' + code.replace(/\|ID\|/g, '#previewarea') + '</style>';
		$ck('#previewarea > .ckstyle').empty().append(csscode);
		ckRemoveWaitIcon(button);
	}).fail(function() {
		alert(Joomla.JText._('CK_FAILED', 'Failed'));
	});
}

/**
* Render the styles from the module helper
*/
function ckSaveStylesparams(button) {
	if (! $ck('#name').val()) {
		$ck('#name').addClass('invalid').focus();
		alert('Please give a name');
		return;
	}
	$ck('#name').removeClass('invalid');
	if (!button) button = '#ckpopupstyleswizard_save';
	ckAddWaitIcon(button);
	var fields = ckMakeJsonFields();
	customstyles = new Object();
	$ck('.menustylescustom').each(function() {
		$this = $ck(this);
		customstyles[$this.attr('data-prefix')] = $this.attr('data-rule');
	});
	customstyles = JSON.stringify(customstyles);
	var myurl = URIBASE + "/index.php?option=com_sliderck&task=ajaxSaveStyles&" + cktoken + "=1";
	$ck.ajax({
		type: "POST",
		url: myurl,
		data: {
			id: $ck('#id').val(),
			name: $ck('#name').val(),
			layoutcss: $ck('#layoutcss').val(),
			customstyles: customstyles,
			customcss: $ck('#customcss').val(),
			fields: fields
		}
	}).done(function(code) {
		try {
			var response = JSON.parse(code);
			if (response.result == '1') {
				$ck('#id').val(response.id);
			} else {
				alert(response.message);
			}
			if ($ck('#returnFunc').val() == 'ckSelectStyle') {
				window.parent.ckSelectStyle($ck('#id').val(), $ck('#name').val(), false)
			}
		}
		catch (e) {
			alert(e);
		}
		ckRemoveWaitIcon(button);
	}).fail(function() {
		alert(Joomla.JText._('CK_FAILED', 'Failed'));
	});
}

/**
 * Alerts the user about the conflict between gradient and image background
 */
function ckCheckGradientImageConflict(from, field) {
	if ($ck(from).val()) {
		if ($ck('#'+field).val()) {
			alert('Warning : you can not have a gradient and a background image at the same time. You must choose which one you want to use');
		}
	}
}

/**
 * Set the file path in the specified field from the file browser
 */
function ckSelectFile(file, field) {
	if (! field) {
		alert('ERROR : no field given in the function ckSelectFile');
		return;
	}
	$ck('#'+field).val(file).trigger('change');
}

/**
* Set the stored value for each field
*/
function ckApplyStylesparams() {
	if ($ck('#params').val()) {
		var fields = JSON.parse($ck('#params').val().replace(/\|qq\|/g, "\""));
		for (var field in fields) {
			ckSetValueToField(field, fields[field])
		}
	}
	// launch the preview to update the interface
	ckPreviewStylesparams();
}

/**
* Set the value in the specified field
*/
function ckSetValueToField(id, value) {
	var field = $ck('#' + id);
	if (!field.length) {
		if ($ck('#ckpopupstyleswizard input[name=' + id + ']').length) {
			$ck('#ckpopupstyleswizard input[name=' + id + ']').each(function(i, radio) {
				radio = $ck(radio);
				if (radio.val() == value) {
					radio.attr('checked', 'checked');
				} else {
					radio.removeAttr('checked');
				}
			});
		}
	} else {
		if (field.hasClass('color')) field.css('background',value);
		$ck('#' + id).val(value);
	}
}

/**
 * Add the spinner icon
 */
function ckAddWaitIcon(button) {
	$ck(button).addClass('ckwait');
}

/**
 * Remove the spinner icon
 */
function ckRemoveWaitIcon(button) {
	$ck(button).removeClass('ckwait');
}

/**
* Clear all fields
*/
function ckClearFields() {
	var confirm_clear = confirm('This will delete all your settings and reset the styles. Do you want to continue ?');
	if (confirm_clear == false) return;
	$ck('#ckpopupstyleswizard input').each(function(i, field) {
		field = $ck(field);
		if (field.attr('type') == 'radio') {
			field.removeAttr('checked');
		} else {
			field.val('');
			if (field.hasClass('color')) field.css('background','');
		}
	});
	// launch the preview
	ckPreviewStylesparams();
}

/*
function ckGetParamsFields(prefix) {
	var fields = {};
	$ck('#ckpopupstyleswizard .' + prefix).each(function(i, field) {
		field = $ck(field);
		var  fieldobj = {};
		if ( field.attr('type') == 'radio' ) {
			if ( field.attr('checked') == 'checked' ) {
				fields[field.attr('name')] = field.val();
			}
		} else if ( field.attr('type') != 'radio' ) {
			fields[field.attr('id')] = field.val();
		}
	});
	return fields;
}*/

/**
 * Export all settings in a json encoded file and send it to the user for download
 */
function ckExportParams() {
	var jsonfields = ckMakeJsonFields();
	var styleid = $ck('#id').val();

	var myurl = 'index.php?option=com_sliderck&task=exportParams';
	$ck.ajax({
		type: "POST",
		url: myurl,
		async: false,
		data: {
			jsonfields: jsonfields,
			styleid: styleid
		}
	}).done(function(response) {
		if (response == '1') {
			if ($ck('#ckexportfile').length) $ck('#ckexportfile').remove();
			$ck('#ckexportpagedownload').append('<div id="ckexportfile"><a class="btn" target="_blank" href="'+URIROOT+'/administrator/components/com_sliderck/export/exportParamsSliderckStyle'+styleid+'.mmck" download="exportParamsSliderckStyle'+styleid+'.mmck">'+Joomla.JText._('CK_DOWNLOAD', 'Download')+'</a></div>');
			CKBox.open({handler:'inline', content: 'ckexportpopup', fullscreen: false, size: {x: '400px', y: '100px'}});
		} else {
			alert('test')
		}
	}).fail(function() {
		// alert(Joomla.JText._('CK_FAILED', 'Failed'));
	});
	return;
}

/**
 * Ask the user to select the file to import
 */
function ckImportParams() {
	CKBox.open({id:'ckimportbox', handler:'inline', content: 'ckimportpopup', fullscreen: false, size: {x: '700px', y: '200px'}});
}

/**
 * Upload the json encoded settings and apply them in the interface
 */
function ckUploadParamsFile(formData) {
	var myurl = 'index.php?option=com_sliderck&task=uploadParamsFile';
	$ck.ajax({
		type: "POST",
		url: myurl,
		async: false,
		data: formData,
		dataType: 'json',
		processData: false,  // indique � jQuery de ne pas traiter les donn�es
		contentType: false   // indique � jQuery de ne pas configurer le contentType
	}).done(function(response) {
		if(typeof response.error === 'undefined')
		{
			// Success
			ckImportParamsFile(response.data);
		} else {
			console.log('ERROR: ' + response.error);
		}
	}).fail(function() {
		// alert(Joomla.JText._('CK_FAILED', 'Failed'));
	});
}

/**
 * Apply the json settings in the interface
 * TODO : can be replaced by the existing function ckApplyStylesparams
 */
function ckImportParamsFile(data) {
	var fields = jQuery.parseJSON(data.replace(/\|qq\|/g, "\""));
	for (var field in fields) {
		ckSetValueToField(field, fields[field])
	}

	// launch the preview
	ckPreviewStylesparams();
	CKBox.close('#importpage');
}

/**
 * Play the animation in the Preview area 
 */
function ckPlayAnimationPreview(prefix) {
	$ck('#stylescontainer .swiper-slide').removeClass('swiper-slide-active');
	var t = setTimeout( function() {
		$ck('#stylescontainer .swiper-slide').addClass('swiper-slide-active');
	}, ( parseFloat($ck('#' + prefix + 'animdur').val()) + parseFloat($ck('#' + prefix + 'animdelay').val()) ) * 1000);
}

function ckSetFloatingOnPreview() {
	var el = $ck('#previewarea');
	el.data('top', el.offset().top);
	el.data('istopfixed', false);
	$ck(window).bind('scroll load', function() { floatElement(el); });
	floatElement(el);
}


function floatElement(el) {
	var $window = $ck(window);
	var winY = $window.scrollTop();
	if (winY > el.data('top') && !el.data('istopfixed')) {
		el.after('<div id="' + el.attr('id') + 'tmp"></div>');
		$ck('#'+el.attr('id')+'tmp').css('visibility', 'hidden').height(el.height());
		el.css({position: 'fixed', zIndex: '1000', marginTop: '0px', top: '20px'})
			.data('istopfixed', true)
			.addClass('istopfixed');
	} else if (el.data('top') >= winY && el.data('istopfixed')) {
		var modtmp = $ck('#'+el.attr('id')+'tmp');
		el.css({position: '', marginTop: ''}).data('istopfixed', false).removeClass('istopfixed');
		modtmp.remove();
	}
}