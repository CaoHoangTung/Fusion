@extends('layout')

@section('content')
<div class="card-header card-title">Contests</div>

<div class="card-body">
    <div class="card">
                                <h5 class="card-header">Current and upcoming contests</h5>
                                <div class="card-body">
                                    <table class="table table-striped" id="upcomingContests">
                                        <thead>
                                            <tr>
                                                <th scope="col">Contest</th>
                                                <th scope="col">Creator</th>
                                                <th scope="col">Duration</th>
                                            
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <hr>
                            <div class="card">
                                <h5 class="card-header">History</h5>
                                <div class="card-body">
                                    <table class="table table-striped" id="contestHistory">
                                        <thead>
                                                <th scope="col">Contest</th>
                                                <th scope="col">Creator</th>
                                                <th scope="col">Duration</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <hr>
</div>
@endsection

@section('scripts')
<script>
    getAllUpcomingContests();
    getContestsHistory();
</script>
@endsection