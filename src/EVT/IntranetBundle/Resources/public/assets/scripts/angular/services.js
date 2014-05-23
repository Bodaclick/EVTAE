var psPostingServices = angular.module('psPostingServices', ['ngResource']);

psPostingServices.factory('Brand', ['$resource',
    function($resource){
        return $resource('js/resources/brands.json', {}, {
            query: {method:'GET', params:{}, isArray:true}
        });
    }]);
