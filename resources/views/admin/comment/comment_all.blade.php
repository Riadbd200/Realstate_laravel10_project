@extends('admin.admin_dashboard')

@section('admin')

<div class="page-content">

    

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">All Comments</h6>
                        <div class="table-responsive">
                        <table id="dataTableExample" class="table">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Post Title</th>
                                <th>User Name</th>
                                <th>Subject</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($comment as $key=>$item)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$item['post']['title']}}</td>
                                <td>{{$item['user']['name']}}</td>
                                <td>{{$item->subject}}</td>
                              
                                <td>
                                    <a href="{{route('admin.comment.reply', $item->id)}}" class="btn btn-inverse-warning btn-sm">Reply</a>
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