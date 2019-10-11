<?php
//////////////////////////////////////////////////////////////////////
// CONFIGS

// FTP driver to netmount
$useFtpNetmout = true;

// Dropbox driver to netmount
// Dropbox driver need next two settings. You can get at https://www.dropbox.com/developers
define('ELFINDER_DROPBOX_CONSUMERKEY',    '');
define('ELFINDER_DROPBOX_CONSUMERSECRET', '');

// Set root path/url
define('ELFINDER_ROOT_PATH', dirname(__FILE__));
define('ELFINDER_ROOT_URL' , dirname($_SERVER['SCRIPT_NAME']));

// Thumbnail, Sync min second option for netmount
$netvolumeOpts = array(
	'tmbURL'    => ELFINDER_ROOT_URL  . '/files/.tmb',
	'tmbPath'   => ELFINDER_ROOT_PATH . '/files/.tmb',
	'syncMinMs' => 30000
);

// Volumes config
// Documentation for connector options:
// https://github.com/Studio-42/elFinder/wiki/Connector-configuration-options
$opts = array(
	'roots' => array(
		array(
			'driver'        => 'LocalFileSystem',           // driver for accessing file system (REQUIRED)
			'path'          => ELFINDER_ROOT_PATH . '/files/', // path to files (REQUIRED)
			'URL'           => ELFINDER_ROOT_URL  . '/files/', // URL to files (REQUIRED)
			'uploadDeny'    => array('all'),                // All Mimetypes not allowed to upload
			'uploadAllow'   => array('image', 'text/plain'),// Mimetype `image` and `text/plain` allowed to upload
			'uploadOrder'   => array('deny', 'allow'),      // allowed Mimetype `image` and `text/plain` only
			'accessControl' => 'access'                     // disable and hide dot starting files (OPTIONAL)
		)
	)
);

//////////////////////////////////////////////////////////////////////
// load composer autoload.php
require './vendor/autoload.php';

// setup elFinderVolumeMyFTP for netmount
if ($useFtpNetmout) {
	class elFinderVolumeMyFTP extends elFinderVolumeFTP
	{
		protected function init() {
			$this->options = array_merge($this->options, $GLOBALS['netvolumeOpts']);
			return parent::init();
		}
	}
	// overwrite net driver class name
	elFinder::$netDrivers['ftp'] = 'MyFTP';
}

// setup elFinderVolumeMyDropbox for netmount
if (ELFINDER_DROPBOX_CONSUMERKEY && ELFINDER_DROPBOX_CONSUMERSECRET) {
	class elFinderVolumeMyDropbox extends elFinderVolumeDropbox
	{
		protected function init() {
			$this->options = array_merge($this->options, $GLOBALS['netvolumeOpts']);
			return parent::init();
		}
	}
	// overwrite net driver class name
	elFinder::$netDrivers['dropbox'] = 'MyDropbox';
}

/**
 * Simple function to demonstrate how to control file access using "accessControl" callback.
 * This method will disable accessing files/folders starting from '.' (dot)
 *
 * @param  string  $attr  attribute name (read|write|locked|hidden)
 * @param  string  $path  file path relative to volume root directory started with directory separator
 * @return bool|null
 **/
function access($attr, $path, $data, $volume) {
	return strpos(basename($path), '.') === 0       // if file/folder begins with '.' (dot)
		? !($attr == 'read' || $attr == 'write')    // set read+write to false, other (locked+hidden) set to true
		:  null;                                    // else elFinder decide it itself
}

// run elFinder
$connector = new elFinderConnector(new elFinder($opts));
$connector->run();

// end connector
