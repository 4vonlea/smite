<?php
/**
 * @var $content
 * @var $breadcrumb
 * @var $script_js
 */
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title><?=Settings_m::getSetting('site_title');?></title>


    <!-- Favicon -->
    <link href="<?= base_url(); ?>themes/argon/img/brand/favicon.png" rel="icon" type="image/png">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">

    <!-- Icons -->
    <link href="<?= base_url(); ?>themes/argon/vendor/nucleo/css/nucleo.css" rel="stylesheet">
    <link href="<?= base_url(); ?>themes/argon/vendor/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">

    <!-- Argon CSS -->
    <link type="text/css" href="<?= base_url(); ?>themes/argon/css/argon.min.css" rel="stylesheet">
    <script src="<?= base_url('themes/script/vue.js'); ?>"></script>
    <style>
        .fade-enter-active, .fade-leave-active {
            transition: opacity .4s;
        }

        .fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */
        {
            opacity: 0;
        }
		 .nav-pills .nav-link.active{
			 color:#fff;
			 background-color: #172b4d!important;
		 }
        .wrapper-datepicker {
            position: relative;
            width: 1%;
            margin-bottom: 0;
            flex: 1 1 auto;
        }
         .table-grid th{
             background-color: #f6f9fc;
             color:#8898aa;
             border-color: #e9ecef;
         }
        .pagination .active{
            color: #fff !important;
            border-color: #5e72e4;
            background-color: #5e72e4;
        }
        .action-th{
            width: 10%;
        }
    </style>
</head>

<body>
<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
    <div class="container-fluid">
        <!-- Toggler -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main"
                aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Brand -->
        <a class="navbar-brand pt-0" href="./index.html">
            <img src="<?= base_url('themes/uploads/logo.png'); ?>" class="navbar-brand-img" alt="...">
        </a>
        <!-- User -->

        <!-- Collapse -->
        <div class="collapse navbar-collapse" id="sidenav-collapse-main">
            <!-- Collapse header -->
            <div class="navbar-collapse-header d-md-none">
                <div class="row">
                    <div class="col-6 collapse-brand">
                        <a href="./index.html">
                            <img src="<?= base_url(); ?>themes/argon/img/brand/blue.png">
                        </a>
                    </div>
                    <div class="col-6 collapse-close">
                        <button type="button" class="navbar-toggler" data-toggle="collapse"
                                data-target="#sidenav-collapse-main" aria-controls="sidenav-main"
                                aria-expanded="false" aria-label="Toggle sidenav">
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Form -->
            <form class="mt-4 mb-3 d-md-none">
                <div class="input-group input-group-rounded input-group-merge">
                    <input type="search" class="form-control form-control-rounded form-control-prepended"
                           placeholder="Search" aria-label="Search">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <span class="fa fa-search"></span>
                        </div>
                    </div>
                </div>
            </form>
            <!-- Navigation -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('admin/dashboard'); ?>">
                        <i class="ni ni-tv-2 text-primary"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('admin/member'); ?>">
                        <i class="ni ni-single-02 text-orange"></i> Members
                    </a>
                </li>
				<li class="nav-item">
					<a class="nav-link" href="<?= base_url('admin/paper'); ?>">
						<i class="ni ni-book-bookmark text-orange"></i> Papers
					</a>
				</li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('admin/event'); ?>">
                        <i class="ni ni-planet text-blue"></i> Events List
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('admin/transaction'); ?>">
                        <i class="ni ni-cart text-orange"></i> Transaction
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('admin/notification'); ?>">
                        <i class="ni ni-chat-round text-blue"></i> Message & Notification
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('admin/setting'); ?>">
                        <i class="ni ni-settings text-red"></i> Setting
                    </a>
                </li>
            </ul>
            <!-- Divider -->
            <hr class="my-3">
            <!-- Heading -->
            <h6 class="navbar-heading text-muted">On Event</h6>
            <!-- Navigation -->
            <ul class="navbar-nav mb-md-3">
                <li class="nav-item">
                    <a class="nav-link"
                       href="#">
                        <i class="ni ni-spaceship text-green"></i> Register On Site
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link"
                       href="#">
                        <i class="ni ni-bullet-list-67 text-green"></i> Presence check
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="main-content">
    <nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
        <div class="container-fluid">
            <!-- Brand -->
            <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block"
               href="<?= current_url(); ?>"><?= $breadcrumb; ?></a>
            <!-- Form -->
            <form class="navbar-search navbar-search-dark form-inline mr-3 d-none d-md-flex ml-lg-auto">
                <div class="form-group mb-0">
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                        <input class="form-control" placeholder="Search" type="text">
                    </div>
                </div>
            </form>
            <!-- User -->
            <ul class="navbar-nav align-items-center d-none d-md-flex">
                <li class="nav-item dropdown">
                    <a class="nav-link pr-0" href="<?=base_url('admin/login/logout');?>" role="button" aria-expanded="false">
                        <div class="media align-items-center">
							<span class="avatar avatar-sm rounded-circle">
								<i class="ni ni-user-run"></i>
							</span>
                            <div class="media-body ml-2 d-none d-lg-block">
                                <span class="mb-0 text-sm  font-weight-bold">Logout</span>
                            </div>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
    <div id="app">
        <?= $content; ?>
    </div>

    <!-- Footer -->
    <div class="container-fluid">
        <footer class="footer">
            <div class="row align-items-center justify-content-xl-between">
                <div class="col-xl-6">
                    <div class="copyright text-center text-xl-left text-muted">
                        &copy; 2018 <a href="https://www.creative-tim.com" class="font-weight-bold ml-1"
                                       target="_blank">Design By Creative Tim</a>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="copyright text-center text-xl-left text-muted">
                        &copy; 2018 <a href="https://www.facebook.com/untung.bimantara" class="font-weight-bold ml-1"
                                       target="_blank">Created By USB & Friends</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

</div>
<!-- Core -->
<script src="<?= base_url(); ?>themes/argon/vendor/jquery/dist/jquery.min.js"></script>
<script src="<?= base_url(); ?>themes/argon/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

<!-- Argon JS -->
<script src="<?= base_url("themes/script/moment.min.js"); ?>"></script>
<script src="<?= base_url("themes/script/vuetable2.js"); ?>"></script>
<script src="<?= base_url("themes/script/sweetalert2@8.js"); ?>"></script>
<script src="<?= base_url("themes/script/datatable.js?") . time(); ?>"></script>
<script>
    Vue.use(Vuetable);
</script>

<?= $script_js; ?>
<script src="<?= base_url(); ?>themes/argon/js/argon.min.js"></script>
</body>

</html>
