<?php
/**
 * Google Analytics Tracking code
 * ==============================
 *
 * Extension to add Google Analytics tracking code to SilverStripe 3
 *
 * Usage: define('GaTrackingCode', 'UA-xxxxxx'); in your config
 * then add $GoogleAnalytics or $GoogleAnalyticsInline to your template(s)
 *
 * License: MIT-style license http://opensource.org/licenses/MIT
 * Authors: Techno Joy development team (www.technojoy.co.nz)
 */

class GaTracker extends SiteTreeExtension {

	/*
	 * Injects GA tracking code & adds a external JS file
	 * to track downloads & outgoing links (as events)
	 */
	public function GoogleAnalytics() {

		if(DEFINED('GaTrackingCode')) {

			$gacode = 'var _gaq = _gaq||[];' . $this->GoogleCode();

			$gacode = $this->Compress($gacode);
			if (!Director::isLive()) $gacode = '/*' . $gacode . '*/';

			Requirements::customScript($gacode);
			Requirements::javascript(
				basename(dirname(dirname(__FILE__))) . "/javascript/gatracker.js"
			);

		}

	}

	/*
	 * Injects GA tracking code & inline code
	 * to track downloads & outgoing links (as events)
	 */
	public function GoogleAnalyticsInline() {

		if(DEFINED('GaTrackingCode')) {

			$gacode = @file_get_contents(
					dirname( dirname( __FILE__ ) ) . '/javascript/gatracker.js'
				) .  $this->GoogleCode();

			$gacode = $this->Compress($gacode);

			if (!Director::isLive()) $gacode = '/*' . $gacode . '*/';
			Requirements::customScript($gacode);

		}

	}

	/*
	 * Returns the legacy Google Analytics code
	 * if 404 || 500 error, invokes a _trackEvent instead of a _trackPageview
	 * @return str
	 */
	protected function GoogleCode(){

		$statusCode = Controller::curr()->getResponse()->getStatusCode();

			if ($statusCode == 404 || $statusCode == 500) {
				$ecode = ($statusCode == 404) ? 'Page Not Found' : 'Page Error';
				$code  = '_gaq.push(["_setAccount","' . GaTrackingCode . '"]);';
				$code .= '_gaq.push(["_trackEvent","' . $ecode . '",document.location.pathname + document.location.search, document.referrer]);';
			}
			else
				$code = '_gaq.push(["_setAccount","' . GaTrackingCode . '"],["_trackPageview"]);';

			$code .= '
				(function(){
					var ga = document.createElement("script"); ga.type = "text/javascript"; ga.async = true;
					ga.src = ("https:" == document.location.protocol ? "https://ssl" : "http://www") + ".google-analytics.com/ga.js";
					var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ga,s);
				})();';

			return $code;

	}

	/*
	 * Compress inline JavaScript
	 * @param str data
	 * @return str
	 */
	protected function Compress($data) {
		$repl = array(
			'!/\*[^*]*\*+([^/][^*]*\*+)*/!' => '', // Comments
			'/(\n|\t)/' => '',
			'/\s?=\s?/' => '=',
			'/\s?==\s?/' => '==',
			'/\s?!=\s?/' => '!=',
			'/\s?;\s?/' => ';',
			'/\s?:\s?/' => ':',
			'/\s?\+\s?/' => '+',
			'/\s?\?\s?/' => '?',
			'/\s?&&\s?/' => '&&',
			'/\s?\(\s?/' => '(',
			'/\s?\)\s?/' => ')',
			'/\s?\|\s?/' => '|',
			'/\s<\s?/' => '<',
			'/\s>\s?/' => '>',
		);
		return preg_replace( array_keys($repl), array_values($repl), $data );
	}

}
