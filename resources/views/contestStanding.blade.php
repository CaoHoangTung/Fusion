@extends('layout')

@section('content')
<div class="card-header card-title">Contests</div>

<div class="card-body">
    <span><a href="/contests"> << Contests</a> / {{$contest->ContestName}}</span><br><br>
    <span id="contestTimer" v={{$Countdown}}>&nbsp</span>
    <div class="card">
        <!-- <h5 class="card-header">Problems</h5> -->
                                <ul class="nav nav-tabs nav-fill" id="myTab7" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link" href="/contests/{{$contest->ContestID}}" role="tab" aria-controls="profile" aria-selected="false">Problems</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link active show" aria-selected="true">Standings</a>
                                    </li>
                                </ul>
        <div class="table-responsive">
                        @if (sizeof($ranks) > 0)
                                        <table class="table table-striped table-bordered first">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>User</th>
                                                    @foreach(current($ranks)->solved as $key=>$value)
                                                        <th>{{$key}}</th>
                                                    @endforeach
                                                    <th>Points</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $count = 0 @endphp
                                                @foreach($ranks as $key=>$user)
                                                @php $count++ @endphp
                                                <tr class= @if(isset($problem->status)) "problem-done" @endif>
                                                    <td>{{$count}}</td>
                                                    <td><a href="/profile/{{$key}}" class="uname" v='{{$user->rating}}'>{{$user->username}}</a></td>
                                                    @foreach($user->solved as $key=>$value)
                                                        <td @if ($value > 0) 
                                                                style="background: green; color: white"
                                                            @endif
                                                            @if($value < 0)
                                                            style="background: red; color: white"
                                                            @endif
                                                        >
                                                            {{$value}}</td>
                                                    @endforeach
                                                    <td>{{$user->point}}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                        @else
                            <h5>No submissions has been made yet</h5>
                        @endif
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