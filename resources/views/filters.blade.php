@extends('layout/master')
@section('content')

<style>
 
 .add-filter-modal .modal-header{
      padding: 5px 1rem;
      background-color: #eee;
 }
 .add-filter-modal .btn-close{
     width: auto;
     height: auto;
     margin-right: 0px;
 }
 .filter-body-sec{
     border:1px solid #bbb;
     padding:1rem;
     display:flex;
     background-color: #98a9e540;
     justify-content: space-between;
     align-items: end;
     gap: 15px;
 }
 .filter-body-sec .form-control{
     border-radius: 0px;
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
                        <h4 class="mb-sm-0">Filters</h4>

                        <div class="page-title-right">
                        <a href="{{ '/domains'}}" class="btn btn-outline-primary">
                                View Domains</span>
                                </a>
                            <button type="button" class="btn btn-primary" id="filterbtn">Add Filter</button>
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
                                        <th>Filter</th>
                                        <th>Added By</th>
                                        <th>Added On</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($filters))
                                    @foreach($filters as $filter)
                                    @php
                                    $added_by = getuser($filter->created_by);
                                    $formattedDate = \Carbon\Carbon::parse($filter->created_at)->format('jS F Y, h:ia');
                                    @endphp
                                    <tr>
                                        <td>{{ $filter->filter }}</td>
                                        <td>{{ $added_by->name }}</td>
                                        <td>{{ $formattedDate}}</td>
                                        <td>
                                            <a href="#" class="btn p-0 edit_filter" data-filterid="<?php echo $filter->id ?>" data-filterval="{{ $filter->filter }}" id="edit_filter">
                                                <span style="color: blue;">Edit</span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="btn p-0 delete_filter" id="delete_filter" data-filterid="<?php echo $filter->id ?>">
                                                <span style="color: blue;">Delete</span>
                                                </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="8">No Filters available</td>
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



<!-- Add filter Modal -->
<div class="modal fade" id="filtermodal" tabindex="-1" aria-labelledby="filtermodalLabel" aria-hidden="true">
<div class="modal-dialog">
 <div class="modal-content add-filter-modal border-0">
   <div class="modal-header border-0">
     <h6 class="modal-title text-muted" id="filtermodalLabel">Add Filter</h6>
     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">				</button>
   </div>
   <div class="modal-body">
     <form method="post" action="{{ '/add-filter' }}">
        @csrf
       <div class="mb-3 filter-body-sec">
           <div class="w-100">
         <label for="filterRegex" class="form-label">Filter Regex</label>
         <input type="text" class="form-control" id="filterregex" name="filterregex" placeholder="Enter Filter Regex" required>
         </div>
         <div>
         <button type="submit" class="btn btn-primary">Update</button>
         </div>
       </div>
       
     </form>
   </div>
 </div>
</div>
</div>

<!-- edit filter Modal -->
<div class="modal fade" id="editfiltermodal" tabindex="-1" aria-labelledby="filtermodalLabel" aria-hidden="true">
<div class="modal-dialog">
 <div class="modal-content add-filter-modal border-0">
   <div class="modal-header border-0">
     <h6 class="modal-title text-muted" id="filtermodalLabel">Edit Filter</h6>
     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">				</button>
   </div>
   <div class="modal-body">
     <form method="post" action="{{ '/edit-filter' }}">
        @csrf
       <div class="mb-3 filter-body-sec">
           <div class="w-100">
         <label for="filterRegex" class="form-label">Filter Regex</label>
         <input type="text" class="form-control" id="editfilterregex" name="editfilterregex" value="" required>
         <input type="hidden" class="form-control" id="regex_id" name="regex_id" value="" required>
         </div>
         <div>
         <button type="submit" class="btn btn-primary">Update</button>
         </div>
       </div>
       
     </form>
   </div>
 </div>
</div>
</div>

    <script>
        $(document).ready(function() {

        $(document).on('click', '#filterbtn', function(e) {
        e.preventDefault();
        $("#filtermodal").modal("show");
        });

        $(document).on('click', '#edit_filter', function(e) {
        e.preventDefault();
        
        let filter_val = $(this).data('filterval');
        let filter_id = $(this).data('filterid');
        // let filterIdString = JSON.stringify(filter_val);
        $("#editfiltermodal").modal("show");
        $("#editfilterregex").val(filter_val);
        $("#regex_id").val(filter_id);
        });

        $(document).on('click', '.delete_filter', function(e) {
            let filter_id = $(this).data('filterid');
            if (confirm('Are you sure you want to delete this domain?')) {
            $.ajax({
                url: '/delete_filter/'+ filter_id,
                type: 'DELETE',
                data: {
                _token: "{{ csrf_token() }}"
            },
                success: function(response) {
                    console.log(response);
                    if (response.status) {
                        alert("Filter deleted successfully");
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
    </script>
    @endsection