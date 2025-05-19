<div class="row">
    <div class="col-xxl-12">
        <div class="card">
            <div class="card-header align-items-xl-center d-xl-flex">
                <p class="text-muted flex-grow-1 mb-xl-0">{{ $domain->domain_name }}</p>
                <div class="flex-shrink-0">
                    <ul class="nav nav-pills card-header-pills" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" data-bs-toggle="tab" href="#dkim" role="tab"
                                aria-selected="false" tabindex="-1">
                                DKIM
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" href="#dmarc" role="tab"
                                aria-selected="false" tabindex="-1">
                                DMARC
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" href="#spf" role="tab"
                                aria-selected="true">
                                SPF
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" href="#mxrecord" role="tab"
                                aria-selected="true">
                                MX Record
                            </a>
                        </li>
                    </ul>
                </div>
            </div><!-- end card header -->
            <hr>
            <div class="card-body">
                <!-- Tab panes -->
                <div class="tab-content text-muted">
                    <div class="tab-pane active" id="dkim" role="tabpanel">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3" style="padding: 15px;background: beige;border: 1px solid #cbcbcb;">
                                    <h5>DKIM</h5>
                                    <p>DKIM (DomainKeys Identified Mail) is an email authentication method that verifies if an email was sent by an authorized server and hasn’t been altered. It uses digital signatures and public-key cryptography to ensure email integrity and prevent spoofing.
                                    </p>
                                    <ul>
                                        <li>DKIM verifies email integrity by checking the digital signature against the sender's public key stored in their domain's DNS records.
                                        </li>
                                        <li>The recipient's mail server uses the sender's public DKIM key to confirm the authenticity of the email's digital signature and ensure it hasn't been tampered with.
                                        </li>
                                    </ul>
                                </div>
                                <h6>Add the following entry yo your DNS record to enable DKIM for this
                                    domain</h6>
                            </div>
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-borderless align-middle table-nowrap mb-0">
                                        <thead>
                                            <tr>
                                                <th scope="col">Type</th>
                                                <th scope="col">Name</th>
                                                <th scope="col">Content</th>
                                                <th scope="col">TTL</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>TXT</td>
                                                <td><input type="text" class="form-control dkim_txt" id="disabledInput" value="{{ $dkim_details->txt_name ?? '' }}" disabled=""></td>
                                                <td><input type="text" class="form-control dkim_val" id="disabledInput" value="{{ $dkim_details->txt_value ?? '' }}" disabled=""></td>
                                                <td>Auto</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="dmarc" role="tabpanel">
                        <div class="row">
                        <div class="col-md-12">
                                <div class="mb-3" style="padding: 15px;background: beige;border: 1px solid #cbcbcb;">
                                    <h5>DMARC</h5>
                                    <p>DMARC (Domain-based Message Authentication, Reporting & Conformance) is an email authentication protocol that helps protect against email spoofing and phishing by aligning SPF and DKIM results with the sender's domain.
                                    </p>
                                    <ul>
                                        <li>DMARC uses the SPF and DKIM results to determine if an email is legitimate, requiring both to pass or be aligned with the sender’s domain.
                                        </li>
                                        <li>If an email fails DMARC checks, it can be rejected, quarantined, or reported based on the domain owner’s DMARC policy.
                                        </li>
                                        <li>DMARC provides feedback to domain owners about email authentication activity, helping them monitor and improve email security.
                                        </li>
                                    </ul>
                                </div>
                                <h6>Add the following entry yo your DNS record to enable DMARC for this
                                    domain</h6>
                            </div>
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-borderless align-middle table-nowrap mb-0">
                                        <thead>
                                            <tr>
                                                <th scope="col">Type</th>
                                                <th scope="col">Name</th>
                                                <th scope="col">Content</th>
                                                <th scope="col">TTL</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>TXT</td>
                                                <td><input type="text" class="form-control" id="disabledInput" value="_dmarc" disabled=""></td>
                                                <td>
                                                    @php
                                                        $dmarcString = "v=DMARC1; p=none; rua=mailto:dmarc-reports@{$domain->domain_name}; ruf=mailto:dmarc-failures@{$domain->domain_name}; fo=1; adkim=s; aspf=s";
                                                    @endphp
                                                        <input type="text" class="form-control" id="disabledInput" value="{{ $dmarcString }}" disabled="">
                                                    </td>
                                                <td>Auto</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="spf" role="tabpanel">
                        <div class="row">
                        <div class="col-md-12">
                                <div class="mb-3" style="padding: 15px;background: beige;border: 1px solid #cbcbcb;">
                                    <h5>SPF</h5>
                                    <p>SPF (Sender Policy Framework) is an email authentication protocol that helps prevent email spoofing by allowing domain owners to specify which mail servers are authorized to send emails on their behalf.
                                    </p>
                                    <ul>
                                        <li>SPF checks if the sending mail server’s IP address matches the list of authorized servers in the sender’s DNS records.
                                        </li>
                                        <li>If the IP doesn’t match, the email is marked as suspicious or rejected, depending on the SPF policy.
                                        </li>
                                        <li>SPF helps reduce spam and phishing by ensuring that only trusted servers can send emails from a domain.
                                        </li>
                                    </ul>
                                </div>
                                <h6>Add the following entry yo your DNS record to enable SPF for this
                                    domain</h6>
                            </div>
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-borderless align-middle table-nowrap mb-0">
                                        <thead>
                                            <tr>
                                                <th scope="col">Type</th>
                                                <th scope="col">Name</th>
                                                <th scope="col">Content</th>
                                                <th scope="col">TTL</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>TXT</td>
                                                <td>@</td>
                                                <td><input type="text" class="form-control" id="disabledInput" value="{{ $final_spf }}" disabled=""></td>
                                                <td>Auto</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="mxrecord" role="tabpanel">
                        <div class="row">
                        <div class="col-md-12">
                                <div class="mb-3" style="padding: 15px;background: beige;border: 1px solid #cbcbcb;">
                                    <h5>MX Record</h5>
                                    <p>MX (Mail Exchange) Record is a DNS record that specifies the mail server responsible for receiving emails for a domain.
                                    </p>
                                    <ul>
                                        <li>When an email is sent, the sending mail server queries the domain's MX record to determine the correct mail server to deliver the email to.
                                        </li>
                                        <li>The MX record points to a mail server’s hostname and its priority, allowing emails to be routed to the correct destination.
                                        </li>
                                        <li>If the primary mail server is unavailable, the MX record allows fallback to secondary servers based on priority settings.
                                        </li>
                                    </ul>
                                </div>
                                <h6>Add the following entry yo your DNS record to enable MX Records for this
                                    domain</h6>
                            </div>
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-borderless align-middle table-nowrap mb-0">
                                        <thead>
                                            <tr>
                                                <th scope="col">Type</th>
                                                <th scope="col">Name</th>
                                                <th scope="col">Priority</th>
                                                <th scope="col">Target</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>MX</td>
                                                <td><input type="text" class="form-control" id="disabledInput" value="@" disabled=""></td>
                                                <td>10</td>
                                                <td>@php
                                    $mxTarget = "mail.{$domain->domain_name}";
                                @endphp
                                    <input type="text" class="form-control" id="disabledInput" value="{{ $mxTarget }}" disabled="">
                                </td>
                                            </tr>
                                        </tbody>
                                        <thead>
                                            <tr>
                                                <th scope="col">Type</th>
                                                <th scope="col">Name</th>
                                                <th scope="col">Connect</th>
                                                <th scope="col">TTL</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>A</td>
                                                <td><input type="text" class="form-control" id="disabledInput" value="mail" disabled=""></td>
                                                <td><input type="text" class="form-control" id="disabledInput" value="15.204.28.113" disabled=""></td>
                                                <td>Auto</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- end col -->

    <!-- end col -->
</div>