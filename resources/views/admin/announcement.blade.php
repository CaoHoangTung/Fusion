@extends('admin.layout')

@section('content')

    <div class="container-fluid dashboard-content">
        <div class="row">
            <div class="form-group col-md-12">
                <form method="post" action="/sadmin/dashboard/announcement/new">
                    @csrf
                    @if(session()->has('success'))
                        <div class="alert alert-success">
                            {{ session()->get('success') }}
                        </div>
                    @endif
                    <label>Header</label>
                    <input required name="Header" class="form-control">                    
                    <label>Content</label>
                    <textarea name="Content" class="form-control" id="announcement"></textarea>
                    <div class="input-group">
                        <button type="submit" class="btn btn-primary">Announce!</button>
                    </div>
                </form>
            </div> 
        </div>
    </div>

@endsection

@section('scripts')
<script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
<script> CKEDITOR.replace('announcement'); </script>

@endsection