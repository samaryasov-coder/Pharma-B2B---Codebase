(function( $ ) {
	$.fn.fRedactor = function() {
		$(this).redactor({
			lang: 'ru',
			deniedTags: false,
			minHeight: 300,
			linkify: false,
			source: false,
			paragraphy: false,
			replaceDivs: false,
			toolbarFixed: true,
			buttonSource: true,
			replaceTags: {
				'b': 'strong',
				'i': 'em',
				'strike': 'del'
			},
			removeNewlines: false,
			removeComments: false,
			imagePosition: true,
			imageResizable: true,
			imageFloatMargin: '1.5em',
			toolbarFixedTopOffset: $('#wa-header').height(),
			buttons: ['html', 'format', /*'inline',*/ 'bold', 'italic', 'underline', 'deleted', 'lists',
				/*'outdent', 'indent', 'image'*/, 'video', 'table', 'link', 'alignment',
				'horizontalrule',  'fontcolor', 'fontsize', 'fontfamily'],
			plugins: ['source', 'fontcolor', 'fontfamily', 'alignment', 'fontsize', /*'inlinestyle',*/ 'table', 'video'],
			callbacks: {}
		});
	};
})(jQuery);