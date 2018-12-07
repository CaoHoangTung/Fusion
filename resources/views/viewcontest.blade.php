@extends('layout')

@section('content')
<div class="card-header card-title">Contests</div>

<div class="card-body">
    <span><a href="/contests"> << Contests</a> / {{$contest->ContestName}}</span><br><br>
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
</script>
@endsection