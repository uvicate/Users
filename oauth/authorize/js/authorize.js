(function(window){
	"use strict";

	var Main = function(){
		var scopes = this.get_raw_scopes();
		this.set_scopes(scopes);

		var client = this.get_raw_client();
		this.set_client(client);

		this.render_info();
	}

	Main.prototype.set_scopes = function(scopes) {
		this.scopes = scopes;
	};

	Main.prototype.get_scopes = function() {
		return this.scopes;
	};

	Main.prototype.get_raw_scopes = function() {
		var container = document.getElementById('scopes_receiver');
		var raw_scopes = container.getAttribute('data-scopes');
		var scopes = raw_scopes.match(/([a-z0-9-_.]+)/gi);

		return scopes;
	};

	Main.prototype.set_client = function(client) {
		this.client = client;
	};

	Main.prototype.get_client = function() {
		return this.client;
	};

	Main.prototype.get_raw_client = function() {
		var container = document.getElementById('client_receiver');
		var raw_client = container.getAttribute('data-client');
		
		return raw_client;
	};

	Main.prototype.render_info = function() {
		var scopes_container = document.getElementById('scopes');
		scopes_container.innerHTML = '';
		this.render_scopes(scopes_container);

		var client_container = document.getElementById('client');
		client_container.innerHTML = '';
		this.render_client(client_container);
	};

	Main.prototype.render_scopes = function(container) {
		var scopes = this.get_scopes();

		for(var i = 0, len = scopes.length; i < len; i++){
			var elm = document.createElement('li');
			var text = document.createTextNode(scopes[i]);

			elm.appendChild(text);
			container.appendChild(elm);
		}
	};

	Main.prototype.render_client = function(container) {
		var client = this.get_client();

		var text = document.createTextNode(client);
		container.appendChild(text);
	};

	var Auth = new Main();

})(window);