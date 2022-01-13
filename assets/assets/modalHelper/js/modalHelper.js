/**
 * Загружает в тело существующей модалки новый контент из renderAjax-вью
 * @param {string} dataUrl
 * @param {string} modalDivId
 * @constructor
 */
function LoadModal(dataUrl, modalDivId) {
	let modal = $("#" + modalDivId),
		modalBody = modal.find('.modal-body');
	modalBody.load(dataUrl, function() {
		modal.modal('show');
	});
}

/**
 * Загружает renderPartial - вью в контейнер с прелоадером
 * @param {string} dataUrl
 * @param {string} modalDivId
 * @param {string} modalContainerId
 */
function AjaxModal(dataUrl, modalDivId, modalContainerId) {
	if (undefined === dataUrl) return;
	let modalContainerDiv;
	if (undefined === modalContainerId) {
		modalContainerId = 'modal-ajax-div';
	}
	modalContainerDiv = $("#" + modalContainerId);
	if (0 === modalContainerDiv.length) {
		modalContainerDiv = $('<div />', {
			'id': modalContainerId,
		});
		$('body').prepend(modalContainerDiv);
	}
	modalContainerDiv.addClass('preloading');
	modalContainerDiv.load(dataUrl, function(response, status, xhr) {
		if ('error' === status) {
			if (302 === xhr.status) {//Контроллер ответил редиректом на ajax-запрос
				window.location = decodeURIComponent(dataUrl);
				return true;
			} else {
				modalContainerDiv.html(response);
				$('#modal-error').modal('show');
			}
		} else {
			modalDiv = (undefined === modalDivId)
				?modalContainerDiv.find('.modal:first')
				:$('#' + modalDivId)
			modalDiv.modal('show');
		}

		modalContainerDiv.removeClass('preloading');
		document.dispatchEvent(new Event('modalIsReady'));

		/**
		 * Добавляем на все ajax-формы внутри модалки обработчики модального постинга
		 */
		let modalForm = modalDiv.find('form');
		modalForm.on('beforeSubmit', function(e) {
			formSubmitAjax(e);
			return false;
		});

	}).show();
}

/**
 * Собственный обработчик аяксового постинга
 * @param {object} event Событие, на которое подвешен метод
 * @returns {boolean} Всегда false - чтобы блокировать любую дальнейшую обработку (может и не надо).
 */
function formSubmitAjax(event) {
	var form = jQuery(event.target);
	var self = this;
	if (form.attr('method') !== 'GET' && window.FormData !== undefined) {

		// Convert form to ajax submit
		jQuery.ajax({
			method: form.attr('method'),
			url: form.attr('action'),
			data: new FormData(form[0]),
			processData: false,
			contentType: false,
			context: this,
			success: function(data, status, xhr) {
				status = processErrors(data, xhr);
			},
		});
	} else {
		// Convert form to ajax submit
		jQuery.ajax({
			method: form.attr('method'),
			url: form.attr('action'),
			data: form.serialize(),
			context: this,
			success: function(data, status, xhr) {
				status = processErrors(data, xhr);
			},
		});
	}
	event.preventDefault();
	event.stopImmediatePropagation();
	return false;

};

/**
 * Добавляем на все ajax-ссылки обработчики загрузки модальных окон
 */
$('.el-ajax-modal').on('click', function(event) {
	event.preventDefault();
	AjaxModal($(this).data('ajax-url'), $(this).data('modal-id'))

});

/**
 * Разбирает массив ошибок валидации в data, отображая их во всплывающем toast-сообщении
 * @param {array} data Массив ошибок валидации
 * @param {object} xhr XMLHttpRequest-объект
 * @returns {boolean} Успех валидации
 */
function processErrors(data, xhr) {
	status = true;
	var contentType = xhr.getResponseHeader('content-type') || '';
	if (contentType.indexOf('html') > -1) {// Assume form contains errors if html
		toastr.error(data, 'Ошибка');
		status = false;
	} else if (contentType.indexOf('json') > -1) {//Assume form contains errors in json format
		status = false;
		var ul = $('<div>');
		for (const [key, value] of Object.entries(data)) {
			value.forEach((errorBlock) => ul.append($('<span>', {class: 'error-title'}).text(errorBlock)));
		}
		toastr.options = {
			// "closeButton": false,
			"debug": false,
			"newestOnTop": false,
			"progressBar": false,
			"positionClass": "toast-top-right",
			"preventDuplicates": true,
			// "onclick": null,
			"showDuration": "300",
			"hideDuration": "1000",
			"timeOut": 0,
			"extendedTimeOut": 0,
			"showEasing": "swing",
			"hideEasing": "linear",
			"showMethod": "fadeIn",
			"hideMethod": "fadeOut"
		};
		toastr.error(ul, 'Ошибка');
	}
	return status;
}