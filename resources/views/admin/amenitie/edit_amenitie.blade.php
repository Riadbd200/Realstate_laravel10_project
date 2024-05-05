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
  
                    <h6 class="card-title"> Add Property Type</h6>

                    <form method="POST" action="{{route('update.amenitie')}}" class="forms-sample" id="myForm">

                      @csrf 

                      <input type="hidden" name="id"  value="{{$amenitie->id}}">
                      
                        <div class="form-group mb-3">
                            <label for="amenitis_name" class="form-label">Amenitie Name</label>
                            <input type="text" name="amenitis_name" value="{{$amenitie->amenitis_name}}" class="form-control" id="amenitis_name" autocomplete="off">
                        </div>

                        <button type="submit" class="btn btn-primary me-2">Update</button>
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
                    amenitis_name: {
                        required : true,
                    }, 
                    
                },
                messages :{
                    amenitis_name: {
                        required : 'Please Enter Amenities Name',
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