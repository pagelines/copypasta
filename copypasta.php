<?php
/*
	Plugin Name: CopyPasta for DMS
	Plugin URI: http://slipperysource.com/downloads/copypasta-for-pagelines-dms/
	Description: Copy section options from one template to another with this handy plugin for Pagelines DMS
	Version: 1.0
	Author: William Mincy
	Author URI: http://slipperysource.com/
	PageLines: true
	V3: true
*/

function wmCopyPastaEnable() {
	include_once(dirname(__FILE__)."/js/scripts.php");
}
add_action( 'pagelines_start_footer', 'wmCopyPastaEnable', 5 );
