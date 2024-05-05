@extends('admin.admin_dashboard')

@section('admin')

<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<div class="page-content">

    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
           <a href="{{route('add.agent')}}" class="btn btn-inverse-info">Add Agent</a>
        </ol>
    </nav>

<div class="row">
<div class="col-md-12 grid-margin stretch-card">
<div class="card">
  <div class="card-body">
    <h6 class="card-title">All Agent</h6>
    <div class="table-responsive">
      <table id="dataTableExample" class="table">
        <thead>
          <tr>
            <th>SL</th>
            <th>Image</th>
            <th>Name</th>
            <th>Role</th>
            <th>Status</th>
            <th>Change</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
            @foreach($allAgent as $key=>$item)
          <tr>
            <td>{{$key+1}}</td>
            <td>
                <img src="{{(!empty($item->photo)) ? url('upload/agent/'. $item->photo) : url('upload/agent/no_image.jpg')}}" style="width:70px; height:40px;" alt="">
            </td>
            <td>{{$item->name}}</td>
            <td>{{$item->role}}</td>
           
            <td>
                @if($item->status == 'active')
                <span class="badge rounded-pill bg-success">Active</span>
                @else 
                <span class="badge rounded-pill bg-danger">InActive</span>
                @endif
            </td>
            <td>
              <input type="checkbox" name="" class="toggle-class" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="InActive" 
              {{$item->status ? "cheecked" : ""}} data-id="{{$item->id}}">
            </td>
            <td>
                <a href="{{route('edit.agent',$item->id)}}" class="btn btn-inverse-warning btn-sm" title="Edit"><i data-feather="edit"></i></a>

                @if(Auth::user()->can('delete.agent'))
                <a href="{{route('delete.agent',$item->id)}}" id="delete" class="btn btn-inverse-danger btn-sm" title="Delete"><i data-feather="trash-2"></i></a>
                @endif 
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


{{-- Script section start --}}

<script type="text/javascript">
  $(function() {
    $('.toggle-class').change(function() {
        var status = $(this).prop('checked') == true ? 1 : 0; 
        var user_id = $(this).data('id'); 
         
        $.ajax({
            type: "GET",
            dataType: "json",
            url: '/changeStatus',
            data: {'status': status, 'user_id': user_id},
            success: function(data){
              // console.log(data.success)

                // Start Message 

            const Toast = Swal.mixin({
                  toast: true,
                  position: 'top-end',
                  icon: 'success', 
                  showConfirmButton: false,
                  timer: 3000 
            })
            if ($.isEmptyObject(data.error)) {
                    
                    Toast.fire({
                    type: 'success',
                    title: data.success, 
                    })

            }else{
               
           Toast.fire({
                    type: 'error',
                    title: data.error, 
                    })
                }

              // End Message   


            }
        });
    })
  })
</script>
 

@endsection