<?php $this->load->view('admin/header')?>
<body class="hold-transition login-page">
	<?php if ($this->session->flashdata('error')) { ?>
			<div class="alert alert-danger">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
			<strong><?php echo $this->session->flashdata('error'); ?></strong>
			</div>
	<?php } ?>

	<?php if ($this->session->flashdata('success')) { ?>
			<div class="alert alert-success">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
			<strong><?php echo $this->session->flashdata('success'); ?></strong>
			</div>
	<?php } ?>
	
	<div class="login-box">
		<div class="login-logo">
			<b>Welcome to STS</b>
			</div><!-- /.login-logo -->
			<div class="login-box-body">
				<p class="login-box-msg">Sign In</p>
				<div class="row">
					<div class="col-md-12">
					</div>
				</div>
		<?php 
		$attributes = array('id' => 'login_form');
		echo form_open('/',$attributes); ?>
		<div class="form-group has-feedback">
			<select name="role_id" id="role_id" class="form-control">
				<option value="" disabled selected>Select Role</option>
				<option value="1" >Admin</option>
				<!--  <option value="2" selected="selected">Staff</option>-->
			</select>
		</div>
		
		<div class="form-group has-feedback" id="admin_username" >
			<input type="text" class="form-control" placeholder="Username" name="username" id="username">
			<span class="glyphicon glyphicon-phone form-control-feedback"></span>
		</div>

		<div class="form-group has-feedback" id="admin_password" >
			<input type="password" class="form-control" placeholder="Password" name="password" id="password">
			<span class="glyphicon glyphicon-lock form-control-feedback"></span>
		</div>

		<div class="form-group has-feedback" id="staff_phone" style="display: none">
			<input type="text" class="form-control" placeholder="Mobile No" name="phone_no" maxlength=10>
			<span class="glyphicon glyphicon-phone form-control-feedback"></span>
		</div>

		<div class="form-group has-feedback" id="staff_pin" style="display: none">
			<input type="text" class="form-control" placeholder="Enter 6 digits PIN No" name="pin_no" maxlength=6>
			<span class="glyphicon glyphicon-lock form-control-feedback"></span>
		</div>

		

	
		
		<div class="row">
			<div class="col-xs-8">
				<!-- <div class="checkbox icheck">
					<label>
						<input type="checkbox"> Remember Me
					</label>
				</div>  -->
				</div><!-- /.col -->
				<div class="col-xs-4">
					<input type="submit" class="btn btn-primary btn-block btn-flat" value="Sign In" name="login_submit" id="login_submit">
					</div><!-- /.col -->
				</div>
			<?php echo form_close(); ?>
						 
			
			</div><!-- /.login-box-body -->
			</div><!-- /.login-box -->
		</body></html>
	<?php $this->load->view('admin/footer')?>

<script src="<?php echo base_url(); ?>js/login.js" type="text/javascript"></script>
<style>
.has-feedback label~.form-control-feedback{top:0px;}
</style>