@extends('admin.layout')

@section('content')
<div class="dashboard-wrapper">
            <div class="container-fluid dashboard-content">
                <div class="row">
                    <div class="col-xl-10">
                        <!-- ============================================================== -->
                        <!-- basic form  -->
                        <!-- ============================================================== -->
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="section-block" id="basicform">
                                    <h3 class="section-title">New Contest</h3>
                                </div>
                                <div class="card">
                                    <h5 class="card-header">Contest ID: <b>{{$newid}}</h5>
                                    <div class="card-body">
                                        <form method="post" href="/sadmin/dashboard/contest/new">
                                            @csrf
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
                                            <div class="form-group">
                                                <label for="inputText3" class="col-form-label">Contest Name</label>
                                                <input required id="ContestName" name="ContestName" value="Name" type="text" class="form-control">
                                            </div>

                                            <div class="form-group">
                                                <label>Start at: <small class="text-muted">mm/dd/yyyy hh:mm:am/pm</small></label>
                                                <input required name="StartAt" id="StartAt" type="datetime-local" class="form-control date-inputmask" id="date-mask" im-insert="true">
                                            </div>

                                            <div class="form-group">
                                                <label>End at: <small class="text-muted">mm/dd/yyyy hh:mm:am/pm</small></label>
                                                <input required name="EndAt" id="EndAt" type="datetime-local" class="form-control date-inputmask" id="date-mask" im-insert="true">
                                            </div>
                            
                                            <div class="form-group">
                                                <label for="exampleFormControlTextarea1">Contest Description</label>
                                                <textarea required id="Description" name="Description" class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                                            </div>
                                            <div class="input-group">
                                                <button type="submit" class="btn btn-primary">Create</button>
                                            </div>
                                        </form>
                                    </div>
                                   
                                </div>
                            </div>
                        </div>
                        <!-- ============================================================== -->
                        <!-- end basic form  -->
                        <!-- ============================================================== -->

                    </div>
                    <!-- ============================================================== -->
                    <!-- sidenavbar -->
                    <!-- ============================================================== -->
                    </div>
            </div>
        </div>
@endsection