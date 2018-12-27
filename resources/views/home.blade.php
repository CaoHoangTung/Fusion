@extends('layout')

@section('content')
    <div class="card-header card-title">Announcements</div>

    <div class="card-body">
        @if (session('status'))
            <div class="alert alert-success" role="alert">               
            </div>
        @endif

        <div class="card-post">
            @foreach($posts as $key=>$post)
            <div class='post'>
                <div class='post-header'><a href='/blog/entry/{{$post->PostID}}'><h3>{{$post->Header}}</h3></a></div>
                <div class='post-subheader'>By {{$post->name}}, <span class="relativeTime">{{$post->CreateDate}}</span></div>
                <div class='post-content'>{!!$post->Content!!}</div>
            </div>
            @endforeach
            {{$posts->links()}}
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // getPosts();
    $('.relativeTime').each(function(){
        var time = relative_time(this.html());
        console.log(time);
    })
</script>
@endsection