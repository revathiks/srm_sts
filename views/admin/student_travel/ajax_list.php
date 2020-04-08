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