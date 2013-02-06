# silverstripe-google-analytics

## Google Analytics tracking code for SilverStripe templates
Extension to add Google Analytics tracking code to your SilverStripe templates. It also supports:
* 404 errors (tracked as an event "404 Errors") - Note: you must Save & Publish error page after installation
* 500 errors (tracked as an event "500 Errors") - Note: you must Save & Publish error page after installation
* Downloads (from the assets folder) are tracked as an event "Downloads"
* Development mode is commented out to prevent false tracking

## Requirements
* SilverStripe 3+

## Usage
In your _config.php or _ss_environment.php add your GA tracking code
<pre>
define('GaTrackingCode', 'UA-xxxxxx');
</pre>
Then in your templates simply add
<pre>
$GoogleAnalytics
</pre>

## Note
The code is actually only activated in live mode: define('SS_ENVIRONMENT_TYPE', 'live');
