<?php
/**
 * Elgg Static Sites library
 *
 * @package StaticSites
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2015
 * @link http://www.thinkglobalschool.org/
 *
 */

function staticsites_handle_page_request($params, $container_guid) {
	if (!$container_guid) {
		$container_guid = elgg_get_logged_in_user_guid();
	}

	$page = get_entity($params[1]);

	if (elgg_instanceof($page, 'object', 'static_page')) {
		echo json_encode(array(
			'title' => $page->title,
			'content' => $page->description,
			'container_guid' => $page->container_guid,
			'guid' => $page->guid
		));
	} else {
		echo json_encode(array(
			'status' => -1,
			'error' => elgg_echo('staticsites:error:invalidpage')
		));
	}
}

function staticsites_handle_pages_request($params, $container_guid) {
	if (!$container_guid) {
		$container_guid = elgg_get_logged_in_user_guid();
	}

	$options = array(
		'type' => 'object',
		'subtype' => 'static_page',
		'container_guid' => $container_guid,
		'limit' => 0, // @TODO
	);

	$pages = elgg_get_entities($options);

	$pages_array = array();

	foreach ($pages as $idx => $page) {
		$pages_array[] = array(
			'title' => $page->title,
			'content' => $page->description,
			'container_guid' => $page->container_guid,
			'guid' => $page->guid
		);
	}

	echo json_encode($pages_array);
}