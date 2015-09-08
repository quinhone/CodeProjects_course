angular.module('app.controllers')
    .controller('ClientListController',['$scope', 'Client',  function($scope, Client){
        $scope.clients = Client.query();


        /*$scope.remove = function(id, name)
        {
            $scope.client = Client.get({
                id: id
            });
            var res = confirm("Deseja excluir o cliente "+name+"?");
            if(res == true)
            {
                $scope.client.$delete(id);
            }
            else{
                return false;
            }
        }*/

    }]);