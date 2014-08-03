// app.js
var constellationsApp = angular.module('constellationsApp', ['ngSanitize', 'ngTagsInput', 'checklist-model', 'ngResource', 'wp.api']);

constellationsApp.factory('Stars', function (wpAPIResource) {
	var Stars = wpAPIResource.query({
		param1: 'stars'
	});

	return Stars;
});

constellationsApp.factory('Tags', function (wpAPIResource) {
	var Tags = wpAPIResource.query({
		param1: 'taxonomies',
		param2: 'pdw_wpc_gh_star_tag',
		param3: 'terms'
	});

	return Tags;
});

constellationsApp.filter('matchesTags', function () {
	return function (items, filterArray) {
		var filtered = [];
		if (filterArray.length > 0) {

			for (var i = 0; i < items.length; i++) {
				var item = items[i];
				if (item.terms.hasOwnProperty('pdw_wpc_gh_star_tag')) {
					if (item.terms.pdw_wpc_gh_star_tag.length > 0) {
						for (var j = 0; j < item.terms.pdw_wpc_gh_star_tag.length; j++) {
							// If any of this repo's tag matches a filter tag then show it
							if (filtered.indexOf(item) === -1 && filterArray.indexOf(item.terms.pdw_wpc_gh_star_tag[j].ID) > -1) {
								filtered.push(item);
							}
						}
					}
				}
			}
			return filtered;
		} else {
			return items;
		}
	};
});

function StarsCtrl($scope, Stars, Tags, wpAPIResource) {

	$scope.wpAPI = wpAPIResource;

	$scope.stars = Stars;

	$scope.tags = Tags;

	$scope.selection = {
		tags: []
	};

	$scope.loadTags = function (query) {

		return $scope.tags;
	};

	$scope.tagAdded = function (tag) {
		var existingTags = this.star.terms.pdw_wpc_gh_star_tag;
		$scope.editPostTags(tag, existingTags, this.star);
	};

	$scope.tagRemoved = function (tag) {
		var existingTags = this.star.terms.pdw_wpc_gh_star_tag;
		$scope.editPostTags(tag, existingTags, this.star);
	};

	$scope.editPostTags = function (tag, existingTags, star) {

		var tagsToSave = [];
		if (existingTags.length > 0) {
			for (var i = 0; i < existingTags.length; i++) {
				tagsToSave[i] = existingTags[i].name;
			}
		} else {
			tagsToSave = [null];
		}
		$scope.wpAPI.save({
				param1: 'stars',
				param2: star.ID
			},
			{
				tax_input: {
					pdw_wpc_gh_star_tag: {term_names: tagsToSave}
				}
			});
	};

}
