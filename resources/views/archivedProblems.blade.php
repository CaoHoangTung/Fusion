@extends('layout')

@section('content')
<div class="card-header card-title">Archived Problems</div>

<div class="card-body">
    <div class="card">
        <h5 class="card-header">Problems</h5>
        <div class="table-responsive">
                                        <table class="table table-striped table-bordered first">
                                            <thead>
                                                <tr>
                                                    <th>Problem ID</th>
                                                    <th>Problem</th>
                                                    <th>Contest</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($problems as $key=>$problem)
                                                <tr id="tr-{{$problem->ProblemID}}" class= @if(isset($problem->status)) "problem-done" @endif>
                                                    <td><a href="/contests/{{$problem->ContestID}}/{{$problem->ProblemID}}">{{$problem->ProblemID}}</a></td>
                                                    <td><a href="/contests/{{$problem->ContestID}}/{{$problem->ProblemID}}">{{$problem->QuestionName}}</a></td>
                                                    <td><a href="/contests/{{$problem->ContestID}}">{{$problem->ContestName}}</a></td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        {{$problems->links()}}
                                    </div>
        <hr>
    </div>
</div>
@endsection

@section('scripts')
<script>
</script>
@endsection