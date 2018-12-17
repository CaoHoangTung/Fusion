@extends('layout')

@section('content')
<div class="card-header card-title">Ranking</div>

<div class="card-body">
<div class="tab-regular">
                                <ul class="nav nav-tabs nav-fill" id="myTab7" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active show" id="home-tab-justify" data-toggle="tab" href="#home-justify" role="tab" aria-controls="home" aria-selected="true">Global Ranking</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="profile-tab-justify" data-toggle="tab" href="#profile-justify" role="tab" aria-controls="profile" aria-selected="false">Hall Of Fame</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent7">
                                    <div class="tab-pane fade active show" id="home-justify" role="tabpanel" aria-labelledby="home-tab-justify">                                        
                                        <div class="card-body">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">#</th>
                                                        <th scope="col">User</th>
                                                        <th scope="col">Rating</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($users as $key=>$user)
                                                    <tr>
                                                        <th scope="row">
                                                        @if (isset($_REQUEST['page']))
                                                            {{($_REQUEST['page']-1)*$perpage+$key+1}}
                                                        @else
                                                            {{$key+1}}
                                                        @endif
                                                        </th>
                                                        <td><a href="/profile/{{$user->id}}">{{$user->name}}</a></td>
                                                        <td>{{$user->Rating}}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                            {{$users->links()}}
                                        </div>                                       
                                    </div>
                                    <div class="tab-pane fade" id="profile-justify" role="tabpanel" aria-labelledby="profile-tab-justify">
                                        <div class="card-body">
                                                <table class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">#</th>
                                                            <th scope="col">Contest</th>
                                                            <th scope="col">Winner</th>
                                                            <th scope="col">2nd</th>
                                                            <th scope="col">3rd</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($hof as $key=>$record)
                                                        <tr>
                                                            <th scope="row">
                                                            @if (isset($_REQUEST['page']))
                                                                {{($_REQUEST['page']-1)*$perpage+$key+1}}
                                                            @else
                                                                {{$key+1}}
                                                            @endif
                                                            </th>
                                                            <td><a href="/contests/{{$record->ContestID}}">{{$record->ContestName}}</a></td>
                                                            <td><img src="/images/firstplace.svg" class='prize-icon'><a href="/profile/{{$record->First}}">{{$record->FirstName}}</a></td>
                                                            <td><img src="/images/secondplace.svg" class='prize-icon'><a href="/profile/{{$record->Second}}">{{$record->SecondName}}</a></td>
                                                            <td><img src="/images/thirdplace.svg" class='prize-icon'><a href="/profile/{{$record->Third}}">{{$record->ThirdName}}</a></td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                                {{$hof->links()}}
                                            </div>                                       
                                        </div>
                                    </div>

                                </div>
</div>

@endsection

@section('scripts')
<script>
</script>
@endsection