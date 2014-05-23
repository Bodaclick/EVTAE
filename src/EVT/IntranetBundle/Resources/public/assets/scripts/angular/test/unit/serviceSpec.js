describe("ShowroomService", function(){

    beforeEach(angular.mock.module('editShowroom'));
    var service, $httpBackend;

    beforeEach(function () {
        angular.mock.inject(function ($injector) {
            $httpBackend = $injector.get('$httpBackend');
            service = $injector.get('ShowroomService');
        })
    });

    describe("getShowroomById", function(){

        it("should make an ajax call to /api/showrooms/13", function(){
            $httpBackend.whenGET("/api/showrooms/13").respond([{evt_id:13, name: "banana", description: "Ejemplo de showroom 35"}]);
            expect(service.getShowroomById()).toBeDefined();
        });

        it("should check that get showroom details are correct", function(){
            var res = {"id":1,"evt_id":13,"name":"Showroom 36","description":"Ejemplo de showroom 35","state":1};
            var res2 = null;
            $httpBackend.expectGET("/api/showrooms/13").respond(res);

            service.getShowroomById({id: 13}, function(data) {
                res2 = data;
            });

            $httpBackend.flush();
            expect(res2 instanceof Object).toBeTruthy();
            expect(res2.name).toBe(res.name);
        });
    });

});
