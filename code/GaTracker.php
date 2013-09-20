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
 * Optional secondary tracker: define('GaTrackingCodeSecondary', 'UA-xxxxxx');
 *
 * License: MIT-style license http://opensource.org/licenses/MIT
 * Authors: Techno Joy development team (www.technojoy.co.nz)
 */

class GaTracker extends SiteTreeExtension {

	/*
	 * Ignore Tasks
	 * Ignore pages loaded with common tasks, ie:
	 * ?flush=[?] , /Security/login
	 */
	public static $ignore_tasks = true;

	/*
	 * Injects GA tracking code & adds a external JS file
	 * to track downloads & outgoing links (as events)
	 */
	public function GoogleAnalytics() {
		if(DEFINED('GaTrackingCode')) {
			$gacode = 'var _gaq = _gaq||[];' . $this->GoogleCode();
			$gacode = $this->Compress($gacode);
			Requirements::customScript($gacode);
			if (defined('GaTrackingCode'))
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
				) . $this->GoogleCode();
			$gacode = $this->Compress($gacode);
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

		$trackingCode = (defined('GaTrackingCode')) ? GaTrackingCode : false;
		$SecondaryTrackingCode = (defined('GaTrackingCodeSecondary')) ? GaTrackingCodeSecondary : false;

		$tracker = array();

		if ($trackingCode) array_push($tracker, '["_setAccount","' . $trackingCode . '"]');
		if ($SecondaryTrackingCode) array_push($tracker, '["b._setAccount","' . $SecondaryTrackingCode . '"]');

		if ($statusCode == 404 || $statusCode == 500) {
			$ecode = ($statusCode == 404) ? 'Page Not Found' : 'Page Error';
			if ($trackingCode) array_push($tracker, '["_trackEvent","' . $ecode . '",d.location.pathname + d.location.search, d.referrer]');
			if ($SecondaryTrackingCode) array_push($tracker, '["b._trackEvent","' . $ecode . '",d.location.pathname + d.location.search, d.referrer]');
		}

		else if ($trackingCode) {
			if ($trackingCode) array_push($tracker, '["_trackPageview"]');
			if ($SecondaryTrackingCode) array_push($tracker, '["b._trackPageview"]');
		}

		$code = 'var d = document; _gaq.push(' . implode($tracker, ',').');';
		$code .= ($SecondaryTrackingCode) ? '_gaq2=!0;' : '_gaq2=!1;';

		$gacode = '
			(function(){
				var ga = d.createElement("script"); ga.type = "text/javascript"; ga.async = true;
				ga.src = ("https:" == d.location.protocol ? "https://ssl" : "http://www") + ".google-analytics.com/ga.js";
				var s = d.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ga,s);
			})();';

		/* Only add GA JavaScript if live */
		if (Director::isLive() && !$this->isIgnored()) $code .=  $gacode;

		return $code;

	}

	/*
	 * Test if query if an admin task
	 * @param null
	 * @return true/false
	 */
	public function isIgnored() {
		if (!self::$ignore_tasks)
			return true;
		if (Controller::curr()->Link() == 'Security/')
			return true;
		if (isset($_GET['flush']))
			return true;
		return false;
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