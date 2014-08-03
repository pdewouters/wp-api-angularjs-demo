<!doctype html>
<html ng-app="constellationsApp" lang="en-US">
<head>
	<meta charset="UTF-8">
	<title></title>
	<?php wp_head(); ?>
</head>

<body>

<div class="row" ng-controller="StarsCtrl">
	<h1>My starred Github repos</h1>
	<div class="panel">
		<h2>Filter by tag</h2>

		<ul class="inline-list">
			<li ng-repeat="tag in tags">
				<div>
					<input type="checkbox" checklist-model="selection.tags" checklist-value="tag.ID" id="{{tag.ID}}" />
					<label for="{{tag.ID}}">{{tag.name}}</label>
				</div>

			</li>
		</ul>

		<div>
			<label for="search">Search</label><input id="search" type="text" ng-model="search.$" />
		</div>
	</div>
	<h2>Results</h2>
	<table>
		<tr ng-repeat="star in stars | filter:search | matchesTags:selection.tags track by $index" >
			<td>
				<h3><a href="http://github.com/{{star.title}}">{{star.title}}</a></h3>
				<div ng-bind-html="star.content"></div>
				<div>

					<tags-input ng-model="star.terms.pdw_wpc_gh_star_tag"
					            display-property="name"
					            replace-spaces-with-dashes="false"
					            on-tag-added="tagAdded($tag)"
					            on-tag-removed="tagRemoved($tag)">

					</tags-input>
				</div>
			</td>
		</tr>
	</table>
</div>
<?php wp_footer(); ?>
</body>
</html>