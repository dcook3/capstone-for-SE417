<body id="page-top">
	<div id="wrapper">
		<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
			<a class="sidebar-brand d-flex align-items-center justify-content-center" href="index?view_orders">
				<div class="sidebar-brand-icon rotate-n-15">
					
				</div>
				<div class="sidebar-brand-text mx-3">Website</div>
			</a>

			<!-- Divider -->
			<hr class="sidebar-divider my-0">

			<!-- Nav Item - Dashboard -->
			<li class="nav-item active">
				<a class="nav-link" href="orders_view">
					<i class="fas fa-fw fa-tachometer-alt"></i>
					<span>Orders</span></a>
			</li>
			<!--<li class="nav-item">
				<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collpaseFour" aria-expanded="true" aria-controls="collpaseFour">
					<i class="fas fa-fw fa-tachometer-alt"></i>
					<span>Orders</span>
				</a>
				<div id="collpaseFour" class="collapse <?php echo ORD_DROPDOWN_SHOW ?>" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
					<div class="bg-white py-2 collapse-inner rounded">
						<h6 class="collapse-header">Orders:</h6>
						<a class="collapse-item <?php echo NEW_ORD ?>" href="index?view_orders&new=1">New Orders</a>
						<a class="collapse-item <?php echo DEL_ORD ?>" href="index?view_orders&delivered=1">Delivered Orders</a>
					</div>
				</div>
			</li>-->

			<!-- Divider -->
			<hr class="sidebar-divider">

			<!-- Heading -->
			<div class="sidebar-heading">
				Content
			</div>

			<!-- Nav Item - Pages Collapse Menu -->
			<!--<li class="nav-item">
				<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
					<i class="fas fa-fw fa-beer"></i>
					<span>Categories</span>
				</a>
				<div id="collapseOne" class="collapse <?php echo CAT_DROPDOWN_SHOW ?>" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
					<div class="bg-white py-2 collapse-inner rounded">
						<h6 class="collapse-header">Modify Categories:</h6>
						<a class="collapse-item <?php echo VIEW_CAT ?>" href="index?cat&view_cat">View</a>
						<a class="collapse-item <?php echo ADD_CAT ?>" href="index?cat&add_cat">Add</a>
						<a class="collapse-item <?php echo MODIFY_CAT ?>" href="index?cat&modify_cat">Modify</a>
					</div>
				</div>
			</li>-->

			<!--<li class="nav-item">
				<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
					<i class="fas fa-fw fa-pizza-slice"></i>
					<span>Items</span>
				</a>
				<div id="collapseTwo" class="collapse <?php echo PROD_DROPDOWN_SHOW; ?>" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
					<div class="bg-white py-2 collapse-inner rounded">
						<h6 class="collapse-header">Add/Modify Items:</h6>
						<a class="collapse-item <?php echo VIEW_PROD; ?>" href="index?prod&view_prod">View/Modify</a>
						<a class="collapse-item <?php echo ADD_PROD; ?>" href="index?prod&add_prod=1">Add</a>
					</div>
				</div>
			</li>-->

            <li class="nav-item">
				<a class="nav-link collapsed" href="menu_view" data-toggle="" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
					<i class="fas fa-fw fa-pizza-slice"></i>
					<span>Menu Items</span>
				</a>
				<div id="collapseThree" class="collapse <?php echo MENU_DROPDOWN_SHOW; ?>" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
					<div class="bg-white py-2 collapse-inner rounded">
						<h6 class="collapse-header">Add/Modify Items:</h6>
						<a class="collapse-item <?php echo VIEW_PROD; ?>" href="index?prod&view_prod">View/Modify</a>
						<a class="collapse-item <?php echo ADD_PROD; ?>" href="index?prod&add_prod=1">Add</a>
					</div>
				</div>
			</li>

			<!-- Divider -->
			<hr class="sidebar-divider">

			<!-- Heading -->
			<div class="sidebar-heading">
				Logins
			</div>

			<!-- Nav Item - Pages Collapse Menu -->
			<li class="nav-item">
				<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collpaseLogin" aria-expanded="true" aria-controls="collpaseLogin">
					<i class="fas fa-globe"></i>
					<span>View / Modify Logins</span>
				</a>
				<div id="collpaseLogin" class="collapse <?php echo LOGIN_DROPDOWN_SHOW ?>" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
					<div class="bg-white py-2 collapse-inner rounded">
						<h6 class="collapse-header">Modify Logins:</h6>
						<a class="collapse-item <?php echo ADMIN_LOGIN ?>" href="index?logins&admin">Admin</a>
						<a class="collapse-item <?php echo STAFF_LOGIN ?>" href="index?logins&staff">Staff</a>
						<a class="collapse-item <?php echo CUST_LOGIN ?>" href="index?logins&cust">Customer</a>
					</div>
				</div>
			</li>

			<!-- Nav Item - Pages Collapse Menu -->
			<li class="nav-item">
				<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collpaseAddLogin" aria-expanded="true" aria-controls="collpaseAddLogin">
					<i class="fas fa-user-plus"></i>
					<span>Add Logins</span>
				</a>
				<div id="collpaseAddLogin" class="collapse <?php echo ADD_LOGIN_DROPDOWN_SHOW ?>" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
					<div class="bg-white py-2 collapse-inner rounded">
						<h6 class="collapse-header">Add New Logins:</h6>
						<a class="collapse-item <?php echo ADD_ADMIN_LOGIN ?>" href="index?add_logins&admin">Admin</a>
						<a class="collapse-item <?php echo ADD_STAFF_LOGIN ?>" href="index?add_logins&staff">Staff</a>
					</div>
				</div>
			</li>

			<!-- Divider -->
			<hr class="sidebar-divider">

			<!-- Heading -->
			<!--<div class="sidebar-heading">
				Delivery Zipcodes
			</div>
			Nav Item - Pages Collapse Menu
			<li class="nav-item">
				<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseZipCode" aria-expanded="true" aria-controls="collapseZipCode">
					<i class="fas fa-truck"></i>
					<span>Zip Codes</span>
				</a>
				<div id="collapseZipCode" class="collapse <?php echo ZIP_DROPDOWN_SHOW ?>" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
					<div class="bg-white py-2 collapse-inner rounded">
						<h6 class="collapse-header">Modify Zip Codes:</h6>
						<a class="collapse-item <?php echo VIEW_ZIP ?>" href="index?zip&modify">View/Remove/Modify</a>
						<a class="collapse-item <?php echo ADD_ZIP ?>" href="index?zip&add">Add New</a>
					</div>
				</div>
			</li>
			<hr class="sidebar-divider">-->
		</ul>