/*
 * global.js
 * berisi fungsi-fungsi yang digunakan bersama oleh semua page
 * @sangkuriang.internasional
 */
$(function(){
	$('.datepicker').datepicker({
		dateFormat: 'yy-mm-dd'
	});
	$('.timepicker').timepicker({
		showSecond: true,
		timeFormat: 'hh:mm:ss'
	});

    $('.datetimepicker').datetimepicker();
});