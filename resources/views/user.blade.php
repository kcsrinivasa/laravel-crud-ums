<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>User Management System</title>

	<!-- style -->
    <link rel="stylesheet" href="{{ asset('public/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/css/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/css/datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/css/sweetalert.min.css') }}">

    <!-- script -->

    <script src="{{ asset('public/js/jquery.min.js')}}"></script>
    <script src="{{ asset('public/js/bootstrap.min.js')}}"></script>
    <script src="{{ asset('public/js/toastr.min.js')}}"></script>
    <script src="{{ asset('public/js/datepicker.min.js') }}"></script>
    <script src="{{ asset('public/js/sweetalert.min.js') }}"></script>
    <script type="text/javascript">
    	/* toastr options */
        toastr.options = {
            "closeButton" : true,
            "progressBar" : true,
            // "fadeIn": 300,
            "fadeOut":5000,
            "extendedTimeOut": 1000,
              // "iconClass": 'toast-info',
            "positionClass": 'toast-top-right',
            "timeOut": 8000, // Set timeOut to 0 to make it sticky
            // "showMethod": "slideDown",
            // "hideMethod": "slideUp"
        };
    </script>
</head>
<body>
<div class="container mt-5">

	<!-- error message -->
	@if($errors->any())
		@foreach($errors->all() as $error)
			<script type="text/javascript">
				toastr.error("{!! $error !!}",'Error').delay(5000).fadeOut(4000);
			</script>
		@endforeach
	@endif

	@if(session()->has('error'))
		<script type="text/javascript">
			toastr.error("{!! session()->get('error') !!}", 'Error');
		</script>
	@endif

	<!-- success message -->
	@if(session()->has('success'))
		<script type="text/javascript">
			toastr.success("{!! session()->get('success') !!}", 'Success');
		</script>
	@endif


	<div class="card">
		<div class="card-header bg-info text-white">
			User Records
			<button type="button" class="btn btn-sm btn-dark float-right" data-toggle="modal" data-target="#addUserModal">&plus; Add new</button>
		</div>
		<div class="card-body">
			<table class="table table-borderd">
				<thead>
					<tr>
						<th>Avatar</th>
						<th>Name</th>
						<th>Email</th>
						<th>Experience</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					@foreach($users as $user)
						<tr>
							<td>
								<img src="{{ ($user->profile)?asset('public/'.$user->profile):asset('public/images/profile.png')}}" width="50">
							</td>
							<td>{{$user->name}}</td>
							<td>{{$user->email}}</td>
							<td>{{$user->experience}}</td>
							<td>
								<div class="d-flex">
									<button type="button" class="btn btn-sm btn-secondary mx-2" onclick="getUserData({{$user->id}})">&#9998; Edit</button>
									<form action="{{route('user.destroy',$user->id)}}" method="POST">
										@csrf @method('DELETE')
										<button type="submit" class="btn btn-sm btn-danger sweetalertDelete">&times; Remove</button>
									</form>
								</div>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div><!-- container end -->

<!-- modal start -->
<!-- Modal -->
<div class="modal fade" id="addUserModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add new record</h5>
        <button type="button" class="close" data-dismiss="modal" >
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      	<div class="modal-body">
      	<form action="{{route('user.store')}}" method="POST" enctype="multipart/form-data" id="addUserForms">
      		@csrf
      		<div class="row form-group">
      			<div class="col-md-3">Email <span class="text-danger">*</span></div>
      			<div class="col-md-9">
      				<input type="email" name="email" class="form-control" placeholder="Email">
      			</div>
      		</div><!-- row end -->

      		<div class="row form-group">
      			<div class="col-md-3">Name <span class="text-danger">*</span></div>
      			<div class="col-md-9">
      				<input type="text" name="name" class="form-control" placeholder="Name">
      			</div>
      		</div><!-- row end -->

      		<div class="row form-group">
      			<div class="col-md-3">Date of Joining <span class="text-danger">*</span></div>
      			<div class="col-md-6">
      				<input type="text" name="doj" class="form-control datepicker" placeholder="dd-mm-yyyy" autocomplete="off">
      			</div>
      		</div><!-- row end -->

      		<div class="row form-group">
      			<div class="col-md-3">Date of Leaving</div>
      			<div class="col-md-6">
      				<input type="text" name="dol" class="form-control datepicker" placeholder="dd-mm-yyyy" autocomplete="off"> 
      			</div>
      			<div class="col-md-3">
      				<label><input type="checkbox" name="stillWorking" value="1"> Still working</label>
      			</div>
      		</div><!-- row end -->

      		<div class="row form-group">
      			<div class="col-md-3">Upload Image</div>
      			<div class="col-md-9">
      				<input type="file" name="profile" class="form-control" placeholder="profile">
      			</div>
      		</div><!-- row end -->
      		<div class="text-center  form-group">
      			<button type="submit" class="btn btn-sm btn-success sweetalertAdd">&#10004; Save</button>
      		</div>
      	</form>
		</div><!-- modal body end -->

    </div>
  </div>
