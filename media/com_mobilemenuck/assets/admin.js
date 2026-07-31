/**
 * @name		Mobile Menu CK
 * @package		com_mobilemenuck
 * @copyright	Copyright (C) 2017. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */

 
var $ck = jQuery.noConflict();

//$ck(document).ready(function(){
//	ckLoadGfontStylesheets();
//});

// manage the tabs
function ckInitTabs(wrap, allowClose) {
	if (! allowClose) allowClose = false;
	if (! wrap) wrap = $ck('#styleswizard_options');
	$ck('div.ckinterfacetab:not(.current)', wrap).hide();
	$ck('.ckinterfacetablink', wrap).each(function(i, tab) {
		$ck(tab).click(function() {
			if ($ck(this).hasClass('current')) {
				var taballowClose = $ck(this).attr('data-allowclose') ? $ck(this).attr('data-allowclose') : allowClose;
				if (taballowClose == true) {
					$ck('div.ckinterfacetab[data-group="'+$ck(tab).attr('data-group')+'"]', wrap).hide();
					$ck('.ckinterfacetablink[data-group="'+$ck(tab).attr('data-group')+'"]', wrap).removeClass('open current active');
				}
			} else {
				$ck('div.ckinterfacetab[data-group="'+$ck(tab).attr('data-group')+'"]', wrap).hide();
				$ck('.ckinterfacetablink[data-group="'+$ck(tab).attr('data-group')+'"]', wrap).removeClass('open current active');
				if ($ck('#' + $ck(tab).attr('data-tab'), wrap).length)
					$ck('#' + $ck(tab).attr('data-tab'), wrap).show();
				$ck(this).addClass('open current active');
			}
		});
	});
}

/**
* Encode the fields id and value in json
*/
function ckMakeJsonFields() {
	var fields = new Object();
	$ck('#styleswizard_options input, #styleswizard_options select, #styleswizard_options textarea').each(function(i, el) {
		el = $ck(el);
		if (el.attr('type') == 'radio') {
			if (el.prop('checked')) {
				fields[el.attr('name')] = el.val();
			} else {
				// fields[el.attr('id')] = '';
			}
		} else if (el.attr('id') == 'customcss') {
			fields[el.attr('name')] = el.val()
				// .replace(/{/g, "|bs|")	// bracket start
				// .replace(/}/g, "|be|")	// bracket end
				.replace(/\\/g, "|sl|"); 	// slash
		} else {
			fields[el.attr('name')] = el.val();
		}
	});
	fields = JSON.stringify(fields);

	return fields;
//	return fields.replace(/"/g, "|qq|");
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
	var myurl = URIBASE + "/index.php?option=com_mobilemenuck&task=ajaxRenderCss";
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
		code = ckMakeCssReplacement(code);
		var csscode = '<style>' + code.replace(/\|ID\|/g, '#previewarea ') + '</style>';
		$ck('#previewarea > .ckstyle').empty().append(csscode);
		ckRemoveWaitIcon(button);
		ckUpdateTitleTheme();
		ckLoadGfontStylesheets();
		
		var menubarbuttonhtml = ckGetMobilebuttonContent($ck('#ckpopupstyleswizard input[name=menubarbuttoncontent]:checked').val(), $ck('#menubarbuttoncontentcustomtext').val());
		$ck('#mobilemenuck-preview-mobile-bar .mobilemenuck-bar-button').html(menubarbuttonhtml);
		var topbarbuttonhtml = ckGetMobilebuttonContent($ck('#ckpopupstyleswizard input[name=topbarbuttoncontent]:checked').val(), $ck('#topbarbuttoncontentcustomtext').val());
		$ck('#mobilemenuck-preview-mobile .mobilemenuck-button').html(topbarbuttonhtml);
	}).fail(function() {
		alert(Joomla.JText._('CK_FAILED', 'Failed'));
	});
}

function ckMakeCssReplacement(code) {
	for (var tag in CKCSSREPLACEMENT) {
		var i = 0;
		while (code.indexOf(tag) != -1 && i < 100) {
			code = code.replace(tag, CKCSSREPLACEMENT[tag]);
			i++;
		}
	}
	return code;
}

