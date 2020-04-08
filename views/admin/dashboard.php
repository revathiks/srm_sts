<?php
/*
* @Author           : Geetha
* @Created Date     : 18/06/2018
* @Version          : 0.1
* @Description      : dashboard page
* View Name   : dashboard.php
*/
?>
<?php $this->load->view('admin/header')?>
<?php $this->load->view('admin/sidebar')?>
<style type="text/css">
 .row-flex {
  display: flex;
  flex-wrap: wrap;
}
.small-box {
  height: 100%;
  padding: 15px 20px 10px;
}

[class*="col-"] {
  margin-bottom: 30px;
}

.small-box>.small-box-footer
{
  background: none !important;
}

</style>
 <div class="content-wrapper" style="min-height: 437.922px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1> <i class="fa fa-tachometer"></i> Dashboard</h1>
    </section>
    <section class="content">
        <div class="row row-flex">
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
			  <a href="<?php echo base_url();?>admin/student" class="small-box-footer">
              <div class="small-box bg-dash1">
                <div class="inner">
                  <h3><?php //echo $result['total'];?></h3>
                  <p>Students List</p>
                </div>
                </a>
              </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
			  <a href="<?php echo base_url();?>admin/Student" class="small-box-footer">
              <div class="small-box bg-dash2">
                <div class="inner">
                  <h3><?php //echo $result_month['data'][0]['monthcount'];?></h3>
                  <p>Students General Notes</p>
                </div>
               </a>
              </div>
            </div><!-- ./col -->
			<div class="col-lg-3 col-xs-6">
              <!-- small box -->
			  <a href="<?php echo base_url();?>admin/student" class="small-box-footer">
              <div class="small-box bg-dash3">
                <div class="inner">
                  <h3><?php //echo $result_week['data'][0]['weekcount'];?></h3>
                  <p>Students Travel History</p>
                </div>
                </a>
              </div>
            </div><!-- ./col -->

            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
        <a href="#" class="small-box-footer">
              <div class="small-box bg-dash4">
                <div class="inner">
                  <h3><?php //if(!empty($result_critical['total'])){ echo $result_critical['total']; } else { echo '0'; }?></h3>
				  <!-- <span class="fa fa-star"></span> <span class="fa fa-star"></span>-->
                  <p>Students Health History</p>
                </div>
                </a>
              </div>
            </div><!-- ./col -->
		</div>
		<!-- <div class="row row-flex">
			
        </div> -->
    </section>
</div>
<?php $this->load->view('admin/footer')?>