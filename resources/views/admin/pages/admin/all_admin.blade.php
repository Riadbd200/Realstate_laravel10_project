@extends('admin.admin_dashboard')

@section('admin')

<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">


<div class="page-content">

    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
           <a href="{{route('add.admin')}}" class="btn btn-inverse-info">Add Admin</a>
        </ol>
    </nav>

<div class="row">
<div class="col-md-12 grid-margin stretch-card">
<div class="card">
  <div class="card-body">
    <h6 class="card-title">All Admin</h6>
    <div class="table-responsive">
      <table id="dataTableExample" class="table">
        <thead>
          <tr>
            <th>SL</th>
            <th>Image</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Role</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
            @foreach($allAdmin as $key=>$item)
          <tr>
            <td>{{$key+1}}</td>
            <td>
                <img src="{{(!empty($item->photo)) ? url('upload/admin/'. $item->photo) : url('upload/admin/no_image.jpg')}}" style="width:70px; height:40px;" alt="">
            </td>
            <td>{{$item->name}}</td>
            <td>{{$item->email}}</td>
            <td>{{$item->phone}}</td>
            <td>
                @foreach($item->roles as $role)
                <span class="badge badge-pill bg-danger">{{$role->name}}</span>
                @endforeach
            </td>
           
            <td>
                <a href="{{route('edit.admin',$item->id)}}" class="btn btn-inverse-warning btn-sm" title="Edit"><i data-feather="edit"></i></a>
                <a href="{{route('delete.admin',$item->id)}}" id="delete" class="btn btn-inverse-danger btn-sm" title="Delete"><i data-feather="trash-2"></i></a>
            </td>
          </tr>
          @endforeach
       
        </tbody>
      </table>
    </div>
  </div>
</div>
        </div>
    </div>

</div>


 

@endsection