</div><!-- add user modal end -->
<!-- Modal -->
<div class="modal fade" id="updateUserModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Update new record</h5>
        <button type="button" class="close" data-dismiss="modal" >
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      	<div class="modal-body">
      	<form action="" method="POST" enctype="multipart/form-data" id="updateUserForm">
      		@csrf @method('PUT')
      		<div class="row form-group">
      			<div class="col-md-3">Email <span class="text-danger">*</span></div>
      			<div class="col-md-9">
      				<input type="email" name="email" id="update_email" class="form-control" placeholder="Email">
      			</div>
      		</div><!-- row end -->

      		<div class="row form-group">
      			<div class="col-md-3">Name <span class="text-danger">*</span></div>
      			<div class="col-md-9">
      				<input type="text" name="name" id="update_name" class="form-control" placeholder="Name">
      			</div>
      		</div><!-- row end -->

      		<div class="row form-group">
      			<div class="col-md-3">Date of Joining <span class="text-danger">*</span></div>
      			<div class="col-md-6">
      				<input type="text" name="doj" id="update_doj" class="form-control datepicker" placeholder="dd-mm-yyyy" autocomplete="off">
      			</div>
      		</div><!-- row end -->

      		<div class="row form-group">
      			<div class="col-md-3">Date of Leaving</div>
      			<div class="col-md-6">
      				<input type="text" name="dol" id="update_dol" class="form-control datepicker" placeholder="dd-mm-yyyy" autocomplete="off">
      			</div>
      			<div class="col-md-3">
      				<label><input type="checkbox" name="stillWorking"  id="update_stillWorking" value="1"> Still working</label>
      			</div>
      		</div><!-- row end -->

      		<div class="row form-group">
      			<div class="col-md-3">Upload Image</div>
      			<div class="col-md-9">
      				<input type="file" name="profile" class="form-control" placeholder="profile">
      			</div>
      		</div><!-- row end -->
      		<div class="text-center  form-group">
      			<button type="submit" class="btn btn-sm btn-success sweetalertUpdate">&#10004; Update</button>
      			<input type="hidden" name="user_id" id="update_user_id">
      		</div>
      	</form>
		</div><!-- modal body end -->

    </div>
  </div>
</div><!-- update user modal end -->

<!-- modal end -->

