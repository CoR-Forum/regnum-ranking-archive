function empty(v) {
	let type = typeof v;
	if(type === 'undefined') {
		return true;
	}
	if(type === 'boolean') {
		return false;
	}
	if(v === null) {
		return true;
	}
	if(v === undefined) {
		return true;
	}
	if(v instanceof Array) {
		if(v.length < 1) {
			return true;
		}
	}
	else if(type === 'string') {
		if(v.length < 1) {
			return true;
		}
	}
	else if(type === 'object') {
		if(Object.keys(v).length < 1) {
			return true;
		}
	}
	else if(type === 'number') {
		if(isNaN(v)) {
			return true;
		}
	}
	return false;
}

function getQueryParameterByName(name, url) {
	if (!url) url = window.location.href;
	name = name.replace(/[\[\]]/g, "\\$&");
	let regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
		results = regex.exec(url);
	if (!results) return null;
	if (!results[2]) return '';
	return decodeURIComponent(results[2].replace(/\+/g, " "));
}