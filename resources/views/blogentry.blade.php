@extends('layout')

@section('content')
    <div class="card-header card-title">Announcements</div>

    <div class="card-body">
        <div class="card-post">
            <div class='post'>
                <span><a href="/home"><< Back to home</a></span><br><br>
                <div class='post-header'><a href='/blog/entry/"+post.PostID+"'><h3>{{$post->Header}}</h3></a></div>
                <div class='post-subheader'>Created at {{$post->CreateDate}}</div>
                <div class='post-content'>{!!$post->Content!!}</div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

@endsection