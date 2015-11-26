/**
 * Static Sites - Backbone template loader module
 *
 * @package StaticSites
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2015
 * @link http://www.thinkglobalschool.org/
 * 
 * Defines a loader for external template files
 * @see http://stackoverflow.com/a/12817337/1202510
 */
define(function() {
	function TemplateLoader() {};
	TemplateLoader.templates = {};

	TemplateLoader.load = function(base_path, name) {

		if (template = TemplateLoader.templates[name]) {
			return template;
		} else {
			return TemplateLoader.templates[name] = $.ajax({
					url: base_path + name,
					async: false
			}).responseText;
		}
	};

	TemplateLoader.getTemplates = function() {
		return TemplateLoader.templates;
	}

	return {
		load: TemplateLoader.load,
		getTemplates: TemplateLoader.getTemplates
	};
});
