/**
 * Форматирование элемента списка по умолчанию
 * @param item
 * @returns {*}
 */
function DealersTemplateResult(item) {
	if (item.loading || item.hasOwnProperty('children')) {
		return item.text;
	}

	return '<div class="select-item">' +
		'<div class="row">' +
		'<div class="col-sm-12">' + item.text + '</div>' +
		'</div>' +
		'</div>';
}

/**
 * Форматирование выбранного элемента в списке
 * @param item
 * @returns {*}
 */
function DealersTemplateSelection(item) {
	return item;
}

/**
 * Расширенный поиск
 * @param params
 * @param data
 * @returns {null|*}
 */
function DealersMatchCustom(params, data) {
	// `data.text` is the text that is displayed for the data object
	// Do not display the item if there is no 'text' property
	// if (typeof data.text === 'undefined') return null;
	// `params.term` should be the term that is used for searching
	let search = ($.trim(params.term).toUpperCase());

	// If there are no search terms, return all of the data
	if ('' === search) return data;

	if (name.indexOf(search) > -1) {
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
function DealersEscapeMarkup(markup) {
	return markup;
}