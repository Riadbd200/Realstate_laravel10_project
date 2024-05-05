@extends('admin.admin_dashboard')

@section('admin')

<div class="page-content">

    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
           <a href="{{route('add.role.permission')}}" class="btn btn-inverse-info">Add Permission in Roles</a> &nbsp;
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">All Roles Permissions</h6>
                    <div class="table-responsive">
                    <table  class="table">
                        <thead>
                        <tr>
                            <th>SL</th>
                            <th>Roles Name</th>
                            <th>Permission</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($roles as $key=>$item)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>{{$item->name}}</td>
                            <td>
                                @foreach($item->permissions as $permission)
                                <span class="badge bg-danger">{{$permission->name}}</span>
                                @endforeach
                            </td>
                            <td>
                                <a href="{{route('admin.edit.roles', $item->id)}}" class="btn btn-inverse-warning btn-sm">Edit</a>
                                <a href="{{route('admin.delete.roles', $item->id)}}" id="delete" class="btn btn-inverse-danger btn-sm">Delete</a>
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