{{-- Extends layout --}}
@extends('layout.default')



{{-- Content --}}
@section('content')
            <!-- row -->
<div class="container-fluid">
  <div class="d-sm-flex d-block justify-content-between align-items-center mb-4">
    <h2 class="text-black font-w600 mb-sm-0 mb-2">Cards Center</h2>
    <button type="button" class="btn btn-rounded btn-primary" data-toggle="modal" data-target="#examplenewcard"><span class="btn-icon-left text-primary"><i class="fa fa-plus" aria-hidden="true"></i> </span>New Card</button>
    <!-- Modal -->
    <div class="modal fade" id="examplenewcard">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Modal title</h5>
            <button type="button" class="close" data-dismiss="modal"><span>&times;</span> </button>
          </div>
          <div class="modal-body">
            <p>Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger light" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-xl-12">
      <div class="cards owl-carousel mb-sm-5 mb-3">
							<div class="items">
								<div class="card-bx mb-0">
									<img src="{{ asset('images/card/card1.png') }}" alt="">
									<div class="card-info text-white">
										<p class="mb-1">Main Balance</p>
										<h2 class="fs-36 text-white mb-sm-4 mb-3">$673,412.66</h2>
										<div class="d-flex align-items-center justify-content-between mb-sm-5 mb-3">
											<img src="{{ asset('images/dual-dot.png') }}" alt="" class="dot-img">
											<h4 class="fs-20 text-white mb-0">**** **** **** 1234</h4>
										</div>
										<div class="d-flex">
											<div class="mr-5">
												<p class="fs-14 mb-1 op6">VALID THRU</p>
												<span>08/21</span>
											</div>
											<div>
												<p class="fs-14 mb-1 op6">CARD HOLDER</p>
												<span>Franklin Jr.</span>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="items">
								<div class="card-bx mb-0">
									<img src="{{ asset('images/card/card2.png') }}" alt="">
									<div class="card-info text-white">
										<p class="mb-1">Purple Card</p>
										<h2 class="fs-36 text-white mb-sm-4 mb-3">$45,662</h2>
										<div class="d-flex align-items-center justify-content-between mb-sm-5 mb-3">
											<img src="{{ asset('images/dual-dot.png') }}" alt="" class="dot-img">
											<h4 class="fs-20 text-white mb-0">**** **** **** 1234</h4>
										</div>
										<div class="d-flex">
											<div class="mr-5">
												<p class="fs-14 mb-1 op6">VALID THRU</p>
												<span>08/21</span>
											</div>
											<div>
												<p class="fs-14 mb-1 op6">CARD HOLDER</p>
												<span>Franklin Jr.</span>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="items">
								<div class="card-bx mb-0">
									<img src="{{ asset('images/card/card3.png') }}" alt="">
									<div class="card-info text-white">
										<p class="mb-1">Green Card</p>
										<h2 class="fs-36 text-white mb-sm-4 mb-3">$23,511</h2>
										<div class="d-flex align-items-center justify-content-between mb-sm-5 mb-3">
											<img src="{{ asset('images/dual-dot.png') }}" alt="" class="dot-img">
											<h4 class="fs-20 text-white mb-0">**** **** **** 1234</h4>
										</div>
										<div class="d-flex">
											<div class="mr-5">
												<p class="fs-14 mb-1 op6">VALID THRU</p>
												<span>08/21</span>
											</div>
											<div>
												<p class="fs-14 mb-1 op6">CARD HOLDER</p>
												<span>Franklin Jr.</span>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="items">
								<div class="card-bx mb-0">
									<img src="{{ asset('images/card/card4.png') }}" alt="">
									<div class="card-info text-white">
										<p class="mb-1">Orange Card</p>
										<h2 class="fs-36 text-white mb-sm-4 mb-3">$340</h2>
										<div class="d-flex align-items-center justify-content-between mb-sm-5 mb-3">
											<img src="{{ asset('images/dual-dot.png') }}" alt="" class="dot-img">
											<h4 class="fs-20 text-white mb-0">**** **** **** 1234</h4>
										</div>
										<div class="d-flex">
											<div class="mr-5">
												<p class="fs-14 mb-1 op6">VALID THRU</p>
												<span>08/21</span>
											</div>
											<div>
												<p class="fs-14 mb-1 op6">CARD HOLDER</p>
												<span>Franklin Jr.</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
    </div>
    <div class="col-xl-9">
      <div class="row">
        <div class="col-xl-12">
          <div class="card">
            <div class="card-header d-sm-flex d-block border-0 pb-0">
              <div>
                <h4 class="fs-20 text-black">Card List</h4>
                <span class="fs-12">Lorem ipsum dolor sit amet, consectetur</span> </div>
              <div class="dropdown custom-dropdown mb-0 mt-3 mt-sm-0">
                <div class="btn btn-light btn-rounded" role="button" data-toggle="dropdown" aria-expanded="false"> Newest <i class="fa fa-caret-down text-primary ml-3" aria-hidden="true"></i> </div>
                <div class="dropdown-menu dropdown-menu-right"> <a class="dropdown-item" href="javascript:void(0);">Details</a> <a class="dropdown-item text-danger" href="javascript:void(0);">Cancel</a> </div>
              </div>
            </div>
            <div class="card-body pb-0">
              <div class="d-flex mb-3 border-bottom justify-content-between flex-wrap align-items-center">
                <div class="d-flex pb-3 align-items-center"> <img src="{{ asset('images/card/1.jpg') }}" alt="" class="rounded mr-3" width="130">
                  <div class="mr-3">
                    <p class="fs-14 mb-1">Card Type</p>
                    <span class="text-black font-w500">Primary</span> </div>
                </div>
                <div class="mr-3 pb-3">
                  <p class="fs-14 mb-1">Bank</p>
                  <span class="text-black font-w500">ABC Bank</span> </div>
                <div class="mr-3 pb-3">
                  <p class="fs-14 mb-1">Card Number</p>
                  <span class="text-black font-w500">**** **** **** 2256</span> </div>
                <div class="mr-3 pb-3">
                  <p class="fs-14 mb-1">Namein Card</p>
                  <span class="text-black font-w500">Franklin Jr.</span> </div>
                <a href="{!! url('/transactions-details'); !!}" class="fs-14 btn-link mr-3 pb-3">See Number</a>
                <div class="dropdown pb-3">
                  <div class="btn-link" role="button" data-toggle="dropdown" aria-expanded="false">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M10 11.9999C10 13.1045 10.8954 13.9999 12 13.9999C13.1046 13.9999 14 13.1045 14 11.9999C14 10.8954 13.1046 9.99994 12 9.99994C10.8954 9.99994 10 10.8954 10 11.9999Z" fill="black"></path>
                      <path d="M10 4.00006C10 5.10463 10.8954 6.00006 12 6.00006C13.1046 6.00006 14 5.10463 14 4.00006C14 2.89549 13.1046 2.00006 12 2.00006C10.8954 2.00006 10 2.89549 10 4.00006Z" fill="black"></path>
                      <path d="M10 20C10 21.1046 10.8954 22 12 22C13.1046 22 14 21.1046 14 20C14 18.8954 13.1046 18 12 18C10.8954 18 10 18.8954 10 20Z" fill="black"></path>
                    </svg>
                  </div>
                  <div class="dropdown-menu dropdown-menu-right"> <a class="dropdown-item" href="javascript:void()">Delete</a> <a class="dropdown-item" href="javascript:void()">Edit</a> </div>
                </div>
              </div>
              <div class="d-flex mb-3 border-bottom justify-content-between flex-wrap align-items-center">
                <div class="d-flex pb-3 align-items-center"> <img src="{{ asset('images/card/2.jpg') }}" alt="" class="rounded mr-3" width="130">
                  <div class="mr-3">
                    <p class="fs-14 mb-1">Card Type</p>
                    <span class="text-black font-w500">Secondary</span> </div>
                </div>
                <div class="mr-3 pb-3">
                  <p class="fs-14 mb-1">Bank</p>
                  <span class="text-black font-w500">Finefine Bank</span> </div>
                <div class="mr-3 pb-3">
                  <p class="fs-14 mb-1">Card Number</p>
                  <span class="text-black font-w500">**** **** **** 6551 </span> </div>
                <div class="mr-3 pb-3">
                  <p class="fs-14 mb-1">Namein Card</p>
                  <span class="text-black font-w500">Franklin Jr.</span> </div>
                <a href="{!! url('/transactions-details'); !!}" class="fs-14 btn-link mr-3 pb-3">See Number</a>
                <div class="dropdown pb-3">
                  <div class="btn-link" role="button" data-toggle="dropdown" aria-expanded="false">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M10 11.9999C10 13.1045 10.8954 13.9999 12 13.9999C13.1046 13.9999 14 13.1045 14 11.9999C14 10.8954 13.1046 9.99994 12 9.99994C10.8954 9.99994 10 10.8954 10 11.9999Z" fill="black"></path>
                      <path d="M10 4.00006C10 5.10463 10.8954 6.00006 12 6.00006C13.1046 6.00006 14 5.10463 14 4.00006C14 2.89549 13.1046 2.00006 12 2.00006C10.8954 2.00006 10 2.89549 10 4.00006Z" fill="black"></path>
                      <path d="M10 20C10 21.1046 10.8954 22 12 22C13.1046 22 14 21.1046 14 20C14 18.8954 13.1046 18 12 18C10.8954 18 10 18.8954 10 20Z" fill="black"></path>
                    </svg>
                  </div>
                  <div class="dropdown-menu dropdown-menu-right"> <a class="dropdown-item" href="javascript:void()">Delete</a> <a class="dropdown-item" href="javascript:void()">Edit</a> </div>
                </div>
              </div>
              <div class="d-flex mb-3 border-bottom justify-content-between flex-wrap align-items-center">
                <div class="d-flex pb-3 align-items-center"> <img src="{{ asset('images/card/3.jpg') }}" alt="" class="rounded mr-3" width="130">
                  <div class="mr-3">
                    <p class="fs-14 mb-1">Card Type</p>
                    <span class="text-black font-w500">Secondary</span> </div>
                </div>
                <div class="mr-3 pb-3">
                  <p class="fs-14 mb-1">Bank</p>
                  <span class="text-black font-w500">Makan Bank</span> </div>
                <div class="mr-3 pb-3">
                  <p class="fs-14 mb-1">Card Number</p>
                  <span class="text-black font-w500">**** **** **** 6783</span> </div>
                <div class="mr-3 pb-3">
                  <p class="fs-14 mb-1">Namein Card</p>
                  <span class="text-black font-w500">Franklin Jr.</span> </div>
                <a href="{!! url('/transactions-details'); !!}" class="fs-14 btn-link mr-3 pb-3">See Number</a>
                <div class="dropdown pb-3">
                  <div class="btn-link" role="button" data-toggle="dropdown" aria-expanded="false">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M10 11.9999C10 13.1045 10.8954 13.9999 12 13.9999C13.1046 13.9999 14 13.1045 14 11.9999C14 10.8954 13.1046 9.99994 12 9.99994C10.8954 9.99994 10 10.8954 10 11.9999Z" fill="black"></path>
                      <path d="M10 4.00006C10 5.10463 10.8954 6.00006 12 6.00006C13.1046 6.00006 14 5.10463 14 4.00006C14 2.89549 13.1046 2.00006 12 2.00006C10.8954 2.00006 10 2.89549 10 4.00006Z" fill="black"></path>
                      <path d="M10 20C10 21.1046 10.8954 22 12 22C13.1046 22 14 21.1046 14 20C14 18.8954 13.1046 18 12 18C10.8954 18 10 18.8954 10 20Z" fill="black"></path>
                    </svg>
                  </div>
                  <div class="dropdown-menu dropdown-menu-right"> <a class="dropdown-item" href="javascript:void()">Delete</a> <a class="dropdown-item" href="javascript:void()">Edit</a> </div>
                </div>
              </div>
              <div class="d-flex border-bottom justify-content-between flex-wrap align-items-center">
                <div class="d-flex pb-3 align-items-center"> <img src="{{ asset('images/card/4.jpg') }}" alt="" class="rounded mr-3" width="130">
                  <div class="mr-3">
                    <p class="fs-14 mb-1">Card Type</p>
                    <span class="text-black font-w500">Secondary</span> </div>
                </div>
                <div class="mr-3 pb-3">
                  <p class="fs-14 mb-1">Bank</p>
                  <span class="text-black font-w500">Where Bank</span> </div>
                <div class="mr-3 pb-3">
                  <p class="fs-14 mb-1">Card Number</p>
                  <span class="text-black font-w500">**** **** **** 8843</span> </div>
                <div class="mr-3 pb-3">
                  <p class="fs-14 mb-1">Namein Card</p>
                  <span class="text-black font-w500">Franklin Jr.</span> </div>
                <a href="{!! url('/transactions-details'); !!}" class="fs-14 btn-link mr-3 pb-3">See Number</a>
                <div class="dropdown pb-3">
                  <div class="btn-link" role="button" data-toggle="dropdown" aria-expanded="false">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M10 11.9999C10 13.1045 10.8954 13.9999 12 13.9999C13.1046 13.9999 14 13.1045 14 11.9999C14 10.8954 13.1046 9.99994 12 9.99994C10.8954 9.99994 10 10.8954 10 11.9999Z" fill="black"></path>
                      <path d="M10 4.00006C10 5.10463 10.8954 6.00006 12 6.00006C13.1046 6.00006 14 5.10463 14 4.00006C14 2.89549 13.1046 2.00006 12 2.00006C10.8954 2.00006 10 2.89549 10 4.00006Z" fill="black"></path>
                      <path d="M10 20C10 21.1046 10.8954 22 12 22C13.1046 22 14 21.1046 14 20C14 18.8954 13.1046 18 12 18C10.8954 18 10 18.8954 10 20Z" fill="black"></path>
                    </svg>
                  </div>
                  <div class="dropdown-menu dropdown-menu-right"> <a class="dropdown-item" href="javascript:void()">Delete</a> <a class="dropdown-item" href="javascript:void()">Edit</a> </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3">
      <div class="row">
        <div class="col-xl-12">
          <div class="card">
            <div class="card-header border-0 pb-0">
              <div>
                <h4 class="fs-20 text-black">Card Statistic</h4>
                <span class="fs-12">Lorem ipsum dolor sit amet, consectetur</span> </div>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-xl-12 col-sm-6">
                  <div id="pieChart"></div>
                </div>
                <div class=" col-xl-12 col-sm-6">
                  <div class="d-flex flex-wrap text-black fs-12 mt-4"> <span class="mr-4 mb-3">
                    <svg class="mr-2" width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <rect width="19" height="19" rx="9.5" fill="#1EAAE7"/>
                    </svg>
                    Main Card </span> <span class="mr-4 mb-3">
                    <svg class="mr-2" width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <rect width="19" height="19" rx="9.5" fill="#FF7A30"/>
                    </svg>
                    Orange Card </span> <span class="mr-4 mb-3">
                    <svg class="mr-2" width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <rect width="19" height="19" rx="9.5" fill="#6418C3"/>
                    </svg>
                    Purple Card </span> <span class="mr-4 mb-3">
                    <svg class="mr-2" width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <rect width="19" height="19" rx="9.5" fill="#FFEF5F"/>
                    </svg>
                    Yellow Card </span> <span class="mr-4 mb-3">
                    <svg class="mr-2" width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <rect width="19" height="19" rx="9.5" fill="#2BC155"/>
                    </svg>
                    Green Card </span> </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

			
@endsection			