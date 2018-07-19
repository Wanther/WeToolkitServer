(function($, document) {

	$.fn.listPanel = function ListPanel(options) {

		var _panel;
		var _data;

		var $el = this;
		$el.click(function(event) {
			event.stopPropagation();
			getPanel($el).show();
			getData(options.url, options.converter, renderData);
		});

		$(document).click(function() {
			getPanel($el).hide();
		});

		function getPanel($el) {
			var left = $el.position().left;
			var top = $el.position().bottom;

			if (!_panel) {
				_panel = $('<div class="wh-list-panel"><span class="wh-list-panel-loading">加载中...</span><ol class="wh-list-panel-data"></ol></div>');
				_panel.appendTo($el.parent());
				_panel.click(function(event) {
					event.stopPropagation();
					var target = $(event.target);
					if (target.is('li')) {
						options.onclick($(event.target).text());
					}
					getPanel($el).hide();
				});
				_panel.blur(function() {
					getPanel($el).hide();
				});
			}

			_panel.css({"left": left + "px", "top": top + "px"});

			return _panel;

		}

		function getData(url, converter, renderData) {

			var loadingPanel = _panel.find('.wh-list-panel-loading');
			var dataPanel = _panel.find('.wh-list-panel-data');

			loadingPanel.show();
			dataPanel.hide();

			if (_data) {
				renderData();
				loadingPanel.hide();
				dataPanel.show();
				return;
			}

			var data = [];
			$.getJSON(url, function(json) {
				for (var i = 0; i < json.length; i++) {
					data.push(converter(json[i]));
				}
				_data = data;

				renderData();
				loadingPanel.hide();
				dataPanel.show();
			})
		}

		function renderData() {
			var container = _panel.find('.wh-list-panel-data').empty();
			for (var i = 0; i < _data.length; i++) {
				container.append('<li>' + _data[i] + '</li>')
			}
		}
	}
})(jQuery, document);