window.onload = function() {
	// Begin Swagger UI call region
	const el = document.getElementById('swagger-ui');
	const ui = SwaggerUIBundle({
		url: el.dataset.schemaUrl,
		dom_id: '#swagger-ui',
		deepLinking: true,
		validatorUrl: null,
		presets: [
			SwaggerUIBundle.presets.apis,
			SwaggerUIStandalonePreset
		],
		plugins: [
			SwaggerUIBundle.plugins.DownloadUrl
		],
		layout: "StandaloneLayout"
	});
	// End Swagger UI call region

	window.ui = ui;
};