<!--

    Source Modal Popup 

-->
<div class="modal fade" id="sourceModal" tabindex="-1" role="dialog" aria-labelledby="sourceModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">

    <div class="modal-content">


      <div class="modal-header">

        <span class="model-title">Source Details</span>
        
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>

      </div>


      <div class="modal-body">

        <form>

          <input type="hidden" id="sourceID" value="__NEW_"/>

          <div class="form-group">
            <label for="sourceName">Name</label>
            <input type="text" class="form-control" id="sourceName" placeholder="Name">
          </div>

          <div class="form-group">
            <label for="sourceAddress">Address</label>
            <input type="text" class="form-control" id="sourceAddress" placeholder="Address">
          </div>

          <div class="form-group">
            <label for="sourcePhone">Phone</label>
            <input type="text" class="form-control" id="sourcePhone" placeholder="Phone">
          </div>

          <div class="form-group">
            <label for="sourceGSTIN">GSTIN</label>
            <input type="text" class="form-control" id="sourceGSTIN" placeholder="GSTIN">
          </div>

          <div class="form-group">
            <label for="dropdown-source-type">Type</label>
            <div class="dropdown" id="dropdown-source">
              <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownSourceType" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="sourceValue"></span>
              </button>
              <ul class="dropdown-menu" id="dropdown-source-type">
                <li><a class="dropdown-item" id="<?php echo _movement_type_receive_; ?>" href="#">Supplier</a></li>
                <li><a class="dropdown-item" id="<?php echo _movement_type_deliver_; ?>" href="#">Client</a></li>
              </ul>
            </div>
          </div>
          <input type="hidden" id="sourceTypeID" value="1"/>


        </form>

      </div>


      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="btn-source-save">Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>


    </div>
  </div>
</div>


