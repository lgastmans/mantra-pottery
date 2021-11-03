<?php
  require_once("include/db_mysqli.php");
  require_once("include/company.inc.php");

  $sql = "SELECT * FROM settings LIMIT 1";
  $qry = $conn->Query($sql);
  $obj = $qry->fetch_object();


  $company = new Company();
  $company->conn = $conn;
  $company->get();

?>

<form>

      <div class="card">
        <h5 class="card-header text-white bg-dark">Inventory Grid</h5>
        <div class="card-body">
          <div class="form-group ">
            <label for="inputMinimumStock">Indicate low stock when quantity reaches</label>
            <input type="number" class="form-control" id="inputMinimumStock" value="<?php echo $obj->minimum_quantity;?>">
          </div>
        </div>
      </div>

      <br/>

      <div class="card">

        <h5 class="card-header text-white bg-dark">Company Details</h5>

        <div class="card-body">

          <input type="hidden" id="companyID" value="<?php echo $company->company_id;?>">
          
          <div class="form-group">
            <label for="companyLegalName">Legal Name</label>
            <input type="text" class="form-control" id="companyLegalName" value="<?php echo $company->legal_name;?>">
            <!-- <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> -->
          </div>

          <div class="form-group">
            <label for="companyTradeName">Trade Name</label>
            <input type="text" class="form-control" id="companyTradeName" value="<?php echo $company->trade_name;?>">
          </div>          

          <div class="form-group">
            <label for="companyBranch">Branch</label>
            <input type="text" class="form-control" id="companyBranch" value="<?php echo $company->branch;?>">
          </div>          

          <div class="form-group">
            <label for="companyAddress">Address</label>
            <input type="text" class="form-control" id="companyAddress" value="<?php echo $company->address;?>">
          </div>          

          <div class="form-group">
            <label for="companyPhone">Phone</label>
            <input type="text" class="form-control" id="companyPhone" value="<?php echo $company->phone;?>">
          </div>          

          <div class="form-group">
            <label for="companyGSTIN">GSTIN</label>
            <input type="text" class="form-control" id="companyGSTIN" value="<?php echo $company->gstin;?>">
          </div>          

        </div>
      </div>

      <br/>

<!--   <fieldset class="form-group">

    <div class="row">
      <legend class="col-form-label col-sm-2 pt-0">Radios</legend>
      <div class="col-sm-10">
        <div class="form-check">
          <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios1" value="option1" checked>
          <label class="form-check-label" for="gridRadios1">
            First radio
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios2" value="option2">
          <label class="form-check-label" for="gridRadios2">
            Second radio
          </label>
        </div>
        <div class="form-check disabled">
          <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios3" value="option3" disabled>
          <label class="form-check-label" for="gridRadios3">
            Third disabled radio
          </label>
        </div>
      </div>
    </div>

  </fieldset>
 -->
<!--   <div class="form-group row">
    <div class="col-sm-2">Checkbox</div>
    <div class="col-sm-10">
      <div class="form-check">
        <input class="form-check-input" type="checkbox" id="gridCheck1">
        <label class="form-check-label" for="gridCheck1">
          Example checkbox
        </label>
      </div>
    </div>
  </div>
 -->  

  <div class="form-group">
      <button type="button" class="btn btn-secondary" id="btn-settings-save">Save</button>
  </div>

</form>