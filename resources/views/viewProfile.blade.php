@extends('layout')

@if (session('success'))
            <div class="alert alert-success" role="alert">
                {{session('success')}}
            </div>
@endif
@if (session('error'))
            <div class="alert alert-success" role="alert">
                {{session('error')}}
            </div>
@endif

@section('content')
<div class="card-header card-title">Profile</div>

<div class="card-body">
    <div class="panel panel-info">
            <div class="panel-heading">
              <h3 class="panel-title">{{$profile->name}}</h3>
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-md-3 col-lg-3" align="center"> 
                    <div class="image-upload">
                            @csrf
                            <label for="file-input">
                                <img src="{!!$profile->Avatar!!}" class="img-circle img-responsive user-avatar"/>
                            </label>

                    </div>
                </div>

                <div class=" col-md-9 col-lg-9 "> 
                    
                    <div class="card text-white bg-success mb-3 card-user-info" style="width: 11rem;">
                        <div class="card-header" style="text-align:center">Rating</div>
                        <div class="card-body">
                            <h5 style="text-align: center">{{$profile->Rating}}</h5>
                        </div>
                    </div>

                    <div class="card text-white bg-primary mb-3 card-user-info" style="width: 11rem;">
                        <div class="card-header" style="text-align:center">Solved problems</div>
                        <div class="card-body">
                            <h5 style="text-align: center">{{$profile->SolveCount}}</h5>
                        </div>
                    </div>

                    <div class="card text-white bg-secondary mb-3 card-user-info" style="width: 11rem;">
                        <div class="card-header" style="text-align:center">Submissions</div>
                        <div class="card-body">
                            <h5 style="text-align: center">{{$profile->SubCount}}</h5>
                        </div>
                    </div>
                    <hr>
                    <div id="chartContainer" style="height: 300px; width: 100%;"></div>
                    <hr>
                    <div class="solved-problems">
                        <h3>Solved Problems</h3>
                        @foreach($profile->solvedProblems as $key=>$problem)
                        <span><a href="/problems/{{$problem->ProblemID}}">{{$problem->QuestionName}}</a></span>
                        @endforeach
                    </div>
                    <hr>
                    <div class="unsolved-problems">
                        <h3>Unsolved Problems</h3>
                        @foreach($profile->unsolvedProblems as $key=>$problem)
                        <span><a href="/problems/{{$problem->ProblemID}}">{{$problem->QuestionName}}</a></span>
                        @endforeach
                    </div>
                </div>
              </div>
            </div>
                 <div class="panel-footer">
                        <a data-original-title="Broadcast Message" data-toggle="tooltip" type="button" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-envelope"></i></a>
                        <span class="pull-right">
                            <a href="#" data-original-title="Edit this user" data-toggle="tooltip" type="button" class="btn btn-sm btn-warning"><i class="glyphicon glyphicon-edit"></i></a>
                            <a data-original-title="Remove this user" data-toggle="tooltip" type="button" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-remove"></i></a>
                        </span>
                    </div>
            
          </div>
</div>
@endsection

@section('scripts')
<script src="/js/canvasjs.min.js"></script>
<script>
    window.onload = function () {

    var chart = new CanvasJS.Chart("chartContainer", {
        animationEnabled: true,
        title:{
            text: "Submissions",
            horizontalAlign: "left"
        },
        data: [{
            type: "doughnut",
            startAngle: 270,
            //innerRadius: 60,
            indexLabelFontSize: 17,
            indexLabel: "{label} - #percent%",
            toolTipContent: "<b>{label}:</b> {y} (#percent%)",
            dataPoints: [
                { y: {{$profile->SuccessCount}}, label: "Successful" },
                { y: {{$profile->FailCount}}, label: "Fail" },
            ]
        }]
    });
    chart.render();

}
</script>


@endsection