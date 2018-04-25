'use strict';

var hSemaforo;

App.loadScript('/js/helpers/helper.js', function(){
	hSemaforo = $.extend({}, Helper, function(){
		return {
			manager : function(){
				return '/configuracion/catalogos/anexos/manager'
			},
		}
	}());
})