<!--

    Species Modal Popup 

-->
<div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">

    <div class="modal-content">


      <div class="modal-header">

        <span class="model-title">Product Details</span>
        
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>

      </div>


      <div class="modal-body">

        <form>

          <input type="hidden" id="productID" value="__NEW_"/>

          <div class="form-group">
            <label for="productCode">Code</label>
            <input type="text" class="form-control" id="productCode" placeholder="code">
          </div>

          <div class="form-group">
            <label for="productDescription">Description</label>
            <input type="text" class="form-control" id="productDescription" placeholder="product">
          </div>


          <div class="form-row">

            <div class="form-group col-md-3">
              <label for="productHeight">Height (cm)</label>
              <input type="text" class="form-control" id="productHeight" placeholder="height">
            </div>

            <div class="form-group col-md-3">
              <label for="ProductWidth">Width (cm)</label>
              <input type="text" class="form-control" id="productWidth" placeholder="width">
            </div>

            <div class="form-group col-md-3">
              <label for="ProductWeight">Weight (gm)</label>
              <input type="text" class="form-control" id="productWeight" placeholder="weight">
            </div>

            <div class="form-group col-md-3">
              <label for="ProductVolume">Volume (cm&sup3;)</label>
              <input type="text" class="form-control" id="productVolume" placeholder="volume">
            </div>

          </div>
          
        </form>



      </div>


      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="btn-product-save">Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>


    </div>
  </div>
</div>


