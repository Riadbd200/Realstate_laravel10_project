@extends('admin.admin_dashboard')

@section('admin')

<div class="page-content">
    <div class="row profile-body">

      <!-- middle wrapper start -->
      <div class="col-md-8 col-xl-8 middle-wrapper">
        <div class="row">
            <div class="card">
                <div class="card-body">
  
                    <h6 class="card-title">Reply Comment</h6>

                    <form method="POST" action="{{route('admin.reply.comment')}}" class="forms-sample">

                      @csrf 

                      <input type="hidden" name="id" value="{{$replyComment->id}}">
                      <input type="hidden" name="user_id" value="{{$replyComment->user_id}}">
                      <input type="hidden" name="post_id" value="{{$replyComment->post_id}}">
                      
                        <div class="mb-3">
                            <label for="" class="form-label">User Name: </label>
                           <code> {{$replyComment['user']['name']}} </code>
                        </div>

                        <div class="mb-3">
                            <label for="" class="form-label">Post Title: </label>
                            <code> {{$replyComment['post']['title']}} </code>
                        </div>

                        <div class="mb-3">
                            <label for="" class="form-label">Subject: </label>
                            <code> {{$replyComment->subject}} </code>
                        </div>

                        <div class="mb-3">
                            <label for="" class="form-label">Message: </label>
                            <code> {{$replyComment->message}} </code>
                        </div>

                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject: </label>
                            <input type="text" name="subject" class="form-control"  autocomplete="off">
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">Message: </label>
                         
                            <textarea name="message" class="form-control"  rows="5"></textarea>
                        </div>



                       

                       

                        <button type="submit" class="btn btn-primary me-2">Reply Comment</button>
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