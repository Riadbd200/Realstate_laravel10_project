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
  
                    <h6 class="card-title">Update Site Setting</h6>

                    <form method="POST" action="{{route('update.site.setting')}}" class="forms-sample" id="myForm" enctype="multipart/form-data">

                      @csrf 
                      <input type="hidden" name="id" value="{{$siteSetting->id}}">
                      
                        <div class="form-group mb-3">
                            <label for="" class="form-label">Support Phone</label>
                            <input type="text" name="support_phone" value="{{$siteSetting->support_phone}}" class="form-control"  autocomplete="off">
                        </div>

                        <div class="form-group mb-3">
                            <label for="" class="form-label">Company Address</label>
                            <input type="text" name="company_address" value="{{$siteSetting->company_address}}" class="form-control"  autocomplete="off">
                        </div>

                        <div class="form-group mb-3">
                            <label for="" class="form-label">Email</label>
                            <input type="email" name="email" value="{{$siteSetting->email}}" class="form-control"  autocomplete="off">
                        </div>

                        <div class="form-group mb-3">
                            <label for="" class="form-label">Facebook</label>
                            <input type="text" name="facebook" value="{{$siteSetting->facebook}}" class="form-control"  autocomplete="off">
                        </div>

                        <div class="form-group mb-3">
                            <label for="" class="form-label">Twitter</label>
                            <input type="text" name="twitter" value="{{$siteSetting->twitter}}" class="form-control"  autocomplete="off">
                        </div>

                        <div class="form-group mb-3">
                            <label for="" class="form-label">About Us</label>
                            <textarea name="about" class="form-control"  rows="5">{{$siteSetting->about}}</textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label for="" class="form-label">Copyright</label>
                            <input type="text" name="copyright" value="{{$siteSetting->copyright}}" class="form-control"  autocomplete="off">
                        </div>
                        
                        <div class="mb-3">
                            <label for="exampleInputUsername1" class="form-label">Logo</label>
                           <input type="file" name="logo" class="form-control" id="image">
                        </div>

                        <div class="mb-3">
                            <label for="exampleInputUsername1" class="form-label"></label>
                            <img  
                             id="showImage" class="wd-80 rounded-circle"
                            src="{{asset($siteSetting->logo)}}" alt="profile">
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