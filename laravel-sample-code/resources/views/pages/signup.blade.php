@extends('layouts.master')
@section('title', 'Sign Up')
@section('content')

<div class="box box-info">
	<div class="box-header with-border">
        <h3 class="box-title">Sign Up</h3>
    </div>

<div class="container">
    <div class="row">
            <div class="col-md-12" ng-app="signupApp" ng-controller="signupCtrl">

                <form name="form" ng-submit="user.signup()" action="" method="post" enctype="multipart/form-data" role="form">
                    <div class="form-group">
                        <label for="firstname">First Name</label>
                        <input type="text" name="firstname" id="firstname" class="form-control" ng-model="user.firstname" required />
                        <span ng-show="form.firstname.$dirty && form.firstname.$error.required" class="hint">First name is required</span>
                    </div>
                    <div class="form-group">
                        <label for="lastname">Last Name</label>
                        <input type="text" name="lastname" id="lastname" class="form-control" ng-model="user.lastname" required />
                        <span ng-show="form.lastname.$dirty && form.lastname.$error.required" class="hint">Last name is required</span>
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" class="form-control" ng-model="user.username" required />
                        <span ng-show="form.username.$dirty && form.username.$error.required" class="hint">Username is required and valid</span>
                    </div>
                    <div class="form-group">
                        <label for="usermail">Email</label>
                        <input type="email" name="usermail" id="usermail" class="form-control" ng-model="user.usermail" required />
                        <span ng-show="form.usermail.$dirty && form.usermail.$error.required" class="hint">Email is required and valid</span>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control" ng-model="user.password" required />
                        <span ng-show="form.password.$dirty && form.password.$error.required" class="hint">Password is required</span>
                    </div>
                    <div class="form-group">
                        <label for="cnfpassword">Confirm Password</label>
                        <input type="password" name="cnfpassword" id="cnfpassword" class="form-control" ng-model="user.cnfpassword" required />
                        <span ng-show="form.cnfpassword.$dirty && form.cnfpassword.$error.required" class="hint">Confirm Password is required</span>
                    </div>
                    <div class="form-group">
                        <button type="submit" ng-disabled="form.$invalid || ajaxLoading" class="btn btn-primary">Sign Up</button>
                        <a href="/" class="btn btn-danger">Cancel</a>
                    </div>
                    <div class="form-group">
						<p class="notes">{{messages}}</p>
                    </div>
                </form>

            </div>

			<script language="javascript" type="text/javascript" >
				var ajaxLoading = false;
				var app = angular.module('signupApp', []);
				app.controller('signupCtrl', function($scope) {
					$scope.user.signup = function() {
						if( $scope.user.password != $scope.user.cnfpassword ) {
							$scope.messages = "Confirm Password is not match with Password.";
							return false;
						}
				
						ajaxLoading = true;
				
						var response = $http.post("/pages/signupsave", $scope.user, {});
				
						response.success(function(data, status, headers, config){
							var json = JSON.parse(data);
							if( json.status == 1 )
								$location.path('/dashboard');
							else {
								$scope.messages = json.message;
								ajaxLoading = false;
							}
						});
				
						response.error(function(data, status, headers, config){
							$scope.messages = "Error on Sign Up.";
							ajaxLoading = false;
						});
				
						return false;
					};
				});
            </script>
    </div>

</div>
</div>

@stop