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
                            <h4 class="mb-sm-0">Email List for {{ $domains->domain_name }}</h4>

                            <div class="page-title-right">
                            <a href="{{ '/domains'}}" class="btn p-0">
                            <span style="color: green;"><i class="ri-arrow-left-line"></i>back to domains</span>
                            </a>
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
                                            <th>Email</th>
                                            <th>SMTP User</th>
                                            <th>SMTP Password</th>
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
                                            <td>{{ $email->username }}</td>
                                            <td>{{ $email->password }}</td>
                                            <td>{{ $email->created_at}}</td>
                                            <td>
                                            <!-- <a href="/mailbox/{{ $email->id}}" class="btn p-0" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="mailbox">
                                                <i class="ri-mail-line"></i>
                                            </a> -->
                                            <a href="#" class="btn p-0" id="sendmail" data-emailid="{{ $email->id }}">
                                            <span style="color: green;">Send test mail</span>
                                            </a>
                                            </td>
                                            <td>
                                                <!-- <button type="button" class="btn p-0" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Details">
                                                    <i class="ri-list-unordered"></i>
                                                </button> -->
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

    <!-- Send mail Modal -->
    <div class="modal fade" id="sendmail_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalgridLabel">Send Test Mail</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <div class="row">
                        <div class="col-xxl-12">
                            <div class="card">

                                <div class="card-body">
                                    <form id="sendmailForm">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="" class="form-label">To </label>
                                                    <input type="text" name="to_mail" id="to_mail" class="form-control" placeholder="Enter Receipent mail address" required>
                                                </div>
                                            </div>
                                            <input type="hidden" name="sender" id="sender" value="">
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="" class="form-label">Subject </label>
                                                    <input type="text" name="subject" id="subject" class="form-control" placeholder="Enter Receipent mail address" required>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="" class="form-label">Message</label>
                                                    <textarea type="text" class="form-control" placeholder="Write message"
                                                    id="message" name="message" required></textarea>
                                                </div>
                                            </div>
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
                        const password = $('#password').val();
                        const confirmPassword = $('#confirm_password').val();
                        if (password === confirmPassword && password.length > 0) {
                            $('#message').text('Passwords match').css('color', 'green');
                        } else {
                            $('#message').text('Passwords do not match').css('color', 'red');
                            return false;
                        }

                        const emailstring = $('#emailstring').val();
                        if (emailstring.includes('@')) {
                            alert('Please do not include "@" — it will be added automatically.');
                            $("#emailstring").val('');
                        }
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

                    $(document).on('click', '#sendmail', function(e) {
                        e.preventDefault();
                        let emailId = $(this).data("emailid");
                        $("#sendmail_modal").modal("show");
                        $('#sender').val(emailId);
                    });

            $('#sendmailForm').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    url: '/sendmail',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            // Close the modal
                            $('#sendmail_modal').modal('hide');
                            // You can also clear the form if needed
                            $('#sendmailForm')[0].reset();

                            // Show success message
                            alert('mail sent successfully');
                            // location.reload();
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

                });

                // Listen for Bootstrap modal shown event
                    document.getElementById('addmail').addEventListener('shown.bs.modal', function () {
                        // Check if Choices is already loaded
                        if (typeof Choices === "undefined") {
                            let script = document.createElement('script');
                            script.src = `{{ asset('/assets/js/choices.min.js')}}`;
                            script.onload = function () {
                                initializeChoices();
                            };
                            document.body.appendChild(script);
                        } else {
                            initializeChoices();
                        }
                    });

                    function initializeChoices() {
                        document.querySelectorAll('[data-choices]').forEach((el) => {
                            new Choices(el);
                        });
                    }
            </script>
            @endsection