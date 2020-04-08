<?php
/*
* @Author           : Geetha
* @Created Date     : 18/06/2018
* @Version          : 3.1.10
* @Description      : login page
* View Name   : login.php
*/
?>

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
			<b>Welcome to SMS</b>
			</div><!-- /.login-logo -->
			<div class="login-box-body">
				<p class="login-box-msg">Sign In</p>
				<div class="row">
					<div class="col-md-12">
					</div>
				</div>
		<?php echo form_open('admin/'); ?>
		<div class="form-group has-feedback">
			<select name="role_id" class="form-control"  onchange="select_role(this.value)">
				<option value="" disabled selected>Select Roles</option>
				<option value="1" selected="selected">Admin</option>
				<option value="2">Teacher</option>
			</select>
		</div>
		
		<div class="form-group has-feedback" id="admin_username">
			<input type="text" class="form-control" placeholder="Username" name="username"  maxlength="50">
			<span class="glyphicon glyphicon-phone form-control-feedback"></span>
		</div>

		<div class="form-group has-feedback" id="staff_email" style="display: none">
			<input type="text" class="form-control" placeholder="Email" name="email"  maxlength="100">
			<span class="glyphicon glyphicon-phone form-control-feedback"></span>
		</div>

		<div class="form-group has-feedback">
			<input type="password" class="form-control" placeholder="Password" name="password" required="">
		</div>

		<div class="form-group has-campus">
		<?php if(!empty($campus['data'])):?>		
					<select name="campus_id" class="form-control">
						<?php foreach($campus['data'] as $results): ?>
							<option value="<?php echo $results['campus_id']?>"><?php echo $results['campus_name'];?>
							</option>
					<?php endforeach; ?>
					</select>
		<?php  else: ?>
		No Campus found......
		<?php endif; ?>
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
					<input type="submit" class="btn btn-primary btn-block btn-flat" value="Sign In" name="login_submit">
					</div><!-- /.col -->
				</div>
			<?php echo form_close(); ?>
						<!--<a href="http://localhost/ems/forgotPassword">Forgot Password</a><br> -->
			
			</div><!-- /.login-box-body -->
			</div><!-- /.login-box -->
		</body></html>
	<?php $this->load->view('admin/footer')?>

	<script type="text/javascript">
		function select_role(id)
		{
			if(id == 2)
			{
				$("#admin_username").hide();
				$("#staff_email").show();
				$("#staff_email").attr("required", "true");
			}
			else
			{
				$("#admin_username").show();
				$("#staff_email").hide();
				$("#admin_username").attr("required", "true");
			}
		}
	</script>