/**
 * 其实是一些工具方法
 */
(function($){
	var App = window.App;

	App.groupCheckbox = function(){
		$('input:checkbox[check-group]').click(function(){
			$this = $(this);
			var target = $(this).attr('check-group');

			if(!target){
				return;
			}

			var checked = $this.prop('checked');

			$(target).prop('checked', checked);
		});
	};

	App.tableSort = function(){
		$('th.sortable[href]').click(function(){
			window.location.href = $(this).attr('href');
		});
	};

	App.registAppButton = function(){
		$('.app-btn').click(function(e){
			e = e || window.event;
			e.preventDefault();

			$this = $(this);

			var result = true;

			var confirmMsg = $this.attr('confirm');

			if(confirmMsg){
				result = confirm(confirmMsg);
			}

			if(!result){
				return;
			}

			if($this.attr('target')){
				var $form = $($this.attr('target'));
				$form.attr('action', $this.attr('href'));
				$form.submit();
			}else{
				window.location.href = $this.attr('href');
			}
		});
	};

	$(function(){

		App.groupCheckbox();

		App.tableSort();

		App.registAppButton();
	});
})(jQuery);