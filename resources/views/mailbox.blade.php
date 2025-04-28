@extends('layout/master')
@section('content')

<!-- Begin page -->
<div id="layout-wrapper">

    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">

                <div class="email-wrapper d-lg-flex gap-1 mx-n3 mt-n3 p-1">
                    <div class="email-menu-sidebar">
                        <div class="p-4 d-flex flex-column h-100">
                            <div class="pb-3">
                                <a href="javascript:void(0);" class="btn btn-primary btn-label">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <i class="ri-arrow-left-line label-icon align-middle fs-lg me-2"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            Back
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="mx-n4 px-4 email-menu-sidebar-scroll" data-simplebar>
                                <div class="mail-list mt-3">
                                    <a href="#" class="active"><i class="bi bi-envelope me-3 align-baseline"></i> <span class="mail-list-link">All</span> <span class="badge bg-secondary-subtle text-secondary ms-auto  ">{{ count($data) }}</span></a>
                                    <a href="#"><i class="bi bi-archive me-3 align-baseline"></i> <span class="mail-list-link">Inbox</span> <span class="badge bg-secondary-subtle text-secondary ms-auto  ">{{ count($data) }}</span></a>
                                    <a href="#"><i class="bi bi-send me-3 align-baseline"></i> <span class="mail-list-link">Sent</span></a>
                                    <a href="#"><i class="bi bi-pencil-square me-3 align-baseline"></i> <span class="mail-list-link">Draft</span></a>
                                    <a href="#"><i class="bi bi-exclamation-diamond me-3 align-baseline"></i> <span class="mail-list-link">Spam</span></a>
                                    <a href="#"><i class="bi bi-trash3 me-3 align-baseline"></i> <span class="mail-list-link">Trash</span></a>
                                    <a href="#"><i class="bi bi-bookmark-star me-3 align-baseline"></i> <span class="mail-list-link">Starred</span></a>
                                    <a href="#"><i class="bi bi-tags me-3 align-baseline"></i> <span class="mail-list-link">Important</span></a>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end email-menu-sidebar -->

                    <div class="email-content">
                        <div class="p-4 pb-0">
                            <div class="border-bottom border-bottom-dashed">
                                <div class="row mb-3 mb-sm-0 align-items-center g-3">

                                    <div class="col-lg-5 me-auto">
                                        <div class="search-box">
                                            <input type="text" class="form-control border-0" id="searchResultList" autocomplete="off" placeholder="Search here...">
                                            <i class="bi bi-search search-icon"></i>
                                        </div>
                                    </div>
                                    <div class="col-lg-auto">
                                        <div class="d-flex gap-sm-1 email-topbar-link">
                                            <button type="button" class="btn btn-subtle-success btn-icon btn-sm fs-lg email-menu-btn d-block d-lg-none">
                                                <i class="ri-menu-2-fill align-bottom"></i>
                                            </button>
                                            <button type="button" class="btn btn-ghost-secondary btn-icon btn-sm fs-lg ms-auto">
                                                <i class="bi bi-arrow-clockwise"></i>
                                            </button>
                                            <div class="dropdown">
                                                <button class="btn btn-ghost-secondary btn-icon btn-sm fs-lg" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="bi bi-three-dots-vertical"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="#">Mark as Unread</a>
                                                    <a class="dropdown-item" href="#">Mark as Important</a>
                                                    <a class="dropdown-item" href="#">Add to Tasks</a>
                                                    <a class="dropdown-item" href="#">Add Star</a>
                                                    <a class="dropdown-item" href="#">Mute</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="px-3">
                                    <div class="row align-items-center mt-3 mb-2 d-flex">
                                        <div class="col">
                                            <div class="hstack gap-sm-1 align-items-center flex-wrap email-topbar-link ms-1">
                                                <div class="form-check fs-md m-0">
                                                    <input class="form-check-input" type="checkbox" value="" id="checkall">
                                                    <label class="form-check-label" for="checkall"></label>
                                                </div>
                                                <div id="email-topbar-actions">
                                                    <div class="hstack gap-sm-1 align-items-center flex-wrap">
                                                        <button type="button" class="btn btn-ghost-secondary btn-icon btn-sm fs-lg" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Archive">
                                                            <i class="bi bi-inbox"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-ghost-secondary btn-icon btn-sm fs-lg" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Report Spam">
                                                            <i class="bi bi-exclamation-triangle"></i>
                                                        </button>
                                                        <div data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Trash">
                                                            <button type="button" class="btn btn-ghost-secondary btn-icon btn-sm fs-lg" data-bs-toggle="modal" data-bs-target="#removeItemModal">
                                                                <i class="bi bi-trash3"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="vr align-self-center mx-2"></div>
                                                <div class="dropdown">
                                                    <button class="btn btn-ghost-secondary btn-icon btn-sm fs-lg" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="bi bi-tag"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#">Support</a>
                                                        <a class="dropdown-item" href="#">Freelance</a>
                                                        <a class="dropdown-item" href="#">Social</a>
                                                        <a class="dropdown-item" href="#">Friends</a>
                                                        <a class="dropdown-item" href="#">Family</a>
                                                    </div>
                                                </div>
                                                <div class="dropdown">
                                                    <button class="btn btn-ghost-secondary btn-icon btn-sm fs-lg" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="#" id="mark-all-read">Mark all as Read</a>
                                                    </div>
                                                </div>
                                                <div class="alert alert-warning alert-dismissible unreadConversations-alert px-4 fade show " id="unreadConversations" role="alert">
                                                    No Unread Conversations
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <div class="text-muted mb-0">1-50 of 154</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class="mail-primary">
                                    <div class="message-list-content mx-n4 px-4 message-list-scroll">
                                        <div id="elmLoader">
                                            <div class="spinner-border text-primary avatar-sm" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                        <!-- <ul class="message-list" id="mail-list"></ul> -->
                                         @if(!empty($data))
                                         @foreach($data as $maildata)
                                        <ul class="message-list">
                                            <li class="">
                                                <div class="col-mail col-mail-1">
                                                    <div class="form-check checkbox-wrapper-mail fs-14">
                                                        <input class="form-check-input" type="checkbox" value="1" id="checkbox-1">
                                                        <label class="form-check-label" for="checkbox-1"></label>
                                                    </div>
                                                    <input type="hidden" value="assets/images/users/48/avatar-2.jpg" class="mail-userimg">
                                                    <button type="button" class="btn avatar-xs p-0 favorite-btn fs-15 active">
                                                        <i class="ri-star-fill"></i>
                                                    </button>
                                                    <a href="javascript: void(0);" class="title">
                                                        <span class="title-name">Peter, me</span>
                                                    </a>
                                                </div>
                                                <div class="col-mail col-mail-2">
                                                    <a href="javascript: void(0);" class="subject">
                                                        <span class="subject-title">Hello</span> –
                                                        <span class="teaser">Trip home from Colombo has been arranged, then Jenna will come get me from Stockholm. :</span>
                                                    </a>
                                                    <div class="date">Mar 7</div>
                                                </div>
                                            </li>
                                            <li class="unread">
                                                <div class="col-mail col-mail-1">
                                                    <div class="form-check checkbox-wrapper-mail fs-14">
                                                        <input class="form-check-input" type="checkbox" value="3" id="checkbox-3">
                                                        <label class="form-check-label" for="checkbox-3"></label>
                                                    </div>
                                                    <input type="hidden" value="assets/images/users/48/avatar-4.jpg" class="mail-userimg">
                                                    <button type="button" class="btn avatar-xs p-0 favorite-btn fs-15 ">
                                                        <i class="ri-star-fill"></i>
                                                    </button>
                                                    <a href="javascript: void(0);" class="title">
                                                        <span class="title-name">Web Support Dennis</span> (7)</a>
                                                </div>
                                                <div class="col-mail col-mail-2">
                                                    <a href="javascript: void(0);" class="subject">
                                                        <span class="subject-title">Re: New mail settings</span> –
                                                        <span class="teaser">Will you answer him asap?</span>
                                                    </a>
                                                    <div class="date">Mar 5</div>
                                                </div>
                                            </li>
                                            <li class="">
                                                <div class="col-mail col-mail-1">
                                                    <div class="form-check checkbox-wrapper-mail fs-14">
                                                        <input class="form-check-input" type="checkbox" value="4" id="checkbox-4">
                                                        <label class="form-check-label" for="checkbox-4"></label>
                                                    </div>
                                                    <input type="hidden" value="assets/images/users/48/avatar-5.jpg" class="mail-userimg">
                                                    <button type="button" class="btn avatar-xs p-0 favorite-btn fs-15">
                                                        <i class="ri-star-fill"></i>
                                                    </button>
                                                    <a href="javascript: void(0);" class="title">
                                                        <span class="title-name">Peter</span>
                                                    </a>
                                                </div>
                                                <div class="col-mail col-mail-2">
                                                    <a href="javascript: void(0);" class="subject">
                                                        <span class="subject-title">Support - Off on Thursday</span> –
                                                        <span class="teaser">Eff that place, you might as well stay here with us instead! Sent from my iPhone 4 4 mar 2014 at 5:55 pm</span>
                                                    </a>
                                                    <div class="date">Mar 4</div>
                                                </div>
                                            </li>
                                        </ul>
                                        @else
                                        <ul class="message-list">
                                        <li class="">
                                                <div class="col-mail col-mail-2">
                                                    <span class="teaser">No email found!</span>
                                                </div>
                                            </li>
                                        </ul>
                                        @endif






                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end email-content -->

                    <div class="email-detail-content">
                        <div class="p-4 d-flex flex-column h-100">
                            <div class="pb-4 border-bottom border-bottom-dashed">
                                <div class="row">
                                    <div class="col">
                                        <div class="">
                                            <button type="button" class="btn btn-subtle-danger btn-icon btn-sm fs-lg close-btn-email" id="close-btn-email">
                                                <i class="ri-close-fill align-bottom"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="hstack gap-sm-1 align-items-center flex-wrap email-topbar-link">
                                            <button class="btn btn-ghost-secondary btn-icon btn-sm fs-lg">
                                                <i class="ri-printer-fill align-bottom"></i>
                                            </button>
                                            <button class="btn btn-ghost-secondary btn-icon btn-sm fs-lg remove-mail" data-remove-id="" data-bs-toggle="modal" data-bs-target="#removeItemModal">
                                                <i class="ri-delete-bin-5-fill align-bottom"></i>
                                            </button>
                                            <div class="dropdown">
                                                <button class="btn btn-ghost-secondary btn-icon btn-sm fs-lg" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="ri-more-2-fill align-bottom"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="#">Mark as Unread</a>
                                                    <a class="dropdown-item" href="#">Mark as Important</a>
                                                    <a class="dropdown-item" href="#">Add to Tasks</a>
                                                    <a class="dropdown-item" href="#">Add Star</a>
                                                    <a class="dropdown-item" href="#">Mute</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mx-n4 px-4 email-detail-content-scroll" data-simplebar>
                                <div class="mt-4 mb-3">
                                    <h5 class="fw-bold email-subject-title">New updates for Steex Theme</h5>
                                </div>

                                <div class="accordion accordion-flush">
                                    <div class="accordion-item border-dashed left">
                                        <div class="accordion-header">
                                            <a role="button" class="btn w-100 text-start px-0 bg-transparent shadow-none collapsed" data-bs-toggle="collapse" href="#email-collapseOne" aria-expanded="true" aria-controls="email-collapseOne">
                                                <div class="d-flex align-items-center text-muted">
                                                    <div class="flex-shrink-0 avatar-xs me-3">
                                                        <img src="{{ asset('assets/images/users/avatar-3.jpg') }}" alt="" class="img-fluid rounded-circle">
                                                    </div>
                                                    <div class="flex-grow-1 overflow-hidden">
                                                        <h5 class="fs-md text-truncate email-user-name mb-0">Jack Davis</h5>
                                                        <div class="text-truncate fs-xs">to: me</div>
                                                    </div>
                                                    <div class="flex-shrink-0 align-self-start">
                                                        <div class="text-muted fs-xs">09 Jan 2022, 11:12 AM</div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>

                                        <div id="email-collapseOne" class="accordion-collapse collapse">
                                            <div class="accordion-body text-body px-0">
                                                <div>
                                                    <p>Hi,</p>
                                                    <p>Praesent dui ex, dapibus eget mauris ut, finibus vestibulum enim. Quisque arcu leo, facilisis in fringilla id, luctus in tortor.</p>
                                                    <p>Sed elementum turpis eu lorem interdum, sed porttitor eros commodo. Nam eu venenatis tortor, id lacinia diam. Sed aliquam in dui et porta. Sed bibendum orci non tincidunt ultrices.</p>
                                                    <p>Sincerly,</p>

                                                    <div class="d-flex gap-3">
                                                        <div class="border rounded avatar-xl h-auto">
                                                            <img src="{{ asset('assets/images/small/img-2.jpg') }}" alt="" class="img-fluid rouned-top">
                                                            <div class="py-2 text-center">
                                                                <a href="" class="d-block fw-semibold">Download</a>
                                                            </div>
                                                        </div>
                                                        <div class="border rounded avatar-xl h-auto">
                                                            <img src="{{ asset('assets/images/small/img-6.jpg') }}" alt="" class="img-fluid rouned-top">
                                                            <div class="py-2 text-center">
                                                                <a href="" class="d-block fw-semibold">Download</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end accordion-item -->

                                    <div class="accordion-item border-dashed right">
                                        <div class="accordion-header">
                                            <a role="button" class="btn w-100 text-start px-0 bg-transparent shadow-none collapsed" data-bs-toggle="collapse" href="#email-collapseTwo" aria-expanded="true" aria-controls="email-collapseTwo">
                                                <div class="d-flex align-items-center text-muted">
                                                    <div class="flex-shrink-0 avatar-xs me-3">
                                                        <img src="{{ asset('assets/images/users/avatar-1.jpg') }}" alt="" class="img-fluid rounded-circle">
                                                    </div>
                                                    <div class="flex-grow-1 overflow-hidden">
                                                        <h5 class="fs-md text-truncate email-user-name-right mb-0">Anna Adam</h5>
                                                        <div class="text-truncate fs-xs">to: jackdavis@email.com</div>
                                                    </div>
                                                    <div class="flex-shrink-0 align-self-start">
                                                        <div class="text-muted fs-xs">09 Jan 2022, 02:15 PM</div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>

                                        <div id="email-collapseTwo" class="accordion-collapse collapse">
                                            <div class="accordion-body text-body px-0">
                                                <div>
                                                    <p>Hi,</p>
                                                    <p>If several languages coalesce, the grammar of the resulting language is more simple and regular than that of the individual.</p>
                                                    <p>Thank you</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end accordion-item -->

                                    <div class="accordion-item border-dashed left">
                                        <div class="accordion-header">
                                            <a role="button" class="btn w-100 text-start px-0 bg-transparent shadow-none" data-bs-toggle="collapse" href="#email-collapseThree" aria-expanded="true" aria-controls="email-collapseThree">
                                                <div class="d-flex align-items-center text-muted">
                                                    <div class="flex-shrink-0 avatar-xs me-3">
                                                        <img src="{{ asset('assets/images/users/avatar-3.jpg') }}" alt="" class="img-fluid rounded-circle">
                                                    </div>
                                                    <div class="flex-grow-1 overflow-hidden">
                                                        <h5 class="fs-md text-truncate email-user-name mb-0">Jack Davis</h5>
                                                        <div class="text-truncate fs-xs">to: me</div>
                                                    </div>
                                                    <div class="flex-shrink-0 align-self-start">
                                                        <div class="text-muted fs-xs">10 Jan 2022, 10:08 AM</div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>

                                        <div id="email-collapseThree" class="accordion-collapse collapse show">
                                            <div class="accordion-body text-body px-0">
                                                <div>
                                                    <p>Hi,</p>
                                                    <p>Everyone realizes why a new common language would be desirable: one could refuse to pay expensive translators. To achieve this, it would be necessary to have uniform grammar pronunciation.</p>
                                                    <p>Thank you</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end accordion-item -->
                                </div>
                                <!-- end accordion -->
                            </div>
                            <div class="mt-auto">
                                <form class="mt-2">
                                    <div>
                                        <label for="exampleFormControlTextarea1" class="form-label">Reply :</label>
                                        <textarea class="form-control border-bottom-0 rounded-top rounded-0 border" id="exampleFormControlTextarea1" rows="3" placeholder="Enter message"></textarea>
                                        <div class="bg-light px-2 py-1 rouned-bottom border">
                                            <div class="row">
                                                <div class="col">
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-sm py-0 fs-lg btn-light" data-bs-toggle="tooltip" data-bs-placement="top" title="Bold"><i class="ri-bold align-bottom"></i></button>
                                                        <button type="button" class="btn btn-sm py-0 fs-lg btn-light" data-bs-toggle="tooltip" data-bs-placement="top" title="Italic"><i class="ri-italic align-bottom"></i></button>
                                                        <button type="button" class="btn btn-sm py-0 fs-lg btn-light" data-bs-toggle="tooltip" data-bs-placement="top" title="Link"><i class="ri-link align-bottom"></i></button>
                                                        <button type="button" class="btn btn-sm py-0 fs-lg btn-light" data-bs-toggle="tooltip" data-bs-placement="top" title="Image"><i class="ri-image-2-line align-bottom"></i></button>
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-sm btn-success"><i class="ri-send-plane-2-fill align-bottom"></i></button>
                                                        <button type="button" class="btn btn-sm btn-success dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <span class="visually-hidden">Toggle Dropdown</span>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li><a class="dropdown-item" href="#"><i class="ri-timer-line text-muted me-1 align-bottom"></i> Schedule Send</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- end email-detail-content -->
                </div>

            </div>
            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->

    </div>
    <!-- end main content-->

</div>

@endsection