<?php
  require_once("include/db_mysqli.php");
  require_once("include/session.php");
  require_once('include/functions.inc.php');
  require_once("mysql_backup.php");


  /*
    retrieve application settings
  */
  $sql = "SELECT * FROM settings LIMIT 1";
  $qry = $conn->Query($sql);
  $obj = $qry->fetch_object();
  $minimum_quantity = $obj->minimum_quantity;

 
  /*
    execute database backup
  */
  $backup_error = false;

  $db = new db();
  $db->db_login = $db_login;
  $db->db_password = $db_password;
  $db->db_database = $db_db;
  $db->filename = "db_backup";
  $db->backup_folder = "/var/www/html/mantra-pottery/backups";
  $db->purge = true;
  $db->purge_pattern = "db_backup";

  $backup_error = $db->backup();

 
?>
<!doctype html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Mantra Pottery">
  <meta name="author" content="Luk Gastmans">
  <title>Mantra Pottery</title>


  <!-- Bootstrap core CSS -->
  <link href="bootstrap-4.4.1-dist/css/bootstrap.min.css" rel="stylesheet">

  <link href="js/jquery-typeahead-2.10.6/dist/jquery.typeahead.min.css" rel="stylesheet">

  <link rel="stylesheet" type="text/css" href="datatables/datatables.min.css"/>
  <link rel="stylesheet" type="text/css" href="datatables/Buttons-1.6.1/css/buttons.dataTables.min.css"/>


  <!-- Favicons -->
  <meta name="theme-color" content="#563d7c">

  <style>

    @font-face {
       font-family: 'diamond';
       src: url('include/diamond.ttf');
       font-weight: normal;
       font-style: normal;
    }

    body {
      background-color: #fcf0e6;
    }

    dt {
      text-align: right;
    }

    @media only screen and (max-width: 600px) {
      dt {
        text-align: left;
      }
    } 

    .hlClass {
      background-color:orange !important;
    }

    .table-hover tbody tr:hover td, .table-hover tbody tr:hover th {
      background-color: lightgrey;
    }

    .bd-placeholder-img {
      font-size: 1.125rem;
      text-anchor: middle;
      -webkit-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none;
      user-select: none;
    }

    @media (min-width: 768px) {
      .bd-placeholder-img-lg {
        font-size: 3.5rem;
      }
    }

    .bg-dark {
      /*background-color: rgb(175,79,2) !important;*/
    }

    #blank-space {
      padding-top:70px;
    }

    p {
      margin-bottom: .5rem;
    }

    h6 {
      margin-top: .5rem;
      margin-bottom: 0rem;
    }

    dt {
      font-weight: 600;
    }

    tr {
      cursor: pointer;
    }

    .fa-indent {
      margin-left: 1rem;
    }



    .model-title {
      font-weight: bold;
      font-size:1.5em;
    }

    /*

        page-browse

    */
    .modal-content {
       background-color: #fcf0e6;
    }
    .col-6 {
      padding:.5rem;
    }
    .card-footer {
      padding: .5rem .5rem;
    }
    .border-dark {
      border-color: #487b23 !important;
    } 

    #browse_tamil {
      font-family: 'diamond', Arial, sans-serif;
    }

/*    .btn-link {
        color: white;
        text-decoration: none;
    }
    .btn-link:visited {
        color: white; 
        text-decoration: none;
    }
    .btn-link:hover {
        color: white; 
        text-decoration: underline;
    }
    .btn-link:active {
        color: white; 
        text-decoration: none;
    }
    .btn-link:focus {
        color: white; 
        text-decoration: none;
    }
*/
  </style>


</head>

<body>

  <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
    
    <span class="navbar-brand mb-0 h1">Mantra</span>
    <!-- <div class="navbar-brand" href="#">Flora Auroviliana</div> -->

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarCollapse">

      <ul class="navbar-nav mr-auto">

        <?php 
          $active="";
          if (($_SESSION['active_nav']=='nav-inventory') || ($_SESSION['active_nav']=='nav-receive') || ($_SESSION['active_nav']=='nav-deliver')) 
            $active="active";
        ?>
        <li class="nav-item dropdown <?php echo $active;?>">
          <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Inventory</a>
          <div class="dropdown-menu">
            <a class="dropdown-item <?php echo ($_SESSION['active_nav']=='nav-inventory')?'font-weight-bold':''; ?>" href="#" id='nav-inventory'>Browse</a>
            <a class="dropdown-item <?php echo ($_SESSION['active_nav']=='nav-receive')?'font-weight-bold':''; ?>" href="#" id='nav-receive'>Receive</a>
            <a class="dropdown-item <?php echo ($_SESSION['active_nav']=='nav-deliver')?'font-weight-bold':''; ?>" href="#" id='nav-deliver'>Deliver</a>
          </div>
        </li>


<!--         <li class="nav-item <?php echo ($_SESSION['active_nav']=='nav-inventory')?'active':''; ?>" id='nav-inventory'>
          <a class="nav-link" href="#">Inventory</a>
        </li>
 -->
        <li class="nav-item <?php echo ($_SESSION['active_nav']=='nav-products')?'active':''; ?>" id='nav-products'>
          <a class="nav-link" href="#">Products</a>
        </li>

        <li class="nav-item <?php echo ($_SESSION['active_nav']=='nav-glazes')?'active':''; ?>" id='nav-glazes'>
          <a class="nav-link" href="#">Glazes</a>
        </li>

        <li class="nav-item <?php echo ($_SESSION['active_nav']=='nav-designs')?'active':''; ?>" id='nav-designs'>
          <a class="nav-link" href="#">Designs</a>
        </li>

        <li class="nav-item <?php echo ($_SESSION['active_nav']=='nav-source')?'active':''; ?>" id='nav-source'>
          <a class="nav-link" href="#">Source</a>
        </li>

        <li class="nav-item <?php echo ($_SESSION['active_nav']=='nav-settings')?'active':''; ?>" id='nav-settings'>
          <a class="nav-link" href="#">Settings</a>
        </li>

<!--         <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Categories
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item " href="#">Action</a>
            <a class="dropdown-item active" href="#">Another action</a>
          </div>
        </li>
 -->
      </ul>

<!--       <form class="form-inline mt-2 mt-md-0">
        <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
      </form>
 -->
    </div>

  </nav>

<main role="main" class="container">

  <div id="blank-space"></div>

    <div id="page_alert" class="alert alert-danger alert-dismissible fade show" role="alert" style="display:none;">
      <var id="page_alert_msg"></var>
      <button type="button" class="close" id="close-page-alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>

    <div id="page_message" class="alert alert-success alert-dismissible fade show" role="alert" style="display:none;">
      <var id="page_message_msg"></var>
      <button type="button" class="close" id="close-page-message" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>

  <?php

    if ($_SESSION['active_nav'] == 'nav-inventory') {

      include('page_inventory.php');
      include('modal_stock.php');
      include('modal_stock_registry.php');
      include('modal_stock_images.php');

    }
    elseif ($_SESSION['active_nav'] == 'nav-products') {

      include('page_products.php');
      include('modal_product.php');

    }
    elseif ($_SESSION['active_nav'] == 'nav-glazes') {

      include('page_glazes.php');
      include('modal_glaze.php');

    }
    elseif ($_SESSION['active_nav'] == 'nav-designs') {

      include('page_designs.php');
      include('modal_design.php');

    }
    elseif ($_SESSION['active_nav'] == 'nav-source') {

      include('page_source.php');
      include('modal_source.php');

    }
    elseif ($_SESSION['active_nav'] == 'nav-settings') {

      include('page_settings.php');

    }
    elseif ($_SESSION['active_nav'] == 'nav-receive') {

      $_SESSION['movement_type'] = 1;
      include('page_movement.php');
      include('modal_movement.php');

    }
    elseif ($_SESSION['active_nav'] == 'nav-deliver') {

      $_SESSION['movement_type'] = 2;
      include('page_movement.php');
      include('modal_movement.php');

    }
    else {

      include('page_blank.php');

    }

  ?>

