<!--     <div class="alert alert-dark alert-dismissible fade show" role="alert">
      Use 'Search' to filter the Family or Botanical Name columns
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
 -->


  <div class="row">

    <div class="col-sm-2">

      <button type="button" class="btn btn-secondary" id="btn-stock-new">
        Inventory
        <svg width="2em" height="2em" viewBox="0 0 16 16" class="bi bi-plus" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd" d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
        </svg>
      </button>

    </div>

    <div class="col-sm-1">

<!--       <button type="button" class="btn btn-secondary" id="btn-stock-images">
        <svg width="2em" height="2em" viewBox="0 0 16 16" class="bi bi-images" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd" d="M12.002 4h-10a1 1 0 0 0-1 1v8l2.646-2.354a.5.5 0 0 1 .63-.062l2.66 1.773 3.71-3.71a.5.5 0 0 1 .577-.094l1.777 1.947V5a1 1 0 0 0-1-1zm-10-1a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2h-10zm4 4.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
          <path fill-rule="evenodd" d="M4 2h10a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1v1a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2h1a1 1 0 0 1 1-1z"/>
        </svg>
      </button>
 -->
    </div>



    <div class="col-sm-1">

      <button type="button" class="btn btn-secondary" id="btn-stock-export">
        <svg width="2em" height="2em" viewBox="0 0 16 16" class="bi bi-file-earmark-arrow-up" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
          <path d="M4 0h5.5v1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h1V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2z"/>
          <path d="M9.5 3V0L14 4.5h-3A1.5 1.5 0 0 1 9.5 3z"/>
          <path fill-rule="evenodd" d="M8 12a.5.5 0 0 0 .5-.5V7.707l1.146 1.147a.5.5 0 0 0 .708-.708l-2-2a.5.5 0 0 0-.708 0l-2 2a.5.5 0 1 0 .708.708L7.5 7.707V11.5a.5.5 0 0 0 .5.5z"/>
        </svg>
      </button>

    </div>

    <div class="col-sm-1">
      <div class="dropdown">
        <button class="btn <?php echo ($_SESSION['inventory_filter']=='__ALL_')?'btn-secondary':'btn-success';?> dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <svg width="2em" height="2em" viewBox="0 0 16 16" class="bi bi-funnel" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2zm1 .5v1.308l4.372 4.858A.5.5 0 0 1 7 8.5v5.306l2-.666V8.5a.5.5 0 0 1 .128-.334L13.5 3.308V2h-11z"/>
          </svg>
        </button>
        <div class="dropdown-menu" id="dropdown-inventory-filter" aria-labelledby="dropdownMenuButton">

          <a class="dropdown-item <?php echo ($_SESSION['inventory_filter']=='__ALL_')?"active":"" ?>" id="__ALL_"  href="#">All</a>

          <a class="dropdown-item <?php echo ($_SESSION['inventory_filter']=='__BELOW_MIN_')?"active":"" ?>" id="__BELOW_MIN_"  href="#">Below Minimum</a>

          <a class="dropdown-item <?php echo ($_SESSION['inventory_filter']=='__NONE_ZERO_')?"active":"" ?>" id="__NONE_ZERO_"  href="#">Non-zero</a>

          <a class="dropdown-item <?php echo ($_SESSION['inventory_filter']=='__ZERO_')?"active":"" ?>" id="__ZERO_"  href="#">Zero</a>

        </div>
      </div>
    </div>

    <div class="col-sm-7">
    </div>

  </div>



    <br/>

    <table id="table-browse" class="table table-hover table-striped table-bordered table-sm">
      <thead>
          <tr>
              <th>Code</th>
              <th>Description</th>
              <th>Stock</th>
              <th>Height</th>
              <th>Width</th>
              <th>Weight</th>
              <th>Volume</th>
              <th>Image</th>
              <th></th>
          </tr>
      </thead>
      <tbody>
      </tbody>
      <tfoot>
          <tr>
              <th>Code</th>
              <th>Description</th>
              <th>Stock</th>
              <th>Height</th>
              <th>Width</th>
              <th>Weight</th>
              <th>Volume</th>
              <th>Image</th>
              <th></th>
          </tr>
      </tfoot>
    </table>




