<div class="list-group">
	<a href="<?= base_url('account/dashboard');?>" class="list-group-item <?= ($page == 'dashboard') ? 'active' : ''?>">
		<i class="fa fa-tachometer"></i> Dashboard
	</a>
    
	<a href="<?= base_url('account/profile/');?>" class="list-group-item <?= ($page == 'profile') ? 'active' : ''?>">
		<i class="fa fa-user"></i> Your Profile
	</a>
	
    <a href="<?= base_url('account/listing/');?>" class="list-group-item <?= ($page == 'listing' || $page == 'listing_add') ? 'active' : ''?>">
		<i class="fa fa-list"></i> Your Listing
	</a>
	
	<a href="<?= base_url('account/enquiries/');?>" class="list-group-item <?= ($page == 'enquiries') ? 'active' : ''?>">
		<i class="fa fa-envelope"></i> Enquiries
	</a>
		
    <a href="<?= base_url('account/change_password/');?>" class="list-group-item <?= ($page == 'change_password') ? 'active' : ''?>">
		<i class="fa fa-key"></i> Change Password
	</a>
    
		
    <a href="<?= base_url('logout');?>" class="list-group-item">
		<i class="fa fa-sign-out"></i> Logout
	</a>
</div>