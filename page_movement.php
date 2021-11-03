<?php

  /*
    the session variable is set at the time of selecting the dropdown from the main menu
    index.php ==> Main Navbar click, which then calls set_session_vars.php
  */

  $movement_type = $_SESSION['movement_type'];

?>

  <div class="row">

    <div class="col-sm-2">

      <button type="button" class="btn btn-secondary" id="btn-movement-new">
        <?php
          if ($movement_type == 1)
            echo "Receive";
          else
            echo "Deliver";
        ?>
        <svg width="2em" height="2em" viewBox="0 0 16 16" class="bi bi-plus" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd" d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
        </svg>
      </button>

    </div>

    <div class="col-sm-1">
    </div>

    <div class="col-sm-1">
    </div>

    <div class="col-sm-1">
    </div>

    <div class="col-sm-7">
    </div>

  </div>



    <br/>

    <table id="table-movement" class="table table-hover table-striped table-bordered table-sm">
      <thead>
          <tr>
              <th>Source</th>
              <th>Reference</th>
              <th>Date</th>
              <th>Status</th>
              <th></th>
          </tr>
      </thead>
      <tbody>
      </tbody>
      <tfoot>
          <tr>
              <th>Source</th>
              <th>Reference</th>
              <th>Date</th>
              <th>Status</th>
              <th></th>
          </tr>
      </tfoot>
    </table>




