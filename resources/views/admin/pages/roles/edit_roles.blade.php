@extends('admin.admin_dashboard')

@section('admin')


<div class="page-content">
    <div class="row profile-body">

      <!-- middle wrapper start -->
      <div class="col-md-8 col-xl-8 middle-wrapper">
        <div class="row">
            <div class="card">
                <div class="card-body">
  
                    <h6 class="card-title"> Update Roles</h6>

                    <form method="POST" action="{{route('update.roles')}}" class="forms-sample" id="myForm">

                      @csrf 

                      <input type="hidden" name="id" value="{{$roles->id}}">
                      
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Roles Name</label>
                            <input type="text" name="name" value="{{$roles->name}}" class="form-control" id="name" autocomplete="off">
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


@endsection