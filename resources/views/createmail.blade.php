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
                                <label for="compnayNameinput" class="form-label">Select User</label>
                                <select class="form-control" id="wildduck_userid" name="wildduck_userid" required>
                                <option value="" disabled selected>Select User</option>
                                <option value="{{ $wildduck_user->wildduck_userid }}">{{ $wildduck_user->username }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="compnayNameinput" class="form-label">Select Ip</label>
                                <select class="form-control" id="zone_ip" name="zone_ip">
                                <option value="" disabled selected>Select Ip</option>
                                <option value="15.204.75.8">15.204.75.8</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="formCheck1" name="id_default">
                                    <label class="form-check-label" for="formCheck1">
                                        is default?
                                    </label>
                                </div>
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