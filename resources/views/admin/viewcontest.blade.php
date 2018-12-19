@extends('admin.layout')

@section('content')
    <div class="container-fluid dashboard-content ">
    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="page-header">
                                <h2 class="pageheader-title">Contest #{{$ContestID}}</h2>
                                <p class="pageheader-text"></p>
                                <div class="page-breadcrumb">
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="/sadmin/dashboard" class="breadcrumb-link">Dashboard</a></li>
                                            <li class="breadcrumb-item"><a href="/sadmin/dashboard/contest/past" class="breadcrumb-link">Contest</a></li>
                                            <li class="breadcrumb-item active" aria-current="page">{{$ContestID}}</li>
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
                                <h5 class="card-header">{{$ContestID}}</h5>
                            
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
                                                    <th>ID</th>
                                                    <th>Contest</th>
                                                    <th>Question name</th>
                                                    <th>Question</th>
                                                    <th>Answer</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($problems as $key=>$problem)
                                                <tr id="tr-{{$problem->ProblemID}}">
                                                    <td>{{$problem->ProblemID}}</td>
                                                    <td class="contest-name">{{$problem->ContestName}}</td>
                                                    <td>{{$problem->QuestionName}}</td>
                                                    <td class="question">{{strip_tags($problem->Question)}}</td>
                                                    <td class="answer">{{$problem->Answer}}</td>
                                                    <td>                                                        
                                                        <a title="Change" id="{{$problem->ProblemID}}" class="change-problem" href="#hidden-form"><i style="color: green" class="m-r-10 mdi mdi-pen"></i></a>
                                                        <a title="Delete" onclick="return confirm('Do you really want to delete the problem?');" href="/sadmin/dashboard/problem/{{$problem->ProblemID}}/delete"><i style="color:red" class="m-r-10 mdi mdi-delete-forever"></i></a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Contest</th>
                                                    <th>Question Name</th>
                                                    <th>Question</th>
                                                    <th>Answer</th>
                                                    <th>Action</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div style="margin-bottom: 15px; margin-left: 17px">
                                    <div class="input-group">
                                        <button id="new-problem" class="btn btn-primary">New problem</button>
                                        <button id="rate-contest" class="btn">Rate contest</button>
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

    var getProblem = function(id){
        return $.ajax({
            url: '/sadmin/dashboard/problem/'+id,
            method: 'get',
            success: function(res){
                json = JSON.parse(res);
            },
            error: function(res){
                console.log(res);
            }
        });
    }
    $('#rate-contest').click(function(){
        $('#hidden-form').html('<div class="alert alert-info">The contest is being judged. Do not turn off the browser<br><img src="/images/loading.gif" width="30px"></div>');
        $.ajax({
            url: '/judge',
            method: 'post',
            data:{
                "_token": "{{ csrf_token() }}",
                "ContestID": "{{$ContestID}}",
            },
            success: function(res){
                console.log(res);
                $('#hidden-form').html("<div class='alert alert-success'>The contest has been judged</div>");
            },
            error: function(res){
                console.log(res);
            }
        })
    });
    $('#new-problem').click(function(){
        str = '<form method="post" action="/sadmin/dashboard/problem/new">'
                + '     @csrf'
                + '     <input name="ContestID" value="{{$ContestID}}" type="hidden">'
                + '     <label>Question Name</label>'
                + '     <input class="form-control" id="QuestionName" name="QuestionName" required>'
                + '     <br>'
                + '     <label>Question</label>'
                + '     <textarea class="form-control" id="Question" name="Question" required></textarea>'
                + '     <br>'
                + '     <label>Answer</label>'
                + '     <textarea class="form-control" id="Answer" name="Answer" required></textarea>'
                + '     <br>'
                + '     <div class="input-group">'
                + '         <button type="submit" class="btn btn-primary">Create</button>'
                + '     </div>'
                + '</form>';
        $('#hidden-form').html(str);
        CKEDITOR.replace('Question');
    });
    $('.change-problem').click(function(){
        $('#hidden-form').html("Loading");
        
        $.when(getProblem(this.id)).done(function(){
            str = '<form method="post" action="/sadmin/dashboard/problem/'+json.ProblemID+'/change">'
                + '     @csrf'
                + '     <input name="ContestID" value="'+json.ContestID+'" type="hidden">'
                + '     <label>Question Name</label>'
                + '     <input class="form-control" id="QuestionName" name="QuestionName" value="'+json.QuestionName+'" required>'
                + '     <br>'
                + '     <label>Question</label>'
                + '     <textarea class="form-control" id="Question" name="Question" required>'+json.Question+'</textarea>'
                + '     <br>'
                + '     <label>Answer</label>'
                + '     <textarea class="form-control" id="Answer" name="Answer" required>'+json.Answer+'</textarea>'
                + '     <br>'
                + '     <div class="input-group">'
                + '         <button type="submit" class="btn btn-primary">Save</button>'
                + '     </div>'
                + '</form>';
            $('#hidden-form').html(str);
            CKEDITOR.replace('Question');
        });
        
    });
</script>
@endsection