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
						 <input type="checkbox" class="checkbox" name="health_track_id" 
						 id="delete_<?php echo $results['health_track_id'];?>"
						 value="<?php echo $results['health_track_id'];?>" 
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
    
});

</script>