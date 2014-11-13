var module = angular.module('blogServices', ['ngResource']);


module.factory('FeedLoader', function ($resource) {
    
    return $resource('http://ajax.googleapis.com/ajax/services/feed/load', {}, {
        fetch: { method: 'JSONP', params: {v: '1.0', callback: 'JSON_CALLBACK'} }
    });
});

module.service('BlogList', function ($rootScope, FeedLoader, portalFactory) {
    var feeds = []; 
    this.get = function() {
        portalFactory.get(function(data) {     
            if (feeds.length === 0) {
                for (var i=0; i<data.length; i++) {
                    
                    FeedLoader.fetch({q: data[i].url, num: 10}, {}, function (newdata) {
                        var feed = newdata.responseData.feed;
                        console.log(feed);
                        feeds.push(feed);
                    });
                }
            }
                      
        });
       return feeds;  
    };
});