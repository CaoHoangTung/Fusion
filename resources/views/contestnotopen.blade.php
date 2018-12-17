@extends('layout')

@section('content')
<div class="card-header card-title">Contests</div>

<div class="card-body">
    <span><a href="/contests"> << Contests</a> / {{$contest->ContestName}}</span><br><br>
    <div class="card">
        <h5 class="card-header">Problems</h5>
        <div class="container">
            <br>
            <h2>Contest has not open yet</h2>   
            <p id ="timer"></p>  
        </div>
        <hr>
    </div>
</div>
@endsection

@section('scripts')
<script>

// Update the count down every 1 second
var distance = {{$TimeLeft}}*1000;
var x = setInterval(function() {
    // Find the distance between now and the count down date
    
    
    // Time calculations for days, hours, minutes and seconds
    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
    // Output the result in an element with id="timer"
    document.getElementById("timer").innerHTML = days + "d " + hours + "h "
    + minutes + "m " + seconds + "s ";
    distance -= 1000;
    // If the count down is over, write some text 
    if (distance <= 0) {
        clearInterval(x);
        document.getElementById("timer").innerHTML = "The contest has begun";
        location.reload();
    }
}, 1000);
</script>
@endsection