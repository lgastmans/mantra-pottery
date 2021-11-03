<!--

    Species Modal Popup 

-->
<div class="modal fade" id="movementModal" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static" aria-labelledby="movementModalLabel" aria-hidden="true">

  <div class="modal-dialog modal-xl" role="document">

    <div class="modal-content">


      <div class="modal-header">

        <span class="model-title">
          <?php
            if ($_SESSION['movement_type'] == 1)
              echo "Receive Stock";
            else
              echo "Deliver Stock";
          ?>
        </span>
        
<!--         <button type="button" class="close" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
 -->
      </div>


      <div class="modal-body">


        <div id="modal_movement_msg" class="alert alert-danger alert-dismissible fade show" role="alert" style="display:none;">
          <var id="modal_movement_msg_text"></var>
          <button type="button" class="close" id="close-page-alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>


        <form onsubmit="return false;">

          <input type="hidden" id="movementID" value="__NEW_"/>

          <div class="form-row">

            <!-- Reference -->
            <div class="form-group col-md-4">
              <label for="inputReference">Reference</label>
              <input type="text" class="form-control" id="inputReference">
            </div>

            <!-- Source -->
            <div class="form-group col-md-4">
              <label for="inputSource"><?php echo ($_SESSION['movement_type'] == 1 ? "Supplier" : "Client") ?></label>
              <div class="typeahead__container">
                <div class="typeahead__field">
                  <span class="typeahead__query">
                    <input class="typeahead-mvment-source" value="" id="mvment_source" name="source[query]" type="search" placeholder="Source" autocomplete="off">
                    <input type="hidden" id="mvment-source-id" value="0">
                  </span>
                </div>
              </div>
              <!-- <input type="text" class="form-control" id="inputSource"> -->
            </div>

            <!-- Date -->
            <div class="form-group col-md-4">
              <label for="inputDate">Date</label>
              <input type="date" class="form-control" id="inputDate">
            </div>

          </div>

          <hr/>

          <div class="form-row">
            <div class="form-group col-md-3">

              <label for="mvment_code">Code</label>
              <div class="typeahead__container">
                <div class="typeahead__field">
                  <span class="typeahead__query">
                    <input class="typeahead-mvment-code" value="" id="mvment_code" name="code[query]" type="search" placeholder="code" autocomplete="off">
                    <input type="hidden" id="mvment-stock-id" value="0">
                  </span>
                </div>
              </div>

            </div>


            <div class="form-group col-md-6">
              <label for="mvmentDescription">Description</label>
              <input type="text" class="form-control" readonly id="mvmentDescription">
            </div>


            <div class="form-group col-md-3">
              <label for="mvmentQty">Qty</label>
              <input type="text" class="form-control" id="mvmentQty"  pattern="[0-9]*" placeholder="Quantity">
            </div>

          </div>

        </form>

        <table id="table-modal-mvment" class="table table-hover table-striped table-bordered table-sm">
          <thead>
              <tr>
                  <th>Code</th>
                  <th>Description</th>
                  <th>Quantity</th>
              </tr>
          </thead>
          <tbody>
          </tbody>
        </table>

      </div> <!-- <div class="modal-body"> -->




      <div class="modal-footer">
        <a class="btn btn-success" id="btn-movement-save">Save</button>
        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
        <a class="btn btn-secondary" data-dismiss="modal">Close</a>
      </div>


    </div>
  </div>
</div>


