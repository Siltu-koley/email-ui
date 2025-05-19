<!DOCTYPE html>
<html lang="en" data-layout="vertical" data-sidebar="dark" data-sidebar-size="lg" data-preloader="disable"
    data-theme="default" data-topbar="light" data-bs-theme="light">

@include('layout/header')

<body>


@include('layout/topbar')
@include('layout/footer')
@yield('content')

<style>
 
 .add-updatepass-modal .modal-header{
      padding: 5px 1rem;
      background-color: #eee;
 }
 .add-updatepass-modal .btn-close{
     width: auto;
     height: auto;
     margin-right: 0px;
 }
 .updatepass-body-sec{
     border:1px solid #bbb;
     padding:1rem;
     display:flex;
     background-color: #98a9e540;
     justify-content: space-between;
     align-items: end;
     gap: 15px;
 }
 .updatepass-body-sec .form-control{
     border-radius: 0px;
 }
 
 
</style>

<!-- Modal -->
<div class="modal fade" id="updatepassModal" tabindex="-1" aria-labelledby="dkimModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content add-updatepass-modal border-0">
      <div class="modal-header border-0">
        <h6 class="modal-title text-muted" id="dkimModalLabel">Update Password</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">				</button>
      </div>
      <div class="modal-body">
        <form id="updatepassForm">
            @csrf
          <div class="mb-3 updatepass-body-sec">
          	<div class="w-100">
            <label for="updatepass" class="form-label">Current Password</label>
            <input type="text" class="form-control" id="oldpass" name="oldpass" placeholder="Enter Current Password" required>
            </div>
            <div class="w-100">
            <label for="newpass" class="form-label">New Password</label>
            <input type="text" class="form-control" id="newpass" name="newpass" placeholder="Enter New Password" required>
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

</body>

</html>