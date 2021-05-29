function RefreshProgress(processImportUrl, progressWidgetId) {
	let progressBar = $("#"+progressWidgetId).find('[role="progressbar"]');
	jQuery.ajax({
		url: processImportUrl,
		async: true,
		method: 'GET'
	}).done(function(data) {
		progressBar.css("width", data.percent+'%')
		if (true === data.done) {

		} else {
			RefreshProgress(processImportUrl, progressWidgetId);
		}
	})
}