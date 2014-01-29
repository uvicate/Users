(function(window){
	"use strict";
	var Module = function(){
		this.a = window.App;

		var forgotten = document.getElementById('forgotten');
		forgotten._t = this;
		forgotten.addEventListener('click', function(){
			this._t.load_module('forgotten');
		}, false);
	};

	Module.prototype.load_module = function(module, callback) {
		callback = (typeof callback === 'function') ? callback : function(){};
		this.a.getModule(module);
		this.a.current.start(callback);
	};

	var m = new Module();
})(window);