<?php $this->load->view('admin/header')?>
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
              <div class="box">
              
              <div class="box-header">
                    <h3 class="box-title" style="float:left; margin-right:30%;">Students List</h3>
					<?php 
                    $action=base_url()."admin/student_health";
                    $filterCategory="";
                    if(isset($_GET['cat']) && isset($_GET['cat'])>0){
                        $filterCategory=$_GET['cat'];
                        $action=$action."?cat=".$filterCategory;
                    }
                    ?>
                    <form action="<?php echo $action;?>" method="POST" id="searchList">
					<?php					
					if(!empty($categories['data'])):?>
					<div>
						<select style="width:140px;" class="pull-left form-control input-sm" id="category_id" name="category_id"">                             
						<option value="0">Category</option>
						<?php foreach($categories['data'] as $cat):
						$selectecat="";
						if(isset($_POST) && $_POST['category_id']!=0){
						    if($_POST['category_id']==$cat['category_id']){
						        $selectecat="selected";
						    }
						}
						?>								
						<option <?php echo $selectecat;?> value="<?php echo $cat['category_id'] ?>"><?php echo $cat['category_name'] ?></option>
						<?php  endforeach; ?>
						</select>
						</div>
						<?php endif;						 
						?>
					<?php					
					if(!empty($level['data'])):?>
					<div>
						<select style="width:140px;" class="pull-left form-control input-sm" id="health_level_id" name="health_level_id">                             
						<option value="0">Health Level</option>
						<?php foreach($level['data'] as $lev):
						$selectelev="";
						if(isset($_POST) && $_POST['health_level_id']!=0){
						    if($_POST['health_level_id']==$lev['health_level_id']){
						        $selectelev="selected";
						    }
						}
						?>								
						<option <?php echo $selectelev;?> value="<?php echo $lev['health_level_id'] ?>"><?php echo $lev['severity'] ?></option>
						<?php  endforeach; ?>
						</select>
						</div>
						<?php endif;						 
						?>
						
						<?php					
					if(!empty($department['data'])):?>
					<div>
						<select style="width:140px;" class="pull-left form-control input-sm" id="department" name="department">                             
						<option value="0">Department</option>
						<?php foreach($department['data'] as $dep):
						$selectedep="";
						if(isset($_POST) && $_POST['department']!=0){
						    if($_POST['department']==$dep['department_id']){
						        $selectedep="selected";
						    }
						}
						?>								
						<option <?php echo $selectedep;?> value="<?php echo $dep['department_id'] ?>"><?php echo $dep['department_name'] ?></option>
						<?php  endforeach; ?>
						</select>
						</div>
						<?php endif;						 
						?>
                    <div class="box-tools"> 
                            <div class=" input-group">                                
                              <input type="text" id="search_text" name="search_text" value="<?php echo set_value('search_text');?>" class="form-control input-sm pull-right" style="width: 150px;" placeholder="Search">
                              <div class="input-group-btn">
                                <!-- <button ><i class="fa fa-search"></i></button> -->
                                <input class="btn btn-sm btn-default searchList" type="submit" name="search_keywords" value="Search">

                              </div>
                              </div>
                    </div>
                    </form>
                    <a href="#" class="reload pull-right"><i class="fa fa-refresh" aria-hidden="true"></i></a>
               
				
                </div>
                
                <!--  box head end-->
                 <div class="row">
                    <div class="col-xs-4 ">
    					<button type="button" class="btn btn-primary btn-flat multiupdate" id="movetrack" data-val="3" disabled="">Move to Red</button>
    				</div>
    				 <div class="col-xs-4"> 
    					<button type="button" class="btn btn-primary btn-flat multiupdate" id="movetrack" data-val="2" disabled="">Move to Yellow</button>
    				</div>
    				<div class="col-xs-4">
    					<button type="button" class="btn btn-primary btn-flat multiupdate" id="movetrack" data-val="1" disabled="">Move to Green</button>
    				</div>
				</div>
                
                <div class="box-body table-responsive no-padding" id="postList">
                  <table class="table table-hover">
                    <tbody><tr>
                    <th> <input type="checkbox" id="select_all"> </th>
                        <th>S.No</th>
                        <th>Reg No</th>
                        <th>Student Name</th>                        
                        <th class="text-center">Health Status</th>
                        <th>Email</th>
                        <th>Phone</th> 
                        <th>Category</th>
                        <th>Department</th>
                        <th class="text-center">Actions</th>
                    </tr>
                     <?php 
                     if(!empty($result['data'])): $cnt = 1; foreach($result['data'] as $results): ?>
                    <tr>
                         <td>
						 <input type="checkbox" class="checkbox" name="student_id" 
						 id="delete_<?php echo $results['student_id'];?>"
						 value="<?php echo $results['student_id'];?>" 
						 onclick="disabled_changefun(this.value);"/>
					    </td>
                        <td><?php echo $cnt;?></td>
                        <td><?php echo $results['reg_no'];?></td>
                        <td><?php echo $results['name'];?></td>                        
                        <td class="text-center" data-toggle="tooltip" data-placement="top" title="<?php echo $results['severity'];?>">
                        <i class="fa fa-circle level_<?php echo strtolower($results['level']);?>"></i>
                        </td>
                        <td><?php echo $results['email'];?></td> 
                        <td><?php echo $results['phone'];?></td>                             
                        <td><?php echo $results['category_name'];?></td>
                        <td><?php echo $results['department_name'];?></td>
                        <td class="text-center" style="width :12%;">
                        <a class="btn btn-sm btn-info" href="<?php echo base_url();?>admin/view_student_health/<?php echo $results['student_id'];?>" title="View"><i class="fa fa-eye"></i></a>
                        <a onclick="return confirm('Do you want delete this record?')" href="<?=base_url()?>admin/delete_student_health/<?=$results['student_id']?>" class="btn btn-sm btn-info"><i class='fa fa-trash'></i></a>
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
<script type="text/javascript">
$(document).ready(function(){
    var trackids = [];
    $('#select_all').on('click',function(){
        if(this.checked){
            $('.multiupdate').removeAttr("disabled");
            $('.checkbox').each(function(){
                this.checked = true;
                 $('.multiupdate').removeAttr("disabled");
            });
        }else{
             $('.checkbox').each(function(){
                this.checked = false;
                $('.multiupdate').attr('disabled', 'disabled');
            });
        }
    });
    
    $('.checkbox').on('click',function(){
        if($('.checkbox:checked').length == $('.checkbox').length){
            $('#select_all').prop('checked',true);
        }else{
            $('#select_all').prop('checked',false);
        }
    });

    $(".multiupdate").click(function(){ 
        var level=$(this).attr("data-val");
        if($('.checkbox:checked').length==0){
        	alert("Please select student(s) to change health level");
        	return false;
    	}else{
        	//alert($("#department").val());
    		var confirmation = confirm("Are you sure to update health level for this user ?");
    		if(confirmation)
    		{
        	trackids = [];               
            $.each($("input[name='student_id']:checked"), function(){
            	trackids.push($(this).val());                
            });
            var trackid_list = trackids.join(",");
            
              $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        url: '<?php echo base_url(); ?>admin/multi_update_students_health/',
                        data: {
                        level:level,
                        trackid_list:trackid_list,
                        keyword:$("#search_text").val(),
                        department:$("#department").val(),
                        health_level_id:$("#health_level_id").val(),
                        category_id:$("#category_id").val()
                        },
                        beforeSend: function () {
                          $('.preload').show();
                        },
                        success: function (response) {
                           console.log(response);  
                           if(response==1){
                               $("#searchList").submit();
                        	  // window.location.href = '<?php //echo base_url(); ?>admin/student?cat=<?php echo $filterCategory;?>';
                           }
                                                         
                        }
                      }); 
    		}
    	} 
        });
    
});
function disabled_changefun(id)
{
    var length_check = $('.checkbox:checked').length;
    if(length_check == 0)
    {
       $('.multiupdate').attr('disabled', 'disabled');
    }
    else
    {
        $('.multiupdate').removeAttr("disabled");
    }
}
</script>