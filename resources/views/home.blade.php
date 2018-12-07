@extends('layout')

@section('content')
    <div class="card-header card-title">Announcements</div>

    <div class="card-body">
        @if (session('status'))
            <div class="alert alert-success" role="alert">               
            </div>
        @endif

        <div class="card-post">
            
        </div>
    </div>
@endsection

@section('scripts')
<script>
    getPosts();
</script>
@endsection