function ckGetMobilebuttonContent(value, customtextfield_value) {
	switch (value) {
		case 'hamburger':
			var content = '&#x2261;';
			break;
		case 'close':
			var content = '×';
			break;
		case 'custom' :
			var content = customtextfield_value;
			break;
		default :
		case 'none':
			var content = '';
			break;
	}
	return content;
}

/**
* Render the styles from the module helper
*/
function ckSaveStylesparams(button) {
	if (! $ck('#name').val()) {
		$ck('#name').addClass('invalid').focus();
		alert(Joomla.JText._('CK_PLEASE_GIVE_NAME', 'Please give a name'));
		return;
	}
	if ($ck('#initialname').val() !== '' && $ck('#initialname').val() !== $ck('#name').val()) {
		var createnewstyle = confirm(Joomla.JText._('CK_CONFIRM_SAVE_AND_COPY'));
		if (createnewstyle) {
			$ck('#id').val('0');
		}
	}
	$ck('#name').removeClass('invalid');
	if (!button) button = '#ckpopupstyleswizard_save';
//	ckAddWaitIcon(button);
	ckAddSpinnerIcon(button);
	var fields = ckMakeJsonFields();
	customstyles = new Object();
	$ck('.menustylescustom').each(function() {
		$this = $ck(this);
		customstyles[$this.attr('data-prefix')] = $this.attr('data-rule');
	});

	customstyles = JSON.stringify(customstyles);
	var myurl = URIBASE + "/index.php?option=com_mobilemenuck&task=ajaxSaveStyles&" + CKTOKEN + "=1";
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
				$ck('#initialname').val($ck('#name').val());
			} else {
				alert(response.message);
			}
			if ($ck('#returnFunc').val() == 'ckSelectStyle') {
				window.parent.ckMobilemenuUpdateStyle(null, $ck('#id').val(), $ck('#name').val())
			}
		}
		catch (e) {
			alert(e);
		}
//		ckRemoveWaitIcon(button);
		ckRemoveSpinnerIcon(button);
	}).fail(function() {
		alert(Joomla.JText._('CK_FAILED', 'Failed'));
	});
}

function ckAddSpinnerIcon(btn) {
	// if (! btn.attr('data-class')) var icon = btn.find('.fa, .fas, .far').attr('class');
	// btn.attr('data-class', icon).find('.fa, .fas, .far').attr('class', 'fas fa-spinner fa-pulse');
	$ck(btn).find('.ckspin').css('display', 'inline-block');
}

