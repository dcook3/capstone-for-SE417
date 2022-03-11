<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
	<button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
		<i class="fa fa-bars"></i>
	</button>
	<ul class="navbar-nav ml-auto">
    <li class="nav-item dropdown no-arrow d-sm-none">
			<a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class="fas fa-search fa-fw"></i>
			</a>
    <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
		<form class="form-inline mr-auto w-100 navbar-search">
					<div class="input-group">
						<input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
						<div class="input-group-append">
							<button class="btn btn-primary" type="button">
								<i class="fas fa-search fa-sm"></i>
							</button>
						</div>
					</div>
			</form>
			</div>
		</li>
        <li class="nav-item dropdown no-arrow"  data-toggle="tooltip" data-placement="left" data-original-title="">
			<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<span class="mr-2 d-none d-lg-inline text-gray-600 small">Users</span>
			</a>
			<!-- Dropdown - User Information -->
			<div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
            <a class="dropdown-item" href="index?logins&admin">
					<i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
					Admin
				</a>
				<a class="dropdown-item" href="index?logins&admin">
					<i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
					Staff
				</a>
				<a class="dropdown-item" href="index?logins&cust">
					<i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
					Customers
				</a>
			</div>
		</li>
        <li class="nav-item"  data-toggle="tooltip" data-placement="left">
			<a class="nav-link" href="index?order" id="" role="button">
				<span class="mr-2 d-none d-lg-inline text-gray-600 small">Orders</span>
			</a>
		</li>
		<li class="nav-item"  data-toggle="tooltip" data-placement="left">
			<a class="nav-link" href="index?menu" id="" role="button">
				<span class="mr-2 d-none d-lg-inline text-gray-600 small">Menu</span>
			</a>
		</li>
        <div class="topbar-divider d-none d-sm-block"></div>
        <li class="nav-item dropdown no-arrow"  data-toggle="tooltip" data-placement="left" data-original-title="Click to display options">
			<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<span class="mr-2 d-none d-lg-inline text-gray-600 small">Account</span>
				<i class="fas fa-chevron-circle-down"></i>
			</a>
			<!-- Dropdown - User Information -->
			<div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
            <!-- <a class="dropdown-item" href="#">
					<i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
					Profile
				</a>
				<a class="dropdown-item" href="#">
					<i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
					Settings
				</a>
				<a class="dropdown-item" href="#">
					<i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
					Activity Log
				</a>
				<div class="dropdown-divider"></div> -->
				<a class="dropdown-item" href="index?changePassword">
					<i class="fas fa-key fa-sm fa-fw mr-2 text-gray-400"></i>
					Change Password
				</a>				
				<a class="dropdown-item" href="logout" data-toggle="modal" data-target="#logoutModal">
					<i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
					Logout
				</a>
			</div>
		</li>
	</ul>
</nav>

			<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
	<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
			<button class="close" type="button" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">×</span>
			</button>
		</div>
		<div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
		<div class="modal-footer">
			<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
			<a class="btn btn-primary" href="logout">Logout</a>
		</div>
	</div>
</div>
</div>