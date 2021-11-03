<!--

    Species Modal Popup 

-->
<div class="modal fade" id="designModal" tabindex="-1" role="dialog" aria-labelledby="designModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">

    <div class="modal-content">


      <div class="modal-header">

        <span class="model-title">Design Details</span>
        
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>

      </div>


      <div class="modal-body">

        <form>

          <input type="hidden" id="designID" value="__NEW_"/>

          <div class="form-group">
            <label for="designCode">Code</label>
            <input type="text" class="form-control" id="designCode" placeholder="code">
          </div>

          <div class="form-group">
            <label for="designDescription">Description</label>
            <input type="text" class="form-control" id="designDescription" placeholder="design">
          </div>

        </form>

      </div>


      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="btn-design-save">Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>


    </div>
  </div>
</div>


