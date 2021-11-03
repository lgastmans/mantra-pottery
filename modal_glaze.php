<!--

    Species Modal Popup 

-->
<div class="modal fade" id="glazeModal" tabindex="-1" role="dialog" aria-labelledby="glazeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">

    <div class="modal-content">


      <div class="modal-header">

        <span class="model-title">Glaze Details</span>
        
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>

      </div>


      <div class="modal-body">

        <form>

          <input type="hidden" id="glazeID" value="__NEW_"/>

          <div class="form-group">
            <label for="glazeCode">Code</label>
            <input type="text" class="form-control" id="glazeCode" placeholder="code">
          </div>

          <div class="form-group">
            <label for="glazeDescription">Description</label>
            <input type="text" class="form-control" id="glazeDescription" placeholder="glaze">
          </div>

        </form>

      </div>


      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="btn-glaze-save">Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>


    </div>
  </div>
</div>


