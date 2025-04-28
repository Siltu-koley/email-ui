@extends('layout/master')
@section('content')

<!-- Begin page -->
<div id="layout-wrapper">

    

    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Email List for {{ $domains->domain_name }}</h4>

                            <div class="page-title-right">
                                <button type="button" class="btn btn-primary" id="add_mail" data-domainid="{{ $domains->id }}">Add Email</button>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Domains list for </h5>
                            </div>
                            <div class="card-body">
                                <table id="example"
                                    class="table table-bordered dt-responsive nowrap table-striped align-middle"
                                    style="width:100%">
                                    <thead>
                                        <tr>
                                            <th data-ordering="false">SR No.</th>
                                            <th>Address</th>
                                            <th>User</th>
                                            <th>Create Date</th>
                                            <th>Manage</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <?php $i = 1; ?>
                                    <tbody>
                                        @if(!empty($emails))
                                        @foreach($emails as $email)
                                        <tr>
                                            <td><?php echo $i;
                                                $i++;  ?></td>
                                            <td>{{ $email->email }}</td>
                                            <td>{{ $email->userid }}</td>
                                            <td>{{ $email->created_at}}</td>
                                            <td>
                                            <a href="/mailbox/{{ $email->id}}" class="btn p-0" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="mailbox">
                                                <i class="ri-mail-line"></i>
                                            </a>
                                                <button type="button" class="btn p-0" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Aliases">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </td>
                                            <td>
                                                <button type="button" class="btn p-0" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Details">
                                                    <i class="ri-list-unordered"></i>
                                                </button>
                                                <button type="button" class="btn p-0" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Edit">
                                                    <i class="ri-pencil-line"></i>
                                                </button>
                                                <button type="button" class="btn p-0" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Delete">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr>
                                            <td colspan="8">No emails available</td>
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

                    $(document).on('click', '#add_mail', function() {
                        let domainId = $(this).data("domainid");
                        $.ajax({
                            url: '/add_mail/' + domainId,
                            type: 'GET',
                            success: function(response) {
                                $("#addmail").modal("show");
                                $('.addmail-body').html(response);
                            },
                            error: function(xhr) {
                                alert("Something went wrong");
                            }
                        });
                    });


                    $(document).on('submit', '#createMail', function(e) {
                        e.preventDefault();
                        var formData = $(this).serialize();
                        $.ajax({
                            url: '/create_mail',
                            type: 'POST',
                            data: formData,
                            success: function(response) {
                                console.log(response);
                                if (response.success) {
                                    $('#addmail').modal('hide');
                                    $('#createMail')[0].reset();
                                    alert('Email created successfully');
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

                })
            </script>
            @endsection