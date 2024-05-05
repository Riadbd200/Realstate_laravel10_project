@extends('admin.admin_dashboard')

@section('admin')

<div class="page-content">
    <div class="row profile-body">

      <!-- middle wrapper start -->
      <div class="col-md-8 col-xl-8 middle-wrapper">
        <div class="row">
            <div class="card">
                <div class="card-body">
  
                    <h6 class="card-title"> Add Admin</h6>

                    <form method="POST" action="{{route('store.admin')}}" class="forms-sample" id="myForm">

                      @csrf 


                       <div class="form-group mb-3">
                        <label for="name" class="form-label">Admin User Name</label>
                        <input type="text" name="username" class="form-control" id="name" autocomplete="off">
                       </div>
                      
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Admin Name</label>
                            <input type="text" name="name" class="form-control" id="name" autocomplete="off">
                        </div>

                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Admin Email</label>
                            <input type="email" name="email" class="form-control" id="email" autocomplete="off">
                        </div>
                        <div class="form-group mb-3">
                            <label for="phone" class="form-label">Admin Phone</label>
                            <input type="text" name="phone" class="form-control" id="phone" autocomplete="off">
                        </div>
                        <div class="form-group mb-3">
                            <label for="address" class="form-label">Admin Address</label>
                            <input type="text" name="address" class="form-control" id="address" autocomplete="off">
                        </div>
                        <div class="form-group mb-3">
                            <label for="password" class="form-label">Admin Passowrd</label>
                            <input type="password" name="password" class="form-control" id="password" autocomplete="off">
                        </div>

                        <div class="form-group mb-3">
                            <label for="" class="form-label">Role Name</label>
                            <select name="roles" class="form-select" id="">
                                <option selected="" disabled="">Select Role</option>
                                @foreach($roles as $role)
                                <option value="{{$role->id}}">{{$role->name}}</option>
                                @endforeach
                            </select>
                           
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