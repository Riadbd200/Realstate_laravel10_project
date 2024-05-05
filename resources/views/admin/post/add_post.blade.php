@extends('admin.admin_dashboard')

@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>


<div class="page-content">
    <div class="row profile-body">

      <!-- middle wrapper start -->
      <div class="col-md-12 col-xl-12 middle-wrapper">
        <div class="row">
            <div class="card">
                <div class="card-body">
  
                    <h6 class="card-title"> Add Post</h6>

                    <form method="POST" action="{{route('store.post')}}" class="forms-sample" enctype="multipart/form-data">

                      @csrf 
                      
                      <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" class="form-control">
                            </div>
                        </div><!-- Col -->
                        <div class="col-sm-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Blog Category</label>
                                <select name="blogcat_id" class="form-select" id="exampleFormControlSelect1">
                                    <option selected="" disabled="">Select Category</option>

                                    @foreach($blogcat as $item)
                                     <option value="{{$item->id}}">{{$item->category_name}}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div><!-- Col -->
                      </div>

                      

                        <div class="col-sm-12">
                            <div class="form-group mb-3">
                                <label class="form-label">Short Description</label>
                                <textarea class="form-control" name="short_desc"  rows="4"></textarea>
                            </div>
                        </div><!-- Col -->


                        <div class="col-sm-12">
                            <div class="form-group mb-3">
                                <label class="form-label">Long Description</label>
                                <textarea class="form-control" name="long_desc" name="tinymce" id="tinymceExample" rows="10"></textarea>
                            </div>
                        </div><!-- Col -->

                        <div class="col-sm-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Tags</label>
                                <input name="tags" id="tags" value="New York,Texas,Florida,New Mexico" />
                            </div>
                        </div><!-- Col -->

                        <div class="mb-3">
                            <label for="exampleInputUsername1" class="form-label">Post Photo</label>
                            <input type="file" name="image" class="form-control" id="image">
                         </div>
 
                         <div class="mb-3">
                             <label for="exampleInputUsername1" class="form-label"></label>
                             <img  
                              id="showImage" class="wd-80 rounded-circle"
                             src="{{(!empty($profileData->photo)) ? url('upload/admin/'. $profileData->photo) : url('upload/admin/no_image.jpg')}}" alt="profile">
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