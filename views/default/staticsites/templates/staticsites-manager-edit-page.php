<?php
/**
 * Static Sites Manager Edit Page App Template
 *
 * @package StaticSites
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2015
 * @link http://www.thinkglobalschool.org/
 *
 */
?>
<script type="text/template" id="edit-page-template">
	<% if (guid) { %>
		<h3>Edit Page</h3>
	<% } else { %>
		<h3>New Page</h3>
	<% } %>
	<hr />
	<div id="staticsites-page-title">
		<input name="title" id="page-title" placeholder="Page Title" value="<%- title %>">
	</div>
	<div id="staticsites-page-editor"><%= content %></div>
	<section id="commands">
		<button id="save" class='staticsites-save-content float-left'>Save</button>
		<% if (guid) { %>
			<button id="delete" class='staticsites-save-content float-right'>Delete</button>
		<% } %>
	</section>	
</script>
