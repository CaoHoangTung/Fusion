@extends('layout')

@section('content')
    <div class="card-header card-title">Lectures</div>

    <div class="card-body">
        @if (session('status'))
            <div class="alert alert-success" role="alert">               
            </div>
        @endif

        <div class="card-post">
            @foreach($lectures as $key=>$lecture)
            <div class='post'>
                <div class='post-header'><a href='/blog/entry/{{$lecture->PostID}}'><h3>{{$lecture->Header}}</h3></a></div>
                <div class='post-subheader'>By <b><a href="/profile/{{$lecture->Creator}}">{{$lecture->name}}</a></b>, <span class="createTime">{{$lecture->CreateDate}}</span></div>
                <div class='post-content'>{!!$lecture->Content!!}</div>
            </div>
            @endforeach
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // $(document).ready(function(){
        $('.createTime').each(function(){
            var rtime = relative_time(this.innerHTML);
            $(this).html(rtime);
        });
    // });
</script>
@endsection