<?php
/**
 * @var $content
 * @var $breadcrumb
 * @var $script_js
 * @var $additional_head
 */
$role = $this->session->user_session['role'];
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<title><?= Settings_m::getSetting('site_title'); ?></title>


	<!-- Favicon -->
	<link href="<?= base_url(); ?>themes/argon/img/brand/favicon.png" rel="icon" type="image/png">

	<!-- Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">

	<!-- Icons -->
	<link href="<?= base_url(); ?>themes/argon/vendor/nucleo/css/nucleo.css" rel="stylesheet">
	<link href="<?= base_url(); ?>themes/argon/vendor/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">

	<!-- Argon CSS -->
	<link type="text/css" href="<?= base_url(); ?>themes/argon/css/argon.min.css" rel="stylesheet">
	<?php if(ENVIRONMENT == "production"):?>
	<script src="https://cdn.jsdelivr.net/npm/vue"></script>
	<?php else: ?>
	<script src="<?= base_url('themes/script/vue.js'); ?>"></script>
	<?php endif; ?>
	<script src="<?= base_url("themes/script/moment.min.js"); ?>"></script>
	<style>
		.fade-enter-active, .fade-leave-active {
			transition: opacity .4s;
		}

		.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */
		{
			opacity: 0;
		}

		.nav-pills .nav-link.active {
			color: #fff;
			background-color: #172b4d !important;
		}

		.wrapper-datepicker {
			position: relative;
			width: 1%;
			margin-bottom: 0;
			flex: 1 1 auto;
		}

		.table-grid th {
			background-color: #f6f9fc;
			color: #8898aa;
			border-color: #e9ecef;
		}

		.pagination .active {
			color: #fff !important;
			border-color: #5e72e4;
			background-color: #5e72e4;
		}

		.action-th {
			width: 10%;
		}
	</style>
	<?=$additional_head;?>
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
		<a class="navbar-brand pt-0" href="#">
			<img src="<?= base_url('themes/uploads/smite.png'); ?>" class="navbar-brand-img" alt="...">
		</a>
		<!-- User -->
		<ul class="nav align-items-center d-md-none">
			<li class="nav-item dropdown">
				<a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<div class="media align-items-center">
					  <span class="avatar avatar-sm rounded-circle">
						  <i class="fa fa-user"></i>
					  </span>
					</div>
				</a>
				<div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
					<div class=" dropdown-header noti-title">
						<h6 class="text-overflow m-0">Welcome!</h6>
					</div>
					<a href="#" class="dropdown-item" data-toggle="modal" data-target="#modal-change-password">
						<i class="ni ni-support-16"></i>
						<span>Change Password</span>
					</a>
					<div class="dropdown-divider"></div>
					<a href="<?=base_url('admin/login/logout');?>" class="dropdown-item">
						<i class="ni ni-user-run"></i>
						<span>Logout</span>
					</a>
				</div>
			</li>
		</ul>
		<!-- Collapse -->
		<div class="collapse navbar-collapse" id="sidenav-collapse-main">
			<!-- Collapse header -->
			<div class="navbar-collapse-header d-md-none">
				<div class="row">
					<div class="col-6 collapse-brand">
						<a href="#">
							<img src="<?= base_url('themes/uploads/logo.png'); ?>">
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

			<!-- Navigation -->
			<?php if (in_array($role, ['1','2','4','5'])): ?>
				<ul class="navbar-nav">
					<?php if(!in_array($role,['4'])):?>
					<li class="nav-item">
						<a class="nav-link" href="<?= base_url('admin/dashboard'); ?>">
							<i class="ni ni-tv-2 text-primary"></i> Dashboard
						</a>
					</li>
					<?php endif;?>
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
						<a class="nav-link" href="<?= base_url('admin/news'); ?>">
							<i class="ni ni-book-bookmark text-blue"></i> News
						</a>
					</li>
					<?php if($role == '1'):?>
					<li class="nav-item">
						<a class="nav-link" href="<?= base_url('admin/account'); ?>">
							<i class="ni ni-circle-08 text-blue"></i> User Account
						</a>
					</li>
					<?php endif;?>
					<li class="nav-item">
						<a class="nav-link" href="<?= base_url('admin/committee'); ?>">
							<i class="fa fa-bookmark text-blue"></i> Committees
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
						   href="<?= base_url("admin/member/register"); ?>">
							<i class="ni ni-spaceship text-green"></i> Manual Registration
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link"
						   href="<?= base_url("admin/presence"); ?>">
							<i class="ni ni-bullet-list-67 text-green"></i> Presence check
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link"
						   href="<?= base_url("admin/administration"); ?>">
							<i class="fa fa-book text-green"></i> Administration
						</a>
					</li>
				</ul>
			<?php elseif (in_array($role, ['3'])): ?>
				<ul class="navbar-nav">
					<li class="nav-item">
						<a class="nav-link" href="<?= base_url('admin/paper'); ?>">
							<i class="ni ni-book-bookmark text-orange"></i> Papers
						</a>
					</li>
				</ul>
			<?php endif; ?>
		</div>
	</div>
