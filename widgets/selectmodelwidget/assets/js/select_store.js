/**
 * Форматирование элемента списка по умолчанию
 * @param item
 * @returns {*}
 */
function StoresTemplateResult(item) {
	if (item.loading || item.hasOwnProperty('children')) {
		return item.text;
	}
	let region = ($(item.element).data('region') || item['data-region'] || ''),//при статической генерации данные всунутся в data-параметры, при ajax-выдаче - в атрибуты. Проще сделать так, чем разбираться
		code = ($(item.element).data('code') || item['data-code'] || ''),
		branch = ($(item.element).data('branch') || item['data-branch'] || ''),
		channel = ($(item.element).data('channel') || item['data-channel'] || ''),
		type = ($(item.element).data('type') || item['data-type'] || '');


	return '<div class="select-item">' +
		'<div class="row">' +
		'<div class="col-sm-9 badge-pill font-weight-bold">' + item.text + '</div>' +
		'<div class="col-sm-3 font-italic">' + code + '</div>' +
		'<div class="col-sm-3">' + type + '</div>' +
		'<div class="col-sm-3">' + branch + '</div>' +
		'<div class="col-sm-3">' + region + '</div>' +
		'<div class="col-sm-3">' + channel + '</div>' +
		'</div>' +
		'</div>';
}

/**
 * Форматирование выбранного элемента в списке
 * @param item
 * @returns {*}
 */
function StoresTemplateSelection(item) {
	return item;
}

/**
 * Расширенный поиск
 * @param params
 * @param data
 * @returns {null|*}
 */
function StoresMatchCustom(params, data) {
	// `data.text` is the text that is displayed for the data object
	// Do not display the item if there is no 'text' property
	// if (typeof data.text === 'undefined') return null;
	// `params.term` should be the term that is used for searching
	let search = ($.trim(params.term).toUpperCase()),
		name = (data.text || '').toUpperCase(),
		code = ($(data.element).data('code') || item['data-code'] || '').toUpperCase();

	// If there are no search terms, return all of the data
	if ('' === search) return data;

	if (name.indexOf(search) > -1 || code.indexOf(search) > -1) {
		// var modifiedData = $.extend({}, data, true);
		// modifiedData.text += ' (matched)';

		// You can return modified objects from here
		// This includes matching the `children` how you want in nested data sets
		return data;
	}

	// Return `null` if the term should not be displayed
	return null;
}


/**
 * Форматирование при передаче HTML
 * @param markup
 * @returns {*}
 */
function StoresEscapeMarkup(markup) {
	return markup;
}