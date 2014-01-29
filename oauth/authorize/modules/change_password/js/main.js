(function(window){
	"use strict";
	var Module = function(){
		this.a = window.App;
		this.form_functionality();
	};

	Module.prototype.form_functionality = function() {
		var form = document.getElementById('forgot-form');
		form._t = this;
		form.addEventListener('submit', function(e){
			if(e.preventDefault){
				e.preventDefault();
			}

			e.returnValue = false;

			this._t.change_password();
		}, false);
	};

	Module.prototype.get_data = function() {
		var data = {};
		var elms = document.getElementsByClassName('form-control');

		for(var i = 0, len = elms.length; i < len; i++){
			var elm = elms[i];

			data[elm.name] = elm.value;
		}

		return data;
	};

	Module.prototype.get_main_data = function() {
		var r = {
			id: document.getElementById('user_id').getAttribute('data-id'),
			key: document.getElementById('forgotten_key').getAttribute('data-key')
		}

		return r;
	};

	Module.prototype.change_password = function() {
		var data = this.get_data();
		var main = this.get_main_data();

		if(data.password !== data.password_2){
			this.display_error();
			return false;
		}
		var send = {
			password: data.password
		}

		var t = this;
		new Vi({url: this.a._data.REST + 'Members/' + main.id + '/forgotten/' + main.key, mode: 'PUT', cache:true, data: send}).ajax(function(r){
			if(r !== ''){
				t.display_success();
			}else{
				t.display_error();
			}
		});
	};

	Module.prototype.prepare_response = function() {
		var main = document.getElementById('response-content');
		main.innerHTML = '';

		return main;
	};

	Module.prototype.display_success = function() {
		var container = this.prepare_response();

		var type = 'success';
		var title = this.a.current.getText('title-'+type);
		var description = this.a.current.getText('description-'+type);

		var alert = this.create_alert(type, title, description);
		container.appendChild(alert);
	};

	Module.prototype.display_error = function() {
		var container = this.prepare_response();

		var type = 'danger';
		var title = this.a.current.getText('title-'+type);
		var description = this.a.current.getText('description-'+type);

		var alert = this.create_alert(type, title, description);
		container.appendChild(alert);
	};

	Module.prototype.create_alert = function(type, title, description) {
		var container = document.createElement('div');
		container.className = 'alert alert-'+ type;

		var title_dom = document.createElement('strong');
		title_dom.appendChild(document.createTextNode(title));
		container.appendChild(title_dom);

		var description_dom = document.createElement('span');
		description_dom.appendChild(document.createTextNode(description));
		container.appendChild(description_dom);

		return container;
	};

	var m = new Module();
})(window);