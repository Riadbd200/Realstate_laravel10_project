@php 

$agents = App\Models\User::where('status', 'active')->where('role', 'agent')
          ->orderBy('id', 'DESC')->limit(5)->get();

@endphp 



<section class="team-section sec-pad centred bg-color-1">
    <div class="pattern-layer" style="background-image: url({{asset('frontend/assets/images/shape/shape-1.png')}});"></div>
    <div class="auto-container">
        <div class="sec-title">
            <h5>Our Agents</h5>
            <h2>Meet Our Excellent Agents</h2>
        </div>
        <div class="single-item-carousel owl-carousel owl-theme owl-dots-none nav-style-one">

            @foreach($agents as $agent)
            <div class="team-block-one">
                <div class="inner-box">
                    <figure class="image-box"><img src="{{(!empty($agent->photo)) ? url('upload/agent/'. $agent->photo) : url('upload/agent/no_image.jpg')}}" style="width:370px; height:370px;" alt=""></figure>
                    <div class="lower-content">
                        <div class="inner">
                            <h4><a href="{{route('agent.details', $agent->id)}}">{{$agent->name}}</a></h4>
                            <span class="designation">{{$agent->email}}</span>
                            <ul class="social-links clearfix">
                                <li><a href="index.html"><i class="fab fa-facebook-f"></i></a></li>
                                <li><a href="index.html"><i class="fab fa-twitter"></i></a></li>
                                <li><a href="index.html"><i class="fab fa-google-plus-g"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
          
        </div>
    </div>
</section>