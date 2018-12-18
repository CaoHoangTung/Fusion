@extends('layout')

@section('content')
<div class="card-header card-title">Contests</div>

<div class="card-body">
<div class="input-group mb-3 searchbox">
    <form method="get" action="/search">
        <div class="input-group mb-3">
            <input class='form-control' value="{{$query}}" type="text" name="query" placeholder="Search" class="form-control">
            <button type='submit' class='btn btn-primary'>Search</button><br>
            <div class="switch-button switch-button-xs" style="width:100%">
                <input type="checkbox" @if(isset($settings['contest']) && $settings['contest'] == 1) checked @endif name="contest" id="checkcontest">
                <span><label for="checkcontest">Contests</label></span>

                <input type="checkbox" @if(isset($settings['problem']) && $settings['problem'] == 1) checked @endif name="problem" id="checkproblems">
                <span><label for="checkproblems">Problems</label></span>

                <input type="checkbox" @if(isset($settings['user']) && $settings['user'] == 1) checked @endif name="user" id="checkusers">
                <span><label for="checkusers">Users</label></span>

                <input type="checkbox" @if(isset($settings['post']) && $settings['post'] == 1) checked @endif name="post" id="checkposts">
                <span><label for="checkposts">Posts</label></span>
            </div>
        </div>

    </form>
</div>

<br><hr>
    @if(isset($settings['contest']) && $settings['contest'] == 1)
    <div class="card">
                            <h5 class="card-header">Contests</h5>
                                <div class="card-body">
                                    <table class="table table-striped" id="upcomingContests">
                                        <thead>
                                            <tr>
                                                <th scope="col">Contest</th>
                                                <th scope="col">Start</th>
                                                <th scope="col">End</th>                                            
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($contests as $key=>$contest)
                                            <tr>
                                                <td>
                                                    <a href="/contests/{{$contest->ContestID}}">{{$contest->ContestName}}</a>
                                                </td>
                                                <td>{{$contest->ContestBegin}}</td>
                                                <td>{{$contest->ContestEnd}}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <hr>
        @endif
        @if(isset($settings['problem']) && $settings['problem'] == 1)
                            <div class="card">
                                <h5 class="card-header">Problems</h5>
                                <div class="card-body">
                                    <table class="table table-striped" id="contestHistory">
                                        <thead>
                                                <th scope="col">ProblemID</th>
                                                <th scope="col">Problem</th>
                                                <th scope="col">Belongs to contest</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($problems as $key=>$problem)
                                        <tr>
                                            <td scope="col">{{$problem->ProblemID}}</td>
                                            <td scope="col">
                                                <a href="/problems/{{$problem->ProblemID}}">{{$problem->QuestionName}}</a>
                                            </td>
                                            <td scope="col">
                                                <a href="/contests/{{$problem->ContestID}}">{{$problem->ContestName}}</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <hr>
    @endif
    @if(isset($settings['user']) && $settings['user'] == 1)
                            <div class="card">
                                <h5 class="card-header">Users</h5>
                                <div class="card-body">
                                    <table class="table table-striped" id="contestHistory">
                                        <thead>
                                                <th scope="col">UserID</th>
                                                <th scope="col">Username</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($users as $key=>$user)                                        
                                            <tr>
                                                <td scope="col">{{$user->id}}</td>
                                                <td scope="col">{{$user->name}}</td>
                                            </tr>                                        
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <hr>
    @endif
    @if(isset($settings['post']) && $settings['post'] == 1)
                            <div class="card">
                                <h5 class="card-header">Posts</h5>
                                <div class="card-body">
                                    <table class="table table-striped" id="contestHistory">
                                        <thead>
                                                <th scope="col">Blog Entry</th>
                                                <th scope="col">Title</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($posts as $key=>$post)
                                        <tr>
                                            <td scope="col">{{$post->PostID}}</td>
                                            <td scope="col">
                                                <a href="/blog/entry/{{$post->PostID}}">{{$post->Header}}</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <hr>
    @endif
</div>
@endsection

@section('scripts')

@endsection