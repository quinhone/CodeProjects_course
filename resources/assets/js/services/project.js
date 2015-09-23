angular.module('app.services')
    .service('Project', ['$resource', '$httpParamSerializer', '$filter', 'appConfig', function($resource, $httpParamSerializer, $filter, appConfig){

        function transformData(data)
        {
            if(angular.isObject(data) && data.hasOwnProperty('due_date')){
                var obj = angular.copy(data);
                obj.due_date = $filter('date')(data.due_date, 'yyyy-MM-dd');
                return appConfig.utils.transformRequest(obj);
            }
            return data;
        };

        return $resource(appConfig.baseUrl + '/project/:id', {
            id: '@id'
        }, {
            save: {
                method: 'POST',
                transformRequest: transformData
            },
            get: {
                method: 'GET',
                transformResponse: function (data, headers)
                {
                    var obj = appConfig.utils.transformResponse(data, headers);
                    if (angular.isObject(obj) && obj.hasOwnProperty('due_date')) {
                        var arrDate = obj.due_date.split('-');
                        var month = parseInt(arrDate[1]) -1;
                        obj.due_date = new Date(arrDate[0], month, arrDate[2]);
                    }
                    return obj;
                }
            },
            update: {
                method: 'PUT',
                 transformRequest: transformData
            }
        });
    }]);