<script type="text/javascript">
	$('.datepicker').datepicker({
		'format':'dd-mm-yyyy'
	})


	$(function() {
      $("#addUserForm").on('submit',function(e) {
          //prevent Default functionality
          e.preventDefault();
        $(this).find(':button[type=submit]').attr("disabled", true);
            $.ajax({
                url: "{!! route('user.store'); !!}",
                type: 'POST', //this is your method
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
		            cache: false,
		            processData:false,
		          // contentType: false,
                success: function(response){
                    console.log(response);
                  $('#addUserForm').find(':button[type=submit]').attr("disabled", false);
                    if(response.error){
                        for(let index = 0;index<response.error.length; ++index){
                        	toastr.error(response.error[index], 'Error');
                        }
                    }
                    if(response.message){
                        toastr.success(response.message, 'Success');
                      setTimeout(function(){ location.reload(); },2000);
                    }
                },
                error:function(request, status, error){
                  $('#addUserForm').find(':button[type=submit]').attr("disabled", false);
                  var response = JSON.parse(request.responseText);
                  $.each( response.errors, function( key, value) {
                      toastr.error(value, 'Error');
                  });
                }
            });
      });/* end add record end*/
    });

	/* get record*/
    function getUserData(user_id){
    	// alert(user_id);
    	$.ajax({
          url: "{!! route('user.index'); !!}/"+user_id,
          type: 'GET', //this is your method
          data: {user_id:user_id},
          dataType: 'json',
          contentType: false,
          cache: false,
          processData:false,
        // contentType: false,
          success: function(response){
              console.log(response);
            
              if(response.error){
                  for(let index = 0;index<response.error.length; ++index){
                  	toastr.error(response.error[index], 'Error');
                  }
              }
              if(response.user){
              	var user = response.user;
              		$('#update_user_id').val(user.id)
                  $('#update_name').val(user.name);
                  $('#update_email').val(user.email);
                  if(user.doj){$('#update_doj').datepicker("setDate", new Date(user.doj));}else{$('#update_doj').val('');}
                  if(user.dol){$('#update_dol').datepicker("setDate", new Date(user.dol));}else{$('#update_dol').val('');}
                  if(user.stillWorking){$('#update_stillWorking').prop('checked',true);}else{$('#update_stillWorking').prop('checked',false);}
                  

                  $('#updateUserModal').modal('show');
              }else{
              	toastr.error('Error occured', 'Error');
              }
          },
          error:function(request, status, error){
            var response = JSON.parse(request.responseText);
            $.each( response.errors, function( key, value) {
                toastr.error(value, 'Error');
            });
          }
      });
    }/*end get record*/


	$(function() {
      $("#updateUserForm").on('submit',function(e) {
          //prevent Default functionality
          e.preventDefault();
        $(this).find(':button[type=submit]').attr("disabled", true);
            $.ajax({
                url: "{!! route('user.index'); !!}/"+$('#update_user_id').val(),
                type: 'POST', //this is your method
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
		            cache: false,
		            processData:false,
		          // contentType: false,
                success: function(response){
                    console.log(response);
                  $('#updateUserForm').find(':button[type=submit]').attr("disabled", false);
                    if(response.error){
                        for(let index = 0;index<response.error.length; ++index){
                        	toastr.error(response.error[index], 'Error');
                        }
                    }
                    if(response.message){
                        toastr.success(response.message, 'Success');
                      setTimeout(function(){ location.reload(); },2000);
                    }
                },
                error:function(request, status, error){
                  $('#updateUserForm').find(':button[type=submit]').attr("disabled", false);
                  var response = JSON.parse(request.responseText);
                  $.each( response.errors, function( key, value) {
                      toastr.error(value, 'Error');
                  });
                }
            });
      });/* end update record end*/
    });

	$(function(){
		$(".sweetalertAdd").on('click', function(event){
        event.preventDefault();
			var form = $(this).closest("form");
			swal({
					title: "Are You sure?",
					text: "Do you want to add this record. Please ensure and then confirm!",
					type: "info",
					showCancelButton: !0,
					confirmButtonText: "Yes, add!",
					cancelButtonText: "No, cancel!",
					reverseButtons: !0
				}).then(function (e) {
						if(e.value === true) { form.submit(); } else { e.dismiss; }
					}, function (dismiss) {
					return false;
				});
		});
		$(".sweetalertUpdate").on('click', function(event){
        event.preventDefault();
			var form = $(this).closest("form");
			swal({
					title: "Are You sure?",
					text: "Do you want to update this record. Please ensure and then confirm!",
					type: "info",
					showCancelButton: !0,
					confirmButtonText: "Yes, update!",
					cancelButtonText: "No, cancel!",
					reverseButtons: !0
				}).then(function (e) {
						if(e.value === true) { form.submit(); } else { e.dismiss; }
					}, function (dismiss) {
					return false;
				});
		});
		 $(".sweetalertDelete").on('click', function(event){
        event.preventDefault();
			var form = $(this).closest("form");
			swal({
					title: "Are You sure?",
					text: "Do you want to delete this record. Please ensure and then confirm!",
					type: "warning",
					showCancelButton: !0,
					confirmButtonText: "Yes, delete!",
					cancelButtonText: "No, cancel!",
					reverseButtons: !0
				}).then(function (e) {
						if(e.value === true) { form.submit(); } else { e.dismiss; }
					}, function (dismiss) {
					return false;
				});
		});

		
	});
</script>
</body>
</html>