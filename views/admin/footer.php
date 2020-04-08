<?php
/*
* @Author           : Geetha
* @Created Date     : 18/06/2018
* @Version          : 3.1.10
* @Description      : footer page
* View Name   : footer.php
*/
?>

<?php
 $session_id        = $this->session->userdata('access_token');
if($this->session->userdata('access_token'))
{
?>
<footer class="main-footer">
	<strong>Copyright © <?php echo date("Y");?> <a href="#"> <img src="<?php echo base_url('images/logo.png'); ?>" style="width:100px;"> </a></strong> All rights reserved.
</footer>
<?php } ?>
<!-- <script src="<?php echo base_url('js/jquery.min.js'); ?>" type="text/javascript"></script> -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<!-- <script src="<?php echo base_url('js/bootstrap.min.js'); ?>" type="text/javascript"></script> -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
<script src="<?php echo base_url('js/adminlte.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('js/jquery.validate.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('js/validation.js'); ?>" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.full.min.js" integrity="sha256-/IUDRcglIrROpUfaxqKxg4kthVduVKB0mvd7PwtlmAk=" crossorigin="anonymous"></script>
<script src="<?php echo base_url('js/common.js'); ?>" type="text/javascript"></script>
<?php $session_id     = $this->session->userdata('access_token'); ?>

<script type="text/javascript">
  var pageSize = '<?php echo PAGE_LIMIT; ?>';
  // baseURL variable
  var baseURL= "<?php echo base_url();?>";
 
  $(document).ready(function(){

    /*var access_token = '<?php echo $session_id['message']?>';

 
    // Faculty change
    $('#faculty_id').change(function(){ 
      var faculty_id = $(this).val();

      // AJAX request
      $.ajax({
        url:'<?=base_url()?>api/view_department/'+faculty_id,
        type: 'get',
        data: {faculty_id: faculty_id},
        dataType: 'json',
        headers: {
        "Authorization": access_token
      },
        success: function(response){
			console.log('response => ');
          // Remove options 
          $('#department_id').find('option').not(':first').remove();
          $('#designation_id').find('option').not(':first').remove();
			response = JSON.stringify(response);
			response = JSON.parse(response);
			console.log('response => '+response);
          // Add options
          $.each(response.data,function(index,data){
			  console.log(data);
              $('#department').append('<option value="'+data.department_id+'">'+data.department_name+'</option>');
             $('#department_id').append('<option value="'+data.department_id+'">'+data.department_name+'</option>');
          });
        }
     });
   });
 
   // Department change
   $('#department_id').change(function(){
     var department_id = $(this).val();

     // AJAX request
     $.ajax({
       url:'<?=base_url()?>api/view_designation/'+department_id,
       type: 'get',
       data: {department_id: department_id},
       dataType: 'json',
       headers: {
        "Authorization": access_token
      },
       success: function(response){
 
         // Remove options
         $('#designation_id').find('option').not(':first').remove();

         // Add options
         $.each(response.data,function(index,data){
           $('#designation_id').append('<option value="'+data['designation_id']+'">'+data['designation_name']+'</option>');
         });
       }
    });
  });
  
  // Student Observation Department change
   $('#department').change(function(){
     var department = $(this).val();
     var faculty_id     = $('#faculty_id').val();
     if(faculty_id == 0)
     {
      alert("Please select faculty Id");
      return false;
     }

     // AJAX request
     $.ajax({
       //url:'<?=base_url()?>staff/search_department/'+department,
       url:'<?=base_url()?>staff/search_department/'+department+'/'+faculty_id,
       type: 'get',
       dataType: 'json',
       headers: {
        "Authorization": access_token
      },
       success: function(response){
 
         // Remove options
         $('#staff').find('option').not(':first').remove();

         // Add options
         $.each(response.data,function(index,data){
           $('#staff').append('<option value="'+data['staff_id']+'">'+data['staff_name']+'</option>');
         });
         $.each(response.student_details,function(index,student_details){
           $('#studentName').append('<option value="'+student_details['student_id']+'">'+student_details['student_name']+'</option>');
         });
       }
    });
  });*/
 });


  function addOptions( fromId, toId ) {
    var fromEl = document.getElementById( fromId ),
        toEl = document.getElementById( toId );

    if ( fromEl.selectedIndex >= 0 ) {
        var index = toEl.options.length;

        for ( var i = 0; i < fromEl.options.length; i++ ) {
            if ( fromEl.options[ i ].selected ) {
                toEl.options[ index ] = fromEl.options[ i ];
                i--;
                index++
            }
        }
    }
}

	$(function () {
  $('a.reload').on("click", function (e) {
    e.preventDefault();
    var page_url = $(location).attr("href");
   window.location.href = page_url; 
  });   
});
</script>
<style>
.pagination{margin-right:15px;}
.box-header>.box-tools{top:10px;}
.reload{margin-top:6px;}
.select2-container--default, .select2-selection--single, .select2-selection__rendered{
	line-height:22px !important;
}
.select2-container, .select2-selection--single, .select2-selection__rendered{
	padding-left: 0px !important;
    text-align: center;
}
</style>
</div> <!-- End Wrapper Div -->
