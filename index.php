<!DOCTYPE html>
<html>
	<head>
		<title>AngularJS Bootstrap Modal</title>
		<script src="jquery.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.15/angular.min.js"></script>
		<script src="jquery.dataTables.min.js"></script>
		<script src="angular-datatables.min.js"></script>
		<script src="bootstrap.min.js"></script>
		<link rel="stylesheet" href="bootstrap.min.css">
		<link rel="stylesheet" href="datatables.bootstrap.css">
		
	</head>
	<body ng-app="crudApp" ng-controller="crudController">
		
		<div class="container" ng-init="fetchData()">
			<br />
				<h3 align="center">Telephone-Directory</h3>
			<br />
			<div class="alert alert-success alert-dismissible" ng-show="success" >
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				{{successMessage}}
			</div>
			<div align="right">
				<button type="button" name="add_button" ng-click="addData()" class="btn btn-success">Add</button>
			</div>
			<br />
			<div class="table-responsive" style="overflow-x: unset;" id="t">
				<table datatable="ng" dt-options="vm.dtOptions" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>ID</th>
							<th>Name</th>
							<th>phonenumber</th>
							<th>Edit</th>
							<th>Delete</th>
						</tr>
					</thead>
					<tbody>
						<tr ng-repeat="name in namesData">
							<td>{{$index+1}}</td>
							<td>{{name.username}}</td>
							<td>{{name.usernumber}}</td>
							<td ng-click="fetchDatas($index)"><a href=""><button type="button"  class="btn btn-warning btn-xs" >Edit</button></a></td>
							<td ng-click="deleteData($index)"><a href=""><button type="button"  class="btn btn-danger btn-xs">Delete</button></a></td>
						</tr>
					</tbody>
				</table>
			</div>

		</div>
	</body>
</html>

<div class="modal fade" tabindex="-1" role="dialog" id="crudmodal">
	<div class="modal-dialog" role="document">
    	<div class="modal-content">
    		<form method="post" ng-submit="submitForm()" id="poki" autocomplete="off">
	      		<div class="modal-header">
	        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        		<h4 class="modal-title">{{modalTitle}}</h4>
	      		</div>
	      		<div class="modal-body">
	      			<div class="alert alert-danger alert-dismissible" ng-show="error" >
						<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
						{{errorMessage}}
					</div>
					<div class="form-group">
						<label>Enter  Contact-ID</label>
						<input type="text" name="cid" ng-model="cid" id="cid" class="form-control" ng-disabled="verify"/>
					</div>
					<div class="form-group">
						<label>Enter  Name</label>
						<input type="text" name="name" ng-model="name" id="name" class="form-control" />
					</div>
					<div class="form-group">
						<label>Enter phone Number</label>
						<input type="text" name="phnumber" ng-model="phnumber" id="phnumber" class="form-control" />
					</div>
	      		</div>
	      		<div class="modal-footer">
	      			<input type="hidden" name="hidden_id" value="{{hidden_id}}" />
	      			<input type="submit" name="submit" id="submit" class="btn btn-info" value="{{submit_button}}" ng-hide="status" />
					<input type="submit" name="submit" id="submits" class="btn btn-info" value="Update" ng-hide="status1" />
	        		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        	</div>
	        </form>
    	</div>
  	</div>
</div>
<script>
	$(document).ready(function(){
		$("#submit").click(function(){
			var cid=document.getElementById("cid").value;
			var name=document.getElementById("name").value;
			var phnumber=document.getElementById("phnumber").value;
			var pattern=/^\d{10}$/g
            var a=pattern.test(phnumber);
			var pattern1=/^\d{1,10}$/g
			var b=pattern1.test(cid);
			if(cid=="" || name=="" || phnumber==""){
				alert("complete All Fields");
			}
			else if(a==false){
				alert("Invalid Mobile Number");
			}
			else if(b==false){
				alert("Invalid C-ID and C-ID must be of number")
			}
			else{
				$.ajax({
					url:"insertrecords.php",
					method:"post",
					async:false,
					data:{
						"cid":cid,
						"name":name,
						"phnumber":phnumber
					},
					success:function(data){
						if(data==="Contact Saved Successfully"){
						$('#poki').hide();
						setTimeout(() => {
							window.location.href="http://localhost/training/angularjs/angularjs1/index.php";		
						},1000);
					  }
					  else if(data==="Contact ID Exists"){
						  alert("Contact ID Exists");
						  
					  }
					  else{
						  alert("Number Already Exists !!!");
					  }
						
					}
				});
			}
		});
	});
</script>
<script>
var app = angular.module('crudApp', ['datatables']);
var config={
	headers:{
		'Content-Type':'application/x-www-form-urlencoded;charset=utf-8;'
	}
}
app.controller('crudController', function($scope, $http,$timeout){

	$scope.success = false;
	$scope.error = false;
	$scope.status1=true;
	$scope.openModal = function(){
		var modal_popup = angular.element('#crudmodal');
		modal_popup.modal('show');
	};

	$scope.closeModal = function(){
		var modal_popup = angular.element('#crudmodal');
		modal_popup.modal('hide');
	};

	$scope.addData = function(){
		$scope.modalTitle = 'Add Data';
		$scope.submit_button = 'Insert';
		$scope.openModal();
	};
	$http.get("http://localhost/training/angularjs/angularjs1/fetchdupe.php").then(function(response){
		$scope.namesData=response.data
		console.log($scope.namesData)
	});
	$scope.deleteData=function(x){
		$http.get("http://localhost/training/angularjs/angularjs1/fetchdupe.php").then(function(responses){
		$scope.deleteid=responses.data[x].Id;
		$http.post("http://localhost/training/angularjs/angularjs1/deleterecords.php",
                {
                    'Id':$scope.deleteid,
                },config
                ).then(function(response){
					$timeout(function(){
						window.location.href="http://localhost/training/angularjs/angularjs1/index.php";
					},1000)
                    
        });
	    });
	}
	$scope.fetchDatas=function(y){
		$scope.status1=false;
		$scope.status=true;
		$scope.modalTitle = 'Add Data';
		$scope.submit_button = 'Insert';
		$scope.openModal();
		$http.get("http://localhost/training/angularjs/angularjs1/fetchdupe.php").then(function(responses){
		$scope.cid=responses.data[y].Id;
		$scope.name=responses.data[y].username;
		$scope.phnumber=responses.data[y].usernumber;
		$scope.verify=true;
		$("#submits").click(function(){
			if($scope.cid!="" || $scope.name!="" || $scope.phnumber!=""){
					$http.post("http://localhost/training/angularjs/angularjs1/updaterecords.php",
							{
								'Id':$scope.cid,
								'username':$scope.name,
								'usernumber':$scope.phnumber
							},config
							).then(function(response){
							if(response.data=="Updated item"){
								$timeout(function(){
									window.location.href="http://localhost/training/angularjs/angularjs1/index.php";
								},1000);
							}
					});
			}

	    });
	  });

	}

});
</script>