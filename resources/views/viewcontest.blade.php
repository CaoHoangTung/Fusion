@extends('layout')

@section('content')
<div class="card-header card-title">Contests</div>

<div class="card-body">
    <span><a href="/contests"> << Contests</a> / {{$contest->ContestName}}</span><br><br>
    <span id="contestTimer" v={{$Countdown}}></span>
    <div class="card">
        <h5 class="card-header">Problems</h5>
        <div class="table-responsive">
                                        <table class="table table-striped table-bordered first">
                                            <thead>
                                                <tr>
                                                    <th>Problem ID</th>
                                                    <th>Question name</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($problems as $key=>$problem)
                                                <tr id="tr-{{$problem->ProblemID}}" class= @if(isset($problem->status)) "problem-done" @endif>
                                                    <td><a href="/contests/{{$contest->ContestID}}/{{$problem->ProblemID}}">{{$problem->ProblemID}}</a></td>
                                                    <td><a href="/contests/{{$contest->ContestID}}/{{$problem->ProblemID}}">{{$problem->QuestionName}}</a></td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
        <hr>
    </div>
</div>
@endsection

@section('scripts')
<script>
                var distance = $('#contestTimer').attr('v')*1000;
                var x1 = setInterval(function() {    

                    // Time calculations for days, hours, minutes and seconds
                    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    
                    // Output the result in an element with id="timer"
                    document.getElementById("contestTimer").innerHTML = days + "d " + hours + "h "
                    + minutes + "m " + seconds + "s ";
                    distance -= 1000;
                    // If the count down is over, write some text 
                    if (distance <= 0) {
                        clearInterval(x1);
                        document.getElementById("contestTimer").innerHTML = "The contest has passed. You can still practice with the problem";
                    }
                }, 1000);
</script>
@endsection