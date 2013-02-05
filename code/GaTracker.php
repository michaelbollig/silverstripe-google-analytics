<?php
/**
 * Google Analytics Tracking code
 * ==============================
 *
 * Extension to add Google Analytics tracking code to SilverStripe 3
 *
 * Usage: define('GaTrackingCode', 'UA-xxxxxx'); in your config
 * then add $GoogleAnalytics to your template(s)
 *
 * License: MIT-style license http://opensource.org/licenses/MIT
 * Authors: Techno Joy development team (www.technojoy.co.nz)
 */

class GaTracker extends SiteTreeExtension {

	function GoogleAnalytics() {

		if(defined('GaTrackingCode')) {
			$statusCode = Controller::curr()->getResponse()->getStatusCode();

			if ($statusCode == 404 || $statusCode == 500)
				$track = '_gaq.push(["_setAccount","' . GaTrackingCode . '"]);
				_gaq.push(["_trackEvent", "' . $statusCode . ' Pages", document.location.pathname + document.location.search, "ref: " + document.referrer]);';
			else
				$track = '_gaq.push(["_setAccount","' . GaTrackingCode . '"],["_trackPageview"]);';
			$gacode = 'var _gaq=_gaq||[];
				' . $track . '
				(function(){
					var a=document.createElement("script");
					a.src=("https:"==document.location.protocol?"https://ssl":"http://www")+".google-analytics.com/ga.js";
					a.setAttribute("async","true");
					document.documentElement.firstChild.appendChild(a);
				})();
				function gaDlTracker(){
					var a = document.getElementsByTagName("a");
					for(i = 0; i < a.length; i++){
						if(a[i].href.match(/\/assets\//)){
							a[i].onclick = function(){
								_gaq.push([\'_trackEvent\',\'Downloads\',this.href.match(/\/assets\/(.*)/)[1]]);
							}
						}
					}
				}
				(function(){
					if(typeof window.onload != "function"){
						window.onload = gaDlTracker;
					}else{
						var old = window.onload;
						window.onload = function(){old();gaDlTracker();}
					}
				});';
			$gacode = preg_replace('/(\t|\n)/', '', $gacode);
			if (!Director::isLive()) $gacode = '/*' . $gacode . '*/';
			Requirements::customScript($gacode);
		}
	}

}