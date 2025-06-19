@extends('index')

@section('title-content')
<title>Học phí</title>
@endsection

@section('main-content')


<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<link href="{{ asset('admin/luanvantemplate/dist/css/my.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<link href="{{ asset('admin/luanvantemplate/dist/css/style.min.css') }}" rel="stylesheet">

<style>

</style>

<body>

    <div id="main-wrapper">
        <div class="container-fluid">
            <!-- ============================================================== -->
            <!-- Start Page Content -->
            <!-- ============================================================== -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title m-b-0">Recent Posts</h4>
                        </div>
                        <div class="comment-widgets scrollable">
                            <!-- Comment Row -->
                            <div class="d-flex flex-row comment-row m-t-0">
                                <div class="p-2"><img src="assets/images/users/1.jpg" alt="user" width="50" class="rounded-circle"></div>
                                <div class="comment-text w-100">
                                    <h6 class="font-medium">James Anderson</h6>
                                    <span class="m-b-15 d-block">Lorem Ipsum is simply dummy text of the printing and type setting industry. </span>
                                    <div class="comment-footer">
                                        <span class="text-muted float-right">April 14, 2016</span>
                                        <button type="button" class="btn btn-cyan btn-sm">Edit</button>
                                        <button type="button" class="btn btn-success btn-sm">Publish</button>
                                        <button type="button" class="btn btn-danger btn-sm">Delete</button>
                                    </div>
                                </div>
                            </div>
                            <!-- Comment Row -->
                            <div class="d-flex flex-row comment-row">
                                <div class="p-2"><img src="assets/images/users/4.jpg" alt="user" width="50" class="rounded-circle"></div>
                                <div class="comment-text active w-100">
                                    <h6 class="font-medium">Michael Jorden</h6>
                                    <span class="m-b-15 d-block">Lorem Ipsum is simply dummy text of the printing and type setting industry. </span>
                                    <div class="comment-footer">
                                        <span class="text-muted float-right">May 10, 2016</span>
                                        <button type="button" class="btn btn-cyan btn-sm">Edit</button>
                                        <button type="button" class="btn btn-success btn-sm">Publish</button>
                                        <button type="button" class="btn btn-danger btn-sm">Delete</button>
                                    </div>
                                </div>
                            </div>
                            <!-- Comment Row -->
                            <div class="d-flex flex-row comment-row">
                                <div class="p-2"><img src="assets/images/users/5.jpg" alt="user" width="50" class="rounded-circle"></div>
                                <div class="comment-text w-100">
                                    <h6 class="font-medium">Johnathan Doeting</h6>
                                    <span class="m-b-15 d-block">Lorem Ipsum is simply dummy text of the printing and type setting industry. </span>
                                    <div class="comment-footer">
                                        <span class="text-muted float-right">August 1, 2016</span>
                                        <button type="button" class="btn btn-cyan btn-sm">Edit</button>
                                        <button type="button" class="btn btn-success btn-sm">Publish</button>
                                        <button type="button" class="btn btn-danger btn-sm">Delete</button>
                                    </div>
                                </div>
                            </div>
                            <!-- Comment Row -->
                            <div class="d-flex flex-row comment-row">
                                <div class="p-2"><img src="assets/images/users/4.jpg" alt="user" width="50" class="rounded-circle"></div>
                                <div class="comment-text w-100">
                                    <h6 class="font-medium">Johnathan Doeting</h6>
                                    <span class="m-b-15 d-block">Lorem Ipsum is simply dummy text of the printing and type setting industry. </span>
                                    <div class="comment-footer">
                                        <span class="text-muted float-right">August 1, 2016</span>
                                        <button type="button" class="btn btn-cyan btn-sm">Edit</button>
                                        <button type="button" class="btn btn-success btn-sm">Publish</button>
                                        <button type="button" class="btn btn-danger btn-sm">Delete</button>
                                    </div>
                                </div>
                            </div>
                            <!-- Comment Row -->
                            <div class="d-flex flex-row comment-row">
                                <div class="p-2"><img src="assets/images/users/3.jpg" alt="user" width="50" class="rounded-circle"></div>
                                <div class="comment-text w-100">
                                    <h6 class="font-medium">Johnathan Doeting</h6>
                                    <span class="m-b-15 d-block">Lorem Ipsum is simply dummy text of the printing and type setting industry. </span>
                                    <div class="comment-footer">
                                        <span class="text-muted float-right">August 1, 2016</span>
                                        <button type="button" class="btn btn-cyan btn-sm">Edit</button>
                                        <button type="button" class="btn btn-success btn-sm">Publish</button>
                                        <button type="button" class="btn btn-danger btn-sm">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- accoridan part -->
                    <div class="accordion" id="accordionExample">
                        <div class="card m-b-0">
                            <div class="card-header" id="headingOne">
                                <h5 class="mb-0">
                                    <a data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        <i class="m-r-5 fa fa-magnet" aria-hidden="true"></i>
                                        <span>Accordion Example 1</span>
                                    </a>
                                </h5>
                            </div>
                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                                <div class="card-body">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                </div>
                            </div>
                        </div>
                        <div class="card m-b-0 border-top">
                            <div class="card-header" id="headingTwo">
                                <h5 class="mb-0">
                                    <a class="collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        <i class="m-r-5 fa fa-magnet" aria-hidden="true"></i>
                                        <span>Accordion Example 2</span>
                                    </a>
                                </h5>
                            </div>
                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                <div class="card-body">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                </div>
                            </div>
                        </div>
                        <div class="card m-b-0 border-top">
                            <div class="card-header" id="headingThree">
                                <h5 class="mb-0">
                                    <a class="collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        <i class="m-r-5 fa fa-magnet" aria-hidden="true"></i>
                                        <span>Accordion Example 3</span>
                                    </a>
                                </h5>
                            </div>
                            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                                <div class="card-body">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- toggle part -->
                    <div id="accordian-4">
                        <div class="card m-t-30">
                            <a class="card-header link" data-toggle="collapse" data-parent="#accordian-4" href="#Toggle-1" aria-expanded="true" aria-controls="Toggle-1">
                                <i class="fa fa-arrow-right" aria-hidden="true"></i>
                                <span>Toggle, Open by default</span>
                            </a>
                            <div id="Toggle-1" class="collapse show multi-collapse">
                                <div class="card-body widget-content">
                                    This box is opened by default, paragraphs and is full of waffle to pad out the comment. Usually, you just wish these sorts of comments would come to an end.
                                </div>
                            </div>
                            <a class="card-header link border-top" data-toggle="collapse" data-parent="#accordian-4" href="#Toggle-2" aria-expanded="false" aria-controls="Toggle-2">
                                <i class="fa fa-times" aria-hidden="true"></i>
                                <span>Toggle, Closed by default</span>
                            </a>
                            <div id="Toggle-2" class="multi-collapse collapse" style="">
                                <div class="card-body widget-content">
                                    This box is now open
                                </div>
                            </div>
                            <a class="card-header link collapsed border-top" data-toggle="collapse" data-parent="#accordian-4" href="#Toggle-3" aria-expanded="false" aria-controls="Toggle-3">
                                <i class="fa fa-times" aria-hidden="true"></i>
                                <span>Toggle, Closed by default</span>
                            </a>
                            <div id="Toggle-3" class="collapse multi-collapse">
                                <div class="card-body widget-content">
                                    This box is now open
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- card new -->
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title m-b-0">News Updates</h4>
                        </div>
                        <ul class="list-style-none">
                            <li class="d-flex no-block card-body">
                                <i class="fa fa-check-circle w-30px m-t-5"></i>
                                <div>
                                    <a href="#" class="m-b-0 font-medium p-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</a>
                                    <span class="text-muted">dolor sit amet, consectetur adipiscing</span>
                                </div>
                                <div class="ml-auto">
                                    <div class="tetx-right">
                                        <h5 class="text-muted m-b-0">20</h5>
                                        <span class="text-muted font-16">Jan</span>
                                    </div>
                                </div>
                            </li>
                            <li class="d-flex no-block card-body border-top">
                                <i class="fa fa-gift w-30px m-t-5"></i>
                                <div>
                                    <a href="#" class="m-b-0 font-medium p-0">Congratulation Maruti, Happy Birthday</a>
                                    <span class="text-muted">many many happy returns of the day</span>
                                </div>
                                <div class="ml-auto">
                                    <div class="tetx-right">
                                        <h5 class="text-muted m-b-0">11</h5>
                                        <span class="text-muted font-16">Jan</span>
                                    </div>
                                </div>
                            </li>
                            <li class="d-flex no-block card-body border-top">
                                <i class="fa fa-plus w-30px m-t-5"></i>
                                <div>
                                    <a href="#" class="m-b-0 font-medium p-0">Maruti is a Responsive Admin theme</a>
                                    <span class="text-muted">But already everything was solved. It will ...</span>
                                </div>
                                <div class="ml-auto">
                                    <div class="tetx-right">
                                        <h5 class="text-muted m-b-0">19</h5>
                                        <span class="text-muted font-16">Jan</span>
                                    </div>
                                </div>
                            </li>
                            <li class="d-flex no-block card-body border-top">
                                <i class="fa fa-leaf w-30px m-t-5"></i>
                                <div>
                                    <a href="#" class="m-b-0 font-medium p-0">Envato approved Maruti Admin template</a>
                                    <span class="text-muted">i am very happy to approved by TF</span>
                                </div>
                                <div class="ml-auto">
                                    <div class="tetx-right">
                                        <h5 class="text-muted m-b-0">20</h5>
                                        <span class="text-muted font-16">Jan</span>
                                    </div>
                                </div>
                            </li>
                            <li class="d-flex no-block card-body border-top">
                                <i class="fa fa-question-circle w-30px m-t-5"></i>
                                <div>
                                    <a href="#" class="m-b-0 font-medium p-0"> I am alwayse here if you have any question</a>
                                    <span class="text-muted">we glad that you choose our template</span>
                                </div>
                                <div class="ml-auto">
                                    <div class="tetx-right">
                                        <h5 class="text-muted m-b-0">15</h5>
                                        <span class="text-muted font-16">Jan</span>
                                    </div>
                                </div>
                            </li>
                            <li class="d-flex no-block card-body border-top">
                                <i class="fa fa-plus w-30px m-t-5"></i>
                                <div>
                                    <a href="#" class="m-b-0 font-medium p-0">Maruti is a Responsive Admin theme</a>
                                    <span class="text-muted">But already everything was solved. It will ...</span>
                                </div>
                                <div class="ml-auto">
                                    <div class="tetx-right">
                                        <h5 class="text-muted m-b-0">19</h5>
                                        <span class="text-muted font-16">Jan</span>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title m-b-0">Tasks</h5>
                        </div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Description</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Making The New Suit</td>
                                    <td class="text-success">Progress</td>
                                    <td>
                                        <a href="#" data-toggle="tooltip" data-placement="top" title="Update">
                                            <i class="mdi mdi-check"></i>
                                        </a>
                                        <a href="#" data-toggle="tooltip" data-placement="top" title="Delete">
                                            </i><i class="mdi mdi-close"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Luanch My New Site</td>
                                    <td class="text-warning">Pending</td>
                                    <td>
                                        <a href="#" data-toggle="tooltip" data-placement="top" title="Update">
                                            <i class="mdi mdi-check"></i>
                                        </a>
                                        <a href="#" data-toggle="tooltip" data-placement="top" title="Delete">
                                            </i><i class="mdi mdi-close"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Maruti Excellant Theme</td>
                                    <td class="text-danger">Cancled</td>
                                    <td>
                                        <a href="#" data-toggle="tooltip" data-placement="top" title="Update">
                                            <i class="mdi mdi-check"></i>
                                        </a>
                                        <a href="#" data-toggle="tooltip" data-placement="top" title="Delete">
                                            </i><i class="mdi mdi-close"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- card new -->

                    <!-- Tabs -->

                    <!-- Card -->

                    <!-- card -->

                </div>
            </div>
            <!-- row -->

            <!-- ============================================================== -->
            <!-- End PAge Content -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Right sidebar -->
            <!-- ============================================================== -->
            <!-- .right-sidebar -->
            <!-- ============================================================== -->
            <!-- End Right sidebar -->
            <!-- ============================================================== -->
        </div>
    </div>

</body>




<script src=" {{ asset('admin/luanvantemplate/dist/js/my.js') }}"></script>
@endsection