function ckRemoveSpinnerIcon(btn) {
	var t = setTimeout( function() {
		// btn.find('.fa, .fas, .far').attr('class', btn.attr('data-class'));
		$ck(btn).find('.ckspin').css('display', 'none');
	}, 500);
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
					radio.prop('checked', 'checked');
				} else {
//					radio.removeAttr('checked');
//					radio.removeProp('checked');
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
	$ck('#styleswizard_options input').each(function(i, field) {
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
	jsonfields = jsonfields.replace(/"/g, "|qq|")
	var styleid = $ck('#id').val();

	var myurl = 'index.php?option=com_mobilemenuck&task=exportParams';
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
			$ck('#ckexportpagedownload').append('<div id="ckexportfile"><a class="ckbutton" target="_blank" href="'+URIROOT+'/administrator/components/com_mobilemenuck/export/exportParamsMobilemenuckStyle'+styleid+'.mmck" download="exportParamsMobilemenuckStyle'+styleid+'.mmck">'+Joomla.JText._('CK_DOWNLOAD', 'Download')+'</a></div>');
			CKBox.open({handler:'inline', content: 'ckexportpopup', fullscreen: false, size: {x: '400px', y: '150px'}});
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
	var myurl = 'index.php?option=com_mobilemenuck&task=uploadParamsFile';
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
 * TODO : can be replaced with the existing function ckApplyStylesparams
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

/**
 * Float the preview on scroll to have it always visible
 */
function ckSetFloatingOnPreview() {
	var el = $ck('#previewarea');
	el.data('top', el.offset().top);
	el.data('istopfixed', false);
	$ck(window).bind('scroll load', function() { ckFloatElement(el); });
	ckFloatElement(el);
}

/**
 * Float the preview on scroll to have it always visible
 */
function ckFloatElement(el) {
	var $window = $ck(window);
	var winY = $window.scrollTop();
	if (winY > el.data('top') && !el.data('istopfixed')) {
		el.after('<div id="' + el.attr('id') + 'tmp"></div>');
		$ck('#'+el.attr('id')+'tmp').css('visibility', 'hidden').height(el.height());
		el.css({position: 'fixed', zIndex: '1000', marginTop: '0px', top: '80px'})
			.data('istopfixed', true)
			.addClass('istopfixed');
	} else if (el.data('top') >= winY && el.data('istopfixed')) {
		var modtmp = $ck('#'+el.attr('id')+'tmp');
		el.css({position: '', marginTop: ''}).data('istopfixed', false).removeClass('istopfixed');
		modtmp.remove();
	}
}

/**
 * Apply the css classes to the title for the theme selection
 */
function ckUpdateTitleTheme() {
	$ck('#previewarea > .mobilemenuck').attr('class', 'mobilemenuck ' + $ck('#titletheme').val() + ' ' + $ck('#titlethemecolor').val());
}

function ckCleanGfontName(field) {
	var myurl = 'index.php?option=com_mobilemenuck&task=cleanGfontName';
	$ck.ajax({
		type: "POST",
		url: myurl,
		async: false,
		data: {
			gfont: $ck(field).val().replace("<", "").replace(">", "")
		}
	}).done(function(response) {
		response = response.trim();
		if ( response.substring(0,5).toLowerCase() == 'error' ) {
			show_ckmodal(response);
			// alert(response);
		} else {
			$ck(field).val(response);
		}
		ckCheckFontExists(field);
	}).fail(function() {
		alert(Joomla.JText._('CK_FAILED', 'Failed'));
	});
}

function ckCheckFontExists(field) {
	if (!field.value) return;
	var myurl = '//fonts.googleapis.com/css?family=' + field.value;
	$ck.ajax({
		url: myurl,
		data: {

		},
		statusCode: {
			200: function() {
				$ck(field).next('.isgfont').val('1');
				ckLoadGfontStylesheets();
			}
		}
	}).done(function(response) {
		$ck(field).next('.isgfont').val('0');
	}).fail(function() {
		alert(Joomla.JText._('CK_IS_NOT_GOOGLE_FONT', 'This is not a google font, check that it is loaded in your website'));
		$ck(field).next('.isgfont').val('0');
	});
}

function ckLoadGfontStylesheets() {
	var gfonturls = '';
	$ck('.isgfont').each(function() {
		if ($ck(this).val() == '1') {
			var gfonturl = ckGetFontStylesheet($ck(this).prev('.gfonturl').val());
			gfonturls += gfonturl;
		}
	});

	$ck('#ckpopupstyleswizardgfont').html(gfonturls);
}

function ckGetFontStylesheet(family) {
	if (! family) return '';
	return ("<link href='https://fonts.googleapis.com/css?family="+family+"' rel='stylesheet' type='text/css'>");
}

/**
 * Loads the file from the preset and apply it to all fields
 */
function ckLoadPreset(name) {
	var confirm_clear = ckClearFields();
	if (confirm_clear == false) return;

	var button = '#ckpopupstyleswizard_makepreview .ckwaiticon';
	ckAddWaitIcon(button);

	// remove the values for all the fields
	

	// ajax call to get the fields
	var myurl = 'index.php?option=com_mobilemenuck&task=loadPresetFields';
	$ck.ajax({
		type: "POST",
		url: myurl,
		dataType: 'json',
		data: {
			preset: name
		}
	}).done(function(r) {
		if (r.result == 1) {
			var fields = r.fields;
			fields = fields.replace(/\|qq\|/g, '"');
			fields = fields.replace(/\|bs\|/g, '{');
			fields = fields.replace(/\|be\|/g, '}');
			fields = fields.replace(/\|sl\|/g, '\\');
			ckSetFieldsValue(fields);
			// get the value for the custom css
			ckLoadPresetCustomcss(name);
			// ckPreviewStylesparams();
		} else {
			alert('Message : ' + r.message);
			ckRemoveWaitIcon(button);
		}
		
	}).fail(function() {
		//alert(Joomla.JText._('CK_FAILED', 'Failed'));
	});

	
}

function ckLoadPresetCustomcss(name) {
	var button = '#ckpopupstyleswizard_makepreview .ckwaiticon';
	// add_wait_icon(button); // already loaded in the previous ajax function load_preset()
	// ajax call to get the custom css
	var myurl = 'index.php?option=com_mobilemenuck&task=loadPresetCustomcss'
	$ck.ajax({
		type: "POST",
		url: myurl,
		data: {
			folder: name
		}
	}).done(function(r) {
		if (r.substr(0, 7) == '|ERROR|') {
			alert('Message : ' + r);
		} else {
			$ck('#customcss').val(r);
			ckPreviewStylesparams();
			// preview_stylesparams();
		}
		ckRemoveWaitIcon(button);
		ckPreviewStylesparams();
	}).fail(function() {
		//alert(Joomla.JText._('CK_FAILED', 'Failed'));
	});
}

function ckSetFieldsValue(fields) {
	var fields = JSON.parse(fields);
	for (field in fields) {
		ckSetValueToField(field, fields[field]);
	}
}

function ckSetStyle(id, styleid, returnFunc) {
	var myurl = 'index.php?option=com_mobilemenuck&task=ajaxSetStyle&' + CKTOKEN;
	$ck.ajax({
		type: "POST",
		url: myurl,
		async: false,
		dataType: 'json',
		data: {
			id: id,
			styleid: styleid
		}
	}).done(function(r) {
		if (r.result == 1) {
			if (typeof(window[returnFunc]) == 'function') window[returnFunc](r.id, r.styleid, r.name);
		} else {
			alert('Message : ' + r.message);
			CKBox.close();
		}
	}).fail(function() {
		alert(Joomla.JText._('CK_FAILED', 'Failed'));
	});
}

function ckSetCustomMenuStyle(id, styleid, returnFunc) {
	var myurl = 'index.php?option=com_mobilemenuck&task=ajaxSetCustomMenuStyle&' + CKTOKEN;
	$ck.ajax({
		type: "POST",
		url: myurl,
		async: false,
		dataType: 'json',
		data: {
			id: id,
			styleid: styleid
		}
	}).done(function(r) {
		if (r.result == 1) {
			if (typeof(window[returnFunc]) == 'function') window[returnFunc](r.id, r.styleid, r.name);
		} else {
			alert('Message : ' + r.message);
			CKBox.close();
		}
	}).fail(function() {
		alert(Joomla.JText._('CK_FAILED', 'Failed'));
	});
}

function ckToggleMobileState(id, state) {
	// var id = jQuery(jQuery('.editing').parents('.ckrow')[0]).attr('data-id');
	ckSetMobileState(id, 1 - parseInt(state), 'ckUpdateMobileState');
}

function ckSetMobileState(id, state, returnFunc) {
	var myurl = 'index.php?option=com_mobilemenuck&task=ajaxSetMobileState&' + CKTOKEN;
	$ck.ajax({
		type: "POST",
		url: myurl,
		async: false,
		dataType: 'json',
		data: {
			id: id,
			state: state
		}
	}).done(function(r) {
		if (r.result == 1) {
			if (typeof(window[returnFunc]) == 'function') window[returnFunc](r.id, r.state);
		} else {
			alert('Message : ' + r.message);
			CKBox.close();
		}
	}).fail(function() {
		alert(Joomla.JText._('CK_FAILED', 'Failed'));
	});
}

function ckSetMerge(mergeid, modulename) {
	var row = $ck($ck('.editing').parents('.ckrow')[0]);
	var id = row.attr('data-id');

	if (mergeid == id) {
		alert('You can not merge the menu with itself.');
		CKBox.close();
		return;
	}
	var myurl = 'index.php?option=com_mobilemenuck&task=ajaxSetMerge&' + CKTOKEN;
	$ck.ajax({
		type: "POST",
		url: myurl,
		async: false,
		dataType: 'json',
		data: {
			id: id,
			mergeid: mergeid
		}
	}).done(function(r) {
		if (r.result == 1) {
//			if (typeof(window[returnFunc]) == 'function') window[returnFunc](r.id, r.styleid, r.name);
			if (mergeid == 0) {
				row.find('.mergeid').text('');
				row.find('.mergename').text('');
				row.find('.mergeorder').hide();
				$ck('.ckrow[data-id="' + id + '"] td.style *').show();
			} else {
				row.find('.mergeid').text('ID ' + mergeid);
				row.find('.mergename').text(modulename);
				row.find('.mergeorder').show();
				$ck('.ckrow[data-id="' + id + '"] td.style  *').hide();
			}
			CKBox.close();
		} else {
			alert('Message : ' + r.message);
			CKBox.close();
		}
	}).fail(function() {
		alert(Joomla.JText._('CK_FAILED', 'Failed'));
	});
}

function ckRemoveMerge() {
//	var id = jQuery(jQuery('.editing').parents('.ckrow')[0]).attr('data-id');
	ckSetMerge(0);
}

function ckSetMergeOrder(btn, mergeorder) {
	var row = $ck($ck(btn).parents('.ckrow')[0]);
	var id = row.attr('data-id');

	var myurl = 'index.php?option=com_mobilemenuck&task=ajaxSetMergeOrder&' + CKTOKEN;
	$ck.ajax({
		type: "POST",
		url: myurl,
		async: false,
		dataType: 'json',
		data: {
			id: id,
			mergeorder: mergeorder
		}
	}).done(function(r) {
		if (r.result == 1) {
//			if (typeof(window[returnFunc]) == 'function') window[returnFunc](r.id, r.styleid, r.name);
//			if (mergeid == 0) {
//				row.find('.mergeid').text('');
//				row.find('.mergename').text('');
//			} else {
//				row.find('.mergeid').text('ID ' + mergeid);
//				row.find('.mergename').text(modulename);
//			}
//			CKBox.close();
		} else {
			alert('Message : ' + r.message);
//			CKBox.close();
		}
	}).fail(function() {
		alert(Joomla.JText._('CK_FAILED', 'Failed'));
	});
}

function ckAddEditingClass(btn) {
	$ck('.editing').removeClass('editing');
	$ck(btn).parent().addClass('editing');
}

function ckMakeItemsSortable() {
	$ck( "#ckitemslist tbody" ).sortable({
		items: ".ckrow",
		helper: "clone",
		// axis: "y",
		handle: "> .ckordering",
		forcePlaceholderSize: true,
		tolerance: "pointer",
		placeholder: "ckplaceholder",
		// zIndex: 9999,
		start: function(e, ui){
			console.log('start');
//			$ck(this).find('.item_content_edition').each(function(){
//				if (tinymce.get($ck(this).attr('id'))) {
//					ckRemoveEditorOnTheFly($ck(this).attr('id'));
//				}
//			});
		},
		update: function(e, ui) {
			console.log('update');
//			$ck(this).find('.item_content_edition:not(.ui-sortable-helper)').each(function(){
//				ckLoadEditorOnTheFly($ck(this).attr('id'));
//			});
//			ckUpdatePreviewArea();
//			$ck( "#<?php echo $id; ?>_preview_accordion" ).accordionck("refresh");
		}
	});
}

// manage the tabs
function ckInitInterfaceTabs() {
	$ck('.ckinterface').each(function() {
		var interface = $ck(this);
		$ck('.div.ckinterfacetab:not(.current)', interface).hide();
		$ck('.ckinterfacetablink', interface).each(function(i, tab) {
			$ck(tab).click(function() {
				$ck('div.ckinterfacetab[data-group="'+$ck(tab).attr('data-group')+'"]', interface).hide();
				$ck('.ckinterfacetablink[data-group="'+$ck(tab).attr('data-group')+'"]', interface).removeClass('current');
				if ($ck('#' + $ck(tab).attr('data-tab')).length)
					$ck('#' + $ck(tab).attr('data-tab')).show();
				$ck(this).addClass('current');
			});
		});
	});
}