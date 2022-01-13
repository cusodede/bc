(function($) {
	var message = 'Неверно указан «ИНН»!';
	var el = $(this);
	var input = el.find('input');
	var form = el.parents('form');
	form.on('afterInit', function(e) {
		$(e.target).yiiActiveForm('add', {
			id: input.prop('id'),
			name: input.prop('name'),
			container: el,
			input: input,
			error: '.help-block',
			validate: function(attribute, value, messages, deferred, $form) {
				if (value.length > 0 && !checkInn(value)) {
					messages.push(message)
				}
			}
		})
	});
})(window.jQuery)

/**
 * Проверка корректности ИНН
 * @param inn
 * @returns {boolean}
 */
function checkInn(inn) {
	if (typeof inn === 'number') {
		inn = inn.toString();
	} else if (typeof inn !== 'string') {
		inn = '';
	}
	if (inn.length === 9 || inn.length === 11) {
		inn = "0" + inn;
	}
	var checkDigit = function(inn, coefficients) {
		var n = 0;
		for (var i in coefficients) {
			if (coefficients.hasOwnProperty(i)) {
				n += coefficients[i] * inn[i];
			}
		}
		return parseInt(n % 11 % 10);
	};
	switch (inn.length) {
		case 10:
			var n10 = checkDigit(inn, [2, 4, 10, 3, 5, 9, 4, 6, 8]);
			if (n10 === parseInt(inn[9])) {
				return true;
			}
			break;
		case 12:
			var n11 = checkDigit(inn, [7, 2, 4, 10, 3, 5, 9, 4, 6, 8]);
			var n12 = checkDigit(inn, [3, 7, 2, 4, 10, 3, 5, 9, 4, 6, 8]);
			if ((n11 === parseInt(inn[10])) && (n12 === parseInt(inn[11]))) {
				return true;
			}
			break;
		default:
			return false;
	}
	return false;
}
