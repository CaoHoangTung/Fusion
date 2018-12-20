@extends('admin.layout')

@section('content')
    <div class="container-fluid dashboard-content ">
    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="page-header">
                                <h2 class="pageheader-title">Lectures</h2>
                                <p class="pageheader-text"></p>
                                <div class="page-breadcrumb">
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="/sadmin/dashboard" class="breadcrumb-link">Dashboard</a></li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>

    <div class="row">
    @if(session()->has('success'))
                                                <div class="alert alert-success">
                                                    {{ session()->get('success') }}
                                                </div>
                                            @endif
                                            @if(session()->has('error'))
                                                <div class="alert alert-danger">
                                                    {{ session()->get('error') }}
                                                </div>
                                            @endif
                        <!-- ============================================================== -->
                        <!-- basic table  -->
                        <!-- ============================================================== -->
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="card">
                                <h5 class="card-header">Lectures</h5>
                            
                                <ul class="navbar-nav ml-auto navbar-right-top">
                                    <li class="nav-item">
                                        
                                        <div id="custom-search" class="top-search-bar">
                                            <input class="form-control" type="text" placeholder="Search..">
                                        </div>
                                    </li>
                                </ul>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered first">
                                            <thead>
                                                <tr>
                                                    <th>Post ID</th>
                                                    <th>Post Header</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($posts as $key=>$post)
                                                <tr id="tr-{{$post->PostID}}">
                                                    <td>{{$post->PostID}}</td>
                                                    <td class="contest-name">{{$post->Header}}</td>
                                                    <td>                                                        
                                                        <a title="Change" id="{{$post->PostID}}" class="change-lecture" href="#hidden-form"><i style="color: green" class="m-r-10 mdi mdi-pen"></i></a>
                                                        <a title="Delete" onclick="return confirm('Do you really want to delete the lecture?');" href="/sadmin/dashboard/blog/{{$post->PostID}}/delete"><i style="color:red" class="m-r-10 mdi mdi-delete-forever"></i></a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>Post ID</th>
                                                    <th>Header</th>
                                                    <th>Action</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                        {{$posts->links()}}
                                    </div>
                                </div>
                                <div style="margin-bottom: 15px; margin-left: 17px">
                                    <div class="input-group">
                                        <button id="new-lecture" class="btn btn-primary">New lecture</button>
                                    </div>
                                    
                                </div>
                                
                                <div id="hidden-form" class="form-group col-md-12">
                                    
                                </div>
                            </div>
                        </div>
                        <!-- ============================================================== -->
                        <!-- end basic table  -->
                        <!-- ============================================================== -->
                    </div>
</div>        
@endsection

@section('scripts')
<script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
<script>
    var str="";
    var json = {};

    var getLecture = function(id){
        return $.ajax({
            url: '/sadmin/dashboard/blog/entry/'+id,
            method: 'get',
            success: function(res){
                json = JSON.parse(res);
            },
            error: function(res){
                console.log(res);
            }
        });
    }

    $('#new-lecture').click(function(){
        str = '<form method="post" action="/sadmin/dashboard/lecture/new">'
                + '     @csrf'
                + '     <label>Header</label>'
                + '     <input class="form-control" id="Header" name="Header" required>'
                + '     <br>'
                + '     <label>Content</label>'
                + '     <textarea class="form-control" id="Content" name="Content" required></textarea>'
                + '     <br>'
                + '     <div class="input-group">'
                + '         <button type="submit" class="btn btn-primary">Create</button>'
                + '     </div>'
                + '</form>';
        $('#hidden-form').html(str);
        CKEDITOR.replace('Content');
    });
    $('.change-lecture').click(function(){
        $('#hidden-form').html("Loading");
        
        $.when(getLecture(this.id)).done(function(){
            str = '<form method="post" action="/sadmin/dashboard/blog/'+json.PostID+'/change">'
                + '     @csrf'
                + '     <input name="PostID" value="'+json.PostID+'" type="hidden">'
                + '     <label>Header</label>'
                + '     <input class="form-control" id="Header" name="Header" value="'+json.Header+'" required>'
                + '     <br>'
                + '     <label>Content</label>'
                + '     <textarea class="form-control" id="Content" name="Content" required>'+json.Content+'</textarea>'
                + '     <br>'
                + '     <div class="input-group">'
                + '         <button type="submit" class="btn btn-primary">Save</button>'
                + '     </div>'
                + '</form>';
            $('#hidden-form').html(str);
            CKEDITOR.replace('Content');
        });
        
    });
</script>
@endsection