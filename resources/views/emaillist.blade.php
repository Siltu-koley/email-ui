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
                            <div class="page-title-left">
                            <h4 class="mb-sm-0" style="margin-bottom: 0;">Emails</h4>
                            <p class="mb-sm-0" style="margin-top: 5px; display:block;">{{ $domains->domain_name }}</p>
                            </div>
                            
                            <div class="page-title-right">
                            <a href="{{ '/domains'}}" class="btn btn-outline-primary">
                            View Domains</span>
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
                            <!-- <div class="card-header">
                                <h5 class="card-title mb-0">Domains list for </h5>
                            </div> -->
                            <div class="card-body">
                                <table id="example"
                                    class="table table-bordered dt-responsive nowrap table-striped align-middle"
                                    style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Email</th>
                                            <th>Added By</th>
                                            <th>Added On</th>
                                            <th>Email Sent</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($emails))
                                        @foreach($emails as $email)
                                        <tr>
                                            <td>{{ $email->email }}</td>
                                            @php
                                            $added_by = getuser($email->created_by);
                                            $formattedDate = \Carbon\Carbon::parse($email->created_at)->format('jS F Y, h:ia');
                                            $emailcount = emailcout($email->id);
                                            @endphp
                                            <td>{{ $added_by->name }}</td>

                                            <td>{{ $formattedDate }}</td>
                                            <td>{{ $emailcount }}</td>
                                            <td>
                                            <!-- <a href="/mailbox/{{ $email->id}}" class="btn p-0" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="mailbox">
                                                <i class="ri-mail-line"></i>
                                            </a> -->
                                            <a href="#" class="btn p-0 sendmail" id="sendmail" data-emailid="{{ $email->id }}">
                                            <span style="color: blue;">Send Test Email</span>
                                            </a>
                                            </td>
                                            <td>
                                            <a href="#" class="btn p-0 emailconfig" id="emailconfig" data-emailid="{{ $email->id }}">
                                            <span style="color: blue;">Email Config</span>
                                            </a>
                                            </td>
                                            <td>
                                            <a href="#" class="btn p-0 password" id="password" data-emailid="{{ $email->id }}" data-passwd="{{ $email->password }}" data-email="{{ $email->email }}">
                                            <span style="color: blue;">Password</span>
                                            </a>
                                            </td>
                                            <td>
                                            <a href="#" class="btn p-0 delete_mail" id="delete_mail" data-emailid="{{ $email->id }}">
                                            <span style="color: blue;">Delete</span>
                                            </a>
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
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #e7e1e1; height: 30px;">
                        <p>Add Email</p>
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
                                                    <button type="submit" class="btn btn-primary">Send</button>
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

    <!-- emailconfig details Modal -->
    <div class="modal fade" id="emailconfigmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header" style="background-color: #e7e1e1; height: 30px;">
                    <p>Email Config</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body email-config-body">

                </div>
            </div>
        </div>
    </div>

    <!-- update password Modal -->
    <div class="modal fade" id="passwordmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header" style="background-color: #e7e1e1; height: 30px;">
                    <p>Password</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <div class="mb-3 fw-bold" id="displayemail">someone@somaedomain.com</div>
                    <div class="mb-4" style="padding: 15px;border:1px solid #bfbfbf;background: #fefce9;">
                        <label class="form-label">Current Password</label>
                        <div class="" id="passwd"></div>
                    </div>
                    <div style="background: #98a9e540;border: 1px solid #bfbfbf;padding: 15px;">
                        <form id="savepass">
                            @csrf
                            <label for="iconrightInput" class="form-label d-block">Update Password</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="newpassword" id="newpassword">
                                <input type="hidden" class="form-control" name="email_id" id="email_id" value="">
                                <button class="btn btn-primary" id="savepassbtn" type="submit" style="margin-left:10px; border-radius:6px;">Save</button>
                            </div>
                        </form>
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
                        
                        const emailstring = $('#emailstring').val();
                        if (emailstring.includes('@')) {
                            alert('Please do not include "@domain.com" — it will be added automatically.');
                            $("#emailstring").val('');
                            return;
                        }
                        if(emailstring.length === 0){
                            alert('please enter email');
                            return;
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

                    $(document).on('click', '.sendmail', function(e) {
                        e.preventDefault();
                        let emailId = $(this).data("emailid");
                        $("#sendmail_modal").modal("show");
                        $('#sender').val(emailId);
                    });

            $('#sendmailForm').submit(function(e) {
                e.preventDefault();
                var $submitBtn = $('#sendmailForm button[type="submit"]');
                var originalBtnText = $submitBtn.html();

                // Disable the button and show loader
                $submitBtn.prop('disabled', true);
                $submitBtn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...');
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
                    },
                    complete: function() {
                        // Re-enable the button and reset its text
                        $submitBtn.prop('disabled', false);
                        $submitBtn.html(originalBtnText);
                    }
                });
            });

            $(document).on('click', '.emailconfig', function(e){
                e.preventDefault();
                let emailid = $(this).data("emailid");
                $.ajax({
                    url: '/emailconfig/' + emailid,
                    type: 'GET',
                    success: function(response) {
                        $("#emailconfigmodal").modal("show");
                        $('.email-config-body').html(response);
                    },
                    error: function(xhr) {
                        alert("Something went wrong");
                    }
                });
            });


            var selectedIps = [];
            $(document).on('click', '#add-ip-btn', function(){
                let routing_ip = $('#routing_ip').val();
                let emails_id = $('#email_id').val();
                let dataToSend = {
                    routing_ip: routing_ip,
                    emails_id: emails_id,
                    _token: "{{ csrf_token() }}"
                };
                $.ajax({
                    url: '/add_routing_ip',
                    type: 'POST',
                    data: dataToSend,
                    success: function(response) {
                        console.log(response);
                        if (response.status) {
                            alert(response.message);
                            var newIpDiv = `
                            <div class="mb-3 d-flex align-items-center justify-content-between">
                                <span>${routing_ip}</span>
                                <a href="#" class="remove-ip">Remove</a>
                            </div>
                        `;

                        // Append the new IP div to the ip_list
                        $('.ip_list').append(newIpDiv);
                        } else {
                            console.warn("Error:", response.message);
                            alert(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("Something went wrong: " + error);
                        console.log(xhr.responseText);
                    }
                });
            });


            $(document).on('click', '.remove-ip', function(e) {
                e.preventDefault();
                var ipToRemove = $(this).siblings('span').text();
                let emails_id = $('#email_id').val();
                let dataToSend = {
                    routing_ip: ipToRemove,
                    emails_id: emails_id,
                    _token: "{{ csrf_token() }}"
                };
                var $this = $(this);
                $.ajax({
                    url: '/remove_routing_ip',
                    type: 'POST',
                    data: dataToSend,
                    success: function(response) {
                        console.log(response);
                        if (response.status) {
                            $this.closest('.mb-3.d-flex').remove();
                            alert(response.message);
                            
                        } else {
                            console.warn("Error:", response.message);
                            alert(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("Something went wrong: " + error);
                        console.log(xhr.responseText);
                    }
                });

                
            });

            $(document).on('click', '.password', function(){
                let email_id = $(this).data("emailid");
                let passwd = $(this).data("passwd");
                let email = $(this).data("email");
                $("#passwordmodal").modal("show");
                $("#passwd").text(passwd);
                $("#displayemail").text(email);
                $("#email_id").val(email_id);
            });

                $(document).on('submit', '#savepass', function(e) {
                    e.preventDefault();
                    var formData = $(this).serialize();
                    
                $.ajax({
                    url: '/update_smtp_pass',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.status) {
                            alert(response.message);
                            location.reload();
                        } else {
                            console.warn("Error:", response.message);
                            alert(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("Something went wrong: " + error);
                        console.log(xhr.responseText);
                    }
                });
            })

            $(document).on('click', '.delete_mail', function(){
                let email_id = $(this).data("emailid");
                if (confirm('Are you sure you want to delete this email?')) {
            $.ajax({
                url: '/delete_mail/'+ email_id,
                type: 'DELETE',
                data: {
                _token: "{{ csrf_token() }}"
            },
                success: function(response) {
                    console.log(response);
                    if (response.status) {
                        alert("Email deleted successfully");
                        location.reload();
                    } else {
                        console.warn("Error:", response.message);
                        alert("Something went wrong");
                    }
                },
                error: function(xhr, status, error) {
                    alert("Something went wrong: " + error);
                    console.log(xhr.responseText);
                }
            });
        }
            });

        });

                // Listen for Bootstrap modal shown event
                    document.getElementById('addmail').addEventListener('shown.bs.modal', function () {
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