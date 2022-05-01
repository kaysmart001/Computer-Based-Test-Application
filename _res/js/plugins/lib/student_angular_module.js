var admin = angular.module('exam-app', []);

admin.controller('PageController', function($scope){
    
    $scope.data = {
        "users": users,
        "sessions": sessions 
    };
    
    //console.log($scope.data['users']);
    
    $scope.current = null;
    
    $scope.getData = function(idx){
        var dat;
        
        $scope.current = null;
        dat = _.findWhere($scope.data['users'], {'id': idx});
        
        $scope.current = angular.copy(dat);
        
        console.log($scope.current);
        
        
    }
    
 
    
});

