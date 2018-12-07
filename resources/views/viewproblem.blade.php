@extends('layout')

@section('content')
<div class="card-header card-title">Contests</div>

<div class="card-body">
    <span><a href="/contests"> << Contests</a> / <a href="/contests/{{$contest->ContestID}}">{{$contest->ContestName}}</a> / <b>{{$problem->QuestionName}}</b></span><br><br>
    <div class="card">
        <h5 class="card-header">Statement</h5>
        <div class="card-body">
            <div class="card-problem">
                {!! $problem->Question !!}
            </div>
            <form class="form-inline" method="post" action="/submit/{{$contest->ContestID}}/{{$problem->ProblemID}}">
                @csrf
                <div class="input-group">
                    <input name="Answer" class="form-control" placeholder="Answer" required>
                    <button type="submit" class="btn btn-primary mb-2">Submit</button>
                </div>
            </form>
            @if(session()->has('success'))
                        <div class="alert alert-success">
                            {{ session()->get('success') }} <a href="/contests/{{$contest->ContestID}}">Back to problem set</a>
                        </div>
                    @endif
                    @if(session()->has('error'))
                        <div class="alert alert-danger">
                            {{ session()->get('error') }}
                        </div>
                    @endif
                    @if(session()->has('error'))
                        <div class="alert alert-info">
                            You have submitted {{ session()->get('sub') }} times
                        </div>
                    @endif
        </div>
        
    </div>
</div>
@endsection

@section('scripts')
@endsection