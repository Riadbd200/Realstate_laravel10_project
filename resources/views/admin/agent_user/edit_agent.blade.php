@extends('admin.admin_dashboard')

@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>


<div class="page-content">
    <div class="row profile-body">

      <!-- middle wrapper start -->
      <div class="col-md-8 col-xl-8 middle-wrapper">
        <div class="row">
            <div class="card">
                <div class="card-body">
  
                    <h6 class="card-title"> Update Agent</h6>

                    <form method="POST" action="{{route('update.agent')}}" class="forms-sample" id="myForm">

                      @csrf 
                      <input type="hidden" name="id" value="{{$allAgent->id}}">
                      
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Agent Name</label>
                            <input type="text" name="name" class="form-control" id="name" value="{{$allAgent->name}}" autocomplete="off">
                        </div>
                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Agent Email</label>
                            <input type="email" name="email" value="{{$allAgent->email}}" class="form-control" id="email" autocomplete="off">
                        </div>
                        <div class="form-group mb-3">
                            <label for="phone" class="form-label">Agent Phone</label>
                            <input type="text" name="phone" value="{{$allAgent->phone}}" class="form-control" id="phone" autocomplete="off">
                        </div>
                        <div class="form-group mb-3">
                            <label for="address" class="form-label">Agent Address</label>
                            <input type="text" name="address" value="{{$allAgent->address}}" class="form-control" id="address" autocomplete="off">
                        </div>
                       

                        <button type="submit" class="btn btn-primary me-2">Save Changes</button>
                    </form>
  
                </div>
              </div>
         
        </div>
      </div>
      <!-- middle wrapper end -->
      <!-- right wrapper start -->
     
      <!-- right wrapper end -->
    </div>

    </div>

      <!-- validation with javascript  -->
    
    <script type="text/javascript">
        $(document).ready(function (){
            $('#myForm').validate({
                rules: {
                    name: {
                        required : true,
                    }, 
                    email: {
                        required : true,
                    }, 
                    phone: {
                        required : true,
                    }, 
                   
                    password: {
                        required : true,
                    }, 
                    
                },
                messages :{
                    name: {
                        required : 'Please Enter  Name',
                    }, 
                    email: {
                        required : 'Please Enter  Email',
                    }, 
                    phone: {
                        required : 'Please Enter  phone',
                    }, 
                 
                    password: {
                        required : 'Please Enter  password',
                    }, 
                     
    
                },
                errorElement : 'span', 
                errorPlacement: function (error,element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight : function(element, errorClass, validClass){
                    $(element).addClass('is-invalid');
                },
                unhighlight : function(element, errorClass, validClass){
                    $(element).removeClass('is-invalid');
                },
            });
        });
        
    </script>

@endsection