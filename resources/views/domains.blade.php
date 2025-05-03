@extends('layout/master')
@section('content')

<!-- Begin page -->
<div id="layout-wrapper">



    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <!-- <div class="main-content"> -->

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Domain List</h4>

                        <div class="page-title-right">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Add Domain</button>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Domains list</h5>
                        </div>
                        <div class="card-body">
                            <table id="example"
                                class="table table-bordered dt-responsive nowrap table-striped align-middle"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th data-ordering="false">SR No.</th>
                                        <th>Domain name</th>
                                        <!-- <th>Mailbox Count</th>
                                        <th>Alias Count</th>
                                        <th>Comment</th> -->
                                        <th>Create Date</th>
                                        <th>Manage</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <?php $i = 1; ?>
                                <tbody>
                                    @if(!empty($domains))
                                    @foreach($domains as $domain)
                                    <tr>
                                        <td><?php echo $i;
                                            $i++;  ?></td>
                                        <td>{{ $domain->domain_name }}</td>
                                        <!-- <td>0</td>
                                        <td>0</td>
                                        <td>{{ $domain->comment }}</td> -->
                                        <td>{{ $domain->created_at}}</td>
                                        <td>
                                            <a href="{{ '/emaillist/' . $domain->id }}" class="btn p-0" data-bs-toggle="tooltip"             data-bs-placement="bottom" data-bs-title="list">
                                            <span style="color: green;">Email list</span>
                                            </a>
                                        </td>
                                        <td>
                                            <button type="button" class="btn p-0" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Details" id="domain_details" data-domainid="<?php echo $domain->id ?>">
                                            <span style="color: #103b60;">Configure</span>
                                            </button>
                                            <!-- <button type="button" class="btn p-0" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Edit">
                                                <i class="ri-pencil-line"></i>
                                            </button> -->
                                            <!-- <button type="button" class="btn p-0" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Delete">
                                            <span style="color: #eff3ef;background-color: red;padding: 3px;border-radius: 6px;">Delete</span>
                                            </button> -->
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="8">No domains available</td>
                                    </tr>
                                    @endif
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


    <!-- staticBackdrop add domain Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalgridLabel">Add Domain</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xxl-12">
                            <div class="card">

                                <div class="card-body">
                                    <form id="domainForm">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="" class="form-label">Domain Name </label>
                                                    <input type="text" name="domain_name" id="domain_name" class="form-control" placeholder="Enter domain"
                                                        id="">
                                                </div>
                                            </div>
                                            <!--end col-->
                                            <!-- <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="" class="form-label">Maximum User Count</label>
                                                    <input type="text" class="form-control"
                                                        placeholder="Enter user count" id="max_user" name="max_user">
                                                </div>
                                            </div> -->
                                            <!--end col-->
                                            <!-- <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="compnayNameinput" class="form-label">Maximum Alias
                                                        Count</label>
                                                    <input type="text" class="form-control"
                                                        placeholder="Enter alias count" id="max_alias" name="max_alias">
                                                </div>
                                            </div> -->
                                            <!--end col-->
                                            <!-- <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="" class="form-label">Comment</label>
                                                    <input type="text" class="form-control" placeholder="Write Comment"
                                                        id="comment" name="comment">
                                                </div>
                                            </div> -->
                                            <!--end col-->
                                            <div class="col-lg-12">
                                                <div class="text-end">
                                                    <button type="submit" class="btn btn-primary">Save</button>
                                                </div>
                                            </div>
                                            <!--end col-->
                                        </div>
                                        <!--end row-->
                                    </form>
                                </div>


                            </div>
                        </div> <!-- end col -->

                        <!-- end col -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- domain details Modal -->
    <div class="modal fade" id="domaindetails" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalgridLabel">Configure Domain</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body verify-domain-body">

                </div>
            </div>
        </div>
    </div>
    <!-- Create mail address Modal -->
    <div class="modal fade" id="addmail" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalgridLabel">Create Email</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body addmail-body">

                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#domainForm').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    url: '/store-domain',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            // Close the modal
                            $('#formModal').modal('hide');
                            // You can also clear the form if needed
                            $('#domainForm')[0].reset();

                            // Show success message
                            alert('Domain created successfully');
                            location.reload();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                        alert('There was an error while submitting the form');
                    }
                });
            });

            $(document).on('click', '#domain_details', function() {
                let domainId = $(this).data("domainid");
                $.ajax({
                    url: '/verfy_domain/' + domainId,
                    type: 'GET',
                    success: function(response) {
                        $("#domaindetails").modal("show");
                        $('.verify-domain-body').html(response);
                    },
                    error: function(xhr) {
                        alert("Something went wrong");
                    }
                });
            });



            $(document).on('click', '.dkim_gen', function(e) {
                e.preventDefault();
                let domainId = $(this).data("domainid");
                let dataToSend = {
                    domainid: domainId,
                    _token: "{{ csrf_token() }}"
                };

                $.ajax({
                    url: '/generate-dkim',
                    type: 'POST',
                    data: dataToSend,
                    success: function(response) {
                        console.log(response);
                        if (response.status) {
                            console.log("DKIM created successfully:", response.dkim);
                            $('.dkim_txt').val(response.dkim.txt_name);
                            $('.dkim_val').val(response.dkim.txt_value);
                        } else {
                            console.warn("Error:", response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("Something went wrong: " + error);
                        console.log(xhr.responseText);
                    }
                });
            });

        })
    </script>
    @endsection