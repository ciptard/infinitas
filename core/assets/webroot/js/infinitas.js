/**
 * popup alert box
 * @access public
 * @return void
 **/
function pr(data){
	alert(data);
}

/**
 * write data to console
 * @access public
 * @return void
 **/
function debug(data){
	// better test http://benalman.com/projects/javascript-debug-console-log/
	if(typeof console.log != 'undefined') {
		console.log(data);
	}
}

if(typeof Infinitas.params.prefix == 'undefined'){
	Infinitas.params.prefix = 'frontend';
}

switch(Infinitas.params.prefix) {
	case 'admin':
		$(document).ready(function(){
			$('.tabs').tabs();
			$('.accordions').accordion();
			$.FormHelper.foucusOnFirst();
			setupAjaxDropdowns();
			setupRowSelecting();
			setupDatePicker();
			setupAjaxPagination();

			$.FormHelper.checkboxToggleAll();

			$(".trigger").click(function(){
				$this = $(this);
				$this.siblings(".panel").toggle("fast");
				$(".trigger").removeClass('active').siblings(".panel").hide();
				$this.toggleClass("active");
				return false;
			});

			$('#PaginationOptionsPaginationLimit').change(function(){
				$('#PaginationOptions').submit();
			});

			if($('.filter-form').length > 0){
				$('.massActions').prepend('<button id="searchForm" name="action" value="search" type="submit"><span><img alt="" src="' + Infinitas.base + 'img/core/icons/actions/search.png"><br>Search</span></button>');
				$('#searchForm').click(function(){
					$('.filter-form').toggle();
					return false;
				});
			}


			$("[title]:not(.textarea *)").tooltip({
				track: true, delay: 0, showURL: false,
				fixPNG: true, showBody: " :: ",
				left: 5, top: -5
			});

			$('#' + Infinitas.model + 'ImageId').imageSelect();
			$('#ProductImageProductImage').imageSelect();
		});
		break;
	default:
		$(document).ready(function(){
			$('.tabs').tabs();
			//setupStarRating();

			$("#accordion").accordion({
				collapsible: true
			});
		});
		break;
}


/** core code */
/**
 *
 * @access public
 * @return void
 **/
function setupAjaxDropdowns(){
	/**
	 * Check for plugin dropdown changes
	 */
	$pluginSelect = $('.pluginSelect');
	$pluginSelect.change(function(){
		var $this = $(this);
		metaData = $.HtmlHelper.getParams($this);
		$.FormHelper.emptySelect(metaData);

		if ($this.val().length != 0) {
			metaData.params.plugin = $this.val();
			$.HtmlHelper.requestAction(metaData, $.FormHelper.input);
		}
	});

	/**
	 * Check for controller dropdown changes
	 */
	$('.controllerSelect').change(function(){
		var $this = $(this);
		metaData = $.HtmlHelper.getParams($this);
		$.FormHelper.emptySelect(metaData);

		if ($this.val().length != 0) {
			metaData.params.plugin     = $pluginSelect.val();
			metaData.params.controller = $this.val();
			$.HtmlHelper.requestAction(metaData, $.FormHelper.input);
		}
	});

	/**
	 * Check for module changes
	 */
	$modulePuluginSelect = $('.modulePuluginSelect');
	$modulePuluginSelect.change(function(){
		var $this = $(this);
		metaData = $.HtmlHelper.getParams($this);
		$.FormHelper.emptySelect(metaData);

		if ($this.val().length != 0) {
			metaData.params.plugin     = $('.modulePuluginSelect').val();
			metaData.params.controller = $this.val();
			$.HtmlHelper.requestAction(metaData, $.FormHelper.input);
		}
	});
}

function setupRowSelecting(){
	$("table.listing input:checkbox").change(function() {
		var $this = $(this);

        if ($this.attr("checked") == true) {
			$this
				.parents('tr')
				.removeClass('highlightRowRelated')
				.addClass("highlightRowSelected");
        } else {
        	$this.parents('tr').removeClass("highlightRowSelected");
        }
	});

	$('td').click(function(){
		var $this = $(this)
		var col = $this.prevAll().length+1;

		if (col > 1){
			var thisClicked = $.trim($this.text());

			$('table.listing td:nth-child(' + col + ')' ).each(function() {
				var $_this = $(this);

				if (thisClicked == $.trim($_this.text())) {
					$_this.parent().removeClass('highlightRowSelected');
					$_this.parent().addClass('highlightRowRelated');
				}
				else{
					$_this.parent().removeClass('highlightRowRelated');
				}
			});
		}
	});
}