</nav>
<div class="main-content">
	<nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
		<div class="container-fluid">
			<!-- Brand -->
			<a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block"
			   href="<?= current_url(); ?>"><?= $breadcrumb; ?></a>

			<!-- User -->
			<ul class="navbar-nav align-items-center d-none d-md-flex">
				<li class="nav-item dropdown">
					<a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<div class="media align-items-center">
							<span class="avatar avatar-sm rounded-circle">
								<i class="fa fa-user"></i>
							</span>
							<div class="media-body ml-2 d-none d-lg-block">
								<span class="mb-0 text-sm  font-weight-bold"><?=$this->session->user_session['name'];?></span>
							</div>
						</div>
					</a>
					<div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
						<div class=" dropdown-header noti-title">
							<h6 class="text-overflow m-0">Welcome!</h6>
						</div>
						<a href="#" class="dropdown-item" data-toggle="modal" data-target="#modal-change-password">
							<i class="ni ni-support-16"></i>
							<span>Change Password</span>
						</a>
						<a href="<?=base_url('admin/login/logout');?>" class="dropdown-item">
							<i class="ni ni-user-run"></i>
							<span>Logout</span>
						</a>
					</div>
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
	<div class="modal fade" id="modal-change-password" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Change Password</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="form-change-password">
						<div class="form-group">
							<label class="form-control-label">Old Password</label>
							<input type="password" name="old_password"  class="form-control" />
						</div>
						<div class="form-group">
							<label class="form-control-label">New Password</label>
							<input type="password" name="new_password" class="form-control" />
						</div>
						<div class="form-group">
							<label class="form-control-label">Confirm Password</label>
							<input type="password" name="confirm_password" class="form-control" />
						</div>
					</form>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="button" id="btn-change-password" class="btn btn-primary">Change</button>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Core -->
<script src="<?= base_url(); ?>themes/argon/vendor/jquery/dist/jquery.min.js"></script>
<script src="<?= base_url(); ?>themes/argon/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

<!-- Argon JS -->
<script src="<?= base_url("themes/script/vuetable2.js"); ?>"></script>
<script src="<?= base_url("themes/script/sweetalert2@8.js"); ?>"></script>
<script src="<?= base_url("themes/script/datatable.js?") . time(); ?>"></script>
<script>
    Vue.use(Vuetable);
</script>
<script>
	$(function () {
	    $("#modal-change-password").on("show.bs.modal",function () {
            $("#modal-change-password .modal-body").find(".alert").remove();
            $("#form-change-password").trigger("reset");
        });

		$("#btn-change-password").click(function () {
			$(this).attr("disabled",true);
			$(this).html("<i class='fa fa-spin fa-spinner'></i>");
            $("#modal-change-password .modal-body").find(".alert").remove();
			var data = $("#form-change-password").serialize();
			$.post("<?=base_url('admin/setting/change_password');?>",data,function (res) {
			    if(res.status){
			        Swal.fire("Success","New password has been saved !","success")
				}else if(res.validation){
					$("#modal-change-password .modal-body").prepend(
					    $("<div class='alert alert-danger'></div>").html(res.validation)
					);
				}
            },"JSON").always(function () {
                $("#btn-change-password").html("Change");
                $("#btn-change-password").removeAttr("disabled");
            }).fail(function () {
                Swal.fire("Fail","Failed request to server","error");
            });
        });
    });
</script>
<?= $script_js; ?>
<script src="<?= base_url(); ?>themes/argon/js/argon.min.js"></script>
</body>

</html>
