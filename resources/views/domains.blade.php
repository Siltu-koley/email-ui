@extends('layout/master')
@section('content')

<style>
    .form-control:disabled {
    color: #0d0e0e;
    background-color: var(--tb-tertiary-bg);
    opacity: 1;
}
</style>
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
                        <h4 class="mb-sm-0">Domains</h4>

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
                        <!-- <div class="card-header">
                            <h5 class="card-title mb-0">Domains list</h5>
                        </div> -->
                        <div class="card-body">
                            <table id="example"
                                class="table table-bordered dt-responsive nowrap table-striped align-middle"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Domain</th>
                                        <th>Added By</th>
                                        <th>Added On</th>
                                        <th>Emails</th>
                                        <th>Email Sent</th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($domaindata))
                                    @foreach($domaindata as $domain)
                                    @php
                                    $formattedDate = \Carbon\Carbon::parse($domain['created_at'])->format('jS F Y, h:ia');
                                    @endphp
                                    <tr>
                                        <td><a href="{{ '/emaillist/' . $domain['id'] }}" class="btn p-0">
                                        <span style="color: blue;">{{ $domain['domain_name'] }}</span>
                                        </a></td>
                                        <td>{{ $domain['name'] }}</td>
                                        <td>{{ $formattedDate}}</td>
                                        <td>{{ $domain['emailcount'] }}</td>
                                        <td>{{ $domain['mailsent']}}</td>
                                        <td>
                                            <a href="#" class="btn p-0 default_route" data-domainid="<?php echo $domain['id'] ?>">
                                                <span style="color: blue;">Default Route</span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="btn p-0 domain_details" id="domain_details" data-domainid="<?php echo $domain['id'] ?>">
                                                <span style="color: blue;">DKIM/DNS Info</span>
                                                </button>
                                        </td>
                                        <td>
                                            <a href="#" class="btn p-0 delete_domain" id="delete_domain" data-domainid="<?php echo $domain['id'] ?>" data-emailcount="<?php echo $domain['emailcount'] ?>">
                                                <span style="color: blue;">Delete</span>
                                                </button>
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
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="" class="form-label">Enter Domain Name</label>
                                                    <input type="text" class="form-control" id="domain_name" name="domain_name" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="" class="form-label">Select Outgoing Zone</label>
                                                    <select class="form-control" id="zone" name="zone" required>
                                                        <option value="" disabled selected>Select Zone</option>
                                                        @foreach($zones as $zone)
                                                        <option value="{{ $zone->id }}">{{ $zone->zone }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3" style="background: #ffffdd;padding:15px;border:1px solid #eee;">
                                                    <div class="input-group">
                                                        <select class="form-select" id="routing_ip" name="routing_ip" aria-label="Example select with button addon">
                                                            <option disabled selected="">Choose ip</option>
                                                        </select>
                                                        <button class="btn btn-primary" type="button" id="add-ip-btn">Add</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-3 ip_list">
                                                <h5 class="mb-3">Ipv4 Address</h5>
                                                
                                            </div>

                                            <div class="col-lg-12">
                                                <div class="text-end">
                                                    <button type="submit" class="btn btn-success">Add Domain</button>
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
                <div class="modal-header" style="background-color: #e7e1e1;">
                    <p>DKIM/DNS info</p>
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

    <!-- default route Modal -->
    <div class="modal fade" id="default_route_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="height: 30px;background-color: #e9e9e9;">
                    <p>Default route</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                <div class="row">
                        <div class="col-xxl-12">
                            <div class="card">
                            <h5 class="modal-title" id="modlabel"></h5>
                            <hr>
                                <div class="card-body">
                                    <form id="defaultrouteForm">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="mb-3" style="background: #ffffdd;padding:15px;border:1px solid #eee;">
                                                    <div class="input-group">
                                                        <select class="form-select" id="routing_ip_2" name="routing_ip_2" aria-label="Example select with button addon">
                                                            <option disabled selected="">Choose ip</option>
                                                        </select>
                                                        <button class="btn btn-primary" type="button" id="add-ip-btn_2">Add</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" id="domain_id" name="domain_id" value="">
                                            <div class="mb-3 ip_list_2">
                                                <h5 class="mb-3">Ipv4 Address</h5>
                                                
                                            </div>

                                            <div class="col-lg-12">
                                                <div class="text-end">
                                                    <button type="submit" class="btn btn-success">Update Route</button>
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

            $(document).on('change', '#zone', function() {
                var zone_id = $(this).val();
                $.ajax({
                    url: '/getzoneip/' + zone_id,
                    type: 'GET',
                    success: function(response) {
                        const $select = $('#routing_ip');
                            $select.empty();
                            response.forEach(ip => {
                                $select.append($('<option>', {
                                    value: ip,
                                    text: ip
                                }));
                            });
                    },
                    error: function(xhr) {
                        alert("Something went wrong");
                    }
                });
            });

            var selectedIps = [];

            $('#add-ip-btn').click(function() {
                // Get the selected IP from the dropdown
                var selectedIp = $('#routing_ip').val();

                // Check if an IP is selected
                if (selectedIp) {
                    // Check if the IP is already in the selectedIps array
                    if (selectedIps.includes(selectedIp)) {
                        alert('This IP is already added.');
                    } else {
                        // Create a new IP div element
                        var newIpDiv = `
                            <div class="mb-3 d-flex align-items-center justify-content-between">
                                <span>${selectedIp}</span>
                                <a href="#" class="remove-ip">Remove</a>
                            </div>
                        `;

                        // Append the new IP div to the ip_list
                        $('.ip_list').append(newIpDiv);

                        // Add the selected IP to the selectedIps array
                        selectedIps.push(selectedIp);

                        // Optionally, reset the dropdown after adding the IP
                        $('#routing_ip').val('');
                    }
                } else {
                    alert('Please select an IP address first.');
                }
            });

            $(document).on('click', '.remove-ip', function(e) {
                e.preventDefault();
                var ipToRemove = $(this).siblings('span').text();

                // Remove the IP from the selectedIps array
                selectedIps = selectedIps.filter(function(ip) {
                    return ip !== ipToRemove;
                });

                // Remove the parent div (the whole IP block)
                $(this).closest('.mb-3.d-flex').remove();
            });

            $('#domainForm').submit(function(e) {
                e.preventDefault();
                if (selectedIps.length === 0) {
                    alert('Please Select Zone and add at least one IP before submitting the form.');
                    return;
                }
                selectedIps.forEach(function(ip, index) {
                    $('#domainForm').append(`<input type="hidden" name="selected_ips[]" value="${ip}">`);
                });
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
                            console.log(response.domain_id);
                            generateDkim(response.domain_id)
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

            $(document).on('click', '.domain_details', function() {
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

            $(document).on('click', '.default_route', function(e) {
                    e.preventDefault();
                let domainId = $(this).data("domainid");
                $.ajax({
                    url: '/get_domain/' + domainId,
                    type: 'GET',
                    success: function(response) {
                        $("#default_route_modal").modal("show");
                        $("#modlabel").text(response.domain_details.domain_name);
                        $("#domain_id").val(response.domain_details.id);
                        var selectedIps_2 = [];

                        const $select = $('#routing_ip_2');
                            $select.empty();
                            response.zone_ips.forEach(ip => {
                                $select.append($('<option>', {
                                    value: ip,
                                    text: ip
                                }));
                            });
                            
                        if(response.domain_details.default_ips.length !== 0){
                            let iplist = JSON.parse(response.domain_details.default_ips);
                            iplist.forEach(function(ip, index) {
                                $('.ip_list_2').append(`<div class="mb-3 d-flex align-items-center justify-content-between">
                                            <span>${ip}</span>
                                            <a href="#" class="remove-ip_2">Remove</a>
                                        </div>`);
                                selectedIps_2.push(ip);
                            });
                        }

                        $('#add-ip-btn_2').click(function() {
                            // Get the selected IP from the dropdown
                            var selectedIp = $('#routing_ip_2').val();

                            // Check if an IP is selected
                            if (selectedIp) {
                                // Check if the IP is already in the selectedIps array
                                if (selectedIps_2.includes(selectedIp)) {
                                    alert('This IP is already added.');
                                } else {
                                    // Create a new IP div element
                                    var newIpDiv = `
                                        <div class="mb-3 d-flex align-items-center justify-content-between">
                                            <span>${selectedIp}</span>
                                            <a href="#" class="remove-ip_2">Remove</a>
                                        </div>
                                    `;

                                    // Append the new IP div to the ip_list
                                    $('.ip_list_2').append(newIpDiv);

                                    // Add the selected IP to the selectedIps array
                                    selectedIps_2.push(selectedIp);

                                    // Optionally, reset the dropdown after adding the IP
                                    $('#routing_ip_2').val('');
                                }
                            } else {
                                alert('Please select an IP address first.');
                            }
                        });
                        /////////////////////////////////////
                        $(document).on('click', '.remove-ip_2', function(e) {
                            e.preventDefault();
                            var ipToRemove = $(this).siblings('span').text();

                            // Remove the IP from the selectedIps array
                            selectedIps_2 = selectedIps_2.filter(function(ip) {
                                return ip !== ipToRemove;
                            });

                            // Remove the parent div (the whole IP block)
                            $(this).closest('.mb-3.d-flex').remove();
                        });
                        //////////////////////

                        $('#defaultrouteForm').submit(function(e) {
                            e.preventDefault();
                            if (selectedIps_2.length === 0) {
                                alert('Number of ip can not be 0');
                                return;
                            }
                            selectedIps_2.forEach(function(ip, index) {
                                $('#defaultrouteForm').append(`<input type="hidden" name="selected_ips[]" value="${ip}">`);
                            });
                            var formData = $(this).serialize();
                            $.ajax({
                                url: '/update_route',
                                type: 'POST',
                                data: formData,
                                success: function(response) {
                                    if (response.success) {
                                        // Close the modal
                                        $('#default_route_modal').modal('hide');
                                        // You can also clear the form if needed
                                        $('#defaultrouteForm')[0].reset();

                                        // Show success message
                                        alert('Route ip updated successfully');
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
                    },
                    error: function(xhr) {
                        alert("Something went wrong");
                    }
                });
            });



                
            function generateDkim(domainId){
                
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
                            // $('.dkim_txt').val(response.dkim.txt_name);
                            // $('.dkim_val').val(response.dkim.txt_value);
                        } else {
                            console.warn("Error:", response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("Something went wrong: " + error);
                        console.log(xhr.responseText);
                    }
                });
            }

        $(document).on('click', '.delete_domain', function(e){
            e.preventDefault();
            let domainId = $(this).data("domainid");
            let emailcount = $(this).data("emailcount");
            if(emailcount > 0){
                alert("Can't delete, There is active mail id with this domain");
                return;
            }
            if (confirm('Are you sure you want to delete this domain?')) {
            $.ajax({
                url: '/delete_domain/'+ domainId,
                type: 'DELETE',
                data: {
                _token: "{{ csrf_token() }}"
            },
                success: function(response) {
                    console.log(response);
                    if (response.status) {
                        alert("Domain deleted successfully");
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

        })
    </script>
    @endsection