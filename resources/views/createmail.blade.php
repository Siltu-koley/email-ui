<div class="row">
    <div class="col-xxl-12">
        <div class="card">
            <div class="card-body">
                <form id="createMail">
                    @csrf
                    <div class="row">
                        <div class="col-xxl-12">
                            <div>
                                <h5 class="fs-md">Adding new Email address</h5>
                                <p>{{ $domain->domain_name }}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-control" style="background: #DEE8F3;padding:15px;border:1px solid #eee;">
                            <label for="" class="form-label">Enter New Email</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="email" id="emailstring" name="emailstring" required>
                                    <input type="hidden" class="form-control" id="emaildomain" name="emaildomain" value="{{ $domain->domain_name }}">
                                    <input type="hidden" class="form-control" id="domain_id" name="domain_id" value="{{ $domain->id }}">
                                    <button type="submit" class="btn btn-primary" style="margin-left: 15px;border-radius: 6px;">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end row-->
                </form>
            </div>
        </div>
    </div> <!-- end col -->

    <!-- end col -->
</div>