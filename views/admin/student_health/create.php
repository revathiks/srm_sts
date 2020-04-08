<?php $this->load->view('admin/header')?>
<?php $this->load->view('admin/sidebar');
$student_id     =    $this->uri->segment(3);
?>
<style>

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
		<h1><i class="fa fa-user-md"></i> Manage Students Travel History</h1><ol class="breadcrumb">
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
								<h3 class="box-title">Create Student Travel History</h3>
							</div>
							<div class="box-body">
								<form action="<?php echo base_url();?>admin/create_student_health" class="form-horizontal" id="form-add_staff" method="post" accept-charset="utf-8">
								<?php if($student_id > 0 && isset($student) && !empty($student)){ ?>
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label>Student Name:</label>
											<div>
												<?php echo $student['name'];?>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label>Category:</label>
											<div>
												<?php echo $student['category_name'];?>
											</div>
										</div>
									</div>
								</div>
								<?php } else { ?>
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label>Category:</label>
											<div>
												<?php	
												
                							    if(!empty($categories['data'])):?>
                								<select class="form-control" id="category_id" name="category_id">                             
                								<option value="">Select Category</option>                								
                								<?php foreach($categories['data'] as $category):?>								
                								<option value="<?php echo $category['category_id'] ?>"><?php echo $category['category_name'] ?></option>
                								<?php endforeach; ?>
                								</select>
                								<?php endif; ?>
											</div>
										</div>
									</div>
								</div>
								 <div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label>Select Category First:</label>
											<div id="loadstudent">
    										<select class="form-control" id="student_idlist" name="student_idlist">
    								        </select>
											</div>
										</div>
									</div>
								</div>
								<?php }?>
								
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label>Health Status:</label>
											<div>
										<?php if(!empty($healthlevel['data'])){ 
										    foreach($healthlevel['data'] as $level){										
										?>	
                                         <label class="radio-inline"><input calss="level_<?php echo strtolower($level['level']);?>" type="radio" name="health_level_id" value="<?php echo $level['health_level_id'];?>">
                                         <?php echo $level['severity'];?>
                                          <i class="fa fa-circle level_<?php echo strtolower($level['level']);?>"></i>
                                         </label>
                                       
                                         <?php }
										}
										?>
											</div>
										</div>
									</div>
								</div>
								
								
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label>Notes:</label>
											<div>
												<textarea class="col-md-12" name="notes" id="notes" rows="4" ></textarea>
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
									<input type="hidden" name="student_id" id="student_id" value=""/>
												<input type="submit" value="Save" name="submit_create" class="btn btn-primary btn-flat"/> 
												<!-- <button type="reset" class="btn btn-warning btn-flat">Reset</button> -->
												<a href="<?php echo base_url();?>admin/student_travel" class="btn btn-default btn-flat">Cancel</a>
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

$('#student_idlist').on('change',function(){ 
	var data = $('#student_idlist').select2('data');
	//$('#student_name').val(data[0].email);
	 var selectedStudentid=$('#student_idlist').val();
	$("#student_id").val(selectedStudentid);
	
});
var srcurl='<?php echo base_url("admin/student_list"); ?>';

document.addEventListener("DOMContentLoaded",function(event) {
getSelect2('student_idlist',srcurl,'Select Student', formatRepo, formatRepoSelection);
    if($('#student_idlist').val()) {      
					$('#student_idlist').trigger('change');
					/*if($('#department').val()) {
						$('#department').trigger('change');
						if($('#staff').val()) {
							$('#staff').trigger('change');
						}
					}*/
				}		
				
			});

$('#category_id').on('change', function() {
	var category_id=$("#category_id").val();
	var srcurl='<?php echo base_url("admin/student_list"); ?>';
	if(category_id){
		var srcurl=srcurl+'?category_id='+category_id;
	}
	getSelect2('student_idlist',srcurl,'Select Student', formatRepo, formatRepoSelection);
});
</script>
<script src="<?php echo base_url(); ?>/js/addTips.js" type="text/javascript"></script>