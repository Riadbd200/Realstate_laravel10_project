@extends('agent.agent_dashboard')

@section('agent')



<div class="page-content">

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <h5 class="card-title text-center text-info mt-2">Schedule Request Details</h5>
                <form action="{{route('agent.update.schedule')}}" method="post" >
                    @csrf 

                    <input type="hidden" name="id" value="{{$schedule->id}}">
                    <input type="hidden" name="email" value="{{$schedule->email}}">

                    <div class="table-responsive pt-3">
                        <table class="table table-bordered">
                           
                            <tbody>
                                <tr>
                                    <td>User Name</td>
                                    <td>
                                        <code>{{$schedule['user']['name']}}</code>
                                    </td>   
                                </tr>

                                <tr>
                                    <td>Property Name</td>
                                    <td>
                                        <code>{{$schedule['property']['property_name']}}</code>
                                    </td>   
                                </tr>

                                <tr>
                                    <td>Tour Date</td>
                                    <td>
                                        <code>{{$schedule->tour_date}}</code>
                                    </td>   
                                </tr>

                                <tr>
                                    <td>Tour Time</td>
                                    <td>
                                        <code>{{$schedule->tour_time}}</code>
                                    </td>   
                                </tr>

                                <tr>
                                    <td>Message</td>
                                    <td>
                                        <code>{{$schedule->message}}</code>
                                    </td>   
                                </tr>

                                <tr>
                                    <td>Request Send  Time</td>
                                    <td>
                                        <code>{{$schedule->created_at->format('l  M d  Y')}}</code>
                                    </td>  
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <br>

                    <button class="btn btn-success" type="submit">Request Confirm</button>
                    
                </form>
            </div>
        </div>
    </div>
</div>

@endsection