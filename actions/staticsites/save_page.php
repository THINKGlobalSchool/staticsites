<?php
/**
 * Static Sites Save Page Action
 *
 * @package StaticSites
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2015
 * @link http://www.thinkglobalschool.org/
 *
 */

$title = get_input('title', false);
$content = get_input('content', false);
$guid = get_input('guid', false);
$container_guid = get_input('container_guid', elgg_get_logged_in_user_guid());

if (!$content || !$title) {
	register_error('staticsites:error:nocontent');
	forward(REFERER);
}

// Check and see if we're editing an existing page
$page = get_entity($guid); 

if (elgg_instanceof($page, 'object', 'static_page')) {
	// Existing page
	$page->description = $content;
	$page->title = $title;
} else {
	// New page
	$page = new ElggObject();
	$page->subtype = 'static_page';
	$page->description = $content;
	$page->title = $title;
	$page->container_guid = $container_guid;
}

$page->save();


use League\HTMLToMarkdown\HtmlConverter;

$converter = new HtmlConverter();

$markdown = $converter->convert($content);

system_message(elgg_echo('staticsites:success:pagesaved'));

// Output page info
echo json_encode(array(
	'guid' => $page->guid,
	'title' => $page->title,
	'content' => $page->description,
	'container_guid' => $page->container_guid,
));

forward(REFERER);