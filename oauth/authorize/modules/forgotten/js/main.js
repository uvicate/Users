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


			this._t.send_forgotten_request();
			e.returnValue = false;

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

	Module.prototype.send_forgotten_request = function(callback) {
		callback = (typeof callback === 'function') ? callback : function(){};
		
		var data = this.get_data();

		var t = this;
		new Vi({url: this.a._data.REST + 'Members/' + data.email + '/forgotten', mode: 'POST', cache:true}).ajax(function(r){
			var tag = 'error';
			var type = 'danger';
			if(r !== ''){
				//Success
				tag = 'success';
				type = 'success';
			}

			var container = document.getElementById('response-content');
			container.innerHTML = '';

			var title = t.a.current.getText('title-' + tag);
			var description = t.a.current.getText('description-' + tag);
			
			var elm = t.create_alert(type, title, description);

			container.appendChild(elm);
		});
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