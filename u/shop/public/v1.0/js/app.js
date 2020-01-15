var tc = angular.module('ragrikol', ['ngMaterial', 'ngMessages', 'ngCookies']);

tc.controller('App', ['$scope', '$sce', '$http', '$mdToast', '$mdDialog', '$location','$cookies', '$cookieStore', function($scope, $sce, $http, $mdToast, $mdDialog, $location, $cookies, $cookieStore)
{
  $scope.fav_num = 0;
  $scope.fav_ids = [];
  $scope.in_progress_favid = false;
  $scope.requesttermprice = {};
  $scope.order_accepted = false;
  $scope.accept_order_key = 'acceptedOrder';
  $scope.accept_order_text = null;
  $scope.accept_order_title = 'Szerződési feltételek elfogadása';

  $scope.findedCity = {};
  $scope.findCityByIrsz = function( event, tinput )
  {
    event.preventDefault();
    var val = event.target.value;

    $http({
      method: 'POST',
      url: '/ajax/get',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        type: "irszCityHint",
        irsz: val
      })
    }).success(function(r){
      if (r.data && r.data.length != 0) {
        if (r.data.length == 1) {
          $('input#'+tinput).val( r.data[0].varos );
        } else {
          $scope.findedCity[tinput] = r.data;
        }
      } else if( r.data && r.data.length == 0) {
        $scope.findedCity[tinput] = [];
      }
    });
  }
  $scope.fillCityHint = function( tinput, city ) {
    $('input#'+tinput).val( city.varos );
    $scope.findedCity[tinput] = [];
  }

  $scope.productAddToFav = function( id, ev ){
    var infav = $scope.fav_ids.indexOf(id);

    if ( infav !== -1 ) {
      var confirmRemoveFav = $mdDialog.confirm()
          .title('Biztos, hogy eltávolítja a kedvencekből?')
          .textContent('Ez a termék jelenleg a kedvencei közt szerepel.')
          .ariaLabel('Eltávolítás a kedvencek közül')
          .targetEvent(ev)
          .ok('Eltávolítás')
          .cancel('Mégse');

      $mdDialog.show(confirmRemoveFav).then(function() {
        $scope.doFavAction('remove', id, function(){
          $scope.syncFavs(function(err, n){
            $scope.fav_num = n;
            $scope.in_progress_favid = false;
          });
        });
      }, function() {

      });
    } else {
      $scope.in_progress_favid = id;
      $scope.doFavAction('add', id, function(){
        $scope.syncFavs(function(err, n){
          $scope.fav_num = n;
          $scope.in_progress_favid = false;
        });
      });
    }
  }

  $scope.init = function( ordernow ){
    $scope.syncFavs(function(err, n){
      $scope.fav_num = n;
    });

    if (typeof ordernow !== 'undefined' && ordernow === true ) {
      $scope.loadSettings( ['tuzvedo_order_pretext','tuzvedo_order_pretext_wanted','tuzvedo_order_pretext_title'], function(settings){
        if (settings.tuzvedo_order_pretext_wanted == '1') {
          $scope.accept_order_title = (settings.tuzvedo_order_pretext_title != '') ? settings.tuzvedo_order_pretext_title : $scope.accept_order_title ;
          $scope.accept_order_text = settings.tuzvedo_order_pretext;
          $scope.acceptBeforeDoneOrder();
        } else {
          $scope.order_accepted = true;
        }
      });
    }
  }

  $scope.loadSettings = function( key, callback ){
    $http({
      method: 'POST',
      url: '/ajax/get',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        type: "settings",
        key: key
      })
    }).success(function(r){
      callback(r.data);
    });
  }

  $scope.productRemoveFromFav = function( id ){

  }

  $scope.acceptBeforeDoneOrder = function(){
    var accepted = $cookieStore.get( $scope.accept_order_key );

    if ( typeof accepted === 'undefined' )
    {
      var confirm = $mdDialog.confirm({
  			controller: acceptBeforeDoneOrderController,
  			templateUrl: '/app/templates/acceptBeforeDoneOrder',
        scope: $scope,
        preserveScope: true,
  			parent: angular.element(document.body),
        locals: {
          order_accepted: $scope.order_accepted,
          accept_order_key: $scope.accept_order_key
        }
  		});

      function acceptBeforeDoneOrderController( $scope, $mdDialog, order_accepted, accept_order_key) {
        $scope.order_accepted = order_accepted;
        $scope.accept_order_key = accept_order_key;

  			$scope.closeDialog = function(){
  				$mdDialog.hide();
  			}
        $scope.acceptOrder = function(){
          $cookies.put($scope.accept_order_key, 1);
          $scope.order_accepted = true;
          $mdDialog.hide();
  			}
  		}
      $mdDialog.show(confirm);
    } else {
      $scope.order_accepted = true;
    }
  }

  $scope.requestPrice = function( id ){
    var confirm = $mdDialog.confirm({
			controller: RequestPriceController,
			templateUrl: '/app/templates/ProductItemPriceRequest',
			parent: angular.element(document.body),
			locals: {
        termid: id,
        requesttermprice: $scope.requesttermprice
			}
		});

    function RequestPriceController( $scope, $mdDialog, termid, requesttermprice) {
      $scope.sending = false;
      $scope.termid = termid;
      $scope.requesttermprice = requesttermprice;

			$scope.closeDialog = function(){
				$mdDialog.hide();
			}

      $scope.validateForm = function(){
        var state = false;
        var phone_test = ''

        if (
          (typeof $scope.requesttermprice.name !== 'undefined' && $scope.requesttermprice.name.length >= 5) &&
          (typeof $scope.requesttermprice.phone !== 'undefined' && !$scope.requesttermprice.phone.$error) &&
          (typeof $scope.requesttermprice.email !== 'undefined' && !$scope.requesttermprice.email.$error)
        ) {
          state = true;
        }

        return state;
      }

      $scope.sendModalMessage = function( type ){
        if (!$scope.sending) {
          $scope.sending = true;

          $http({
      			method: 'POST',
      			url: '/ajax/post',
      			headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      			data: $.param({
      				type: "modalMessage",
              modalby: type,
              datas: {
                termid: $scope.termid
              }
      			})
      		}).success(function(r){
      			console.log(r);
      			$scope.sending = false;

      			if (r.error == 1) {
      				$scope.toast(r.msg, 'alert', 10000);
      			} else {
      				$mdToast.hide();
              $scope.closeDialog();
      				$scope.toast('Köszönjük érdeklődését! Ingyenes visszahívás kérés igénylés elküldve. Hamarosan jelentkezünk!', 'success', 10000);
      			}
      		});
        }
      }

      $scope.toast = function( text, mode, delay ){
    		mode = (typeof mode === 'undefined') ? 'simple' : mode;
        delay = (typeof delay === 'undefined') ? 5000 : delay;

    		if (typeof text !== 'undefined') {
    			$mdToast.show(
    				$mdToast.simple()
    				.textContent(text)
    				.position('top')
    				.toastClass('alert-toast mode-'+mode)
    				.hideDelay(delay)
    			);
    		}
    	}
		}

    $http({
      method: 'POST',
      url: '/ajax/post',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        type: "getTermItem",
        id: id
      })
    }).success(function(r){
      console.log(r);
      if (r.error == 1) {
        $scope.toast(r.msg, 'alert', 10000);
      } else {
        $scope.requesttermprice.product = r.product;
        $mdDialog.show(confirm)
    		.then(function() {
          $scope.status = 'You decided to get rid of your debt.';
        }, function() {
          $scope.status = 'You decided to keep your debt.';
        });
      }
    });

  }

  $scope.doFavAction = function( type, id, callback ){
    $http({
      method: 'POST',
      url: '/ajax/post',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        type: "productFavorite",
        action: type,
        tid: id
      })
    }).success(function(r){
      if (r.error == 1) {
        $scope.toast(r.msg, 'alert', 10000);
      } else {
        $mdToast.hide();
        $scope.toast(r.msg, 'success', 5000);
      }

      if (typeof callback === 'function') {
        callback(r.error, r.msg, r);
      }
    });
  }

  $scope.syncFavs = async function( callback ){
    $http({
      method: 'POST',
      url: '/ajax/post',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        type: "productFavorite",
        action: 'get',
        own: 1
      })
    }).success(function(r){
      if (r.ids) {
        $scope.fav_ids = [];
        angular.forEach(r.ids, function(v,i){
          $scope.fav_ids.push(v);
        });
      }
      if (typeof callback === 'function') {
        callback(r.error, r.num);
      }
    });
  }

  $scope.toast = function( text, mode, delay ){
    mode = (typeof mode === 'undefined') ? 'simple' : mode;
    delay = (typeof delay === 'undefined') ? 5000 : delay;

    if (typeof text !== 'undefined') {
      $mdToast.show(
        $mdToast.simple()
        .textContent(text)
        .position('top')
        .toastClass('alert-toast mode-'+mode)
        .hideDelay(delay)
      );
    }
  }

}]);

