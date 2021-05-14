/**
 * Обработчик события "modalIsReady".
 * После загрузки модалки, создаем listener для поиска по разрешениям
 */
document.addEventListener('modalIsReady', function() {
	document.getElementById('search-permission').addEventListener('keyup', function() {
		let reg = new RegExp(this.value, 'i');
		$('#ms-permissionscollections-relatedpermissions li:not(.ms-selected)').find('span').each(function() {
			if (this.innerHTML.search(reg) !== -1) {
				$(this).parent().show();
			} else {
				$(this).parent().hide();
			}
		})
	});
}, false);
