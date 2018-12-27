@extends('layout')

@section('content')
<div class="card-header card-title">Contests</div>

<div class="card-body">
    <div class="card">
                                <h5 class="card-header">Present and future contests</h5>
                                <div class="card-body">
                                    <table class="table table-striped" id="upcomingContests">
                                        <thead>
                                            <tr>
                                                <th scope="col">Contest</th>
                                                <th scope="col">Creator</th>
                                                <th scope="col">Duration</th>
                                            
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($contests_on as $key=>$contest)
                                                <tr>
                                                    <th scope='row'><a href='/contests/{{$contest->ContestID}}'>{{$contest->ContestName}}</a></th>
                                                    <td>{{$contest->name}}</td>
                                                    <td class="contestDuration" v="{{strtotime($contest->ContestEnd)-strtotime($contest->ContestBegin)}}"></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <hr>
                            <div class="card">
                                <h5 class="card-header">Past contests</h5>
                                <div class="card-body">
                                    <table class="table table-striped" id="contestHistory">
                                        <thead>
                                                <th scope="col">Contest</th>
                                                <th scope="col">Creator</th>
                                                <th scope="col">Duration</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($contests_old as $key=>$contest)
                                                <tr>
                                                    <th scope='row'><a href='/contests/{{$contest->ContestID}}'>{{$contest->ContestName}}</a></th>
                                                    <td>{{$contest->name}}</td>
                                                    <td class="contestDuration" v="{{strtotime($contest->ContestEnd)-strtotime($contest->ContestBegin)}}"></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    {{$contests_old->links()}}
                                </div>
                            </div>
                            <hr>
</div>
@endsection

@section('scripts')
<script>
    // getAllUpcomingContests();
    $(document).ready(function(){
        $('.contestDuration').each(function(){
            var diff = $(this).attr('v');
            var duration = secondsToHIS(diff);
            console.log(duration);
            $(this).html(duration);
        });
    });
    // getContestsHistory();
</script>
@endsection