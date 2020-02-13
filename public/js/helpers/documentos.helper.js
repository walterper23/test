'use strict';

var hDocumento;

App.loadScript('/js/helpers/helper.js', function(){
	hDocumento = $.extend({}, Helper, function(){
		return {
			manager : function(){
				return '/recepcion/documentos/recepcionados/manager'
			},

		}
	}())
})