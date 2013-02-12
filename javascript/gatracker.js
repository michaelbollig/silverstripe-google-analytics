var _gaq = _gaq||[];

function gaLt(e) {
	var el = e ? e.target : window.event.srcElement; /* window.event.srcElement for IE */
	var type = el.tagName.toLowerCase();
	if(type == 'a') {
		var ref = document.location.pathname + document.location.search;
		if( el.href.indexOf(location.host) == -1 ) {
			_gaq.push(["_trackEvent","Outgoing Links",el.href,ref]);
			if( !el.target ){
				setTimeout(function(){document.location.href=el.href;}.bind(el),500);
				return false;
			}
		}
		else if ( el.href.match(/\/assets\//) ) {
			_gaq.push(["_trackEvent","Downloads",el.href.match(/\/assets\/(.*)/)[1],ref]);
			if ( !el.target ) {
				setTimeout(function(){document.location.href=el.href;}.bind(el),500);
				return false;
			}
		}
	}
}
/* Add tracker to all document click events */
var b = document.body;
var o = b.onclick;
if ( typeof o != "function" ) o = gaLt;
else {
	b.onclick = function() {
		o();
		gaLt();
	}
}
