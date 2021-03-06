<?php $this->load->view('admin/header')?>
<?php $this->load->view('admin/sidebar')?>
<style>
.box-header>.box-tools{margin-right:25px !important;}
</style>
 <div class="content-wrapper" style="min-height: 437.922px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">

      <h1>
        <i class="fa fa fa-users"></i> Manage Students
        <!-- <small>Add, Edit, Delete</small> -->
      </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 text-right">
               <div class="form-group">
               <?php 
               $catidurl=$catid=$delurl="";
               if(isset($_GET['cat']) && ($_GET['cat'])>0){
                   $catid=$_GET['cat'];
                   $catidurl="/".$catid;
                   $delurl="?cat=".$catid;
               }
               ?>
                    <a class="btn btn-primary" href="<?php echo base_url();?>admin/create_student<?php echo $catidurl;?>"><i class="fa fa-plus"></i> Create Student</a>
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
                    <h3 class="box-title" style="float:left; margin-right:58%;">Students List</h3>
					<?php 
                    $action=base_url()."admin/student";
                    $filterCategory="";
                    if(isset($_GET['cat']) && isset($_GET['cat'])>0){
                        $filterCategory=$_GET['cat'];
                        $action=$action."?cat=".$filterCategory;
                    }
                    ?>
                    <form action="<?php echo $action;?>" method="POST" id="searchList">
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
                <div class="col-xs-12 text-right">
							<button type="button" class="btn btn-primary btn-flat" id="delete_btn" disabled="">Delete</button>
						</div>
                </div>
                
                <!--  box head end-->
                
                <div class="box-body table-responsive no-padding" id="postList">
                  <table class="table table-hover">
                    <tbody><tr>
                        <th>
                        <input type="checkbox" id="select_all"> </th>
						<th>S.No</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                         <th>Department</th>
                         <?php if(isset($catid) && $catid==2){?><th>State</th><?php }?>
                        <th>Category</th>
                        <th>Status</th>
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
                        <td><?php echo $results['name'];?></td>
                        <td><?php echo $results['email'];?></td>
                        <td><?php echo $results['phone'];?></td>
                        <!-- <td><?php echo $results['description'];?></td> -->
                        <td>
                        <?php echo $results['department_name'];
                        ?>
                        </td>
                        <?php if(isset($catid) && $catid==2){?><td> <?php echo $results['state_name'];?></td><?php }?>
                        <td>
                        <?php                        
                        echo $results['category_name'];
                        ?>
                        </td>          
                        <td><?php if($results['status'] == 1){echo "Active" ; }else{echo "Inactive";}?></td>
                        <td class="text-center" style="width :12%;">
                        <a class="btn btn-sm btn-info" href="<?php echo base_url();?>admin/view_student/<?php echo $results['student_id'];?>" title="View"><i class="fa fa-eye"></i></a>
                        <a class="btn btn-sm btn-info" href="<?php echo base_url();?>admin/edit_student/<?php echo $results['student_id'];?>" title="Edit"><i class="fa fa-edit"></i></a>
                        <!-- <a class="btn btn-sm btn-info" href="<?php echo base_url();?>admin/delete_student/<?php echo $results['student_id'];?><?php echo $delurl;?>=" title="Delete"><i class="fa fa-trash"></i></a> -->
                        <a onclick="return confirm('Do you want delete this record?')" href="<?=base_url()?>admin/delete_student/<?=$results['student_id'].$delurl?>" class="btn btn-sm btn-info"><i class='fa fa-trash'></i></a>
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
    var to_studentid = [];

    $('#select_all').on('click',function(){
        if(this.checked){
            $('#delete_btn').removeAttr("disabled");
            $('.checkbox').each(function(){
                this.checked = true;
                 $('#delete_btn').removeAttr("disabled");
            });
        }else{
             $('.checkbox').each(function(){
                this.checked = false;
                $('#delete_btn').attr('disabled', 'disabled');
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


    $("#delete_btn").click(function(){ 
    	if($('.checkbox:checked').length==0){
        	alert("Please select student(s) to delete");
        	return false;
    	}else{
        	//alert($("#department").val());
    		var confirmation = confirm("Are you sure to delete this user ?");
    		if(confirmation)
    		{
        	to_student = [];               
            $.each($("input[name='student_id']:checked"), function(){
            	to_student.push($(this).val());                
            });
            var to_student_id = to_student.join(",");
              $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        url: '<?php echo base_url(); ?>admin/multi_delete_student/',
                        data: {
                        to_student_id:to_student_id,
                        keyword:$("#search_text").val(),
                        department:$("#department").val(),
                        category_id:'<?php echo $filterCategory;?>'
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
           $('#delete_btn').attr('disabled', 'disabled');
        }
        else
        {
            $('#delete_btn').removeAttr("disabled");
        }
    }


    
</script>