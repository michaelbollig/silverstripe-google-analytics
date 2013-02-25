var _gaq = _gaq || [];

function _gaLt(event){
	var el = event.srcElement || event.target;

	/* Loop through parent elements if clicked element is not a link (ie: <a><img /></a> */
	while(el && (typeof el.tagName == 'undefined' || el.tagName.toLowerCase() != 'a' || !el.href))
		el = el.parentNode;

	if(el && el.href){
		dl = document.location;
		ref = dl.pathname+dl.search;
		h = el.href;
		t = false;
		if(h.indexOf(location.host) == -1)
			t = ["_trackEvent","Outgoing Links",h,ref];
		else if (h.match(/\/assets\//) && !h.match(/\.(jpe?g|bmp|png|gif|tiff?)$/i))
			t = ["_trackEvent","Downloads",h.match(/\/assets\/(.*)/)[1],ref];
		if(t){
			_gaq.push(t);
			if(!el.target || el.target.match(/^_(self|parent|top)$/i)){
				/* if target not set delay opening of window by 0.5s */
				setTimeout(function(){
					document.location.href = el.href;
				}.bind(el),500);
				/* Prevent standard click */
				if(event.preventDefault)
					event.preventDefault();
				else event.returnValue = false;
			}
		}
	}
}

var d = document;
if(d.addEventListener)
	d.addEventListener("click",_gaLt,false);
else if(d.attachEvent)
	d.attachEvent("onclick",_gaLt);
