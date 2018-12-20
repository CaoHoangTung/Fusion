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
                        <form action="/profile/change/avatar" method="post">
                            @csrf
                            <label for="file-input">
                                <img src="{!!$profile->Avatar!!}" class="img-circle img-responsive user-avatar"/>
                                <img src="" class="img-circle img-responsive user-avatar" id="testimg" hidden/>
                            </label>

                            <input id="file-avatar-input" type="file" name="avatar" accept="image/png, image/jpeg, image/jpg, image/ico" required/>
                            <input id="base64" name="base64img" type='hidden'>
                            <button type="submit" class="btn btn-primary" id="avatar-change">Change</button>
                        </form>
                    </div>
                    <div class="settings-menu">
                        <a href="/settings" class="settings-menu-option">Settings</a>
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
                        <span><a href="/contests/{{$problem->ContestID}}/{{$problem->ProblemID}}">{{$problem->QuestionName}}</a></span>
                        @endforeach
                    </div>
                    <hr>
                    <div class="unsolved-problems">
                        <h3>Unsolved Problems</h3>
                        @foreach($profile->unsolvedProblems as $key=>$problem)
                        <span><a href="/contests/{{$problem->ContestID}}/{{$problem->ProblemID}}">{{$problem->QuestionName}}</a></span>
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
    function getBase64(file) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = () => resolve(reader.result);
            reader.onerror = error => reject(error);
        });
    }

    function resizeBase64Img(base64, width, height) {
        var canvas = document.createElement("canvas");
        var newimg = document.createElement("img");
        newimg.src=base64;
        newimg.id="uploadimg";
        canvas.width = width;
        canvas.height = height;
        var context = canvas.getContext("2d");

        $('#uploadimg').ready(function(){    
            // context.scale(width,1);
            context.drawImage(newimg, 0, 0);             
            var newBase64 = canvas.toDataURL();
            // $('#testimg').attr('src',newBase64); 
            // $('#testimg').removeAttr('hidden');
            console.log(canvas.toDataURL());
            return canvas.toDataURL();    
        });
    }

    $(document).ready(function(){
        $('#file-avatar-input').change(function(){
            $('#avatar-change').attr('disabled','disabled');
            var file = this.files[0];
            
            getBase64(file).then(
                data => {
                    console.log(data);
                    var x = (resizeBase64Img(data, 300, 300));
                    $('#base64').val(data);
                    $('#avatar-change').removeAttr('disabled');
                }
            );
        });
    });

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