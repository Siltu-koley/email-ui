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
                                <a href="#" class="btn btn-outline-primary add-ip-btn">
                                Add IP address</span>
                                </a>

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
                                            @if (count($zonedata) > 0)
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
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div><!--end col-->
                    </div><!--end row-->



                    <div class="modal ip-add-modal fade" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">All available IPs</h5>

                            </div>
                            <div class="modal-body">
                                <div class="all-address-cont d-none">
                                    <p>Here you can add IPs to your zone.</p>
                                    <div class="ip-list">
                                        <ul class="list-unstyled all_ips_data_here">
                                            <li>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span>127.0.0.1</span>
                                                    <div>
                                                        <input type="button" data-address="127.0.0.1" value="Add IP" class="btn btn-primary add-ip-to-vm"/>
                                                        <div>
                                                            <div class="spinner-border" role="status">
                                                                <span class="sr-only"></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="empty-address-cont d-none">
                                    <div class="alert alert-info" role="alert">
                                    <h4 class="alert-heading">No IP address!</h4>
                                    <p>To add a dedicated IP address to your virtual machine, you’ll need to purchase one first.
                                        Once the purchase is complete, you can easily assign it to your VM through the management panel.</p>
                                    <hr>
                                    <p class="mb-0">👉 Click here to purchase an IP address <a class="text-decoration-underline" target="_blank" href="https://us.ovhcloud.com/manager/#/public-cloud/pci/projects/314d083a02054b4086b46232ac65ac0b/public-ips/order">OVH Console</a> </p>
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                {{-- <button type="button" class="btn btn-primary">Add IP to Zone</button> --}}
                            </div>
                            </div>
                        </div>
                    </div>


            </div>
            <!-- container-fluid -->

        </div>
        <!-- End Page-content -->


        <script>
            $(document).ready(function() {

                $('.add-ip-btn').on('click', function(event) {
                    event.preventDefault();

                    // $('.ip-add-modal').modal('show');

                    $.ajax({
                        url: '/ovh/additonal_ips',
                        type: 'GET',
                        success: function(dataadditonalips) {

                            if(dataadditonalips.status == 'success') {
                                let vm_id = dataadditonalips.vm_id;
                                let allips = dataadditonalips.data;
                                let ipList = '';
                                let ipListHtml = '';
                                console.log(allips,allips.length);
                                if(allips.length == 0){
                                    // alert("inside  0 data");
                                    $('.empty-address-cont').removeClass('d-none');
                                    $('.all-address-cont').addClass('d-none');
                                }else{
                                    $('.all-address-cont').removeClass('d-none');
                                    $('.empty-address-cont').addClass('d-none');
                                }
                                allips.forEach(function(ipaddress) {
                                    // ipListHtml += '<li>' + ipaddress.ip + '</li>';
                                    ipListHtml +=`
                                    <li>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span>${ipaddress.ip}</span>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <input type="button" data-address="${ipaddress.ip}" data-id="${ipaddress.id}" value="Add IP" class="btn btn-primary add-ip-to-vm"/>
                                                <div class="px-2 spinner-lodaer-${ipaddress.id} d-none">
                                                    <div class="spinner-border" role="status">
                                                        <span class="sr-only"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>`;

                                });
                                ipListHtml += '';

                                // $('.all-address-cont').removeClass('d-none');
                                $('.modal-body .all_ips_data_here').html(ipListHtml);
                            }else{
                                $('.all-address-cont').removeClass('d-none')
                                let errorMessage = dataadditonalips.message;
                                $('.modal-body .all_ips_data_here').html('<p>' + errorMessage + '</p>');
                            }
                            $('.ip-add-modal').modal('show');
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching IPs:', error);
                        }
                    });

                });

                $(document).on('click', '.add-ip-to-vm', function(event){
                    event.preventDefault();
                    var attached_address = $(this).attr('data-address');
                    var data_id = $(this).attr('data-id');
                    $('.spinner-lodaer-'+data_id).removeClass('d-none');
                    $.ajax({
                        url: '/ovh/attached_additional_ip_to_vm?attached_ip='+attached_address,
                        type: 'GET',
                        success: function(datastatus) {

                            if(datastatus.status == "success"){
                                $('.spinner-lodaer-'+data_id).addClass('d-none');
                                alert(datastatus.message);
                            }else{
                                $('.spinner-lodaer-'+data_id).addClass('d-none');
                                alert("Something went wrong! Please try again");
                            }

                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching IPs:', error);
                            alert("Something went wrong! Please try again.");
                        }
                    });
                });



            });

            // $(document).ready(function() {
            //     $('#example').DataTable();
            // });
        </script>

            @endsection
