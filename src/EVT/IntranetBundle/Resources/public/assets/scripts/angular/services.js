var editShowroomServices = angular.module('editShowroomServices', ['ngResource']);

editShowroomServices.factory('ShowroomService', ['$resource',
    function($resource){
        return $resource(':url', {}, {
            getShowroomById:{
                url:'/api/showrooms/:id',
                method:'GET',
                params: {id:'id'},
                isArray:false
            }
        });
    }
]);