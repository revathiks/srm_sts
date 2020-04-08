<?php $this->load->view('admin/header')?>
<?php $this->load->view('admin/sidebar')?>
<div class="content-wrapper" style="min-height: 237px;">
	<section class="content-header">
		<h1><i class="fa fa-lightbulb-o"></i> Manage Students</h1><ol class="breadcrumb">
			<li><a href="<?= base_url();?>/admin/dashboard">
				<i class="fa fa-dashboard"></i> Dashboard</a> </li>
				<li><a href="<?= base_url();?>/admin/student">Students</a> </li>
				<li class="active">Edit</li></ol>
			</section>
			<section class="content">
				<div class="row">
					<div class="col-md-12">
						<?php if ($this->session->flashdata('success')) { ?>
						<div class="alert alert-success">
						<a href="" class="close" data-dismiss="alert" aria-label="close">×</a>
						<strong><?php echo $this->session->flashdata('success'); ?></strong>
						</div>
					<?php } ?>
					<?php if ($this->session->flashdata('error')) { ?>
						<div class="alert alert-error">
						<a href="" class="close" data-dismiss="alert" aria-label="close">×</a>
						<strong><?php echo $this->session->flashdata('error'); ?></strong>
						</div>
					<?php } ?>
						<div class="box">
							<div class="box-header with-border">
								<h3 class="box-title">Edit Student</h3>
							</div>
							<div class="box-body">
								<form action="<?php echo base_url();?>admin/edit_student/<?php echo $result['student_id']?>" class="form-horizontal" id="form-add_staff" method="post" accept-charset="utf-8">
									<div class="row">
									<div class="col-md-12">
		                                    <div class="form-group">
		                                        <label>Name:</label>
		                                        <div>
		                                        	<input type="text" name="name" value="<?php echo $result['name'];?>" id="name" class="form-control">
		                                        </div>
		                                    </div>
		                                </div>
		                            </div>
		                            
		                            <div class="row">
									<div class="col-md-12">
		                                    <div class="form-group">
		                                        <label>Email:</label>
		                                        <div>
		                                        	<input type="text" name="email" value="<?php echo $result['email'];?>" id="email" class="form-control">
		                                        </div>
		                                    </div>
		                                </div>
		                            </div>
		                            
		                            <div class="row">
									<div class="col-md-12">
		                                    <div class="form-group">
		                                        <label>Phone:</label>
		                                        <div>
		                                        	<input type="text" name="phone" value="<?php echo $result['phone'];?>" id="phone" class="form-control">
		                                        </div>
		                                    </div>
		                                </div>
		                            </div>
		                            <div class="row">
									<div class="col-md-12">
		                                    <div class="form-group">
		                                        <label>District:</label>
		                                        <div>
		                                        	<input type="text" name="district" value="<?php echo $result['district'];?>" id="district" class="form-control">
		                                        </div>
		                                    </div>
		                                </div>
		                            </div>

								<div class="row">
									<div class="col-md-12">
		                                    <div class="form-group">
		                                        <label>Address:</label>
		                                        <div>
		            							<textarea class="col-md-12" name="address" id="address" rows="4" ><?php echo $result['address'];?></textarea>
		                                        	<!-- <input type="text" name="description" value="" id="description" class="form-control"> -->
		                                        </div>
		                                        </div>
		                                    </div>
		                                </div>
		                           

		                            <div class="row">
									<div class="col-md-12">
		                                    <div class="form-group">
		                                        <label>Status:</label>
												<select name="status" class="pull-left form-control" >
												<option value="1" <?php if($result['status'] == 1) { ?> selected <?php }?> > Active
												</option>
												<option value="0" <?php if($result['status'] == 0) { ?> selected <?php }?> >Inactive
												</option>
												</select>
		                                        
		                                    </div>
		                                </div>
		                            </div>

									<div class="box-footer">
											<input type="hidden" name="id" value="<?php echo $result['student_id']?>"><input type="hidden" name="NKOrTyAf" value="PJruqNxocMmjkyX0UFsn">
												<input type="submit" value="Save" name="submit_edit" class="btn btn-primary btn-flat"/>
												<!-- <button type="reset" class="btn btn-warning btn-flat">Reset</button> -->
												<a href="<?php echo base_url();?>admin/student" class="btn btn-default btn-flat">Cancel</a>
											</div>
										


									</form>
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>
<?php $this->load->view('admin/footer')?>
<script src="<?php echo base_url(); ?>/js/addTips.js" type="text/javascript"></script>