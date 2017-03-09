<style>
  input[type="number"]::-webkit-outer-spin-button,
  input[type="number"]::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
  }
  input[type="number"] {
      -moz-appearance: textfield;
  }
</style>
<?php //print_r($products); ?>
<!-- Horizontal Form -->
  <div class="box box-info">
    <div class="box-header with-border">
      <h3 class="box-title">Pembayaran Tagihan</h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form id="form" class="form-horizontal" action="confirm_tagihan" method="post">
      <div class="box-body">
      <div class="col-md-12">
      	<div class="form-group">
          <label for="email" class="col-sm-2 control-label">Operator</label>
          <div class="col-sm-4">
            <select name="oprcode" id="oprcode" class="form-control" >
            	<?php 
            		foreach($products as $key => $val){
            			$opr = explode('.',$val);
						echo "<option value=$key>$opr[1]</option>";
					}
					
            	?>
            </select>
          </div>
        </div>
        <div class="form-group">
            <label for="nomer" class="col-sm-2 control-label">Nomer</label>
            <div class="col-sm-4">
              <input type="number" required class="form-control" value="" name="nomer" id="nomer" onkeyup='saveValue(this);' >
            </div>
          </div>
          
          <div class="form-group">
            <label for="first_name" class="col-sm-2 control-label"></label>
            <div class="col-sm-4">
            <div id="warn"></div>
            </div>
          </div>
          
        </div>
        <!-- /.col -->
      </div>
      <!-- /.box-body -->
      <div class="box-footer">
        <div class="col-sm-6">
	        <button id="btn-submit" type="submit" class="btn btn-success pull-right "><i class="fa fa-paper-plane"></i> Submit</button>
        </div>	  
      </div>
      <!-- /.box-footer -->
    </form>
  </div>
  <!-- /.box -->
  <script>
    $( document ).ready(function() {
            
    $("#form").on("submit", function(event) {
        $("#btn-submit").removeClass('btn-success');
          $("#btn-submit").addClass('btn-warning');
          $("#btn-submit").attr('disabled',true);
          $("#btn-submit").children("i").removeClass('fa-paper-plane');
          $("#btn-submit").children("i").addClass('fa-refresh fa-spin');
    });
    
  });

document.getElementById("nomer").value = getSavedValue("nomer");
function saveValue(e){
            var id = e.id;  
            var val = e.value; 
            localStorage.setItem(id, val);
        }
function getSavedValue  (v){
            if (localStorage.getItem(v) === null) {
                return "";
            }
            return localStorage.getItem(v);
        }
  </script>