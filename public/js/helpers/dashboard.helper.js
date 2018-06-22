'use strict';

var hDashboard;

App.loadScript('/js/helpers/helper.js', function(){
	hDashboard = $.extend({}, Helper, function(){
		return {
			manager : function(){
				return '/manager'
			}
		}
	}());
});