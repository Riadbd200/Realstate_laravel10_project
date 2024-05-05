@extends('admin.admin_dashboard')

@section('admin')


<div class="page-content">

    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
<div class="card">
  <div class="card-body">
                    <h6 class="card-title">Property Details</h6>
                    <div class="table-responsive">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <th>Property Name</th>
                                        <td><code>{{$property->property_name}}</code></td>
                                    </tr>

                                    <tr>
                                        <th>Property Status</th>
                                        <td><code>{{$property->property_status}}</code></td>
                                    </tr>

                                    <tr>
                                        <th>Lowest Price</th>
                                        <td><code>{{$property->lowest_price}}</code></td>
                                    </tr>

                                    <tr>
                                        <th>Max Price</th>
                                        <td><code>{{$property->max_price}}</code></td>
                                    </tr>
                                    <tr>
                                        <th>Bedrooms</th>
                                        <td><code>{{$property->bedrooms}}</code></td>
                                    </tr>
                                    <tr>
                                        <th>Bathrooms</th>
                                        <td><code>{{$property->bathrooms}}</code></td>
                                    </tr>
                                    <tr>
                                        <th>Garage</th>
                                        <td><code>{{$property->garage}}</code></td>
                                    </tr>
                                    <tr>
                                        <th>Garage Size</th>
                                        <td><code>{{$property->garage_size}}</code></td>
                                    </tr>
                                    <tr>
                                        <th>Address</th>
                                        <td><code>{{$property->address}}</code></td>
                                    </tr>
                                    <tr>
                                        <th>City</th>
                                        <td><code>{{$property->city}}</code></td>
                                    </tr>
                                    <tr>
                                        <th>State</th>
                                        <td><code>{{$property['pstate']['state_name']}}</code></td>
                                    </tr>
                                    <tr>
                                        <th>Postal Code</th>
                                        <td><code>{{$property->postal_code}}</code></td>
                                    </tr>
                                    <tr>
                                        <th>Main Image</th>
                                        <td>
                                            
                                            <img style="width:80px !important; height:80px !important;" src="{{asset($property->property_thumbnail)}}"  alt="">
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            @if($property->status == 1)
                                            <span class="badge rounded-pill bg-success">Active</span>
                                            @else 
                                            <span class="badge rounded-pill bg-danger">InActive</span>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                    </div>
  </div>
</div>
</div>

<div class="col-md-6 grid-margin stretch-card">
<div class="card">
  <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <td>Property Code</td>
                        <td><code>{{$property->property_code}}</code></td>
                    </tr>
                    <tr>
                        <td>Property Size</td>
                        <td><code>{{$property->property_size}}</code></td>
                    </tr>
                    <tr>
                        <td>Property Video</td>
                        <td><code>{{$property->property_video}}</code></td>
                    </tr>
                    <tr>
                        <td>Neighborhood</td>
                        <td><code>{{$property->neighborhood}}</code></td>
                    </tr>
                    <tr>
                        <td>Latitude</td>
                        <td><code>{{$property->latitude}}</code></td>
                    </tr>
                    <tr>
                        <td>Longitude</td>
                        <td><code>{{$property->longitude}}</code></td>
                    </tr>
                    <tr>
                        <td>Property Type</td>
                        <td><code>{{$property['type']['type_name']}}</code></td>
                    </tr>
                    <tr>
                        <td>Property Amenities</td>
                        <td>
                            <select name="amenities_id[]" class="js-example-basic-multiple form-select" multiple="multiple" data-width="100%">

                                @foreach($amenities as $amenitie)
                                <option value="{{$amenitie->amenitis_name}}"
                                    {{(in_array($amenitie->amenitis_name,$amenitis)) ? 'selected' : ''}}>{{$amenitie->amenitis_name}}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <th>Agent</th>
                        <td>
                           @if($property->agent_id == NULL)
                           <td><code>Admin</code></td>
                           @else 
                           <td><code>{{$property['user']['name']}}</code></td>
                           @endif 
                        </td>
                    </tr>
                  
                </tbody>
            </table>
            <br>

            @if($property->status == 1)
            <form  action="{{route('inactive.property')}}" method="POST">
                @csrf 

                <input type="hidden" name="id" value="{{$property->id}}">
                <button type="submit" class="btn btn-primary">Inactive</button>
            </form>
            @else 
            <form  action="{{route('active.property')}}" method="POST">
                @csrf 

                <input type="hidden" name="id" value="{{$property->id}}">
                <button type="submit" class="btn btn-primary">Active</button>
            </form>

            @endif


        </div>
  </div>
</div>
</div>
</div>


</div>

@endsection