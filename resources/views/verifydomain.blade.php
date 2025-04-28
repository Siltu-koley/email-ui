<div class="row">
    <div class="col-xxl-12">
        <div class="card">
            <div class="card-body">
                <h5 class="mb-0">SPF</h5>
                <p class="text-muted">Paste the following value in your domain's current SPF record. This will merge SPF value with any pre-existing SPF record values. If you don't already have an SPF record, then create a new TXT record with this value.</p>
                <div class="table-responsive">
                    <!-- Tables Without Borders -->
                    <table class="table table-borderless table-nowrap">
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
                                <td></td>
                                <td>
                                    <div>
                                        <input type="text" class="form-control" id="disabledInput" value="v=spf1 ip4:15.204.75.8 -all" disabled="">
                                    </div>
                                </td>
                                <td>Auto</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="mb-0">DKIM</h5>
                        <p class="text-muted">Create a new record with the following information:</p>
                    </div>
                    <div>
                        <a href="#" class="dkim_gen" data-domainid="{{ $domain->id }}">
                            Generate DKIM
                        </a>
                    </div>
                </div>
                <table class="table table-borderless table-nowrap">
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
                            <td>
                                <div>
                                    <input type="text" class="form-control dkim_txt" id="disabledInput" value="{{ $dkim_details->txt_name ?? '' }}" disabled="">
                                </div>
                            </td>
                            <td>
                                <div>
                                    <input type="text" class="form-control dkim_val" id="disabledInput" value="{{ $dkim_details->txt_value ?? '' }}" disabled="">
                                </div>
                            </td>
                            <td>Auto</td>
                        </tr>
                    </tbody>
                </table>
                <h5 class="mb-0">DMARC</h5>
                <p class="text-muted">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Voluptas fugit rem facilis doloremque expedita tempore voluptates nostrum placeat porro.</p>
                <table class="table table-borderless table-nowrap">
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
                            <td>TXT</td>
                            <td>
                                <div>
                                    <input type="text" class="form-control" id="disabledInput" value="_dmarc" disabled="">
                                </div>
                            </td>
                            <td>
                                <div>
                                @php
                                    $dmarcString = "v=DMARC1; p=none; rua=mailto:dmarc-reports@{$domain->domain_name}; ruf=mailto:dmarc-failures@{$domain->domain_name}; fo=1; adkim=s; aspf=s";
                                @endphp
                                    <input type="text" class="form-control" id="disabledInput" value="{{ $dmarcString }}" disabled="">
                                </div>
                            </td>
                            <td>Auto</td>
                        </tr>
                    </tbody>
                </table>
                <h5 class="mb-0">MX</h5>
                <p class="text-muted">Add MX record to receive mail.</p>
                <table class="table table-borderless table-nowrap">
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
                            <td>
                                <div>
                                    <input type="text" class="form-control" id="disabledInput" value="@" disabled="">
                                </div>
                            </td>
                            <td>10</td>
                            <td>
                                <div>
                                @php
                                    $mxTarget = "mail.{$domain->domain_name}";
                                @endphp
                                    <input type="text" class="form-control" id="disabledInput" value="{{ $mxTarget }}" disabled="">
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <h5 class="mb-0">A (optional)</h5>
                <p class="text-muted">You also need an A record so that mail.{{$domain->domain_name}} points to your server’s IP.</p>
                <table class="table table-borderless table-nowrap">
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
                            <td>
                                <div>
                                    <input type="text" class="form-control" id="disabledInput" value="mail" disabled="">
                                </div>
                            </td>
                            <td>
                                <div>
                                    <input type="text" class="form-control" id="disabledInput" value="15.204.75.8" disabled="">
                                </div>
                            </td>
                            <td>Auto</td>
                        </tr>
                    </tbody>
                </table>
            </div>


        </div>
    </div> <!-- end col -->

    <!-- end col -->
</div>