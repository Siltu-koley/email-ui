<div class="row">
    <div class="col-xxl-12">
        <div class="card">
            <div class="card-body">
                <form id="createMail">
                    @csrf
                    <div class="row">
                        <div class="col-xxl-12">
                            <!-- <div>
                                <h5 class="fs-md mb-3">General</h5>
                            </div> -->
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="" class="form-label">E-mail</label>
                                <div class="input_wrapper" style="display: flex;">
                                <input type="text" class="form-control" placeholder="example" id="emailstring" name="emailstring" required>
                                <span style="font-weight: bold;">{{ '@' . $domain->domain_name }}</span>
                                </div>
                                <input type="hidden" class="form-control" id="emaildomain" name="emaildomain" value="{{ $domain->domain_name }}">
                                <input type="hidden" class="form-control" id="domain_id" name="domain_id" value="{{ $domain->id }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="compnayNameinput" class="form-label">Password</label>
                                <input type="password" class="form-control" placeholder="enter password" id="password" name="password" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="compnayNameinput" class="form-label">Confirm Password</label>
                                <input type="confirm_password" class="form-control" placeholder="Confirm Password" id="confirm_password" name="confirm_password" required>
                            </div>
                        </div>
                        <span id="message" style="color:red;"></span><br>
                        
                        <div class="col-lg-12 col-md-6">
                            <div class="mb-3">
                                <label for="choices-multiple-remove-button" class="form-label text-muted">Select IPs</label>
                                <select class="form-control" id="ips" name="ips" data-choices data-choices-removeItem name="choices-multiple-remove-button" multiple>
                                    <!-- <option value="15.204.28.113" selected>15.204.28.113</option>
                                    <option value="15.204.14.108">15.204.14.108</option> -->
                                </select>
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