/**
 * Elgg Static Sites Editor App
 *
 * @package StaticSites
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2015
 * @link http://www.thinkglobalschool.org/
 *
 */
define(['underscore', 'backbone', '_backbone/templateloader', 'alloyEditor'], function (_, Backbone, loader, AlloyEditor) {

	var init = function(container_guid) {
		if (Backbone) {
			Backbone.history.stop();
		}

		var read_base_url = elgg.get_site_url() + 'staticsites/';

		if (container_guid) {
			read_base_url += container_guid;
		}

		// Template base dir
		var template_base = elgg.get_site_url() + 'ajax/view/staticsites/templates/'; 

		// Load in templates
		var app_template = loader.load(template_base, 'staticsites-manager-skeleton');
		var page_list_item = loader.load(template_base, 'staticsites-manager-page-list-item');
		var page_edit_template = loader.load(template_base, 'staticsites-manager-edit-page');

		// Create main body
		$('body').append(app_template);

		var staticPagesApp = {};

		// Page model
		staticPagesApp.Page = Backbone.Model.extend({
			defaults: {
				guid: undefined,
				title: undefined,
				content: undefined,
				container_guid: undefined
			},
			idAttribute: 'guid', // map Elgg guid as ID attribute
			initialize: function() {

			},
			// Custom sync function to handle CRUD -> Elgg Actions
			sync: function(method, model, options) {
				options || (options = {});

				switch (method) {
					case 'create':
						Backbone.elggRequest("staticsites/save_page", method, model, options);
						break;
					case 'read':
						Backbone.elggRequest(read_base_url + '/page', method, model, options);
						break;
					case 'update':
						Backbone.elggRequest("staticsites/save_page", method, model, options);
						break;
					case 'delete':
						Backbone.elggRequest("staticsites/delete_page", method, model, options);
						break;
				}
			}
		});

		// Page list collection
		staticPagesApp.PageList = Backbone.Collection.extend({
			model: staticPagesApp.Page,
			url: read_base_url + "/pages"
		});

		// Define a page list collection for the app
		staticPagesApp.pageList = new staticPagesApp.PageList();

		// Page List Item View
		staticPagesApp.PageListItemView = Backbone.View.extend({
			tagName: 'li',
			template: _.template($(page_list_item).html()),
			render: function(){
				this.$el.html(this.template(this.model.toJSON()));
				return this;
			},
			initialize: function() {
				console.log('initting page list item view');

				this.listenTo(this.model, 'change', this.render);
				this.listenTo(this.model, 'destroy', this.remove);
			},
			remove: function() {
				this._removeElement();
				this.stopListening();
				return this;
		    },
			events: {}
		});

		// Page List View
		staticPagesApp.PageListView = Backbone.View.extend({
			//el: $('#pages-nav-items'),
			events: {
			
			},
			initialize: function() {
				console.log('initting page list view');

				this.listenTo(staticPagesApp.pageList, 'reset', this.addAll);
				this.listenTo(staticPagesApp.pageList, 'add', this.addOne);
			},
			render: function() {
				staticPagesApp.pageList.fetch();
				return this;

			},
			addOne: function(page){
				var view = new staticPagesApp.PageListItemView({model: page});
				$(this.el).append(view.render().el);
			},
			addAll: function(){
				$(this.el).html(''); 
				staticPagesApp.pageList.each(this.addOne, this);
			},
		});

		// Main app view
		staticPagesApp.AppView = Backbone.View.extend({
			'el': $('#staticsites-manager-core'),
			initialize: function() {
				console.log('initting app view');

				// Child views
				this.pageListView = new staticPagesApp.PageListView({});
				
				this.render();
			},
			events: {
				'click #new-page': 'newPage'
			}, 
			render: function() {
				var template = loader.load(template_base, 'staticsites-manager-app');
				this.$el.html(template);

				// Render the app list view 
				this.pageListView.el = '#pages-nav-items';
				this.pageListView.render();
				this.pageListView.delegateEvents();

				return this;
			},
			newPage: function(event) {
				// Navigate to new page
				staticPagesApp.router.navigate("", {trigger: true});
			}
		});

		// New Page view
		staticPagesApp.EditPageView = Backbone.View.extend({
			template: _.template($(page_edit_template).html()),
			initialize: function(page) {
				if (!page) {
					console.log('initting new page view');
					this.model = new staticPagesApp.Page({content: "<b>Edit here!</b> Highlight text for <i>styling</i>!"});
				} else {
					console.log('initting edit page view');
					this.model = page;
				}
				this.page = this.model;
			},
			render: function() {
				$(this.el).html(this.template(this.model.toJSON()));

				// Set up allow editor
				this.editor = AlloyEditor.editable('staticsites-page-editor');

				return this;
		    },
			events: {
				'click #save': 'savePage',
				'click #delete': 'deletePage',
			},
			savePage: function(event) {
				event.preventDefault();

				// Validate and save page
				var title = $('input[name=title]').val();
				var content = CKEDITOR.instances['staticsites-page-editor'].getData();

				if (title && content) {
					// Set and save page model
					this.page.save(
						{'title': title, "content": content, "container_guid": container_guid}, {
						success: function (model, response, options) {
							// Add to the page list collection (merge if it exists)
							staticPagesApp.pageList.add(model, {merge: true});
							staticPagesApp.router.navigate("page/" + model.id, {trigger: true});
						}
					});
				} else {
					elgg.register_error(elgg.echo("staticsites:error:nocontent"));
				}
			},
			deletePage: function(event) {
				event.preventDefault();

				this.page.destroy({
					success: function(model, response) {
						staticPagesApp.router.navigate("", {trigger: true});
					},
					wait: true
			    });
				
			},
			remove: function() {
				// Empty the element and remove it from the DOM while preserving events
				$(this.el).empty().detach();

				return this;
			}
		});

		// Custom elgg request function
		Backbone.elggRequest = function(request, method, model, options) {
			// Handle create, update, delete
			if (method != "read") {
				elgg.action(request, {
					data: model.attributes,
					success: function(data) {
						if (data.status != -1 && options.success) {
							// Good, fire success callback
							options.success(data.output);
						} else {
							// Handle error here
							if (options.error) {
								options.error(data); // Do something extra with the response as needed
							}
						}
					}
				});
			} else {
				// Handle read (for single model)
				var read_url = request + '/' + model.id;

				elgg.getJSON(read_url, {
					success: function(data) {
						if (data.status != -1 && options.success) {
							// Good, fire success callback
							options.success(data);
						} else {
							// Handle error here
							if (options.error) {
								options.error(data.error); // Do something extra with the response as needed
							}
						}
					}
				});
			}
		}

		// Main view
		var appView = new staticPagesApp.AppView();

		// Router
		staticPagesApp.EditorRouter = Backbone.Router.extend({
			currentView: null,
			initialize: function(el) {
				this.el = el;
			},
 			routes: {
				'page/:guid': "getPage",
				'*path': "newPage" // Default to new page for now
			},
			newPage: function() {
				// Create a new page editor view
				this.newPageView = new staticPagesApp.EditPageView();
				this.switchView(this.newPageView);
			},
			getPage: function (guid) {
				var page = staticPagesApp.pageList.get(guid);
				
				var _this = this;

				// Get page from db
				page.fetch({
					success: function(page, resp, opt) {
						_this.editPageView = new staticPagesApp.EditPageView(page);
						_this.switchView(_this.editPageView);
					}, 
					error: function(model, error) {
						elgg.register_error(error);
					}
				});
			},
			switchView: function(view) {
				if (this.currentView) {
					// Detach the old view
					this.currentView.remove();
				}

				// Move the view element into the DOM (replacing the old content)
				this.el.html(view.el);

				// Render view after it is in the DOM (styles are applied)
				view.render();

				view.delegateEvents();

				this.currentView = view;
			}
		});

		staticPagesApp.router = new staticPagesApp.EditorRouter($('#main'));
		Backbone.history.start();
	}

	return {
		init: init
	};
});