</main>




 
  <script src="js/jquery-3.5.1.min.js"></script>

  <script src="bootstrap-4.4.1-dist/js/bootstrap.bundle.min.js"></script>

  <script type="text/javascript" src="datatables/datatables.min.js"></script>

  <script type="text/javascript" src="datatables/datatables.min.js"></script>
  <script type="text/javascript" src="datatables/Buttons-1.6.1/js/dataTables.buttons.min.js"></script>
  <script type="text/javascript" src="datatables/Buttons-1.6.1/js/buttons.html5.min.js"></script>

  <script type="text/javascript" src="bootbox/bootbox.all.min.js"></script>
  <!-- <script src="js/typeahead.bundle.js"></script> -->

  <script type="text/javascript" charset="utf8" src="js/jquery-typeahead-2.10.6/dist/jquery.typeahead.min.js"></script>

  <script>

    $(document).ready(function() {


      var tableBrowseIndex = 0; 
      var tableBrowseData = {};
      var minQty = <?php echo $minimum_quantity;?>;

      /*


        Main NavBar


      */
      //$('.navbar li').click( function(e) {
      $('#nav-inventory, #nav-receive, #nav-deliver, #nav-products, #nav-glazes, #nav-designs, #nav-settings, #nav-source').click( function() {

        var page_id = $(this).attr("id");

        // var str = $(this).attr("class");

        // if (str.includes("dropdown")) 
        
        console.log('page_id '+page_id);

        $.ajax({
          method  : "POST",
          url   : "include/set_session_vars.php",
          data  : {selected_nav : page_id}
        })
        .done( function( msg ) {

          var obj = JSON.parse( msg );

          location.reload(true);

        });

      });


      /*
        Multiple Modals function

        the following code comes from :
          https://stackoverflow.com/questions/28077066/bootstrap-modal-issue-scrolling-gets-disabled      


        This is because when you close a modal, it removes the modal-open from the <body> tag. Bootstrap doesn't support multiple modals on the same page (At least until BS3).

        One way to make it work, is to use the hidden.bs.modal event triggered by BS when closing a modal, then check if there is anyother modal open in order to force the modal-open class on the body.

      */
      //fire on closing modal box
      $('.modal').on("hidden.bs.modal", function (e) { 

        // check whether parent modal is opend after child modal close
        if ($('.modal:visible').length) { 

          // if open mean length is 1 then add a bootstrap css class to body of the page
          $('body').addClass('modal-open'); 

        }

      });



      raise_error = function(msg) {

        $(" #page_alert_msg ").html( msg );
        $(" #page_alert ").removeClass( "alert-danger" ).addClass( "alert-warning" );
        $(" #page_alert ").show();

        setTimeout(function() {
          $(" #page_alert ").fadeTo(2000, 500).hide();
        }, 10000);

      }
      $(" #close-page-alert ").on('click', function (e) {
        e.preventDefault();
        $(" #page_alert ").hide();
      });


      raise_message = function(msg) {

        $(" #page_message_msg ").html( msg );
        //$(" #page_message ").removeClass( "alert-danger" ).addClass( "alert-success" );
        $(" #page_message ").show();

        setTimeout(function() {
          $(" #page_message ").fadeTo(2000, 500).hide();
        }, 5000);

      }
      $(" #close-page-message ").on('click', function (e) {
        e.preventDefault();
        $(" #page_message ").hide();
      });


      raise_message_movement = function(msg)
      {
        $(" #modal_movement_msg_text ").html( msg );
        //$(" #page_message ").removeClass( "alert-danger" ).addClass( "alert-success" );
        $(" #modal_movement_msg ").show();

        setTimeout(function() {
          $(" #modal_movement_msg ").fadeTo(2000, 500).hide();
        }, 3000);
      }



      <?php if ($_SESSION['active_nav'] == 'nav-inventory') { ?>

      /*

        --------------

        Inventory page

        --------------

      */
      var browseTable = $('#table-browse').DataTable({
        processing  : true,
        serverSide  : true,
//        ajax        : "include/get_stock.php",
        ajax        : 
        {
          url   : "include/stock_controller.php",
          data  : function ( d ) {
            d.action = 'get_data';
          }
        },  
        searching   : true,
        dom         : "ltip",
        paging      : true,
        pageLength  : 10,
        pagingType  : 'full',
        filter      : true,
        responsive  : true,
        // dom: 'Blfrtip',
        // buttons     : [
        //   'csvHtml5',
        // ],
        createdRow  : function( row, data, dataIndex) {
          //console.log('data' + data['quantity']); //JSON.stringify(data));
          if( data['stock'] <= minQty ){
              //$(row).css({'background-color':'orange'}); 
              $(row).addClass('hlClass');

          }
        },
        language    : {
            'loadingRecords': '&nbsp;',
            'processing': '<div class="spinner-border text-success" role="status"><span class="sr-only">Loading...</span></div>'
        },
        // initComplete: function () {
        //     this.api().columns().every( function () {
        //         var that = this;
        //         $( 'input', this.header() ).on( 'keyup change clear', function () {
        //       console.log(this.value);
        //             if ( that.search() !== this.value ) {
        //                 that
        //                     .search( this.value )
        //                     .draw();
        //             }
        //         } );
        //     } );
        // },        
        //"data"      : data,
        columns   : [
          { data: 'code', orderable : false},
          { data: 'description', orderable : false},
          { data: 'stock', orderable : false},
          { data: 'height', orderable : false}, //, visible : false},
          { data: 'width', orderable : false}, //, visible : false},
          { data: 'weight', orderable : false}, //, visible : false},
          { data: 'volume', orderable : false}, //, visible : false},
          { data: 'image', orderable : false,
            render: function(data, type) {
              //return JSON.stringify(data);
              if (data)
                return '<img src="images/'+data+'" width="75px">';
              else 
                return '<span></span>';
            }
          },
          {
            data: 'delete',
            orderable: false,
            width: '100px',
            render: function(data, type, row, meta) {
              return '<a class="text-dark" href="javascript:edit_stock('+row.DT_RowId+')"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pencil" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5L13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175l-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/></svg></a>&nbsp;<a class="text-dark" href="javascript:stock_registry_view('+row.DT_RowId+')"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>  <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/></svg>&nbsp;</a><a class="text-dark" href="javascript:delete_stock('+row.DT_RowId+')" alt="delete this row"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-trash" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/><path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/></svg></a>&nbsp;<a class="text-dark" href="javascript:stock_images('+row.DT_RowId+')" alt="delete this row"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-images" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M12.002 4h-10a1 1 0 0 0-1 1v8l2.646-2.354a.5.5 0 0 1 .63-.062l2.66 1.773 3.71-3.71a.5.5 0 0 1 .577-.094l1.777 1.947V5a1 1 0 0 0-1-1zm-10-1a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2h-10zm4 4.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/><path fill-rule="evenodd" d="M4 2h10a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1v1a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2h1a1 1 0 0 1 1-1z"/></svg></a>';
            }
          }        
        ],
      });


      $('#table-browse thead th').each( function (e) {

        var title = $(this).text();

        if (title == 'Code')
          $(this).html( '<input type="text" class="form-control" id="table-browse-col-code" placeholder="'+title+'"> ' );
        else if (title == 'Description')
          $(this).html( '<input type="text" class="form-control" id="table-browse-col-descr" placeholder="'+title+'"> ' );

      });

      $('#table-browse-col-code').on( 'keyup', function () {
          browseTable
              .columns( 0 )
              .search( $(this).val() )
              .draw();
      } );

      $('#table-browse-col-descr').on( 'keyup', function () {
          browseTable
              .columns( 1 )
              .search( $(this).val() )
              .draw();
      } );


      delete_stock = function(id) {

        bootbox.confirm({

            message: "Are you sure you want to delete this stock entry ?<br/>This will remove all corresponding stock entries and images.<br/><strong>This action cannot be undone!</strong><br/>",

            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
              if (result) {

                $.ajax({
                  method  : "POST",
                  url   : "include/stock_controller.php",
                  data  : {
                    stock_id : id, 
                    action : 'delete',
                  }
                })
                .done( function( msg ) {

                  var obj = JSON.parse( msg );

                  if (obj.error)

                    raise_error(obj.msg);

                  else {

                    browseTable.ajax.reload();

                  }
                });
              }
            }
        });
      }

      // $("[id^=del_product_id_]").on('click', function() {
      //   console.log('delete this product');
      // });

      $('#table-browse').on('click', 'tr', function(e) {
        
        tableBrowseIndex = browseTable.row( this ).index();
        tableBrowseData = browseTable.row( this ).data();

      });


      $('#table-browse').on('dblclick', 'tr', function(e) {  // or dblclick?

        e.stopPropagation();

        tableBrowseIndex = browseTable.row( this ).index();
        tableBrowseData = browseTable.row( this ).data();

        let rowID = $(this).attr('id');
        //var search = $('.dataTables_filter input').val();

        edit_stock(rowID);

      });


      stock_images = function(rowID) {

        if (typeof rowID !== 'undefined') {

          $.ajax({
            method  : "POST",
            //url   : "include/get_stock_images.php",
            url   : "include/stock_controller.php",
            data  : {
              stock_id : rowID,
              action : "get_images",
            }
          })
          .done( function( msg ) {

            var obj = JSON.parse( msg );

            $(' #stockID ').val(obj.stock_id);
            $(' #modal-images-title' ).html(obj.title);

            $(" #stock-images ").empty();

            $.each( obj.images, function( key, value ) {

              /*
                create the card image thumbnails
              */
              card = 
                '<div class="col mb-4">'+
                  '<div class="card shadow">'+
                    '<img src="images/' + value.filename + '" class="card-img-top" alt="">'+
                    '<div class="card-footer text-center">'+
                      '<a href="#" class="btn btn-danger btn-sm" id="btn-image-delete-'+key+'">Delete</a>'+
                    '</div>'+
                  '</div>'+
                '</div>';

              $( '#stock-images ').append(card);

            });

            $('#stockImagesModal').modal('show');
  
          });
        }
      }


      $(" #upload-stock-image ").on('change',function(){
        let fileName = $(this).val().split('\\').pop();
        $(this).siblings('.custom-file-label').addClass("selected").html(fileName);
      });

      $(" #btn-upload-stock-image ").on("click", function() {

        var fd = new FormData();
        var files = $('#upload-stock-image')[0].files;
        var stock_id = $(" #stockID ").val();
        
        // Check file selected or not
        if(files.length > 0 ) {

          fd.append('file',files[0]);
          fd.append('stock_id',stock_id);

          $.ajax({
            url: 'include/upload_image.php',
            //url: 'include/stock_controller.php',
            type: 'post',
            data: fd,
            contentType: false,
            processData: false,
            action: "upload_image",
          })
          .done( function( msg ) {
            console.log(JSON.stringify(msg));
            stock_images(stock_id);
            $(" #upload-stock-image ").siblings('.custom-file-label').html('Choose file');
          });
        }
      })

      $(" #stock-images ").on('click', "a[id^='btn-image-delete']", function(e) {

        var obj = $(this).attr('id').split('-');

        var imageID = obj[3];
        var stock_id = $(" #stockID ").val();

        bootbox.confirm({

            message: "Are you sure you want to delete this image ?",
            
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
              if (result) {

                $.ajax({
                  method  : "POST",
                  url   : "include/stock_controller.php",
                  data  : {
                    image_id : imageID, 
                    action : "delete_image",
                  }
                })
                .done( function( msg ) {

                  var obj = JSON.parse( msg );

                  if (obj.error) {

                    console.log('Error deleting image ' + obj.msg);

                    alert(obj.msg);
                    //raise_error(obj.msg);

                  }
                  else {

                    stock_images(stock_id);
                    $(" #upload-stock-image ").siblings('.custom-file-label').html('Choose file');

                  }
                });
              }
            }
          });
      });


      edit_stock = function(rowID) {

        if (typeof rowID !== 'undefined') {

          // get the data
          $.ajax({
            method  : "POST",
            url   : "include/stock_controller.php",
            data  : {
              row_id : rowID,
              action : 'get',
            }
          })
          .done( function( msg ) {

            var obj = JSON.parse( msg );

            console.log(obj);

            //productModalLoadData(obj);
            $( '#stockID ').val(obj.stock_id);
            $( '#stock-product-id ').val(obj.product_id);
            $( '#product_description ').val(obj.product_description);
            $( '#stock-glaze-id').val(obj.glaze_id);
            $( '#glaze_description').val(obj.glaze_description);
            $( '#stock-design-id').val(obj.design_id);
            $( '#design_description').val(obj.design_description);
            $( '#stock-height ').html(obj.height);
            $( '#stock-width ').html(obj.width);
            $( '#stock-weight ').html(obj.weight);
            $( '#stock-volume ').html(obj.volume);
            

            $('#stockModal').modal('show');

          });
        }

      };

      $(" #dropdown-inventory-filter a ").on("click", function() {
        
        var sel = $(this).attr('id');

        console.log('clicky '+sel);

        if (sel=="__ALL_") {
          $(" #dropdownMenuButton ").removeClass( "btn-success" ).addClass( "btn-secondary" );
          $(" #__ALL_ ").addClass("active");
          $(" #__BELOW_MIN_ ").removeClass("active");
          $(" #__NONE_ZERO_ ").removeClass("active");
          $(" #__ZERO_ ").removeClass("active");
        }
        else
          $(" #dropdownMenuButton ").removeClass( "btn-secondary" ).addClass( "btn-success" );

        if (sel=="__BELOW_MIN_") {
          $(" #__ALL_ ").removeClass("active");
          $(" #__BELOW_MIN_ ").addClass("active");
          $(" #__NONE_ZERO_ ").removeClass("active");
          $(" #__ZERO_ ").removeClass("active");
        }
        else if (sel=="__NONE_ZERO_") {
          $(" #__ALL_ ").removeClass("active");
          $(" #__BELOW_MIN_ ").removeClass("active");
          $(" #__NONE_ZERO_ ").addClass("active");
          $(" #__ZERO_ ").removeClass("active");
        }
        else if (sel=="__ZERO_") {
          $(" #__ALL_ ").removeClass("active");
          $(" #__BELOW_MIN_ ").removeClass("active");
          $(" #__NONE_ZERO_ ").removeClass("active");
          $(" #__ZERO_ ").addClass("active");
        }


        $.ajax({
          method  : "POST",
          url   : "include/set_session_vars.php",
          data  : {
            session_var : 'inventory_filter',
            session_val : sel,
          }
        })
        .done( function( msg ) {

          var obj = JSON.parse( msg );

          browseTable.ajax.reload();

        });        
      });

      $(" #btn-stock-save ").on("click", function() {

        var stock_id = $( '#stockID ').val();
        var product_id = $( '#stock-product-id ').val();
        var glaze_id = $( '#stock-glaze-id ').val();
        var design_id = $( '#stock-design-id ').val();
        
        
        // var product_code = $(' #productCode ').val();
        // var product_description = $( '#productDescription ').val();

        $.ajax({
          method  : "POST",
          //url   : "include/save_stock_data.php",
          url   : "include/stock_controller.php",
          data  : {
            stock_id : stock_id, 
            product_id : product_id,
            glaze_id : glaze_id,
            design_id : design_id,
            action : 'edit',

            // product_code : product_code, 
            // product_description: product_description
          }
        })
        .done( function( msg ) {

          var obj = JSON.parse( msg );

          if (obj.error) {
            console.log('Error saving changes:\n' + obj.msg);

            $('#stockModal').modal('hide');

            raise_error(obj.msg);
          }
          else {

            $('#stockModal').modal('hide');
            browseTable.ajax.reload();

          }
        });
      })

      $(" #btn-stock-new ").on("click", function() {

        $( '#stockID ').val('__NEW_');
        $( '#stock-product-id ').val('0');
        $( '#product_description ').val('');
        $( '#stock-glaze-id').val('0');
        $( '#glaze_description').val('');
        $( '#stock-design-id').val('0');
        $( '#design_description').val('');
        $( '#stock-height').html('');
        $( '#stock-width').html('');
        $( '#stock-weight').html('');
        $( '#stock-volume').html('');

        $('#stockModal').modal('show');

      });


      $(" #btn-stock-export ").on("click", function() {

        window.open("include/export_stock_csv.php");
        
      });

      /*
        Stock Modal Functions
      */
      $(' .typeahead-product ').typeahead({
        order       : "asc",
        display     : ["code", "description"],
        templateValue: "{{code}} - {{description}}",
        emptyTemplate: "No results found for {{query}}",
        autoselect  : true,
        hint: true,
        highlight: true,
        source : {
            products: {
              ajax: {
                  url: "include/get_typeahead_products.php"
              }
            }
        },
        callback: {
          onClickAfter: function (node, a, item, event) {
     
            event.preventDefault();

//console.log(JSON.stringify(item));      

            $(" #stock-product-id ").val(item.product_id);
            $(" #stock-height ").html(item.height);
            $(" #stock-width ").html(item.width);
            $(" #stock-weight ").html(item.weight);
            $(" #stock-volume ").html(item.volume);
            
          }
        }
      });

      $(' .typeahead-glaze ').typeahead({
        order       : "asc",
        display     : ["code", "description"],
        templateValue: "{{code}} - {{description}}",
        emptyTemplate: "No results found for {{query}}",
        autoselect  : true,
        hint: true,
        highlight: true,
        source : {
            products: {
              ajax: {
                  url: "include/get_typeahead_glazes.php"
              }
            }
        },
        callback: {
          onClickAfter: function (node, a, item, event) {
     
            event.preventDefault();
      
            $(" #stock-glaze-id ").val(item.glaze_id);
            
          }
        }
      });

      $(' .typeahead-design ').typeahead({
        order       : "asc",
        display     : ["code", "description"],
        templateValue: "{{code}} - {{description}}",
        emptyTemplate: "No results found for {{query}}",
        autoselect  : true,
        hint: true,
        highlight: true,
        source : {
            products: {
              ajax: {
                  url: "include/get_typeahead_designs.php"
              }
            }
        },
        callback: {
          onClickAfter: function (node, a, item, event) {
     
            event.preventDefault();
      
            $(" #stock-design-id ").val(item.design_id);
            
          }
        }
      });

      <?php } ?>


      /*

        --------------

        stock registry

        --------------

      */