function setupStarRating() {
	var $rating = $('.star-rating');
	metaData = $.HtmlHelper.getParams($rating);
	pr(metaData);
	url = $.HtmlHelper.url(metaData);

	$('.coreRatingBox').empty();
	$rating.rater(
		url + metaData.url.id,
		{
			curvalue: metaData.currentRating
		}
	);
}

function setupDatePicker() {
	var currentDate;
	var now = new Date(); now.setDate(now.getDate());

	/**
	 * Start dates
	 */
	var date1 = $("#" + Infinitas.model + "StartDate");
	if(date1.length){
		currentDate = now;
		if(date1.val() != '') {
			currentDate = date1.val().split('-');
			currentDate = new Date (currentDate[0], currentDate[1]-1, currentDate[2]);
		}

		startDate = $("#" + Infinitas.model + "DatePickerStartDate").calendarPicker({
			"date": currentDate,
			callback: function(cal){
				date1.val(cal.mysqlDate);
			}
		});
	}

	/**
	 * end dates
	 */
	var date2 = $("#" + Infinitas.model + "EndDate");
	if(date2.length){
		currentDate = now;
		if(date2.val() != ''){
			currentDate = date2.val().split('-');
			currentDate = new Date (currentDate[0], currentDate[1]-1, currentDate[2]);
		}

		endDate = $("#" + Infinitas.model + "DatePickerEndDate").calendarPicker({
			"date": currentDate,
			callback: function(cal){
				date2.val(cal.mysqlDate);
			}
		});
	}

	/**
	 * general dates
	 */
	var date3 = $("#" + Infinitas.model + "Date");
	if(date3.length){
		currentDate = now;
		if(date3.val() != '') {
			currentDate = date3.val().split('-');
			currentDate = new Date (currentDate[0], currentDate[1]-1, currentDate[2]);
		}

		date = $("#" + Infinitas.model + "DatePickerDate").calendarPicker({
			"date": new Date (currentDate[0], currentDate[1]-1, currentDate[2]),
			callback: function(cal){
				date3.val(cal.mysqlDate);
			}
		});
	}
}

function setupAjaxPagination() {
	$link = $('a.ajax');
	$.HtmlHelper.loading('showMore');
	$link.live('click', function(event){
		$('.showMore').remove();
		$.ajax({
			url: $(this).attr('href'),
		  	success: function(data, textStatus, XMLHttpRequest){
				data = $('<div>'+data+'</div>').find('.list').children();
				$('.list').append(data);
				data = '';
				$.HtmlHelper.loading('', false);
			}
		});
		return false;
	});
}

;(function($) {
	$.fn.imageSelect = function(options){
		var opts = $.extend({}, $.fn.imageSelect.defaults, options);
		return this.each(function() {
			var $this = $(this);
			if(this.tagName == 'SELECT'){
				$this.wrap('<div class="' + opts.containerClass + '">' );
				var html = '';
				$this.children('option').each(function(){
					if(this.selected == true){
						selectClass = 'selected';
					}
					else{
						selectClass = '';
					}

					var src;
					if(opts.imageSrc == 'text'){
						src = $(this).text();
					}
					else{
						src = this.value;
					}

					if ((this.value == '') || (this.value == undefined)) {
						/*html += '<a class="' + selectClass + ' ' + opts.imageClass + '" href="#select_' + this.value +
							'"><div style="background-color: #ccc; width: ' + opts.thumbnailWidth + 'px; height: ' + opts.thumbnailWidth +
							'px">'+opts.emptyText+'</div></a>';*/
					}
					else {
						html += '<a class="' + selectClass + ' ' + opts.imageClass + '" href="#select_' + this.value +
						'"><img src="' + src + '" style="width: ' + opts.thumbnailWidth + 'px" /></a>';
					}
				});

				$(this).after(html);
				$('a.image-select').click($.fn.imageSelect.selectImage);
				$this.css('display', 'none');
			}
		});
	}

	$.fn.imageSelect.selectImage = function(e){
		var $selectBox = $(this).prevAll('select:first');

		if($selectBox.attr('multiple') == true){
			var $option = $selectBox.children('option[value='+this.href.split('_')[1]+']');

			if($option.attr('selected') == true){
				$option.attr('selected', false);
				$(this).removeClass('selected');
			}
			else{
				$option.attr('selected', true);
				$(this).addClass('selected');
			}
		}
		else{
			$selectBox.val(this.href.split('_')[1]);
			$(this).parent().children('a').removeClass('selected');
			$(this).addClass('selected');
		}

		return false;
	}

	$.fn.imageSelect.defaults = {
		containerClass: 'image-select-container',
		imageClass: 'image-select',
		imageSrc: 'text',
		thumbnailWidth: '60',
		emptyText: 'No image'
	};
})(jQuery);