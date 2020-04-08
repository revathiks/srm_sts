<?php
/*
* @Author           : Geetha
* @Created Date     : 18/06/2018
* @Version          : 3.1.10
* @Description      : view tips using param id
* View Name   : view.php
*/
?>
<?php $this->load->view('admin/header')?>
<?php $this->load->view('admin/sidebar')?>
<div class="content-wrapper" style="min-height: 237px;">
	<section class="content-header">
		<h1><i class="fa fa-lightbulb-o"></i> Manage Students</h1>
		<ol class="breadcrumb">
			<li>
			<a href="<?= base_url();?>/admin/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a> </li>
			<li>
				<a href="<?= base_url();?>/admin/student">Students</a>
			</li>
			<li class="active">View</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title">View Student</h3>
					</div>
					<div class="box-body">
					<div class="row">
						<div class="col-md-6">
								<div class="form-group">
									<label>Category:</label>
									<div>
										<?php echo $result['category_name'];?>
									</div>
								</div>
							</div>
					    </div>
						<div class="row">
						<div class="col-md-6">
								<div class="form-group">
									<label>Reg No:</label>
									<div>
										<?php echo $result['reg_no'];?>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Name:</label>
									<div>
										<?php echo $result['name'];?>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Email:</label>
									<div>
										<?php echo $result['email'];?>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Phone:</label>
									<div>
										<?php echo $result['phone'];?>
									</div>
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Department:</label>
									<div>
										<?php echo $result['department'];?>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Year of Study:</label>
									<div>
										<?php echo $result['year_of_study'];?>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
						<div class="col-md-6">
								<div class="form-group">
									<label>District:</label>
									<div>
										<?php echo $result['district'];?>
									</div>
								</div>
						</div>
						<div class="col-md-6">
								<div class="form-group">
									<label>Address:</label>
									<div>
										<?php echo $result['address'];?>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-8">
								<div class="form-group">
									<label>status:</label>
									<div>
									<?php $status = $result['status']; 
									if($status == '1') { ?>
									<span class="label label-success">Active</span>
									<?php } else { ?>
									<span class="label label-error">Inactive</span>
									<?php } ?>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								<div class="btn-group">
									<a href="<?php echo base_url();?>admin/student" class="btn btn-default btn-flat">Back</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<?php $this->load->view('admin/footer')?>