/*
      stock_registry_view = function(rowID) {

        if (typeof rowID !== 'undefined') {

          // get the data
          $.ajax({
            method  : "POST",
            url : "include/get_registry_data.php",
            data : {
              row_id : rowID,
            }
          })
          .done( function( msg ) {

            var obj = JSON.parse( msg );

            $( '#stockID ').val(obj.stock_id);
            $( '#modal-registry-title' ).html(obj.description);

            $(" #stockRegistryID ").val("__NEW_");
            $(" #inputRegistryDate ").val("");
            $(" #inputRegistryQuantity ").val("");
            $(" #inputRegistryComment ").val("");            

            stockRegistryTable.ajax.reload();

            $('#stockRegistryModal').modal('show');
  
          });
        }
      }
*/

      stock_registry_view = function(rowID)
      {
        if (typeof rowID !== 'undefined') 
        {
            //$( '#stockID ').val(obj.stock_id);
            $( '#stockID ').val(rowID);

            stockRegistryTable.ajax.reload();

            $('#stockRegistryModal').modal('show');
        }
      }


      $(' #btn-registry-close ').on('click', function(e) {

        browseTable.row(tableBrowseIndex).data(tableBrowseData).invalidate();

        $('#stockRegistryModal').modal('hide');

      });
      

      var stockRegistryTable = $('#table-stock-registry').DataTable({
        processing  : true,
        serverSide  : true,
        deferLoading: 0,
        ajax        : 
        {
          url   : "include/stock_controller.php",
          data  : function ( d ) {
            d.row_id = $( '#stockID ').val(),
            d.action = 'get_registry_data'
          }
        },
        searching   : false,
        paging      : true,
        pageLength  : 10,
        pagingType  : 'full',
        filter      : true,
        responsive  : true,
        order       : [[ 0, "desc" ]],
        language    : {
            'loadingRecords': '&nbsp;',
            'processing': '<div class="spinner-border text-success" role="status"><span class="sr-only">Loading...</span></div>'
        },
        //"data"      : data,
        columns   : [
          { data: 'date'},
          { data: 'quantity', orderable: false},
          { data: 'comment', orderable: false},
          { data: 'entry_type', orderable: false},
/*
          {
            data: 'actions',
            orderable: false,
            render: function(data, type, row, meta) {
              return '<a class="text-dark" href="javascript:delete_registry('+row.DT_RowId+')"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-trash" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/><path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/></svg></a>';
            },
          },
*/
        ],
      });


      getFormattedDate = function(strdate='') {
          if (strdate=='') {
            var d = new Date();
            var strdate = d.getFullYear() + "/" + (d.getMonth()+1) + "/" + d.getDate();
          }

          var date = new Date(strdate);

          let year = date.getFullYear();
          let month = (1 + date.getMonth()).toString().padStart(2, '0');
          let day = date.getDate().toString().padStart(2, '0');
        
          return year +'-' + month + '-' + day;
      }

      $(' #table-stock-registry ').on('click', 'tr', function(e) {  // or dblclick?

        e.stopPropagation();

        let rowID = $(this).attr('id');
        var arr = stockRegistryTable.row( this ).data();

        $(" #stockRegistryID ").val(rowID);
        $(" #inputRegistryDate ").val(getFormattedDate(arr.date));//arr.date);
        $(" #inputRegistryQuantity ").val(arr.quantity);
        $(" #inputRegistryComment ").val(arr.comment);

      });


      $(" #inputRegistryQuantity, #inputMinimumStock ").on("keypress keyup blur",function (event) {
        
        $(this).val($(this).val().replace(/[^0-9\.]/g,''));

        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
          event.preventDefault();
        }
      });

      $( "#btn-registry-clear" ).on("click", function(e) {

        $(" #stockRegistryID ").val("__NEW_");
        $(" #inputRegistryDate ").val("");
        $(" #inputRegistryQuantity ").val("");
        $(" #inputRegistryComment ").val("");

      })

      $(" #btn-registry-save ").on("click", function(e) {

        var stock_registry_id = $(" #stockRegistryID ").val();
        var stock_id = $( '#stockID ').val();
        var registry_date = $(" #inputRegistryDate ").val();
        var registry_quantity = $(" #inputRegistryQuantity ").val();
        var registry_comment = $(" #inputRegistryComment ").val();

        $.ajax({
          method  : "POST",
          url   : "include/save_registry_data.php",
          data  : {
            stock_registry_id : stock_registry_id,
            stock_id : stock_id, 
            registry_date : registry_date,
            registry_quantity : registry_quantity,
            registry_comment : registry_comment,
          }
        })
        .done( function( msg ) {

          var obj = JSON.parse( msg );

          if (obj.error) {
            //raise_error(obj.msg);
            bootbox.alert(obj.msg);
          }
          else {

            tableBrowseData.quantity = obj.data.quantity;
            tableBrowseData.date = obj.data.date;
            tableBrowseData.comment = obj.data.comment;

            $(" #collapseOne ").collapse('hide');

            $(" #stockRegistryID ").val("__NEW_");
            $(" #inputRegistryDate ").val("");
            $(" #inputRegistryQuantity ").val("");
            $(" #inputRegistryComment ").val("");

            stockRegistryTable.ajax.reload();

          }
        });

      });

      delete_registry = function(id) {

        bootbox.confirm({
            message: "Are you sure you want to delete this registry entry ?",
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
              if (result) {

                $.ajax({
                  method  : "POST",
                  url   : "include/delete_registry.php",
                  data  : {
                    stock_registry_id : id, 
                  }
                })
                .done( function( msg ) {

                  var obj = JSON.parse( msg );

                  if (obj.error) {

                    console.log('Error deleting registry ' + obj.msg);

                    alert(obj.msg);
                    //raise_error(obj.msg);

                  }
                  else {
                    tableBrowseData.quantity = obj.data.quantity;
                    tableBrowseData.date = obj.data.date;
                    tableBrowseData.comment = obj.data.comment;
                    
                    stockRegistryTable.ajax.reload();

                  }
                });
              }
            }
        });
      }


      /*


        Product page


      */
      var productsTable = $('#table-products').DataTable({
        processing  : true,
        serverSide  : true,
        //ajax        : "include/get_products.php",
        ajax        : 
        {
          url   : "include/product_controller.php",
          data  : function ( d ) {
            d.action = 'get_data';
          }
        },        
        searching   : true,
        paging      : true,
        pageLength  : 10,
        pagingType  : 'full',
        filter      : true,
        responsive  : true,
        language    : {
            'loadingRecords': '&nbsp;',
            'processing': '<div class="spinner-border text-success" role="status"><span class="sr-only">Loading...</span></div>'
        },
        //"data"      : data,
        columns   : [
          { data: 'code'},
          { data: 'description'},
          { data: 'height'},
          { data: 'width'},
          { data: 'weight'},
          { data: 'volume'},
          // {
          //   data: 'edit',
          //   orderable: false,
          //   render: function(data, type, row, meta) {
          //     return '<a class="text-dark" href="javascript:edit_product('+row.DT_RowId+')"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pencil" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5L13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175l-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/></svg></a>';
          //   },
          // },
          {
            data: 'delete',
            orderable: false,
            render: function(data, type, row, meta) {
              return '<a class="text-dark" href="javascript:edit_product('+row.DT_RowId+')"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pencil" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5L13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175l-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/></svg></a>  <a class="text-dark" href="javascript:delete_product('+row.DT_RowId+')"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-trash" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/><path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/></svg></a>';
            },
          }
        ],
      });

      delete_product = function(id) {

        bootbox.confirm({
            message: "Are you sure you want to delete this product ?",
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
              if (result) {

                $.ajax({
                  method  : "POST",
                  url   : "include/product_controller.php",
                  data  : {
                    product_id : id, 
                    action : 'delete',
                  }                  
                })
                .done( function( msg ) {

                  var obj = JSON.parse( msg );

                  if (obj.error) {

                    console.log('Error deleting product ' + obj.msg);

                    raise_error(obj.msg);

                  }
                  else {
                    productsTable.ajax.reload();
                  }
                });

              }
            }
        });

      }

      // $("[id^=del_product_id_]").on('click', function() {
      //   console.log('delete this product');
      // });

      $('#table-products').on('dblclick', 'tr', function(e) {  // or dblclick?

        e.stopPropagation();

        let rowID = $(this).attr('id');
        //var search = $('.dataTables_filter input').val();

        edit_product(rowID);

      });

      edit_product = function(rowID) {

        if (typeof rowID !== 'undefined') {

          // get the data
          $.ajax({
            method  : "POST",
            //url   : "include/get_product_data.php",
            url   : "include/product_controller.php",
            data  : {
              row_id : rowID,
              action : 'get',
            }
          })
          .done( function( msg ) {

            var obj = JSON.parse( msg );

            console.log(obj);

            //productModalLoadData(obj);
            $( '#productID ').val(obj.product_id);
            $( '#productCode ').val(obj.code);
            $( '#productDescription ').val(obj.description);
            $( '#productHeight ').val(obj.height);
            $( '#productWidth ').val(obj.width);
            $( '#productWeight ').val(obj.weight);
            $( '#productVolume ').val(obj.volume);

            $('#productModal').modal('show');

          });
        }

      };


      $(" #btn-product-save ").on("click", function() {

        var product_id = $( '#productID ').val();
        var product_code = $(' #productCode ').val();
        var product_description = $( '#productDescription ').val();
        var product_height = $( '#productHeight ').val();
        var product_width = $( '#productWidth ').val();
        var product_weight = $( '#productWeight ').val();
        var product_volume = $( '#productVolume ').val();

        $.ajax({
          method  : "POST",
          url   : "include/product_controller.php",
          data  : {
            product_id : product_id, 
            product_code : product_code, 
            product_description: product_description,
            product_height: product_height,
            product_width: product_width,
            product_weight: product_weight,
            product_volume: product_volume,
            action : 'edit',
          }
        })
        .done( function( msg ) {

          var obj = JSON.parse( msg );

          if (obj.error) {

            console.log('Error saving changes:\n' + obj.msg);

            raise_error(obj.msg);

          }
          else {

            $('#productModal').modal('hide');
            productsTable.ajax.reload();

          }
        });
      })

      $(" #btn-product-new ").on("click", function() {

        $( '#productID ').val('__NEW_');
        $( '#productCode ').val('');
        $( '#productDescription ').val('');
        $( '#productHeight ').val('');
        $( '#productWidth ').val('');
        $( '#productWeight ').val('');
        $( '#productVolume ').val('');

        $('#productModal').modal('show');

      })



      /*

        Glazes page

      */
      var glazesTable = $('#table-glazes').DataTable({
        processing  : true,
        serverSide  : true,
        ajax        : 
        {
          url   : "include/glaze_controller.php",
          data  : function ( d ) {
            d.action = 'get_data';
          }
        },
        searching   : true,
        paging      : true,
        pageLength  : 10,
        pagingType  : 'full',
        filter      : true,
        responsive  : true,
        language    : {
            'loadingRecords': '&nbsp;',
            'processing': '<div class="spinner-border text-success" role="status"><span class="sr-only">Loading...</span></div>'
        },
        //"data"      : data,
        columns   : [
          { data: 'code'},
          { data: 'description'},
          {
            data: 'delete',
            orderable: false,
//            max-width: '30px',
            render: function(data, type, row, meta) {
              return '<a class="text-dark" href="javascript:edit_glaze('+row.DT_RowId+')"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pencil" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5L13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175l-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/></svg></a>  <a class="text-dark" href="javascript:delete_glaze('+row.DT_RowId+')"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-trash" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/><path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/></svg></a>';
            },
          }
        ],
      });

      delete_glaze = function(id) {

        bootbox.confirm({
            message: "Are you sure you want to delete this glaze ?",
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
              if (result) {

                $.ajax({
                  method  : "POST",
                  //url   : "include/delete_glaze.php",
                  url   : "include/glaze_controller.php",
                  data  : {
                    glaze_id : id, 
                    action : 'delete',
                  }
                })
                .done( function( msg ) {

                  var obj = JSON.parse( msg );

                  if (obj.error) {

                    raise_error(obj.msg);

                    console.log('Error deleting glaze ' + obj.msg);
                  }
                  else {
                    glazesTable.ajax.reload();
                  }
                });
              }
            }
        });
      }

      // $("[id^=del_product_id_]").on('click', function() {
      //   console.log('delete this product');
      // });

      $('#table-glazes').on('dblclick', 'tr', function(e) {  // or dblclick?

        e.stopPropagation();

        let rowID = $(this).attr('id');
        //var search = $('.dataTables_filter input').val();

        edit_glaze(rowID);

      });

      edit_glaze = function(rowID) {

        if (typeof rowID !== 'undefined') {

          // get the data
          $.ajax({
            method  : "POST",
            //url   : "include/get_glaze_data.php",
            url   : "include/glaze_controller.php",
            data  : {
              row_id : rowID,
              action : 'get',
            }
          })
          .done( function( msg ) {

            var obj = JSON.parse( msg );

            console.log(obj);

            //productModalLoadData(obj);
            $( '#glazeID ').val(obj.glaze_id);
            $( '#glazeCode ').val(obj.code);
            $( '#glazeDescription ').val(obj.description);

            $('#glazeModal').modal('show');

          });
        }

      };


      $(" #btn-glaze-save ").on("click", function() {

        var glaze_id = $( '#glazeID ').val();
        var glaze_code = $(' #glazeCode ').val();
        var glaze_description = $( '#glazeDescription ').val();

        $.ajax({
          method  : "POST",
          //url   : "include/save_glaze_data.php",
          url   : "include/glaze_controller.php",
          data  : {
            glaze_id : glaze_id, 
            glaze_code : glaze_code, 
            glaze_description : glaze_description,
            action : 'edit',
          }
        })
        .done( function( msg ) {

          var obj = JSON.parse( msg );

          if (obj.error) {
            console.log('Error saving changes:\n' + obj.msg);
            raise_error(obj.msg);
          }
          else {

            $('#glazeModal').modal('hide');
            glazesTable.ajax.reload();

          }
        });
      });

      $(" #btn-glaze-new ").on("click", function() {

        $( '#glazeID ').val('__NEW_');
        $( '#glazeCode ').val('');
        $( '#glazeDescription ').val('');

        $('#glazeModal').modal('show');

      });




      /*

        Designs page

      */
      var designsTable = $('#table-designs').DataTable({
        processing  : true,
        serverSide  : true,
        //ajax        : "include/get_designs.php",
        ajax        : 
        {
          url   : "include/design_controller.php",
          data  : function ( d ) {
            d.action = 'get_data';
          }
        },
        searching   : true,
        paging      : true,
        pageLength  : 10,
        pagingType  : 'full',
        filter      : true,
        responsive  : true,
        language    : {
            'loadingRecords': '&nbsp;',
            'processing': '<div class="spinner-border text-success" role="status"><span class="sr-only">Loading...</span></div>'
        },
        //"data"      : data,
        columns   : [
          { data: 'code'},
          { data: 'description'},
          {
            data: 'delete',
            orderable: false,
//            max-width: '30px',
            render: function(data, type, row, meta) {
              return '<a class="text-dark" href="javascript:edit_design('+row.DT_RowId+')"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pencil" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5L13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175l-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/></svg></a>  <a class="text-dark" href="javascript:delete_design('+row.DT_RowId+')"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-trash" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/><path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/></svg></a>';
            },
          }
        ],
      });

      delete_design = function(id) {

        bootbox.confirm({
            message: "Are you sure you want to delete this design ?",
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
              if (result) {

                $.ajax({
                  method  : "POST",
                  //url   : "include/delete_design.php",
                  url   : "include/design_controller.php",
                  data  : {
                    design_id : id, 
                    action : 'delete',
                  }                  
                })
                .done( function( msg ) {

                  var obj = JSON.parse( msg );

                  if (obj.error) {
                    console.log('Error deleting design ' + obj.msg);
                    raise_error(obj.msg);
                  }
                  else {
                    designsTable.ajax.reload();
                  }
                });
              }
            }
        });
      }

      // $("[id^=del_product_id_]").on('click', function() {
      //   console.log('delete this product');
      // });

      $('#table-designs').on('dblclick', 'tr', function(e) {  // or dblclick?

        e.stopPropagation();

        let rowID = $(this).attr('id');
        //var search = $('.dataTables_filter input').val();

        edit_design(rowID);

      });

      edit_design = function(rowID) {

        if (typeof rowID !== 'undefined') {

          // get the data
          $.ajax({
            method  : "POST",
            //url   : "include/get_design_data.php",
            url   : "include/design_controller.php",
            data  : {
              row_id : rowID,
              action : 'get',
            }           
          })
          .done( function( msg ) {

            var obj = JSON.parse( msg );

            console.log(obj);

            //productModalLoadData(obj);
            $( '#designID ').val(obj.design_id);
            $( '#designCode ').val(obj.code);
            $( '#designDescription ').val(obj.description);

            $('#designModal').modal('show');

          });
        }

      };


      $(" #btn-design-save ").on("click", function() {

        var design_id = $( '#designID ').val();
        var design_code = $(' #designCode ').val();
        var design_description = $( '#designDescription ').val();

        $.ajax({
          method  : "POST",
          url   : "include/design_controller.php",
          data  : {
            design_id : design_id, 
            design_code : design_code, 
            design_description: design_description,
            action : 'edit',
          }
        })
        .done( function( msg ) {

          var obj = JSON.parse( msg );

          if (obj.error) {
            alert('Error saving changes:\n' + obj.msg);
            raise_error(obj.msg);
          }
          else {

            $('#designModal').modal('hide');
            designsTable.ajax.reload();

          }
        });
      });


      $(" #btn-design-new ").on("click", function() {

        $( '#designID ').val('__NEW_');
        $( '#designCode ').val('');
        $( '#designDescription ').val('');

        $('#designModal').modal('show');

      });





      <?php if (($_SESSION['active_nav'] == 'nav-receive') || ($_SESSION['active_nav'] == 'nav-deliver')) { ?>

      /*

        Movements page

        Grid

      */
      var movementsTable = $(' #table-movement ').DataTable({
        processing  : true,
        serverSide  : true,
        ajax        : 
        {
          url   : "include/movement_controller.php",
          data  : function ( d ) {
            d.action = 'get_data';
          }
        },
        searching   : true,
        paging      : true,
        pageLength  : 10,
        pagingType  : 'full',
        filter      : true,
        responsive  : true,
        language    : {
            'loadingRecords': '&nbsp;',
            'processing': '<div class="spinner-border text-success" role="status"><span class="sr-only">Loading...</span></div>'
        },
        //"data"      : data,
        columns   : [
          { data: 'source'},
          { data: 'reference'},
          { data: 'date'},
          { data: 'status'},
          {
            data: 'delete',
            orderable: false,
//            max-width: '30px',
            render: function(data, type, row, meta) {
              return '<a class="text-dark" href="javascript:edit_movement('+row.DT_RowId+')"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pencil" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5L13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175l-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/></svg></a>&nbsp;<a class="text-dark" href="javascript:view_movement('+row.DT_RowId+')"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-arrow-up" viewBox="0 0 16 16">  <path d="M8 11a.5.5 0 0 0 .5-.5V6.707l1.146 1.147a.5.5 0 0 0 .708-.708l-2-2a.5.5 0 0 0-.708 0l-2 2a.5.5 0 1 0 .708.708L7.5 6.707V10.5a.5.5 0 0 0 .5.5z"/>  <path d="M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H4zm0 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/></svg></a>&nbsp;<a class="text-dark" href="javascript:cancel_movement('+row.DT_RowId+')"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>  <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/></svg></a>';
            },
          }
        ],
      });



      /*
          New Movement button
      */

      $(" #btn-movement-new ").on("click", function() {

        $( '#movementID ').val('__NEW_');

        let ref = 1; //echo get_receive_reference();
        $(" #inputReference ").val(ref);

        let cur_date = getFormattedDate();
        $(" #inputDate ").val(cur_date);

        /*
          set the session variable that will hold the 
          details and items of the form, and open
          the form on success
        */
        $.ajax({
          method  : "POST",
          url   : "include/movement_controller.php",
          data  : {
            action : 'session_vars',
          }
        })
        .done( function( msg ) {

          var obj = JSON.parse( msg );

          console.log(JSON.stringify(msg));

          if (obj.error) {

            alert('Error opening form:\n' + obj.msg);
            raise_error(obj.msg);

          }
          else {

            $('#movementModal').modal('show');

          }
        });

      });


      cancel_movement = function(id) {

        bootbox.confirm({
            message: "Are you sure you want to cancel this entry ?",
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
              if (result) {

                $.ajax({
                  method  : "POST",
                  url   : "include/movement_controller.php",
                  data  : {
                    stock_movement_id : id, 
                    action : 'cancel',
                  }
                })
                .done( function( msg ) {

                  var obj = JSON.parse( msg );

                  if (obj.error) {

                    raise_error(obj.msg);

                    console.log('Error deleting movement ' + obj.msg);
                  }
                  else {

                    movementsTable.ajax.reload();

                  }
                });
              }
            }
        });
      }

      // $("[id^=del_product_id_]").on('click', function() {
      //   console.log('delete this product');
      // });

      $('#table-movement').on('dblclick', 'tr', function(e) {  // or dblclick?

        e.stopPropagation();

        let rowID = $(this).attr('id');
        //var search = $('.dataTables_filter input').val();

        //edit_movement(rowID);

      });

      edit_movement = function(rowID) {

        if (typeof rowID !== 'undefined') {

          raise_message('currently cannot edit');

          /*
          $.ajax({
            method  : "POST",
            url   : "include/movement_controller.php",
            data  : {
              row_id : rowID,
              action : 'get',
            }
          })
          .done( function( msg ) {

            var obj = JSON.parse( msg );
            console.log(obj);

          });
          */

        }
      };


      view_movement = function(rowID) {

        if (typeof rowID !== 'undefined') {

          var myWin = window.open('include/movement_controller.php?stock_movement_id='+rowID+'&action=view', 'mantra-pottery-view');

        }        
      }


      /*
          Movement Modal Save button
      */
      $(" #btn-movement-save ").on("click", function() {

        var reference = $(" #inputReference ").val();
        var source_id = $(" #mvment-source-id ").val();
        var date = $(" #inputDate ").val();

        $.ajax({
          method  : "POST",
          url   : "include/movement_controller.php",
          data  : {
            action : 'edit',
            reference : reference,
            source_id : source_id,
            // movement_type : 1 ( determined by the setting of hidden movementID value )
            date : date,
            // items ( session variable )
          }
        })
        .done( function( msg ) {

          var obj = JSON.parse( msg );
          console.log(JSON.stringify(obj));

          if (obj.error) {
            
            raise_message_movement(obj.msg);
            
          }
          else {

            $('#movementModal').modal('hide');

            movementsTable.ajax.reload();

          }
        });
      });


      /*
          Modal window

          Reference
      */
      $(" #inputReference ").keyup(function( event ) {

        if (( event.keyCode == 13 ) || ( event.keyCode == 9)) {

          event.preventDefault();

          $(" #mvment_source ").focus();

        }

      });


      /*
          Modal window
          
          Source typeahead
      */
      $(' .typeahead-mvment-source ').typeahead({
        order       : "asc",
        display     : ["name"],
        templateValue: "{{name}}",
        emptyTemplate: "No results found for {{query}}",
        autoselect  : true,
        hint: true,
        highlight: true,
        source : {
            products: {
              ajax: {
                  url: "include/get_typeahead_source.php"
              }
            }
        },
        callback: {
          onClickAfter: function (node, a, item, event) {
     
            event.preventDefault();

            //console.log(JSON.stringify(item.description));      

            $(" #mvment-source-id ").val(item.source_id);
            $(" #mvment_source ").val(item.name);
            $(" #mvmentQty ").val('1');
            
          },
          onsubmit: function() {
            console.log('submitted');
            event.preventDefault();
          }          
        }
      });

      $(" .typeahead-mvment-source ").keydown(function( event ) {

        if (( event.keyCode == 13 ) || ( event.keyCode == 9)) {

          event.preventDefault();

          $(".typeahead__result").hide();

          $(" #inputDate ").focus();

          //getDescription( $(this).val() );
        }
        else
          $(".typeahead__result").show();
      });


      /*
          Modal window
          
          Code typeahead
      */
      $(' .typeahead-mvment-code ').typeahead({
        order       : "asc",
        display     : ["code", "description"],
        templateValue: "{{code}}",
        emptyTemplate: "No results found for {{query}}",
        cancelButton: false,
        maxItem: false,
        dynamic: true,
        //dynamicFilter: 'at',
        source : {
            products: {
              ajax: {
                  url: "include/get_typeahead_stock.php",
                  data: $(this).val(),
              }
            }
        },
        callback: {
          onClickAfter: function (node, a, item, event) {
     
            event.preventDefault();

            //console.log(JSON.stringify(item.description));      

            $(" #mvment-stock-id ").val(item.stock_id);
            $(" #mvmentDescription ").val(item.description);
            $(" #mvmentQty ").val('1');

          },
          onsubmit: function() {
            event.preventDefault();
          }          
        }
      });


      $(" .typeahead-mvment-code ").keydown(function( event ) {

      if (( event.keyCode == 13 ) || ( event.keyCode == 9)) {

          event.preventDefault();

          $.ajax({
            method  : "POST",
            url   : "include/movement_controller.php",
            data  : {
              action : 'check_session_var',
              stock_id : $(" #mvment-stock-id ").val(),
              code : '',
              description: '',
              quantity : 0
            }
          })
          .done( function( msg ) {

            var obj = JSON.parse( msg );

            console.log(JSON.stringify(obj));


            if (obj.msg=='found') {

              $(" #mvmentQty ").val(obj.quantity);

              movementsModalTable.ajax.reload();

            }
            else {
              $(" #mvmentQty ").val("1");
            }
           
          });

          $(".typeahead__result").hide();

          $(" #mvmentQty ").focus();

          //getDescription( $(this).val() );
        }
        else
          $(".typeahead__result").show();
      });

      
      $(" #mvmentQty ").focusin(function() {

        event.preventDefault();

        $( this ).select();

      });


      $(" #mvmentQty ").keydown(function( event ) {


        if (( event.keyCode == 13 ) || ( event.keyCode == 9)) {

          event.preventDefault();

          $.ajax({
            method  : "POST",
            url   : "include/movement_controller.php",
            data  : {
              action : 'update_session_var',
              stock_id : $(" #mvment-stock-id ").val(),
              code : $(" .typeahead-mvment-code ").val(),
              description: $(" #mvmentDescription ").val(),
              quantity : $(" #mvmentQty ").val()
            }
          })
          .done( function( msg ) {

            var obj = JSON.parse( msg );

            if (obj.error) {
              
              if (obj.msg.indexOf("quantity") >= 0) {

                $(" #mvmentQty ").select();

              }
              else {

                $(" #mvment-stock-id ").val("");
                $(" .typeahead-mvment-code ").val("");
                $(" .typeahead-mvment-code ").focus();
                $(" #mvmentDescription ").val("");
                $(" #mvmentQty ").val("1");

              }

              raise_message_movement(obj.msg);

            }
            else {

              $(" #mvment-stock-id ").val("");
              $(" .typeahead-mvment-code ").val("");
              $(" .typeahead-mvment-code ").focus();
              $(" #mvmentDescription ").val("");
              $(" #mvmentQty ").val("1");

              movementsModalTable.ajax.reload();
            }


          });
        }
        else if (event.keyCode == 27) {

          $(" #mvment-stock-id ").val("");
          $(" .typeahead-mvment-code ").val("");
          $(" .typeahead-mvment-code ").focus();
          $(" #mvmentDescription ").val("");
          $(" #mvmentQty ").val("1");

        }
      });




      var movementsModalTable = $(' #table-modal-mvment').DataTable({
        processing  : true,
        serverSide  : true,
        deferLoading: 0, //
        ajax        : 
        {
          url   : "include/movement_controller.php",
          data  : function ( d ) {
            d.action = 'get_session_data';
          }
        },
        searching   : false,
        paging      : false,
        pageLength  : 10,
        pagingType  : 'full',
        filter      : false,
        responsive  : true,
        language    : {
            'loadingRecords': '&nbsp;',
            'processing': '<div class="spinner-border text-success" role="status"><span class="sr-only">Loading...</span></div>'
        },
        //"data"      : data,
        columns   : [
          { data: 'code', orderable: false},
          { data: 'description', orderable: false},
          { data: 'quantity', orderable: false},
        ],
      });



      $('#movementModal').on('shown.bs.modal', function (event) {
        
        $(" #inputReference ").focus();
        movementsModalTable.ajax.reload();

        console.log('movement modal has been shown');
      });



      $('#movementModal').on('hidden.bs.modal', function (event) {

        console.log('movement modal has been closed');

      });






      <?php } ?>  /* movements page */



      /*


        Source Page


      */
      var sourceTable = $(' #table-source ').DataTable({
        processing  : true,
        serverSide  : true,
        ajax        : 
        {
          url   : "include/source_controller.php",
          data  : function ( d ) {
            d.action = 'get_data';
          }
        },
        searching   : true,
        paging      : true,
        pageLength  : 10,
        pagingType  : 'full',
        filter      : true,
        responsive  : true,
        language    : {
            'loadingRecords': '&nbsp;',
            'processing': '<div class="spinner-border text-success" role="status"><span class="sr-only">Loading...</span></div>'
        },
        //"data"      : data,
        columns   : [
          { data: 'name'},
          { data: 'address'},
          { data: 'phone'},
          { data: 'type'},
          { data: 'gstin'},
          {
            data: 'delete',
            orderable: false,
//            max-width: '30px',
            render: function(data, type, row, meta) {
              return '<a class="text-dark" href="javascript:edit_source('+row.DT_RowId+')"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pencil" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5L13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175l-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/></svg></a>  <a class="text-dark" href="javascript:delete_source('+row.DT_RowId+')"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-trash" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/><path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/></svg></a>';
            },
          }
        ],
      });


      delete_source = function(id) {

        bootbox.confirm({
            message: "Are you sure you want to delete this source ?",
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
              if (result) {

                $.ajax({
                  method  : "POST",
                  //url   : "include/delete_design.php",
                  url   : "include/source_controller.php",
                  data  : {
                    source_id : id, 
                    action : 'delete',
                  }                  
                })
                .done( function( msg ) {

                  var obj = JSON.parse( msg );

                  if (obj.error) {
                    console.log('Error deleting source ' + obj.msg);
                    raise_error(obj.msg);
                  }
                  else {
                    sourceTable.ajax.reload();
                  }
                });
              }
            }
        });
      }


      $('#table-source').on('dblclick', 'tr', function(e) {  // or dblclick?

        e.stopPropagation();

        let rowID = $(this).attr('id');
        //var search = $('.dataTables_filter input').val();

        edit_source(rowID);

      });

      edit_source = function(rowID) {

        if (typeof rowID !== 'undefined') {

          // get the data
          $.ajax({
            method  : "POST",
            //url   : "include/get_design_data.php",
            url   : "include/source_controller.php",
            data  : {
              row_id : rowID,
              action : 'get',
            }           
          })
          .done( function( msg ) {

            var obj = JSON.parse( msg );

            console.log(obj);

            //productModalLoadData(obj);
            $( '#sourceID ').val(obj.source_id);
            $( '#sourceTypeID ').val(obj.type_id);
            $( '#sourceName ').val(obj.name);
            $( '#sourceAddress ').val(obj.address);
            $( '#sourcePhone ').val(obj.phone);
            $( '#sourceGSTIN ').val(obj.gstin);
            if (obj.type_id == 1)
              $( '.sourceValue ').text("Supplier");
            else
              $( '.sourceValue ').text("Client");

            $('#sourceModal').modal('show');

          });
        }

      };

      
      $("#dropdown-source-type li a").click(function(){

        var sel = $(this).attr('id');

        $(this).parents("#dropdown-source").find('.sourceValue').text($(this).text());

        $( '#sourceTypeID' ).val(sel);

        // if (sel==1)
        //   $(" #dropdownOnline ").removeClass( "btn-danger" ).addClass( "btn-success" );
        // else
        //   $(" #dropdownOnline ").removeClass( "btn-success" ).addClass( "btn-danger" );

        // $.ajax({
        //   method  : "POST",
        //   url   : "data/session_vars.php",
        //   data  : { name: "connect_mode", value: sel }
        // })
        // .done(function( msg ) {
        //   console.log( msg );
        // });

      });


      $(" #btn-source-save ").on("click", function() {

        var source_id = $( '#sourceID ').val();
        var type_id = $( '#sourceTypeID' ).val();
        var source_name = $(' #sourceName ').val();
        var source_address = $( '#sourceAddress ').val();
        var source_phone = $( '#sourcePhone ').val();
        var source_gstin = $( '#sourceGSTIN ').val();

        $.ajax({
          method  : "POST",
          url   : "include/source_controller.php",
          data  : {
            source_id : source_id,
            type_id : type_id,
            source_name : source_name, 
            source_address : source_address,
            source_phone : source_phone,
            source_gstin : source_gstin,
            action : 'edit',
          }
        })
        .done( function( msg ) {

          var obj = JSON.parse( msg );

          if (obj.error) {
            alert('Error saving changes:\n' + obj.msg);
            raise_error(obj.msg);
          }
          else {

            $('#sourceModal').modal('hide');
            sourceTable.ajax.reload();

          }
        });
      });



      $(" #btn-source-new ").on("click", function() {

        $( '#sourceID ').val('__NEW_');
        $( '.sourceValue ').text('Client');
        $( '#sourceName ').val('');
        $( '#sourceAddress ').val('');
        $( '#sourcePhone ').val('');
        $( '#sourceGSTIN ').val('');

        $('#sourceModal').modal('show');

      });



      /*


        Settings Page


      */
      $(" #btn-settings-save ").on("click", function(e) {

        var minimum_quantity = $(" #inputMinimumStock ").val();

        var companyID = $(" #companyID ").val();
        var companyLegalName = $(" #companyLegalName ").val();
        var companyTradeName = $(" #companyTradeName ").val();
        var companyBranch = $(" #companyBranch ").val();
        var companyAddress = $(" #companyAddress ").val();
        var companyPhone = $(" #companyPhone ").val();
        var companyGSTIN = $(" #companyGSTIN ").val();

        $.ajax({
          method  : "POST",
          url   : "include/save_settings_data.php",
          data  : {
            minimum_quantity : minimum_quantity,
            company_id : companyID,
            legal_name : companyLegalName,
            trade_name : companyTradeName,
            branch : companyBranch,
            address : companyAddress,
            phone : companyPhone,
            gstin : companyGSTIN,
          }
        })
        .done( function( msg ) {

          var obj = JSON.parse( msg );

          if (obj.error) {
            //raise_error(obj.msg);
            bootbox.alert(obj.msg);
          }
          else {

            raise_message(obj.msg);

          }
        });

      });



      <?php if (!$backup_error) { ?>

        let error_message = '<?php echo $db->error_msg; ?>';
        raise_error(error_message);

      <?php } ?>

      

    });

  </script>

</body>

</html>
