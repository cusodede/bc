function RefreshProgress(processImportUrl, progressWidgetId) {
	let progressBar = $("#" + progressWidgetId).find('[role="progressbar"]'),
		status = $('.status-label'),
		processed = $('.processed'),
		imported = $('.imported'),
		skipped = $('.skipped'),
		error = $('.error');
	jQuery.ajax({
		url: processImportUrl,
		async: true,
		method: 'GET'
	}).done(function(data) {
		progressBar.css("width", data.percent + '%').text(data.percent + '%');
		status.text(data.status);
		processed.text(data.processed);
		imported.text(data.imported);
		skipped.text(data.skipped);
		error.text(data.error);
		if (data.messages) {
			console.log(data.messages);
		}
		if (true !== data.done) {
			setTimeout(function refresh() {
				RefreshProgress(processImportUrl, progressWidgetId);
			}, 2000)
		}
	})
}