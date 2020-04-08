<?php 
$dashclass=$studentclass=$healthclass=$generalclass=$travelclass=$tsmapclass = $vtsmapclass=$rprtclass=$criticalclass="";
$activeClass = 'active';
if($this->uri->segment(2) == 'dashboard'){
	$dashclass = $activeClass ;
}elseif($this->uri->segment(2) == 'student' || $this->uri->segment(2) == 'create_student' || $this->uri->segment(2) == 'view_student' || $this->uri->segment(2) == 'edit_student') {
	$studentclass = $activeClass ;
}
elseif($this->uri->segment(2) == 'student_health' || $this->uri->segment(2) == 'create_student_health' || $this->uri->segment(2) == 'view_student_health' || $this->uri->segment(2) == 'edit_student_health') {
	$healthclass = $activeClass ;
}
elseif($this->uri->segment(2) == 'student_general' || $this->uri->segment(2) == 'create_student_general' || $this->uri->segment(2) == 'view_student_general' || $this->uri->segment(2) == 'edit_student_general') {
	$generalclass = $activeClass ;
}
elseif($this->uri->segment(2) == 'student_travel' || $this->uri->segment(2) == 'create_student_travel' || $this->uri->segment(2) == 'view_student_travel' || $this->uri->segment(2) == 'edit_student_travel') {
	$travelclass = $activeClass ;
}
$session_user_id        = $this->session->userdata('sess_user_details')->user_id;
?>

<!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <?php if($session_user_id == 1) { ?>
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
			<li class="treeview <?php echo $dashclass;?>">
              <a href="<?php echo base_url('admin/dashboard');?>" class="<?php echo $dashclass;?>">
                <i class="fa fa-dashboard"></i> <span>Dashboard</span>
              </a>
            </li>
			<li class="treeview <?php echo $studentclass;?>">
              <a href="<?php echo base_url('admin/student?cat=1');?>" class="<?php echo $studentclass;?>">
                <i class="fa fa-users"></i> <span>Manage Tamilnadu Students</span>
				</a>
            </li>
            <li class="treeview <?php echo $studentclass;?>">
              <a href="<?php echo base_url('admin/student?cat=2');?>" class="<?php echo $studentclass;?>">
                <i class="fa fa-users"></i> <span>Manage Other State Students</span>
				</a>
            </li>
            <li class="treeview <?php echo $studentclass;?>">
              <a href="<?php echo base_url('admin/student?cat=3');?>" class="<?php echo $studentclass;?>">
                <i class="fa fa-users"></i> <span>Manage International Students</span>
				</a>
            </li>
			<li class="treeview <?php echo $healthclass;?>">
              <a href="<?php echo base_url('admin/student_health');?>" class="<?php echo $healthclass;?>">
                <i class="fa fa-user-md"></i> <span>Manage Students Health </span>
				</a>
            </li>
            
            <li class="treeview <?php echo $generalclass;?>">
              <a href="<?php echo base_url('admin/student');?>" class="<?php echo $generalclass;?>">
                <i class="fa fa-users"></i> <span>Manage Students Health </span>
				</a>
            </li>
            
            <li class="treeview <?php echo $travelclass;?>">
              <a href="<?php echo base_url('admin/student_travel');?>" class="<?php echo $travelclass;?>">
                <i class="fa fa-plane"></i> <span>Manage Students Travel History </span>
				</a>
            </li>
          </ul>
        <?php } else { ?>
          <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <li class="treeview <?php echo $vtsmapclass;?>">
              <a href="<?php echo base_url('admin/student_teacher_map_list');?>" class="<?php echo $vtsmapclass;?>">
                <i class="fa fa-street-view"></i>
                <span>View Student Mapped List</span>
              </a>
            </li>

        <?php } ?>
        </section>
        <!-- /.sidebar -->
      </aside>