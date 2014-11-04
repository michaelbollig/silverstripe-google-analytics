/* Attach tracking to all download & external links */
var _gaq = _gaq || [];

function _gaLt(event){
	var el = event.srcElement || event.target;

	/* Loop through parent elements if clicked element is not a link (ie: <a><img /></a> */
	while(el && (typeof el.tagName == 'undefined' || el.tagName.toLowerCase() != 'a' || !el.href))
		el = el.parentNode;

	if(el && el.href){
		dl = document.location;
		l = dl.pathname + dl.search;
		h = el.href;
		c = !1;
		if (h.indexOf('tel:') === 0) {
			c = "Phone";
			a = h.replace(/\D/g,'');
		} else if (h.indexOf('mailto:') === 0) {
			c = "Email";
			a = h.slice(7); 
		} else if (h.indexOf(location.host) == -1){
			c = "Outgoing Links";
			a = h;
		} else if (h.match(/\/assets\//) && !h.match(/\.(jpe?g|bmp|png|gif|tiff?)$/i)) {
			c = "Downloads";
			a = h.match(/\/assets\/(.*)/)[1];
		}
		if (c){
			_gaq.push(["_trackEvent",c,a,l]);
			/* Push secondary tracker if set */
			_gaq2 && _gaq.push(["b._trackEvent",c,a,l]);
			/* if target not set delay opening of window by 0.5s to allow tracking */
			if(!el.target || el.target.match(/^_(self|parent|top)$/i)){
				setTimeout(function(){
					document.location.href = el.href;
				}.bind(el),500);
				/* Prevent standard click */
				event.preventDefault ? event.preventDefault() : event.returnValue = !1;
			}
		}

	}
}

/* Attach the event to all clicks in the document after page has loaded */
var w = window;
w.addEventListener ? w.addEventListener("load",function(){document.body.addEventListener("click",_gaLt,!1)},!1)
 : w.attachEvent && w.attachEvent("onload",function(){document.body.attachEvent("onclick",_gaLt)});
