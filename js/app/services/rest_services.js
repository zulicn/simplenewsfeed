var module = angular.module('restServices', ['ngResource']);

module.factory('usersFactory', function ($resource) { 
    return $resource('https://simplenewsfeed.herokuapp.com/rest/services.php/user', {}, {
        create: { method: 'POST' }, 
        getLoggedUser: { method:'GET', isArray: false},
        updateUser: {method:'PUT'}
    })

});

module.factory('userFactory', function ($resource) { 
    return $resource('https://simplenewsfeed.herokuapp.com/rest/services.php/login', {}, {
        login: { method: 'POST'}, 
        logout: {method: 'GET', params: {unset: 'true'}}
    })

});

module.factory('portalFactory', function ($resource) { 
    return $resource('https://simplenewsfeed.herokuapp.com/rest/services.php/portal/:id', {}, {
        create: { method: 'POST'}, 
        get: { method:'GET' , isArray: true}, 
        deleteById: { method:'DELETE', params: {id: '@id'}}
    })
});

module.config(function ($httpProvider) {
    $httpProvider.defaults.transformRequest = function (data) {
        var str = [];
        for (var p in data) {
            data[p] !== undefined && str.push(encodeURIComponent(p) + '=' + encodeURIComponent(data[p]));
        }
        return str.join('&');
    };
    $httpProvider.defaults.headers.put['Content-Type'] = $httpProvider.defaults.headers.post['Content-Type'] =
        'application/x-www-form-urlencoded; charset=UTF-8';
});