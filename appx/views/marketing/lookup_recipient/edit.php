<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">    
    <span class="pull-right"><a class="btn btn-success" href="<?php echo base_url("marketing/lookup_recipient");?>">All Lookup Recipient</a></span>
      <h1>Edit Lookup Recipient</h1>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
      <div class="col-md-12">
      <form method="POST" action="<?php echo base_url("marketing/lookup_recipient_update");?>">
        <div class="form-group">
          <label>Name</label>
          <input type="text" value="<?php echo $data['name'];?>" class="form-control" required name="name" placeholder="Name of Lookup Recipient">
          <input type="hidden" value="<?php echo $data['id'];?>"required name="id" >
        </div>
      	<div class="form-group">
      		<label>Query</label>
      		<textarea class="form-control notes" required name="query" id="notes"><?php echo $data['query'];?></textarea>
      	</div>
      	<div class="form-group">
      		<div class="pull-right">
      			<button type="submit" class="btn btn-primary">Submit</button>
      		</div>
      	</div>
      </form>
      </div>

      </div>
      <!-- /.row (main row) -->

    </section>
    <!-- /.content -->
  </div>