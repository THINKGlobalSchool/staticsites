<?php
/**
 * Static Sites Delete Page Action
 *
 * @package StaticSites
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2015
 * @link http://www.thinkglobalschool.org/
 *
 */

$guid = get_input('guid', false);

$page = get_entity($guid);

if (elgg_instanceof($page, 'object', 'static_page')) {
	if ($page->delete()) {
		system_message(elgg_echo('staticsites:success:deletepage'));
	} else {
		register_error(elgg_echo('staticsites:error:deletepage'));
	}
} else {
	register_error(elgg_echo('staticsites:error:invalidpage'));
}

forward(REFERER);