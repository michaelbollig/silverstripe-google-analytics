# silverstripe-google-analytics

## Google Analytics tracking code for SilverStripe 3
Extension to add Google Analytics tracking code to your SilverStripe templates.

## Features
* Tracking of 404 & 500 errors (tracked as an event "404 Errors" & "500 Errors") - 
Note: you must **Save & Publish** both your error pages after module installation.
* File downloads (from the assets folder) are tracked as events "Downloads".
* Outgoing links are tracked as events "Outgoing Links".
* Development mode is commented out to prevent false tracking. It only actually works in **live mode**.

## Requirements
* SilverStripe 3+

## Usage
In your _config.php or _ss_environment.php add your GA tracking code
<pre>define('GaTrackingCode', 'UA-xxxxxx');</pre>

Then in your templates simply add
<pre>$GoogleAnalytics</pre>

To start tracking, make sure your website is in Live mode
<pre>define('SS_ENVIRONMENT_TYPE', 'live');</pre>
