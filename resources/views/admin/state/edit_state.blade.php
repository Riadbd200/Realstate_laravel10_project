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
  
                    <h6 class="card-title"> Update State</h6>

                    <form method="POST" action="{{route('update.state')}}" class="forms-sample" enctype="multipart/form-data">

                      @csrf 

                      <input type="hidden" name="id" value="{{$editState->id}}">
                      
                        <div class="mb-3">
                            <label for="type_name" class="form-label">State Name</label>
                            <input type="text" name="state_name" value="{{$editState->state_name}}" class="form-control" id="state_name" autocomplete="off">
                        </div>

                        <div class="mb-3">
                            <label for="exampleInputUsername1" class="form-label">State Photo</label>
                           <input type="file" name="state_image" class="form-control" id="image">
                        </div>

                        <div class="mb-3">
                            <label for="exampleInputUsername1" class="form-label"></label>
                            <img  
                             id="showImage" class="wd-80 rounded-circle"
                            src="{{asset($editState->state_image)}}" alt="profile">
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

        <!-- Preview image before update -->
        <script type="text/javascript">
            $(document).ready(function(){
                $('#image').change(function(e){   
                    var reader = new FileReader();
                    reader.onload = function(e){
                        $('#showImage').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(e.target.files['0']);
                })
            });
        </script>

@endsection