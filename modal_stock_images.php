<!--

    Images Modal Popup 

-->
<div class="modal fade" id="stockImagesModal" tabindex="-1" role="dialog" aria-labelledby="stockImagesModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">

    <div class="modal-content">


      <div class="modal-header">

        <span class="model-title" id="modal-images-title">Stock Images</span>
        
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>

      </div>


      <div class="modal-body">

        <input type="hidden" id="stockID" value="__NEW_"/>

        <form method="post" action="" enctype="multipart/form-data" id="stockimageform">

          <div class="input-group mb-3">
            <div class="custom-file">
              <input type="file" name="file" class="custom-file-input" id="upload-stock-image">
              <label class="custom-file-label" for="upload-stock-image" aria-describedby="btn-upload-stock-image">Choose file</label>
            </div>
            <div class="input-group-append">
              <!-- <span class="input-group-text" id="inputGroupFileAddon02">Upload</span> -->
              <button type="button" class="btn btn-secondary" id="btn-upload-stock-image" >Upload</button>
            </div>
          </div>

        </form>

        <!-- <div class="card-deck" id="stock-images"> -->
        <div class="row row-cols-1 row-cols-md-3" id="stock-images">
        </div>

      </div>


      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-secondary" id="btn-stock-registry-save">Save</button> -->
        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
        <!-- <button type="button" class="btn btn-secondary" id="btn-registry-close">Close</button> -->
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>


    </div>
  </div>
</div>


