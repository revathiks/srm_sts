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
										<label for="department">Category</label>
										<select class="form-control required" id="category_id" name="category_id">
										<?php if(!empty($category['data'])): ?>		
														<?php foreach($category['data'] as $cat):
														$selected="";
														if($cat['category_id']==$result['category_id'])
														{ 
														     $selected="selected";
														} ?>
															<option value="<?php echo $cat['category_id']?>" <?php echo $selected;?>><?php echo $cat['category_name'];?>
															</option>
														<?php endforeach; ?>
													<?php  else: ?>
													No Record Found
											<?php endif; ?>
										</select>
									</div>
								</div>									
								</div>
								<?php if($result['category_id']==2){ ?>
							
								<div class="row" id="existingState">
									<div class="col-md-12">
									<div class="form-group">
										<label for="department">State</label>
										<select class="form-control required existingState" id="state_id" name="state_id">
											
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
								<?php } else {
								    $staticState=0;
								    if($result['category_id']==1 || set_value('category_id')==1){
								        $staticState=31;
								    }
								    ?>
								    <div id="existingState">
							         <input class="existingState" type="hidden" name="state_id" id="state_id" value="<?php echo $staticState;?>"/>
							         </div>
							         <?php 
								}
								?>
								
								<div class="row" id="newstate">
									<div class="col-md-12">
									<div class="form-group">
										<label for="department">State</label>
										<select disabled class="form-control required statedropdown" id="state_id" name="state_id">
											
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
								<div class="row" id="newstate2">								
							     <input class="statehiddenfield" disabled type="hidden" name="state_id" id="state_id" value=""/>
							   </div>
								<div class="row">
    								<div class="col-md-6">
    									<div class="form-group">
    										<label for="reg_no">Reg. No</label>
    										<input type="text" class="form-control valid" id="reg_no" name="reg_no" value="<?php echo $result['reg_no'];?>" maxlength="15">
    									</div>
    								</div>
    								<div class="col-md-6">                                
    									<div class="form-group">
    										<label for="student_name">Full Name</label>
    										<input type="text" class="form-control valid" id="name"  name="name" value="<?php echo $result['name'];?>" maxlength="128">
    									</div>
    									
    								</div>
							    </div>
							<div class="row">
								<div class="col-md-6">
								<div class="form-group">
											<label>Email:</label>
											<div>
												<input type="text" name="email"  id="email" class="form-control" value="<?php echo $result['email'];?>"/>
											</div>
								</div>
									
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="mobileno">Phone Number</label>
										<input type="text" class="form-control" id="phone"  name="phone" value="<?php echo $result['phone'];?>" maxlength="10" >
									</div>
								</div>
								</div>
								
								
								<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="department">Department</label>
										<select class="form-control required" id="department" name="department">
											
											<?php if(!empty($department['data'])):?>		
														<?php foreach($department['data'] as $dep):
														$selected2="";
														if($dep['department_id']==$result['department']) 
														{
														    $selected2="selected";
														}?>
															<option value="<?php echo $dep['department_id']?>" <?php echo $selected2;?>><?php echo $dep['department_name'];?>
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
                                    	<?php 
                                    	$yearStudy=array(
                                    	    1 =>'First Year', 
                                    	    2=>'Second Year',
                                    	    3=>'Third Year',
                                    	    4=>'Fourth Year',
                                    	    5=>'Final Year'
                                    	);
                                    	?>
                                    	<select class="form-control required" id="year_of_study" name="year_of_study">
                                    		<option value="" selected>Year of Study</option>
                                    		<?php 
                                    		
                                    		foreach($yearStudy as $key=>$year){
                                    		    $selected="";
                                    		    if($key==$result['year_of_study']){
                                    		        $selected="selected";
                                    		    }
                                    		?>
                                    		<option value="<?php echo $key;?>" <?php echo $selected;?>><?php echo $year;?></option>
                                    		<?php     
                                    		}
                                    		?>
                                    	</select>
                                        </div>
									</div>
								</div>
								
								
								<div class="row">
								<div class="col-md-6">
										<div class="form-group">
											<label>District:</label>
											<div>
												<input type="text" name="district"  id="district" class="form-control" value="<?php echo $result['district']?>"/>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>Address:</label>
											<div>
												<textarea class="col-md-12" name="address" id="address" rows="4" ><?php echo $result['address'];?></textarea>
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
<script>
    $(document).ready(function() {        
    	 $("#newstate").hide();
    	 $("#newstate2").hide();//hidden field
           $('#category_id').on('change', function() {  
        	$("#existingstate").hide();
        	 $('.existingstate').attr('disabled', 'disabled');
            var category_id = $("#category_id").val();            
            if(category_id==2){
            	 $("#newstate").show();
            	 $("#newstate2").hide();
            	 $('.statedropdown').removeAttr("disabled");
            	 $('.statehiddenfield').attr('disabled', 'disabled');            	                  
            }else{
            	$("#newstate").hide();
            	$("#newstate2").show();
            	$('.statehiddenfield').removeAttr("disabled");
            	 $('.statedropdown').attr('disabled', 'disabled');
            	 $('.statehiddenfield').prop("value", "0"); 
                if(category_id==1){
                	$('.statehiddenfield').prop("value", "31"); 
                }
            	
            }


        });
    })


    </script>
<script src="<?php echo base_url(); ?>/js/addTips.js" type="text/javascript"></script>