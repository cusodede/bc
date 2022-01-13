/**
 * @param {string} tableId
 * @param {string} columnId
 */
function toggleGridColumn(tableId, columnId) {
	let columns = $(tableId + ' colgroup col'),
		columnIndex = columns.index($(columnId)),
		columnIndexPlus = columnIndex + 1;//из-за того, что первая колонка не имеет td
	$(columnId + ', ' + tableId + ' th:nth-child(' + columnIndexPlus + '), ' + tableId + ' thead > tr > td:nth-child(' + columnIndex + '),' + tableId + ' td:nth-child(' + columnIndexPlus + ')').toggle();
}

/**
 * @param {string} tableId
 * @param {string} filterName
 * @param {any} filterValue
 * @param {boolean} apply
 */
function setFakeGridFilter(tableId, filterName, filterValue, apply) {
	apply = ('undefined' === typeof apply)?true:apply;//set default
	let filterRow = $(tableId + '-filters'),
		currentFilter = filterRow.find('input[name="' + filterName + '"]');
	if (currentFilter.length > 0) {
		currentFilter.val(filterValue)
	} else {
		if (Array.isArray(filterValue)) {
			let select = $('<select />', {
				class: 'hidden',
				multiple: true,
				name: filterName + '[]'
			}).appendTo(filterRow);
			$(filterValue).each(function(index) {
				let option = $('<option/>');
				option.attr({
					value: filterValue[index],
					selected: true
				}).text(filterValue[index]);
				select.append(option);
			});

		} else {
			$('<input />', {
				type: 'hidden',
				name: filterName,
				value: filterValue
			}).appendTo(filterRow);
		}

	}
	if (apply) $(tableId).yiiGridView("applyFilter");
}

function getSelect2SelectedBitmask(Select2) {
	let i = 0;
	Select2.select2("data").forEach(Element => {
		i += parseInt(Element.id)
	});
	return i;
}