<div class="row">
    <div class="col-xxl-12">
        <div class="card">
        <h5 class="mb-xl-0">{{ $emails->email_id }}</h5>
            <div class="card-header align-items-xl-center d-xl-flex">
                <div class="flex-shrink-0">
                    <ul class="nav nav-pills card-header-pills" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" data-bs-toggle="tab" href="#smtp" role="tab"
                                aria-selected="false" tabindex="-1">
                                SMTP
                            </a>
                        </li>
                        <!-- <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" href="#pop3" role="tab"
                                aria-selected="false" tabindex="-1">
                                POP3
                            </a>
                        </li> -->
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" href="#imap" role="tab"
                                aria-selected="true">
                                IMAP
                            </a>
                        </li>
                        <!-- <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" href="#api" role="tab"
                                aria-selected="true">
                                API
                            </a>
                        </li> -->
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" href="#iproute" role="tab"
                                aria-selected="true">
                                IP Route
                            </a>
                        </li>
                    </ul>
                </div>
            </div><!-- end card header -->
            <hr>
            <div class="card-body">
                <!-- Tab panes -->
                <div class="tab-content text-muted">
                    <div class="tab-pane active" id="smtp" role="tabpanel">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3" style="padding: 15px;background: beige;border: 1px solid #cbcbcb;">
                                    <p>SMTP Info</p>
                                    <div class="table-responsive">
                                    <table class="table table-borderless align-middle table-nowrap mb-0">
                                        
                                            <tr>
                                                <th>SMTP Server</th>
                                                <!-- <td>{{ $ip_array[0] }}</td> -->
                                                <td>smtp.sendengage.com</td>
                                            </tr>
                                            <tr>
                                                <th>SMTP User</th>
                                                <td>{{ $emails->email_id }}</td>
                                            </tr>
                                            <tr>
                                                <th>SMTP Port</th>
                                                <td>587</td>
                                            </tr>
                                            <tr>
                                                <th>SSL/TSL</th>
                                                <td>Yes</td>
                                            </tr>
                                    </table>
                                </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="pop3" role="tabpanel">
                        <div class="row">
                        <div class="col-md-12">
                                <div class="mb-3" style="padding: 15px;background: beige;border: 1px solid #cbcbcb;">
                                    <h5>POP3</h5>
                                    <p>POP3 Info</p>
                                    <div class="table-responsive">
                                    <table class="table table-borderless align-middle table-nowrap mb-0">
                                        
                                            <tr>
                                                <th>POP3 Server</th>
                                                <td>15.204.28.113</td>
                                            </tr>
                                            <tr>
                                                <th>POP3 User</th>
                                                <td>{{ $emails->email_id }}</td>
                                            </tr>
                                            <tr>
                                                <th>POP3 Port</th>
                                                <td>110</td>
                                            </tr>
                                            <tr>
                                                <th>SSL/TSL</th>
                                                <td>No</td>
                                            </tr>
                                    </table>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="imap" role="tabpanel">
                        <div class="row">
                        <div class="col-md-12">
                                <div class="mb-3" style="padding: 15px;background: beige;border: 1px solid #cbcbcb;">
                                    <h5>IMAP</h5>
                                    <p>IMAP Info</p>
                                    <div class="table-responsive">
                                    <table class="table table-borderless align-middle table-nowrap mb-0">
                                        
                                            <tr>
                                                <th>IMAP Server</th>
                                                <td>imap.sendengage.com</td>
                                            </tr>
                                            <tr>
                                                <th>IMAP User</th>
                                                <td>{{ $emails->email_id }}</td>
                                            </tr>
                                            <tr>
                                                <th>IMAP Port</th>
                                                <td>993</td>
                                            </tr>
                                            <tr>
                                                <th>SSL/TSL</th>
                                                <td>Yes</td>
                                            </tr>
                                    </table>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="api" role="tabpanel">
                        <div class="row">
                        <div class="col-md-12">
                                <div class="mb-3" style="padding: 15px;background: beige;border: 1px solid #cbcbcb;">
                                    <h5>API</h5>
                                    <p>Coming Soon..
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="iproute" role="tabpanel">
                        <div class="row">
                        <div class="col-md-12">
                            
                                <form id="defaultrouteForm">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-3" style="background: #ffffdd;padding:15px;border:1px solid #eee;">
                                                <div class="input-group">
                                                    <select class="form-select" id="routing_ip" name="routing_ip" aria-label="Example select with button addon">
                                                        <option disabled selected="">Choose ip</option>
                                                        @foreach($ip_array as $ip)
                                                        <option value="{{ $ip }}">{{ $ip }}</option>
                                                        @endforeach
                                                    </select>
                                                    <input type="hidden" name="email_id" id="email_id" value="{{ $emails->user_emails_id }}">
                                                    <button class="btn btn-primary" type="button" id="add-ip-btn" style="margin-left: 6px; border-radius:6px;">Add</button>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" id="domain_id" name="domain_id" value="">
                                        <div class="mb-3 ip_list">
                                            <h5 class="mb-3">Ipv4 Address</h5>
                                            @if($emails->routing_ips != '')
                                            @foreach(json_decode($emails->routing_ips, true) as $selectedIp)
                                            <div class="mb-3 d-flex align-items-center justify-content-between">
                                                <span>{{ $selectedIp }}</span>
                                                <a href="#" class="remove-ip">Remove</a>
                                            </div>
                                            @endforeach
                                            @endif
                                        </div>

                                        <!-- <div class="col-lg-12">
                                            <div class="text-end">
                                                <button type="submit" class="btn btn-success">Update Route</button>
                                            </div>
                                        </div> -->
                                        <!--end col-->
                                    </div>
                                    <!--end row-->
                                </form>
                                </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- end col -->

    <!-- end col -->
</div>