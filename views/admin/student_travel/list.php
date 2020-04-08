<?php $this->load->view('admin/header')?>
<?php $this->load->view('admin/sidebar')?>
<style>
.box-header>.box-tools{margin-right:25px !important;}
</style>
 <div class="content-wrapper" style="min-height: 437.922px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">

      <h1>
        <i class="fa fa-user-md"></i> Manage Students Health Level
        <!-- <small>Add, Edit, Delete</small> -->
      </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 text-right">
               <div class="form-group">
                    <a class="btn btn-primary" href="<?php echo base_url();?>admin/create_student_health"><i class="fa fa-plus"></i> Create Student health</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
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
              <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title pull-left">Student List</h3>
                    <div class="box-tools">
                        <form action="<?php echo base_url();?>admin/student_health" method="POST" id="searchList">
                           
                            <div class="row">
							<div class="col-md-5 filter_select">
							<?php if(!empty($categories['data'])):?>
								<select class="form-control" id="category_id" name="category_id">                             
								<option value="0">Select Category</option>
								<option value="0">All</option>
								<?php foreach($categories['data'] as $cat): ?>								
								<option value="<?php echo $cat['category_id'] ?>"><?php echo $cat['category_name'] ?></option>
								<?php  endforeach; ?>
								</select>
								<?php endif; ?>
							</div>
							
							<div class="col-md-5 filter_select">
							<?php							
							if(!empty($level['data'])):?>
								<select class="form-control" id="health_level_id" name="health_level_id">                             
								<option value="0">Select Health Level</option>
								<option value="0">All</option>
								<?php foreach($level['data'] as $lev): ?>								
								<option value="<?php echo $lev['health_level_id'] ?>"><?php echo $lev['severity'] ?></option>
								<?php  endforeach; ?>
								</select>
								<?php endif; ?>
							</div>
							
							
							<div class="col-md-5 input-group">                                
                              <input type="text" name="search_text" value="" class="form-control input-sm pull-right" style="width: 150px;" placeholder="Search">
                              <div class="input-group-btn">
                                <!-- <button ><i class="fa fa-search"></i></button> -->
                                <input class="btn btn-sm btn-default searchList" type="submit" name="search_keywords" value="Search">

                              </div>
                            </div>
                            
							
						</div>
                            
                        </form>
                    </div>
					<a href="#" class="reload pull-right"><i class="fa fa-refresh" aria-hidden="true"></i></a>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive no-padding" id="postList">
                  <table class="table table-hover">
                    <tbody><tr>
                        <th>S.No</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Level</th>
                        <th>severity</th>                        
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                     <?php 
                     if(!empty($result['data'])): $cnt = 1; foreach($result['data'] as $results): ?>
                    <tr>
                        <td><?php echo $cnt;?></td>
                        <td><?php echo $results['name'];?></td>
                        <td><?php echo $results['category_name'];?></td>
                        <td><?php echo $results['level'];?></td>
                        <td><?php echo $results['severity'];?></td>                              
                        <td><?php if($results['status'] == 1){echo "Active" ; }else{echo "Inactive";}?></td>
                        <td class="text-center" style="width :12%;">
                        <a class="btn btn-sm btn-info" href="<?php echo base_url();?>admin/view_student_health/<?php echo $results['health_track_id'];?>" title="View"><i class="fa fa-eye"></i></a>
                        <a class="btn btn-sm btn-info" href="<?php echo base_url();?>admin/edit_student_health/<?php echo $results['health_track_id'];?>" title="Edit"><i class="fa fa-edit"></i></a>
                        <a onclick="return confirm('Do you want delete this record?')" href="<?=base_url()?>admin/delete_student_health/<?=$results['health_track_id']?>" class="btn btn-sm btn-info"><i class='fa fa-trash'></i></a>
                        </td>
                    <?php $cnt++; endforeach; else: ?>
                    <tr><td colspan="5" class="no_record_found">No Record Found</td></tr>
                    <?php endif; ?>
                    </tbody></table>
                    <div class="clearfix"></div>
					<ul class="pagination pull-right">
					  <?php echo $this->ajax_pagination->create_links($result); ?>
					</ul>
                </div><!-- /.box-body -->
                <div class="box-footer clearfix">
                                    </div>
              </div><!-- /.box -->
            </div>
        </div>
    </section>
</div>
<?php $this->load->view('admin/footer')?>