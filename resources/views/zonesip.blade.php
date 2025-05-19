@extends('layout/master')
@section('content')

<!-- Begin page -->
<div id="layout-wrapper">


        <div class="page-content">
            <div class="container-fluid">

               
                <!-- start page title -->
                <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0">Zones & IP</h4>
                                <div class="page-title-right">
                                <a href="{{ '/domains'}}" class="btn btn-outline-primary">
                                View Domains</span>
                                </a>
                            </div>
                                
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                     

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <table id=""
                                        class="table table-bordered dt-responsive nowrap table-striped align-middle"
                                        style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Zone Name</th>
                                                <th>IPs</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($zonedata as $ipzone)
                                            <tr>
                                                <td>{{ $ipzone['zone'] }}</td>
                                                <td>
                                                    @foreach($ipzone['ips'] as $ip)
                                                    <div>{{ $ip }}</div>
                                                    @endforeach
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div><!--end col-->
                    </div><!--end row-->

            </div>
            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->

            @endsection