var app = angular.module('app', ['ngRoute', 'angular-oauth2', 'app.controllers', 'app.services', 'app.filters', 'app.directives', 'ui.bootstrap.typeahead',
    'ui.bootstrap.datepicker', 'ui.bootstrap.tpls', 'ui.bootstrap.tooltip', 'ui.bootstrap.modal', 'ngFileUpload', 'http-auth-interceptor',
    'angularUtils.directives.dirPagination', 'ui.bootstrap.dropdown']);

angular.module('app.controllers', ['ngMessages', 'angular-oauth2']);
angular.module('app.filters', []);
angular.module('app.directives', []);
angular.module('app.services', ['ngResource']);

app.provider('appConfig', ['$httpParamSerializerProvider', function ($httpParamSerializerProvider) {
    var config = {
        baseUrl: 'http://localhost:8000',
        project: {
            status: [
                {value: 1, label: 'Não Iniciado'},
                {value: 2, label: 'Iniciado'},
                {value: 3, label: 'Concluído'}
            ]
        },
        projectTask: {
            status: [
                {value: 1, label: 'Imcompleta'},
                {value: 2, label: 'Completa'}
            ]
        },
        urls: {
            projectFile: '/project/{{id}}/file/{{idFile}}'
        },
        utils: {
            transformRequest: function (data) {
                if (angular.isObject(data)) {
                    return $httpParamSerializerProvider.$get()(data);
                }
                return data;
            },
            transformResponse: function (data, headers) {
                var headersGetter = headers();
                if (headersGetter['content-type'] == 'application/json' || headersGetter['content-type'] == 'text/json') {
                    var dataJson = JSON.parse(data);
                    if (dataJson.hasOwnProperty('data') && Object.keys(dataJson).length == 1 ) {
                        dataJson = dataJson.data;
                    }
                    return dataJson;
                }
                return data;
            }
        }
    };

    return {
        config: config,
        $get: function () {
            return config;
        }
    }
}]);

