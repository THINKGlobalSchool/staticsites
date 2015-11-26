/**
 * Static Sites Main JS
 *
 * @package StaticSites
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2015
 * @link http://www.thinkglobalschool.org/
 *
 */

define(function(require) {
	var $ = require('jquery');
	var elgg = require('elgg');

	var originalBody;
	var originalHash;

	// Click handler to load static site manager
	var openManager = function(event) {
		event.preventDefault();

		var container_guid = $(this).data('container_guid');

		$('body').toggle('fade', 200, function() {
			// Store the original body and hash if needed
			originalHash = window.location.hash;
			originalBody = $(this).detach();

			// Clear hash
			window.location.hash = "";

			$('html').append($(document.createElement('body')).attr('id', 'staticsites-manage-body'));

			require(['staticsites/editorapp'], function(editorapp) {
				editorapp.init(container_guid);
			});
		});
	}

	// Click handler to close static site manager
	var closeManager = function(event) {
		event.preventDefault();
		window.location.hash = originalHash;
		$('body').replaceWith(originalBody);
		$('body').fadeIn();
		originalBody = $('body');
	}

	var init = function() {
		console.log('Static sites loaded.');
		
		// Bind event handlers
		$(document).on('click', '.staticsites-manager-open', openManager);
		$(document).on('click', '.staticsites-manager-close', closeManager);
	};

	elgg.register_hook_handler('init', 'system', init);

	return {};
});