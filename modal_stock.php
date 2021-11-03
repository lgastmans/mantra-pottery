<!--

    Inventory Modal Popup 

-->
<div class="modal fade" id="stockModal" tabindex="-1" role="dialog" aria-labelledby="stockModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">

    <div class="modal-content">


      <div class="modal-header">

        <span class="model-title">Stock Details</span>
        
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>

      </div>


      <div class="modal-body">

        <form>

          <input type="hidden" id="stockID" value="__NEW_"/>

          <!-- product -->
          <div class="form-group">
            <label for="productCode">Product</label>
            <div class="typeahead__container">
              <div class="typeahead__field">
                <span class="typeahead__query">
                  <input class="typeahead-product" value="" id="product_description" name="product[query]" type="search" placeholder="product" autocomplete="off">
                  <input type="hidden" id="stock-product-id" value="0">
                </span>
              </div>
            </div>
          </div>

          <!-- glaze -->
          <div class="form-group">
            <label for="productCode">Glaze</label>
            <div class="typeahead__container">
              <div class="typeahead__field">
                <span class="typeahead__query">
                  <input class="typeahead-glaze" value="" id="glaze_description" name="glaze[query]" type="search" placeholder="glaze" autocomplete="off">
                  <input type="hidden" id="stock-glaze-id" value="0">
                </span>
              </div>
            </div>
          </div>

          <!-- design -->
          <div class="form-group">
            <label for="productCode">Design</label>
            <div class="typeahead__container">
              <div class="typeahead__field">
                <span class="typeahead__query">
                  <input class="typeahead-design" value="" id="design_description" name="design[query]" type="search" placeholder="design" autocomplete="off">
                  <input type="hidden" id="stock-design-id" value="0">
                </span>
              </div>
            </div>
          </div>


          <div class="form-row">

            <div class="form-group col-md-3">
              <label for="stock-height">Height (cm):</label>
              <label id="stock-height" class="font-weight-bold"></label>
            </div>

            <div class="form-group col-md-3">
              <label for="stock-width">Width (cm):</label>
              <label id="stock-width" class="font-weight-bold"></label>
            </div>

            <div class="form-group col-md-3">
              <label for="stock-weight">Weight (gm):</label>
              <label id="stock-weight" class="font-weight-bold"></label>
            </div>

            <div class="form-group col-md-3">
              <label for="stock-volume">Volume (cm&sup3;):</label>
              <label id="stock-volume" class="font-weight-bold"></label>
            </div>

          </div>

<!--           <div class="form-group">
            <label for="productCode">Code</label>
            <input type="text" class="form-control" id="productCode" placeholder="code">
          </div>

          <div class="form-group">
            <label for="productDescription">Description</label>
            <input type="text" class="form-control" id="productDescription" placeholder="product">
          </div>
 -->
        </form>

      </div>


      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="btn-stock-save">Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>


    </div>
  </div>
</div>


