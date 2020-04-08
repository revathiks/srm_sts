<?php $this->load->view('admin/header')?>
<?php $this->load->view('admin/sidebar');
$category_id     =    $this->uri->segment(3);
?>
<div class="content-wrapper" style="min-height: 237px;">
	<section class="content-header">
		<h1><i class="fa fa-user"></i> Manage Students</h1><ol class="breadcrumb">
			<li><a href="<?= base_url();?>/admin/dashboard">
				<i class="fa fa-dashboard"></i> Dashboard</a> </li>
				<li><a href="<?= base_url();?>/admin/tips">Students</a> </li>
				<li class="active">Add</li></ol>
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
								<h3 class="box-title">Create Student</h3>
							</div>
							<div class="box-body">
							
                				<form action="<?php echo base_url();?>admin/create_student" class="form-horizontal" id="form-add_staff" method="post" accept-charset="utf-8">
								<?php 
								if(isset($category_id) && $category_id >0){                                 
                                 ?>
                                 <input type="hidden" name="category_id" id="category_id" value="<?php echo $category_id;?>"/>
                                 <?php
							     }else{
							         $postedCat=set_value('category_id');
							         if(isset($postedCat) && $postedCat >0){
							          ?>
                                     <input type="hidden" name="category_id" id="category_id" value="<?php echo $postedCat;?>"/>
                                     <?php
							         }
							         else {							             
                                     ?>
                            
								<div class="row">
									<div class="col-md-12">
									<div class="form-group">
										<label for="department">Category</label>
										<select class="form-control required" id="category_id" name="category_id">
											<option value="">Select Category</option>
											<?php if(!empty($category['data'])):?>		
														<?php foreach($category['data'] as $cat): ?>
															<option value="<?php echo $cat['category_id']?>"><?php echo $cat['category_name'];?>
															</option>
														<?php endforeach; ?>
													<?php  else: ?>
													No Record Found
											<?php endif; ?>
										</select>
									</div>
								</div>									
								</div>
								<?php } 
							     }
							     if($category_id==2 || set_value('category_id')==2){
							         ?>
							        <div class="row">
									<div class="col-md-12">
									<div class="form-group">
										<label for="department">State</label>
										<select class="form-control required" id="state_id" name="state_id">
											<option value="">Select State</option>
											<?php if(!empty($state['data'])):?>		
														<?php foreach($state['data'] as $sta): ?>
															<option value="<?php echo $sta['state_id']?>"><?php echo ucfirst(strtolower($sta['state_name']));?>
															</option>
														<?php endforeach; ?>
													<?php  else: ?>
													No Record Found
											<?php endif; ?>
										</select>
									</div>
								</div>									
								</div>
							         <?php 
							     }else{
							         $staticState=0;
							         if($category_id==1 || set_value('category_id')==1){
							             $staticState=31;
							         }
							         ?>
							         <input type="hidden" name="state_id" id="state_id" value="<?php echo $staticState;?>"/>
							         <?php 
							     }
								?>
								
								<div class="row">
    								<div class="col-md-6">
    									<div class="form-group">
    										<label for="reg_no">Reg. No</label>
    										<input type="text" class="form-control valid" id="reg_no" placeholder="Registration No" name="reg_no" value="<?php echo set_value('reg_no');?>" maxlength="15">
    									</div>
    								</div>
    								<div class="col-md-6">                                
    									<div class="form-group">
    										<label for="student_name">Full Name</label>
    										<input type="text" class="form-control valid" id="name" placeholder="Full Name" name="name" value="<?php echo set_value('name');?>" maxlength="128">
    									</div>
    									
    								</div>
							    </div>
							<div class="row">
								<div class="col-md-6">
								<div class="form-group">
											<label>Email:</label>
											<div>
												<input type="text" name="email"  id="email" class="form-control" value="<?php echo set_value('email');?>"/>
											</div>
								</div>
									
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="mobileno">Phone Number</label>
										<input type="text" class="form-control" id="phone" placeholder="Phone Number" name="phone" value="<?php echo set_value('phone');?>" maxlength="10" >
									</div>
								</div>
								</div>
								
								
								<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="department">Department</label>
										<select class="form-control required" id="department" name="department">
											<option value="">Select Department</option>
											<?php if(!empty($department['data'])):?>		
														<?php foreach($department['data'] as $dep): ?>
															<option value="<?php echo $dep['department_id']?>"><?php echo $dep['department_name'];?>
															</option>
														<?php endforeach; ?>
													<?php  else: ?>
													No Record Found
											<?php endif; ?>
										</select>
									</div>
								</div>
								<div class="col-md-6">
                                    	<div class="form-group">
                                    	<label for="year_of_study">Year of Study</label>
                                    	<select class="form-control required" id="year_of_study" name="year_of_study">
                                    		<option value="" selected>Year of Study</option>
                                    		<option value="1">First Year</option>
                                    		<option value="2">Second Year</option>
                                    		<option value="3">Third Year</option>
                                    		<option value="4">Fourth Year</option>
                                    		<option value="5">Final Year</option>
                                    	</select>
                                        </div>
									</div>
								</div>
								
								
								<div class="row">
								<div class="col-md-6">
										<div class="form-group">
											<label>District:</label>
											<div>
												<input type="text" name="district"  id="district" class="form-control" value="<?php echo set_value('district');?>"/>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>Address:</label>
											<div>
												<textarea class="col-md-12" name="address" id="address" rows="4" ><?php echo set_value('address');?></textarea>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label>Status:</label>
											<div>
												<select name="status" class="pull-left form-control" >
													<option value="1" selected  > Active
													</option>
													<option value="0">Inactive
													</option>
												</select>
											</div>
										</div>
									</div>
								</div>							

									<div class="box-footer">
												<input type="submit" value="Save" name="submit_create" class="btn btn-primary btn-flat"/> 
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