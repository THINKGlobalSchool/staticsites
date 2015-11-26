<?php
/**
 * Static Sites Backbone helper lib
 *
 * @package StaticSites
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2015
 * @link http://www.thinkglobalschool.org/
 *
 */

/**
 * Helper function to ajax whitelist files that 
 * are intended to be used as backbone templates
 * 
 * Note: this only works with files in the 'default' viewtype
 * 
 * @param  string $base_path The base path to search for ajax views
 * @return void
 */
function backbone_whitelist_templates($path) {
	// Get the view path for given path
	$view_location = rtrim(substr($path, strpos($path, 'default/') + 8), '/');

	// Iterate over files in given path
	$dir = new DirectoryIterator($path);
	foreach ($dir as $fileinfo) {
		if (!$fileinfo->isDot()) {
			// Register view
			elgg_register_ajax_view($view_location . '/' . $fileinfo->getBasename('.php'));
		}
	}
}