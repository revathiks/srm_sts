<?php $this->load->view('admin/header')?>
<?php $this->load->view('admin/sidebar')?>
<?php $this->load->view('admin/sidebar')?>
<style>
.box-header>.box-tools{margin-right:25px !important;}
.level_red{
color:red;
}
.level_green{
color:green;
}
.level_yellow{
color:yellow;
}
</style>
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
						<?php foreach ($result as $value){ ?>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Name:</label>
									<div>
										<?php echo $value['name'];?>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Reg No:</label>
									<div>
										<?php echo $value['reg_no'];?>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Updated on:</label>
									<div>
										<?php echo $value['created_at'];?>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Level:</label>
									<div>
									<i class="fa fa-circle level_<?php echo strtolower($value['level']);?>"></i>
										<?php echo $value['severity'];?>
									</div>
								</div>
							</div>
							<div class="col-md-8">
								<div class="form-group">
									<label>Notes:</label>
									<div>
										<?php echo $value['notes'];?>
									</div>
								</div>
							</div>
						</div>
						
						<div class="row">
							<hr>
						</div>						
						
						<?php } ?>
						<div class="row">
							<div class="col-md-3">
								<div class="btn-group">
									<a href="<?php echo base_url();?>admin/student_health" class="btn btn-default btn-flat">Back</a>
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