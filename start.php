<?php
/**
 * Elgg Static Sites start.php
 *
 * @package StaticSites
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2015
 * @link http://www.thinkglobalschool.org/
 *
 */


elgg_register_event_handler('init', 'system', 'staticsites_init');

function staticsites_init() {
	// Register backbone
	elgg_register_library('elgg:backbone', elgg_get_plugins_path() . 'staticsites/lib/backbone/backbone.php');
	elgg_load_library('elgg:backbone');

	elgg_register_library('elgg:staticsites', elgg_get_plugins_path() . 'staticsites/lib/staticsites.php');
	elgg_load_library('elgg:staticsites');

	// Include plugin vendors
	require elgg_get_plugins_path() . 'staticsites/vendor/autoload.php';

	// Static sites CSS
	elgg_extend_view('css/elgg', 'css/staticsites/css');

	// Register underscore with requirejs (and elgg)
	elgg_define_js('underscore', array(
		'src' => 'mod/staticsites/vendors/underscore-min.js',
		'location' => 'footer',
		'exports' => '_',
	));

	// Register backbone with requirejs (and elgg)
	elgg_define_js('backbone', array(
		'src' => 'mod/staticsites/vendors/backbone-min.js',
		'location' => 'footer',
		'deps' => array('jquery'),
		'exports' => 'Backbone',
	));

	// Alloy Editor
	elgg_define_js('alloyEditor', array(
		'src' => 'mod/staticsites/vendors/alloy-editor/alloy-editor-all-min.js',
		'location' => 'footer',
		'exports' => 'AlloyEditor',
	));

	// Alloy Editor CSS
	elgg_register_css('alloyeditor', 'mod/staticsites/vendors/alloy-editor/assets/alloy-editor-ocean-min.css');
	elgg_load_css('alloyeditor');

	// Set up group admin tools menu
	elgg_register_plugin_hook_handler('register', 'menu:groups:admin', 'staticsites_groups_admin_menu_setup');

	// Register main page handler
	elgg_register_page_handler('staticsites', 'staticsites_page_handler');

	// Actions
	$action_base = elgg_get_plugins_path() . "staticsites/actions/staticsites";
	elgg_register_action('staticsites/save_page', "$action_base/save_page.php");
	elgg_register_action('staticsites/delete_page', "$action_base/delete_page.php");

	// Whitelist template dir for ajax loads
	backbone_whitelist_templates(elgg_get_plugins_path() . 'staticsites/views/default/staticsites/templates/');
}

/**
 * Page handler for static sites
 * 
 * This page handler will behave like a REST-ful api endpoint for GET request
 * Since static sites 'containers' are groups, the resource naming and endpoints
 * will look like:
 * 
 * GET  - {elgg_url}/staticsites/{group_guid}/page/{id}
 * GET  - {elgg_url}/staticsites/{group_guid}/pages
 *
 */
function staticsites_page_handler($params) {
	// Logged in only
	gatekeeper();

	$is_xhr = elgg_is_xhr();
	$is_xhr = 1; // Debug

	if ($is_xhr && $params[0]) {
		// Check for container_guid as first param
		if (is_numeric($params[0])) {
			$container_guid = $params[0];

			unset($params[0]);
			$params = array_values($params);
		}

		switch ($params[0]) {
			case "page":
				staticsites_handle_page_request($params, $container_guid);
				break;
			case "pages":
				staticsites_handle_pages_request($params, $container_guid);
				break;
			default:
				return FALSE;
				break;
		}

	} else {
		return FALSE;
	}

	return TRUE;
}

/**
 * Add items to the group admin menu
 *
 * @param string $hook
 * @param string $type
 * @param array  $value
 * @param array  $params
 * @return array
 */
function staticsites_groups_admin_menu_setup($hook, $type, $value, $params) {
	elgg_require_js('staticsites/main');

	$group = elgg_get_page_owner_entity();
	$create_url = "todo/add/" . $group->guid;

	$options = array(
			'name' => 'manage-staticsites',
			'text' => elgg_echo('staticsites:menu:managesites'),
			'href' => '#',
			'link_class' => 'staticsites-manager-open',
			'data-container_guid' => elgg_get_page_owner_guid()
	);
	
	$value[] = ElggMenuItem::factory($options);

	return $value;
}