tc.controller('ActionButtons', ['$scope', '$http', '$mdDialog', '$mdToast', function($scope, $http, $mdDialog, $mdToast){

  $scope.showHints = true;
  $scope.recall = {};
  $scope.ajanlat = {};

  /**
  * Ingyenes visszahívás modal
  **/
  $scope.requestRecall = function(){
		var confirm = $mdDialog.confirm({
			controller: ConfirmPackageOrder,
			templateUrl: '/app/templates/recall',
			parent: angular.element(document.body),
			locals: {
        showHints: $scope.showHints,
        recall: $scope.recall,
        ajanlat: $scope.ajanlat
			}
		});

		function ConfirmPackageOrder( $scope, $mdDialog, showHints, recall, ajanlat) {
      $scope.showHints = showHints;
      $scope.recall = recall;
      $scope.ajanlat = ajanlat;
      $scope.sending = false;

			$scope.closeDialog = function(){
				$mdDialog.hide();
			}
      $scope.validateForm = function(){
        var state = false;
        var phone_test = ''

        if (
          (typeof $scope.recall.name !== 'undefined' && $scope.recall.name.length >= 5) &&
          (typeof $scope.recall.phone !== 'undefined' && !$scope.recall.phone.$error) &&
          (typeof $scope.recall.subject !== 'undefined')
        ) {
          state = true;
        }

        return state;
      }

      $scope.sendModalMessage = function( type ){
        if (!$scope.sending) {
          $scope.sending = true;

          $http({
      			method: 'POST',
      			url: '/ajax/post',
      			headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      			data: $.param({
      				type: "modalMessage",
              modalby: type,
              datas: $scope[type]
      			})
      		}).success(function(r){
      			$scope.sending = false;
      			$scope.recall = {};

      			if (r.error == 1) {
      				$scope.toast(r.msg, 'alert', 10000);
      			} else {
      				$mdToast.hide();
              $scope.closeDialog();
      				$scope.toast('Köszönjük érdeklődését! Ingyenes visszahívás kérés igénylés elküldve. Hamarosan jelentkezünk!', 'success', 10000);
      			}
      		});
        }
      }
      $scope.toast = function( text, mode, delay ){
    		mode = (typeof mode === 'undefined') ? 'simple' : mode;
        delay = (typeof delay === 'undefined') ? 5000 : delay;

    		if (typeof text !== 'undefined') {
    			$mdToast.show(
    				$mdToast.simple()
    				.textContent(text)
    				.position('top')
    				.toastClass('alert-toast mode-'+mode)
    				.hideDelay(delay)
    			);
    		}
    	}
		}

		$mdDialog.show(confirm)
		.then(function() {
      $scope.status = 'You decided to get rid of your debt.';
    }, function() {
      $scope.status = 'You decided to keep your debt.';
    });
  }
  /**
  * Ajánlatkérés modal
  **/
  $scope.requestAjanlat = function()
  {
    var confirm = $mdDialog.confirm({
			controller: ConfirmPackageOrder,
			templateUrl: '/app/templates/ajanlatkeres',
			parent: angular.element(document.body),
			locals: {
        showHints: $scope.showHints,
        ajanlat: $scope.ajanlat
			}
		});

		function ConfirmPackageOrder( $scope, $mdDialog, showHints, ajanlat) {
      $scope.showHints = showHints;
      $scope.ajanlat = ajanlat;
      $scope.sending = false;

			$scope.closeDialog = function(){
				$mdDialog.hide();
			}
      $scope.validateForm = function(){
        var state = false;
        var phone_test = ''

        if (
          (typeof $scope.ajanlat.name !== 'undefined' && $scope.ajanlat.name.length >= 5) &&
          (typeof $scope.ajanlat.phone !== 'undefined' && !$scope.ajanlat.phone.$error) &&
          (typeof $scope.ajanlat.email !== 'undefined' && !$scope.ajanlat.email.$error) &&
          (typeof $scope.ajanlat.message !== 'undefined')
        ) {
          state = true;
        }

        return state;
      }

      $scope.sendModalMessage = function( type ){
        if (!$scope.sending) {
          $scope.sending = true;

          $http({
      			method: 'POST',
      			url: '/ajax/post',
      			headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      			data: $.param({
      				type: "modalMessage",
              modalby: type,
              datas: $scope[type]
      			})
      		}).success(function(r){
      			$scope.sending = false;
      			$scope.ajanlat = {};

      			if (r.error == 1) {
      				$scope.toast(r.msg, 'alert', 10000);
      			} else {
      				$mdToast.hide();
              $scope.closeDialog();
      				$scope.toast('Köszönjük érdeklődését! Hamarosan jelentkezünk személyre szabott ajánlatunkkal!', 'success', 10000);
      			}
      		});
        }
      }

      $scope.toast = function( text, mode, delay ){
    		mode = (typeof mode === 'undefined') ? 'simple' : mode;
        delay = (typeof delay === 'undefined') ? 5000 : delay;

    		if (typeof text !== 'undefined') {
    			$mdToast.show(
    				$mdToast.simple()
    				.textContent(text)
    				.position('top')
    				.toastClass('alert-toast mode-'+mode)
    				.hideDelay(delay)
    			);
    		}
    	}
		}

		$mdDialog.show(confirm)
		.then(function() {
      $scope.status = 'You decided to get rid of your debt.';
    }, function() {
      $scope.status = 'You decided to keep your debt.';
    });
  }


}]);

tc.filter('unsafe', function($sce){ return $sce.trustAsHtml; });
