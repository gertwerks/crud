'use strict';

// Controllers

var app = angular.module('myApp.controllers', [
    'ng',
    'ngResource',   
    'myApp.services'    
]);

// Controller for Users page
app.controller('UsersController', function($resource, $scope, $location, $route) {  

    var Users = $resource('/api/users'); 

    // Get Users from API
    $scope.users = Users.query();
    
    // Delete a User then relaod view
    $scope.deleteUser = function(userId) {
        
        var User = $resource('/api/users/:id', { id: userId });    
    
        User.delete( 
            function() {
                // success  
                $route.reload();             
            },
            function(error) {
                // error
                console.log(error);
            }
        );
    } 



});


// Controller for User pages
app.controller('UserController', function($routeParams, $resource, $scope, $location, $window) {   

    var userId = $routeParams.id;
    
    if (userId) {
        var User = $resource('/api/users/:id', { id: userId });
        
        // Get User from API
        $scope.user = User.get();
    }   


    
    // Create a User
    $scope.createUser = function() {
    
        var Users = $resource('/api/users');       
    
        if ($scope.userForm.$valid) {
            Users.save($scope.user, 
                function() {
                    // success
                    $location.path('/users');                     
                },
                function(error) {
                    // error
                    console.log(error);
                }               
            );
        }      
    }
    
    // Update User details
    $scope.updateUser = function() {
    
        var User = $resource('/api/users/:id', { id: userId }, { update: { method:'PUT' } });    
    
       
            User.update($scope.user, 
                function() {
                    // success
                    $location.path('/users');                     
                });
        
    }
    
    // Delete a User
    $scope.deleteUser = function(userId) {
        
        var User = $resource('/api/users/:id', { id: userId });    
    
        User.delete( 
            function() {
                // success
                $location.path('/users');                     
            },
            function(error) {
                // error
                console.log(error);
            }
        );
    }


    
    // Go back in history
    $scope.goBack = function() {
    
        $window.history.back();
    };       
    
});

// Controller for products page
app.controller('ProductController', function($routeParams, $resource, $scope, $location, $route, $window ) {
       
         var Products = $resource('/api/products'); 
     
          // Get Products from API
          $scope.products = Products.query();

          $scope.goBack = function() {
        
          $window.history.back();
    };  

    // // Method using $http.get()
    // $http.get('/api/products')
    //     .success(
    //         function(data, status, headers, config) {
    //             $scope.products = data;
    //             console.log(data);
    //         })
    //     .error(
    //         function(data, status, headers, config) {
            
    //             $scope.status = status;
    //         });    

});




// Controller for User pages
app.controller('AddProductController', function($routeParams, $resource, $scope, $location, $window) {   

    var productId = $routeParams.id;
    
    if (productId) {
        var Product = $resource('/api/product/s:id', { id: productId });
        
        // Get User from API
        $scope.product = Product.get();
    }   


    
    // Create a User
    $scope.addProduct = function() {
    
        var Product = $resource('/api/products');       
    
        if ($scope.productForm.$valid) {
            Product.save($scope.product, 
                function() {
                    // success

                    $location.path('/products');                     
                },
                function(error) {
                    // error
                    console.log(error);
                }               
            );
        }      
    }
    
    // Update User details
    // $scope.updateProduct = function() {
    
    //     var Product = $resource('/api/products/:id', { id: productId }, { update: { method:'PUT' } });    
    
       
    //         Product.update($scope.product, 
    //             function() {
    //                 // success
    //                 $location.path('/users');                     
    //             });
        
    // }
    
    // Delete a User
    // $scope.deleteUser = function(userId) {
        
    //     var User = $resource('/api/users/:id', { id: userId });    
    
    //     User.delete( 
    //         function() {
    //             // success
    //             $location.path('/users');                     
    //         },
    //         function(error) {
    //             // error
    //             console.log(error);
    //         }
    //     );
    // }


    
    // Go back in history
    $scope.goBack = function() {
    
        $window.history.back();
    };       
    
});