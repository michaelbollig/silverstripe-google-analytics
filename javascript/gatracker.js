var _gaq=_gaq||[];
function _gaLt(event){
	var el = event.srcElement || event.target;
	if(el.tagName.toLowerCase() == 'a' && el.href){
		var ref = document.location.pathname + document.location.search;
		if(el.href.indexOf(location.host) == -1){
			_gaq.push(["_trackEvent","Outgoing Links",el.href,ref]);
			if( !el.target ){
				setTimeout(function(){document.location.href=el.href;}.bind(el),500);
				return false;
			}
		}
		else if (el.href.match(/\/assets\//) && !el.href.match(/\.(jpe?g|bmp|png|gif)$/i)) {
			_gaq.push(["_trackEvent","Downloads",el.href.match(/\/assets\/(.*)/)[1],ref]);
			if (!el.target){
				setTimeout(function(){document.location.href=el.href;}.bind(el),500);
				return false;
			}
		}
	}
}
var d=document;
if(d.addEventListener)d.addEventListener("click",_gaLt);
else if(d.attachEvent)d.attachEvent("onclick",_gaLt);