app.config(['$routeProvider', '$httpProvider', 'OAuthProvider', 'OAuthTokenProvider', 'appConfigProvider', function ($routeProvider, $httpProvider, OAuthProvider, OAuthTokenProvider, appConfigProvider) {
    $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
    $httpProvider.defaults.headers.put['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
    $httpProvider.defaults.transformRequest = appConfigProvider.config.utils.transformRequest;
    $httpProvider.defaults.transformResponse = appConfigProvider.config.utils.transformResponse;

    $httpProvider.interceptors.splice(0, 1);
    $httpProvider.interceptors.splice(0, 1);

    $httpProvider.interceptors.push('oauthFixInterceptor');

    $routeProvider
        .when('/login', {
            templateUrl: 'build/views/login.html',
            controller: 'LoginController'
        })
        .when('/logout', {
            resolve: {
                logout: ['$location', 'OAuthToken', function ($location, OAuthToken) {
                    OAuthToken.removeToken();
                    return $location.path('/');
                }]
            }
        })
        .when('/home', {
            templateUrl: 'build/views/home.html',
            controller: 'HomeController'
        })
        .when('/clients', {
            templateUrl: 'build/views/client/list.html',
            controller: 'ClientListController'
        })
        .when('/client/new', {
            templateUrl: 'build/views/client/new.html',
            controller: 'ClientNewController'
        })
        .when('/client/:id/edit', {
            templateUrl: 'build/views/client/edit.html',
            controller: 'ClientEditController'
        })
        .when('/client/:id/remove', {
            templateUrl: 'build/views/client/remove.html',
            controller: 'ClientRemoveController'
        })
        .when('/client/:id/detail', {
            templateUrl: 'build/views/client/detail.html',
            controller: 'ClientDetailController'
        })
        .when('/projects', {
            templateUrl: 'build/views/project/list.html',
            controller: 'ProjectListController'
        })
        .when('/project/new', {
            templateUrl: 'build/views/project/new.html',
            controller: 'ProjectNewController'
        })
        .when('/project/:id/edit', {
            templateUrl: 'build/views/project/edit.html',
            controller: 'ProjectEditController'
        })
        .when('/project/:id/remove', {
            templateUrl: 'build/views/project/remove.html',
            controller: 'ProjectRemoveController'
        })
        .when('/project/:id/notes', {
            templateUrl: 'build/views/project-note/list.html',
            controller: 'ProjectNoteListController'
        })
        .when('/project/:id/note/:idNote/show', {
            templateUrl: 'build/views/project-note/show.html',
            controller: 'ProjectNoteShowController'
        })
        .when('/project/:id/note/new', {
            templateUrl: 'build/views/project-note/new.html',
            controller: 'ProjectNoteNewController'
        })
        .when('/project/:id/note/:idNote/edit', {
            templateUrl: 'build/views/project-note/edit.html',
            controller: 'ProjectNoteEditController'
        })
        .when('/project/:id/note/:idNote/remove', {
            templateUrl: 'build/views/project-note/remove.html',
            controller: 'ProjectNoteRemoveController'
        })
        .when('/project/:id/tasks', {
            templateUrl: 'build/views/project-task/list.html',
            controller: 'ProjectTaskListController'
        })
        .when('/project/:id/task/:idTask/show', {
            templateUrl: 'build/views/project-task/show.html',
            controller: 'ProjectTaskShowController'
        })
        .when('/project/:id/task/new', {
            templateUrl: 'build/views/project-task/new.html',
            controller: 'ProjectTaskNewController'
        })
        .when('/project/:id/task/:idTask/edit', {
            templateUrl: 'build/views/project-task/edit.html',
            controller: 'ProjectTaskEditController'
        })
        .when('/project/:id/task/:idTask/remove', {
            templateUrl: 'build/views/project-task/remove.html',
            controller: 'ProjectTaskRemoveController'
        })
        .when('/project/:id/files', {
            templateUrl: 'build/views/project-file/list.html',
            controller: 'ProjectFileListController'
        })
        .when('/project/:id/file/new', {
            templateUrl: 'build/views/project-file/new.html',
            controller: 'ProjectFileNewController'
        })
        .when('/project/:id/file/:idFile/edit', {
            templateUrl: 'build/views/project-file/edit.html',
            controller: 'ProjectFileEditController'
        })
        .when('/project/:id/file/:idFile/remove', {
            templateUrl: 'build/views/project-file/remove.html',
            controller: 'ProjectFileRemoveController'
        })
        .when('/project/:id/members', {
            templateUrl: 'build/views/project-member/list.html',
            controller: 'ProjectMemberListController'
        })
        .when('/project/:id/member/:idProjectMember/remove', {
            templateUrl: 'build/views/project-member/remove.html',
            controller: 'ProjectMemberRemoveController'
        });


    OAuthProvider.configure({
        baseUrl: appConfigProvider.config.baseUrl,
        clientId: 'appid01',
        clientSecret: 'secret',
        grantPath: '/oauth/access_token',
    });

    OAuthTokenProvider.configure({
        name: 'token',
        options: {
            secure: false
        }
    })
}]);

app.run(['$rootScope', '$location', '$http', '$modal', 'httpBuffer', 'OAuth', function ($rootScope, $location, $http, $modal, httpBuffer, OAuth) {

    $rootScope.$on('$routeChangeStart', function (event, next, current) {
        if (next.$$route.originalPath != '/login') {
            if (!OAuth.isAuthenticated()) {
                $location.path('/login');
            }
        }
    });

    $rootScope.$on('oauth:error', function (event, data) {
        // Ignore `invalid_grant` error - should be catched on `LoginController`.
        if ('invalid_grant' === data.rejection.data.error) {
            return;
        }

        // Refresh token when a `invalid_token` error occurs.
        if ('access_denied' === data.rejection.data.error) {
            httpBuffer.append(data.rejection.config, data.deferred);

            if(!$rootScope.loginModalOpened){

                var modalInstance = $modal.open({
                    templateUrl: 'build/views/templates/loginModal.html',
                    controller: 'LoginModalController'
                });

                $rootScope.loginModalOpened = true;

            }
            return;
        }

        return $location.path('login');
    });

}]);
