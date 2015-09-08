angular.module('app.controllers')
    .controller('ClientDetailController',['$scope', '$location', '$routeParams', 'Client',  function($scope, $location, $routeParams, Client){
        $scope.client = Client.get(
            {
                id: $routeParams.id
            }
        );

        $scope.back = function()
        {
            $location.path('/clients');
        }
    }]);