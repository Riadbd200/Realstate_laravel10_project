@extends('admin.admin_dashboard')

@section('admin')

<div class="page-content">

    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
           <a href="{{route('add.post')}}" class="btn btn-inverse-info">Add Post</a>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">All State</h6>
                    <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <thead>
                        <tr>
                            <th>SL</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Image</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($post as $key=>$item)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>{{$item->title}}</td>
                            <td>{{$item['category']['category_name']}}</td>
                            <td>
                                <img src="{{asset($item->image)}}" style="width:100px; height:100px;" alt="">
                            </td>
                            <td>
                                <a href="{{route('edit.post', $item->id)}}" class="btn btn-inverse-warning btn-sm">Edit</a>
                                <a href="{{route('delete.post', $item->id)}}" id="delete" class="btn btn-inverse-danger btn-sm">Delete</a>
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