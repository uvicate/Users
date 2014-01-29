(function(window){
	"use strict";
	window.App = {};

	var Main = function(){
		this.getConfig(function(r, t){
			t.start(r);
		});
	}

	Main.prototype.get_initial_file = function() {
		var elm = document.getElementById('initial_file');
		var initial = elm.getAttribute('data-initialfile');

		return initial;
	};

	Main.prototype.getConfig = function(callback) {
		var file = this.get_initial_file();
		var t = this;

		new Vi({url:'../authorize/login.json', 'response': 'object'}).server(function(r){
			if(typeof callback === 'function'){
				callback(r, t);
			}
		});
	};

	Main.prototype.start = function(r) {
		var file = this.get_initial_file();

		var lang = this.browserLanguage();
		var modules = {};

		var mods = Object.keys(r.modules);
		for(var i = 0, len = mods.length; i < len; i++){
			var k = mods[i];
			modules[k] = {nombre: k, url:r.modules_path};
		}

		var j = {name: 'Login', modules:modules, div:'#content', currentLang: lang, relativePath: '../authorize/'};
		this.a = new AppSystem(j);
		App = this.a;
		this.a.loaded = false;

		this.a._data = r;
		var t = this;

		this.a.init(function(){
			var module = file;
			t.a.getModule(module);
			t.a.current.start(function(){});
		});
	};

	Main.prototype.browserLanguage = function() {
		var lang = navigator.language || navigator.userLanguage;
		lang = lang.match(/([a-z]+)/gi);
		if(lang !== null){
			lang = lang[0];
		}

		var l = '';
		switch(lang){
			case 'es':
				l = lang;
			break;
			default:
			case 'en':
				l = lang;
			break;
		}

		return l;
	};

	var Login = new Main();
})(window);