<?php
/*
* @Author           : Geetha
* @Created Date     : 18/06/2018
* @Version          : 3.1.10
* @Description      : login page
* View Name   : login.php
*/
?>
<?php $this->load->view('admin/header')?>
<link rel="stylesheet" href="<?=base_url();?>/css/tab_login.css">
<body class="hold-transition login-page">

<div class="login-logo">
			<b>Welcome to SMS</b>
			</div><!-- /.login-logo -->

<div class="forms login-box">
	<ul class="tab-group">

		<li class="tab active"><a href="#">Log In</a></li>

		<!--<li class="tab"><a href="#signup">Sign Up</a></li>-->

	</ul>

	<?php echo form_open('admin/'); ?>

	      <h1>Login on SMS</h1>

		      <div class="input-field">

			<label for="email">Select Role</label>
			<div class="form-group has-feedback">
				<select name="role_id" class="form-control"  onchange="select_role(this.value)">
				<option value="1" >Admin</option>
				<option value="2" selected="selected">Teacher</option>
				</select>
			</div>

			<div id="admin_login_div" style="display: none;"> <!-- Admin Div Start -->
			<label for="username">Username</label>
			<div class="form-group has-feedback" id="admin_username">
				<input type="text" class="form-control" placeholder="Username" name="username" maxlength="50">
				<span class="glyphicon glyphicon-phone form-control-feedback"></span>
			</div>

			<label for="password">Password</label>
			<div class="form-group has-feedback" id="admin_password">
			<input type="password" class="form-control" placeholder="Password" name="password">
			</div>			
			</div> <!-- Admin Div End -->


			<div id="teacher_login_div" > <!-- Teacher Div Start -->
			<label for="username">Phone</label>
			<div class="form-group has-feedback" id="staff_phone">
				<input type="text" class="form-control" placeholder="Phone" name="phone"  maxlength="50">
				<span class="glyphicon glyphicon-phone form-control-feedback"></span>
			</div>
			<label for="password">Pin</label>
			<div class="form-group has-feedback" id="staff_pin">
			<input type="password" class="form-control" placeholder="Pin" name="pin">
			</div>			
			</div> <!-- Teacher Div End -->

			<div class="form-group has-campus">
				<?php if(!empty($campus['data'])):?>		
				<select name="campus_id" class="form-control">
				<?php foreach($campus['data'] as $results): ?>
					<option value="<?php echo $results['campus_id']?>"><?php echo $results['campus_name'];?>
					</option>
				<?php endforeach; ?>
				</select>
				<?php  else: ?>
				No Campus found......
				<?php endif; ?>
			</div>

	        <input type="submit" value="Login" class="button" name="login_submit" />

	        <p class="text-p"> <a href="#">Reset Pin?</a> </p>

	      </div>

	 <?php echo form_close(); ?>
</div>
</body>
<?php $this->load->view('admin/footer')?>

<script type="text/javascript">

$(document).ready(function(){

	  $('.tab a').on('click', function (e) {

	  e.preventDefault();

	  

	  $(this).parent().addClass('active');

	  $(this).parent().siblings().removeClass('active');

	  

	  var href = $(this).attr('href');

	  $('.forms > form').hide();

	  $(href).fadeIn(500);

	});

});


function select_role(id)
		{
			if(id == 1)
			{
				
				$("#admin_login_div").show();
				$("#teacher_login_div").hide();
				$("#admin_username").attr("required", "true");
				$("#admin_password").attr("required", "true");
			}
			else
			{
				$("#admin_login_div").hide();
				$("#teacher_login_div").show();
				$("#staff_pin").attr("required", "true");
				$("#staff_phone").attr("required", "true");
			}
		}

function signup_staff()
{
	var contact_no = $('#contact_no').val();
	var values = $('#signup').serialize();
	$.ajax({
            type: "POST",
            url: 'login_tab/staff_register',
            data: values ,
            success: function(response)
            {
            	var jsonData = JSON.parse(response);
            	if(jsonData.status == 'error')
            	{
            		$('.error_msg').show();
            		$('.error_msg').html('<b>'+jsonData.message+'</b>');

            	}
            	if(jsonData.status == 'success')
            	{

            		location.href = '<?=base_url();?>admin/pin_verfication';

            	}
            	
               /* var jsonData = JSON.parse(response);
 
                // user is logged in successfully in the back-end
                // let's redirect
                if (jsonData.success == "1")
                {
                    //location.href = 'my_profile.php';
                }
                else
                {
                    //alert('Invalid Credentials!');
                }*/
           }
       });
    
}

</script>