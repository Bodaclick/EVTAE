'use strict';

describe('editShowroom controllers', function() {

    beforeEach(module('editShowroom'));
    beforeEach(module('editShowroomServices'));
    var service, $httpBackend;

    beforeEach(function () {
        angular.mock.inject(function ($injector) {
            $httpBackend = $injector.get('$httpBackend');
            service = $injector.get('ShowroomService');
        })
    });

    describe('editShowroomCtrl', function(){
        var scope, ctrl;

        it("should make an ajax call to get a showroom object", function(){
            var res = {"id":1,"evt_id":13,"name":"Showroom 36","description":"Ejemplo de showroom 35","state":1};
            $httpBackend.expectGET("/api/showrooms/13").respond(res);

            expect(res instanceof Object).toBeTruthy();
            expect(service.getShowroomById()).toBeDefined();
        });

        it("expects PATCH http call and returns mock data", inject(function ($http) {
            var url = '/api/showrooms/13/name',
                data = '{name: "TestName"}',
                header = {'Content-Type': 'application/json'},
                successCallback = jasmine.createSpy('success'),
                errorCallback = jasmine.createSpy('error');

            // Create expectation
            // headers is a unction that receives http header object and returns true
            // if the headers match the current expectation.
            $httpBackend.expectPATCH(url, data, function(headers) {}).respond(204, 'Ok');

            // Call http service
            $http({method: 'PATCH', url: url, data: data}).success(successCallback).error(errorCallback);

            // flush response
            $httpBackend.flush();

            // Verify expectations
            expect(successCallback.mostRecentCall.args).toContain('Ok');
            expect(successCallback.mostRecentCall.args).toContain(204);
        }));

        /*it("should make a patch to change showroom state to MODIFIED", function(){
            $httpBackend.expectPATCH("/api/showrooms/13/name", {name: "testName"}).respond(204, '');
        });

        it("should make a patch to change showroom state to MODIFIED", function(){
            $httpBackend.expectPATCH("/api/showrooms/13/description", {description: "testDescription"}).respond(204, '');
        });

        // The injector ignores leading and trailing underscores here (i.e. _$httpBackend_).
        // This allows us to inject a service but then attach it to a variable
        // with the same name as the service.
        /*beforeEach(inject(function(_$httpBackend_, $rootScope, $controller) {
            $httpBackend = _$httpBackend_;
            $httpBackend.when('phones/phones.json').
                respond([{name: 'Nexus S'}, {name: 'Motorola DROID'}]);

            scope = $rootScope.$new();
            ctrl = $controller('PhoneListCtrl', {$scope: scope});

       /* beforeEach(inject(function(_$httpBackend_, $rootScope, $controller) {
            $httpBackend = _$httpBackend_;
            $httpBackend.expect('GET', '/api/showrooms/13/')
                .respond({
                    "evt_id": 13,
                    "name": "name",
                    "description": "description"
                });

            scope = $rootScope.$new();
            ctrl = $controller("editShowroomCtrl", {$scope: scope});
        }));

        console.info(scope);*/

        /*
        it('should return data for showroom with id 13', function() {

            console.info(scope.evtName);
            expect(scope.data).toEqualData([]);
            $httpBackend.flush();

            expect(scope.phones).toEqualData(
                [{name: 'Nexus S'}, {name: 'Motorola DROID'}]);
        });*/
    });
});
