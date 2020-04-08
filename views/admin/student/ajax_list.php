<?php
$catidurl=$catid="";
if(isset($_GET['category_id']) && ($_GET['category_id'])>0){
  $catid=$_GET['category_id'];  
}
?>
               <table class="table table-hover">
                    <tbody><tr>
                   <th><input type="checkbox" id="select_all"></th>
					 <th>S.No</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <?php if(isset($catid) && $catid==2){?><th>State</th><?php }?>
                        <th>Category</th>
                        <th>Department</th>
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
                        
                      <?php if(isset($catid) && $catid==2){ ?><td> <?php echo $results['state_name'];?></td><?php }?>
                       
                        <td>
                        <?php                        
                        echo $results['category_name'];
                        ?>
                        </td> 
                        <td>
                        <?php echo $results['department_name'];
                        ?>
                        </td>         
                        <td><?php if($results['status'] == 1){echo "Active" ; }else{echo "Inactive";}?></td>
                        <td class="text-center" style="width :12%;">
                        <a class="btn btn-sm btn-info" href="<?php echo base_url();?>admin/view_student/<?php echo $results['student_id'];?>" title="View"><i class="fa fa-eye"></i></a>
                        <a class="btn btn-sm btn-info" href="<?php echo base_url();?>admin/edit_student/<?php echo $results['student_id'];?>" title="Edit"><i class="fa fa-edit"></i></a>
                        <!-- <a class="btn btn-sm btn-info" href="<?php echo base_url();?>admin/delete_student/<?php echo $results['student_id'];?>" title="Delete"><i class="fa fa-trash"></i></a> -->
                        <a onclick="return confirm('Do you want delete this record?')" href="<?=base_url()?>admin/delete_student/<?=$results['student_id']?>" class="btn btn-sm btn-info"><i class='fa fa-trash'></i></a>
                        </td>
                    <?php $cnt++; endforeach; else: ?>
                    <tr><td colspan="5" class="no_record_found">No Record Found</td></tr>
                    <?php endif; ?>
                    </tbody></table>
                    <div class="clearfix"></div>
    <ul class="pagination pull-right">
      <?php echo $this->ajax_pagination->create_links($result); ?>
    </ul>
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
});

    
</script> 
    