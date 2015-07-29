CKEDITOR.on('instanceReady', function (ev) {
	// Ends self closing tags the HTML4 way, like <br>.
	ev.editor.dataProcessor.htmlFilter.addRules(
		{
			elements:
			{
				$: function (element) {
					// Output dimensions of images as width and height
					if (element.name == 'img') {
						var style = element.attributes.style;

						if (style) {
							// Get the width from the style.
							var match = /(?:^|\s)width\s*:\s*(\d+)px/i.exec(style),
								width = match && match[1];

							// Get the height from the style.
							match = /(?:^|\s)height\s*:\s*(\d+)px/i.exec(style);
							var height = match && match[1];

							// Get the align from the style

							var match = /(?:^|\s)float\s*:\s*(left|right)/i.exec(style),
							align = match && match[1];

							if (width) {
								element.attributes.style = element.attributes.style.replace(/(?:^|\s)width\s*:\s*(\d+)px;?/i, '');
								element.attributes.width = width;
							}

							if (height) {
								element.attributes.style = element.attributes.style.replace(/(?:^|\s)height\s*:\s*(\d+)px;?/i, '');
								element.attributes.height = height;
							}

							if (align) {
								element.attributes.style = element.attributes.style.replace(/(?:^|\s)float\s*:\s*(left|right);?/i, '');
								element.attributes.align = align;
							}
						}
					}



					if (!element.attributes.style)
						delete element.attributes.style;

					return element;
				}
			}
		});
	});