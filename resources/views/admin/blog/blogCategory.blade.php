@extends('admin.admin_dashboard')

@section('admin')

<div class="page-content">

    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
           <button type="button" class="btn btn-inverse-info" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Add Category
          </button>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
<div class="card">
  <div class="card-body">
    <h6 class="card-title">All Category</h6>
    <div class="table-responsive">
      <table id="dataTableExample" class="table">
        <thead>
          <tr>
            <th>SL</th>
            <th>Category Name</th>
            <th>Category Slug</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
            @foreach($category as $key=>$item)
          <tr>
            <td>{{$key+1}}</td>
            <td>{{$item->category_name}}</td>
            <td>{{$item->category_slug}}</td>
           
            <td>
              
                <button type="button" class="btn btn-inverse-warning" data-bs-toggle="modal" data-bs-target="#editModal" id="{{$item->id}}" onclick="categoryEdit(this.id)">Edit</button>

                <a href="{{route('delete.category', $item->id)}}" id="delete" class="btn btn-inverse-danger btn-sm">Delete</a>
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

{{-- Add Category Modal --}}
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add Category</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{route('store.category')}}" class="forms-sample">

                @csrf  
                    <div class="form-group mb-3">
                        <label for="category_name" class="form-label">Category Name</label>
                        <input type="text" name="category_name" class="form-control" id="category_name" autocomplete="off">
                    </div>   
                </div>
                <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
      </div>
    </div>
  </div>

  {{-- Edit Category Modal --}}
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModal" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editModal">Update Category</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{route('update.category')}}" class="forms-sample">

                @csrf  
                <input type="hidden" name="cat_id" id="cat_id">
                
                    <div class="form-group mb-3">
                        <label for="category_name" class="form-label">Category Name</label>
                        <input type="text" name="category_name" class="form-control" id="cat" autocomplete="off">
                    </div>   
                </div>
                <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
      </div>
    </div>
  </div>

  <script type="text/javascript">

  function categoryEdit(id)
  {

    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: 'GET',
        url: '/blog/category/'+id,
        dataType: 'json',

        success:function(data){
            $('#cat').val(data.category_name);
            $('#cat_id').val(data.id);

        }
    })
  }

  </script>

@endsection