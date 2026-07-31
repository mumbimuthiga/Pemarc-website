/**
 * @copyright	Copyright (C) 2016 Cedric KEIFLIN alias ced1870
 * http://www.joomlack.fr
 * @license		GNU/GPL
 * */

jQuery(document).ready(function (){
	ckUpdateLabelsFromFolder(jQuery('#jform_params_folder').val());
});

function ckGetFolder(id, path) {
	document.getElementById(id).value = path;
	CKBox.close();
	ckUpdateLabelsFromFolder(path);
}

function ckUpdateLabelsFromFolder(path) {
	myurl = CKURIBASE + '/index.php?option=com_sliderck&task=ajaxLoadLabelsFromFolder'
	jQuery.ajax({
			type: "POST",
			url: myurl,
			data: {
				moduleId: CKMODULEID,
				path: path
			},
		}).done(function(code) {
			jQuery('#jform_params_folderimagespreview').empty().append(code);
//			ckMakeImagesSortable();
		}).fail(function() {
			// alert(Joomla.JText._('CK_FAILED', 'Failed'));
		});
}

function ckMakeImagesSortable() {
	jQuery('#labelseditor').sortable({
		items: '> div:not(.ckheader)',
		handle: '.ckmove',
		update: function( event, ui ){
		}
	});
}

function ckEditLabelsFile() {
	var path = jQuery('#jform_params_folder').val();
	myurl = CKURIBASE + '/index.php?option=com_sliderck&task=ajaxLoadLabelsFile'
	jQuery.ajax({
			type: "POST",
			url: myurl,
			data: {
				path: path
			},
		}).done(function(code) {
			jQuery('#jform_params_folderlabelseditor').empty().append(code);
			CKBox.open({
				handler: 'inline',
				content: 'jform_params_folderlabelseditor',
				footerHtml: '<a class="ckboxmodal-button" href="javascript:void(0)" onclick="ckSaveLabelsToFile()">'+Joomla.JText._('MOD_SLIDERCK_SAVE_CLOSE')+'</a>'
			});
			ckMakeImagesSortable();
		}).fail(function() {
			// alert(Joomla.JText._('CK_FAILED', 'Failed'));
		});
}

function ckSaveLabelsToFile() {
	var path = jQuery('#jform_params_folder').val();
	var labels = '';
	
	jQuery('#labelseditor .ckrow').each(function() {
		var title = jQuery(this).find('.labeltitle').val();
		var desc = jQuery(this).find('.labeldesc').val();
		var link = jQuery(this).find('.labellink').val();

		labels += jQuery(this).find('.labelimage').val()
			+ '|' + title
			+ '|' + desc
			+ '|' + link;
		labels += "\r\n";
	});
	myurl = CKURIBASE + '/index.php?option=com_sliderck&task=ajaxWriteLabelsFile'
	jQuery.ajax({
			type: "POST",
			url: myurl,
			data: {
				path: path,
				labels: labels
			},
		}).done(function(code) {
			if (code != '1') {
				alert('Error when writing the file labels.txt in the folder ' + path + ', please edit it manually.');
			}
			CKBox.close();
			ckUpdateLabelsFromFolder(jQuery('#jform_params_folder').val());
		}).fail(function() {
			// alert(Joomla.JText._('CK_FAILED', 'Failed'));
		});
}