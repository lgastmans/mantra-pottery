<!--

    Species Modal Popup 

-->
<div class="modal fade" id="stockRegistryModal" tabindex="-1" role="dialog" aria-labelledby="stockRegistryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">

    <div class="modal-content">


      <div class="modal-header">

        <span class="model-title" id="modal-registry-title">Stock Registry Details</span>
        
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>

      </div>


      <div class="modal-body">

        <form>

          <input type="hidden" id="stockRegistryID" value="__NEW_"/>

<!--
          <div class="accordion" id="accordionSearchInfo">
            <div class="card">
              <div class="card-header" id="headingOne">
                <h2 class="mb-0">
                  <button type="button" class="btn btn-secondary" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">Update Stock</button>
                </h2>
              </div>

              <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionSearchInfo">
                <div class="card-body">

                  <form>

                    <div class="form-group row">
                      <label for="inputRegistryDate" class="col-sm-2 col-form-label">Date</label>
                      <div class="col-sm-10">
                        <input type="date" class="form-control" id="inputRegistryDate">
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="inputRegistryQuantity" class="col-sm-2 col-form-label">Quantity</label>
                      <div class="col-sm-10">
                        <input type="number" class="form-control" id="inputRegistryQuantity">
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="inputRegistryComment" class="col-sm-2 col-form-label">Comment</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputRegistryComment">
                      </div>
                    </div>

                    <button type="button" class="btn btn-secondary" id="btn-registry-clear">Clear</button>
                    <button type="button" class="btn btn-secondary" id="btn-registry-save">Save</button>

                  </form>
                  
                </div>
              </div>
            </div>
          </div>
          <br/>
-->
        </form>

        <table id="table-stock-registry" class="table table-hover table-striped table-bordered table-sm">
          <thead>
              <tr>
                  <th>Date</th>
                  <th>Quantity</th>
                  <th>Comment</th>
                  <th>Type</th>
              </tr>
          </thead>
          <tbody>
          </tbody>
          <tfoot>
              <tr>
                  <th>Date</th>
                  <th>Quantity</th>
                  <th>Comment</th>
                  <th>Type</th>
              </tr>
          </tfoot>
        </table>

      </div>


      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-secondary" id="btn-stock-registry-save">Save</button> -->
        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
        <button type="button" class="btn btn-secondary" id="btn-registry-close">Close</button>
      </div>


    </div>
  </div>
</div>


