let image = $('#cropper__user-logo_img');
let uploadedImageURL;

$('#cropper__upload-input').on('change', function () {
	let files = this.files;
	let file;

	if (files && files.length) {
		file = files[0];
		if (/^image\/\w+/.test(file.type)) {
			if (uploadedImageURL) {
				URL.revokeObjectURL(uploadedImageURL);
			}

			uploadedImageURL = URL.createObjectURL(file);
			image.data('cropper').replace(uploadedImageURL);

			this.value = null;
		} else {
			toastr.warning('Недопустимый формат файла');
		}
	}
})

$('#cropper__crop').on('click', function () {
	const cropper = image.data('cropper');
	cropper.getCroppedCanvas({width: 300, height: 300}).toBlob((blob) => {
		const formData = new FormData();

		formData.append('croppedImage', blob);

		$.ajax('/users/logo-upload', {
			method: 'POST',
			data: formData,
			processData: false,
			contentType: false,
		}).done(function () {
			toastr.success('Изображение успешно изменено');
		}).fail(function (data) {
			toastr.error(data.responseJSON.error);
		})
	});
});