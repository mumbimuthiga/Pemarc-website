/**
 * @copyright	Copyright (C) 2017. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */

var $ck = window.$ck || jQuery.noConflict();

$ck(document).ready(function(){
	ckAddDndForImageUpload(document.getElementById('ckfolderupload'));
});

/*------------------------------------------------------
 * Functions for the image drag and drop upload
 *-----------------------------------------------------*/

function ckReadDndImages(holder, files) {
	// empty the place if there is already an image -> no !!
	// if ($ck(holder).find('img').length) $ck(holder).find('img').remove();
	var formData = !!window.FormData ? new FormData() : null;
    for (var i = 0; i < files.length; i++) {
		if (!files[i].type.match(/^image\//)) {
			alert('The file must be an image : ' + files[i].name) ;
			continue ;
		}
		if (!!window.FormData) formData.append('file', files[i]);
		formData.append('path', $ck('.ckfoldertree.ckcurrent').attr('data-path'));
    
	if (!!window.FormData) {
		$ck(holder).append('<progress max="100" value="0" class="progress"></progress>');
		var holderProgress = $ck(holder).find('.progress');
		var myurl = 'index.php?option=com_sliderck&task=ajaxAddPicture&' + CKTOKEN + '=1';
		$ck.ajax({
			type: "POST",
			url: myurl,
			// async: false,
			data: formData,
			dataType: 'json',
			processData: false,  // indique � jQuery de ne pas traiter les donn�es
			contentType: false,  // indique � jQuery de ne pas configurer le contentType
			xhr: function () {
				var xhr = new window.XMLHttpRequest();
				xhr.upload.addEventListener("progress", function (evt) {
					if (evt.lengthComputable) {
						var percentComplete = evt.loaded / evt.total;
						holderProgress.val(
							percentComplete * 100
						);
						if (percentComplete === 1) {
							holderProgress.addClass('hide');
						}
					}
				}, false);
				xhr.addEventListener("progress", function (evt) {
					if (evt.lengthComputable) {
						var percentComplete = evt.loaded / evt.total;
						holderProgress.val(
							percentComplete * 100
						);
					}
				}, false);
				return xhr;
			}
		}).done(function(response) {
			if(typeof response.error === 'undefined')
			{
				// Success
				if(typeof response.img !== 'undefined') {
					holderProgress.remove();
					// if the image already exists, return
					if ($ck('.ckfoldertree.ckcurrent').find('> .ckfoldertreefiles').find('[data-filename="' + response.filename + '"]').length) return;

					$ck('.ckfoldertree.ckcurrent').find('> .ckfoldertreecount').text(parseInt($ck('.ckfoldertree.ckcurrent').find('> .ckfoldertreecount').text())+1);
					$ck('.ckfoldertree.ckcurrent').find('> .ckfoldertreefiles')
						.prepend('<div class="ckfoldertreefile" data-filename="' + response.filename + '" data-path="'+ $ck('.ckfoldertree.ckcurrent').attr('data-path') +'" onclick="ckSelectFile(this)" data-type="image">'
						+ '<img title="' + response.filename + '" data-src="' + response.img + '" src="' + URIROOT + response.img+'" />'
						+ '</div>');
					$ck('#tck_file_upload').val('');
				}
			} else {
				alert('ERROR: ' + response.error);
			}
		}).fail(function() {
			// alert(Joomla.JText._('CK_FAILED', 'Failed'));
		});
    }
	}
}

function ckAddDndForImageUpload(holder) {
	if (typeof FileReader == 'undefined') return;
		if ('draggable' in document.createElement('span')) {
			holder.ondragover = function () { $ck(holder).addClass('ckdndhover'); return false; };
			holder.ondragleave = function () { $ck(holder).removeClass('ckdndhover'); return false; };
			holder.ondragend = function () { $ck(holder).removeClass('ckdndhover'); return false; };
			holder.ondrop = function (e) {
				$ck(holder).removeClass('ckdndhover');
				e.preventDefault();
				ckReadDndImages(holder, e.dataTransfer.files);
			}
		} else {
			alert('Message : Drag and drop for images not supported');
			// fileupload.className = 'hidden';
		}
		$ck('#tck_file_upload').on('change', function () {
			ckReadDndImages(holder, this.files);
		});
}

/*------------------------------------------------------
 * END of image drag and drop 
 *-----------------------------------------------------*/