  <div class="content-wrapper">
    <section class="content-header">
      <h1>
      My Tasks
      </h1>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-solid collapsed-box">
            <div class="box-header" style="cursor: move;">
              <i class="fa fa-filter"></i>
              <h3 class="box-title">Filter Data</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-sm" data-widget="collapse"><i class="fa fa-plus"></i>
                </button>
              </div>
            </div>
            <div class="box-body border-radius-none collapse" style="display: none;">
              <form method="POST" action="<?php echo base_url('servicedesk/my_tasks');?>">
              <table class="table">
                <tr>
                  <td>Date Tasks</td>
                  <td><input value="<?php echo date_format(date_create($this->session->userdata('my_tasks_date_start')),"m/d/Y");?> - <?php echo date_format(date_create($this->session->userdata('my_tasks_date_end')),"m/d/Y");?>" type="text" name="date_tasks" class="datepicker form-control"></td>
                </tr>
                <tr>
                  <td></td>
                  <td><button class="btn btn-primary">Search</button>
                  <a class="btn btn-warning" href="<?php echo base_url("servicedesk/my_tasks/clear");?>">Reset</a>
                  <a class="btn btn-success" href="<?php echo base_url("servicedesk/expr_my_tasks");?>">Export</a></td>
                </tr>
              </table>
              </form>
            </div>
          </div>
      <table class="table table-bordered table-striped for_datatables">
        <thead>
          <tr>
            <th>IDTicket</th>
            <th>Info</th>
            <th>Pengirim</th>
            <th>Penerima</th>
            <th>Date Start</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($my_tasks as $key){ ?>
          <tr>
            <td><?php echo $key['id_ticket'];?></td>
            <td><?php echo $key['info'];?></td>
            <td><?php echo $key['pengirim'];?></td>
            <td><?php echo $key['penerima'];?></td>
            <td><?php echo $key['created_at'];?></td>
            <td><?php echo ($key['status']==0)?"Open":(($key['status']==1)?"Hold":(($key['status']==2)?"Done":""));?></td>
          </tr>
        <?php } ?>
        </tbody>
      </table>
      </div>
      </div>
    </section>
  </div>