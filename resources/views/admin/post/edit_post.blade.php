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
  
                    <h6 class="card-title"> Update Post</h6>

                    <form method="POST" action="{{route('update.post')}}" class="forms-sample" enctype="multipart/form-data">

                      @csrf 
                      <input type="hidden" name="id" value="{{$post->id}}">
                      <input type="hidden" name="old_img" value="{{$post->image}}">
                      
                      <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" value="{{$post->title}}" class="form-control">
                            </div>
                        </div><!-- Col -->
                        <div class="col-sm-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Blog Category</label>
                                <select name="blogcat_id" class="form-select" id="exampleFormControlSelect1">
                                    <option selected="" disabled="">Select Category</option>

                                    @foreach($blogcat as $item)
                                     <option value="{{$item->id}}" {{$item->id == $post->blogcat_id ? 'selected' : ''}}>{{$item->category_name}}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div><!-- Col -->
                      </div>

                      

                        <div class="col-sm-12">
                            <div class="form-group mb-3">
                                <label class="form-label">Short Description</label>
                                <textarea class="form-control" name="short_desc"  rows="4">
                                    {{$post->short_desc}}
                                </textarea>
                            </div>
                        </div><!-- Col -->


                        <div class="col-sm-12">
                            <div class="form-group mb-3">
                                <label class="form-label">Long Description</label>
                                <textarea class="form-control" name="long_desc" name="tinymce" id="tinymceExample" rows="10">
                                    {!! $post->long_desc !!}
                                </textarea>
                            </div>
                        </div><!-- Col -->

                        <div class="col-sm-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Tags</label>
                                <input name="tags" id="tags" value="{{$post->tags}}" />
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
                             src="{{asset($post->image)}}" alt="profile">
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