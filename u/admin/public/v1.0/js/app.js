/**
* Dokumentumok
**/
var docs = angular.module('Documents', []);

docs.controller("List", ['$scope', '$http', function($scope, $http)
{
	$scope.docs = [];
	$scope.searcher = null;
	$scope.loading = false;
	$scope.termid = 0;
	$scope.error = false;

	$scope.init = function( id ){
		$scope.termid = id;
		$scope.loadList();
	}

	$scope.loadList = function()
	{
		$scope.loading = true;
		$http({
      method: 'POST',
      url: '/ajax/get',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        type: "Documents",
        key: 'List',
				id: $scope.termid
      })
    }).success(function(r){
			$scope.loading = false;
			console.log(r);
			if (r.error == 0) {
				$scope.error = false;
 
			} else {
				$scope.error = r.msg;
			}
    });
	}
}]);
