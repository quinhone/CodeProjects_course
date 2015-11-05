angular.module('app.controllers')
    .controller('ProjectTaskShowController',['$scope', '$location', '$routeParams', 'ProjectTask',  function($scope, $location, $routeParams, ProjectTask){
        $scope.projectTask = ProjectTask.get(
            {
                id: $routeParams.id,
                idTask: $routeParams.idTask
            }
        );

        $scope.back = function()
        {
            $location.path('/project/'+$routeParams.id+'/tasks');
        }
    }]);