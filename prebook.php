
<?php include("login/active.php") ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Booking Dot Pants - PreBook Visitor</title>
  <!-- Bootstrap style sheet  -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
  <!-- Our style sheet  -->
  <link rel="stylesheet" href="css/main.css">
  <!-- Jquery java file  -->
  <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>

  <!-- Select2 style sheet & java file  -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.css" rel="stylesheet" />
  <script>
  const logginUID = "<?php echo($_SESSION['uid']) ?>";
  $(document).ready(function(){
/// Generates visitor search bar contents
   $("#selUser").select2({
    ajax: {
     url: "ajax/getData.php",
     type: "post",
     dataType: 'json',
     delay: 250,
     data: function (params) {
      return {
        searchTerm: params.term // search term
      };
     },
     processResults: function (response) {
       return {
          results: response
       };
     },
     cache: true
   },
    theme: "bootstrap",
    placeholder: "-- Search Visitors --",
    language: {
      noResults: function(){
        return $("<a href='http://google.com/'>Visitor Not Found Create New...</a>");
      }
    }
   });

/// Generates hosts search bar
  $("#selHost").select2({
   ajax: {
    url: "ajax/getHost.php",
    type: "post",
    dataType: 'json',
    delay: 250,
    data: function (params) {
     return {
       searchTerm: params.term // search term
     };
    },
    processResults: function (response) {
      return {
         results: response
      };
    },
    cache: true
  },
   theme: "bootstrap",
   placeholder: "-- Search Hosts --",
   language: {
     noResults: function(){
       return $("<a href='http://google.com/'>Visitor Not Found Create New...</a>");
     }
   }
  });

/// Get visitor information from sql and append page table with record
   $('#selUser').change(function(){
     var visitorPid = $('#selUser').find(":selected").val(); // gets us the pid of the selected visitor
     var visitorSelected = $('#selUser').find(":selected").text(); // gets us the text from the select box
     $.ajax({
       url:"ajax/getVisitorRecord.php",
       type: "post",
       data:{pid:visitorPid},
       success:function(data){
          generateRow(data)},
           dataType:"json"}
         );
       });

/// Get hosts record to update table
  $('#selHost').change(function(){
    var visitorPid = $('#selHost').find(":selected").val(); // gets us the pid of the selected visitor
    var visitorSelected = $('#selHost').find(":selected").text(); // gets us the text from the select box
    $.ajax({
      url:"ajax/getHostRecord.php",
      type: "post",
      data:{pid:visitorPid},
      success:function(data){
         generateRowHost(data)},
          dataType:"json"}
        );
      });

/// Adds logged in user to the host list
    $.ajax({
      url:"ajax/getHostRecord.php",
      type: "post",
      data:{pid:logginUID},
      success:function(data){
         generateRowHost(data)},
          dataType:"json"}
        );

/// Bootstrap configs
   $(function () {
       $('[data-toggle="tooltip"]').tooltip({placement: "right"})
   });


   // setting up default dates for inputs
   const d = new Date();
   const year = d.getFullYear();
   const month = ("0" + (d.getMonth()+1)).slice(-2);
   const day = d.getDate().toString().padStart(2, "0");;
   const hour = ("0" + d.getHours()).slice(-2);
   const minutes = ("0" + d.getMinutes()).slice(-2);

   $('#sdate, #edate').val(year +"-"+ month +"-"+ day);
   $('#stime, #etime').val(hour +":" + minutes);

/// End document ready
  });



/// Addational functions
/// Variable setup
  var dictVisitors = {}; // holds each visitors infomation to pass on in the form
  var dictHosts = {}; // holds all the hosts information
  var vid = ""; // used in the generateRow function is the unique id of the visitor

/// Handles appending table and creating array of data to pass on for processing
  function generateRow(data)
  {
    vid = data[0]['visitorid'];
    dictVisitors[data[0]['visitorid']] = [data[0]['fname'],data[0]['lname'],data[0]['cname']];
    $('#selUser').val(null).trigger('change'); // clears the selected name from the dropdown box
    $("#vistorsTable").append(
      "<tr id='row"+data[0]['visitorid']+"' value='testTR'><td name='fname' class='fname'>"+data[0]['fname']+"<input name='input_fname' type='hidden' name='array[]' value='"+data[0]['fname']+"'/><input type='hidden' name='fname' value='test fname'/></td>"+
      "<td class='lname' value='testTD'>"+data[0]['lname']+"<input type='hidden' name='lname' value='test lname'/><input type='hidden' name='array[]' value='"+data[0]['lname']+"'/></td>"+
      "<td class='cname'>"+data[0]['cname']+"</td><input type='hidden' name='array[]' value='"+data[0]['cname']+"'/>"+
      "<td><button class='btn btn-secondary btn-sm' type='button' value='"+data[0]['visitorid']+"' onclick='editVisitor($(this).val())' data-bs-toggle='modal' data-bs-target='#modal_edit' aria-controls='offcanvasBottom'><i class='bi bi-pencil-square'></i> Edit</button></td>"+
      "<td><button class='btn btn-secondary btn-sm' type='button' value='"+vid+"' onclick='$(this).parent().parent().empty(); deleteVisitor($(this).val())'><i class='bi bi-trash3'></i> Delete</button></td>"+
//      "<td>"+data[0]['visitorid']+"</td>"+
      "</tr>"
      );
  };

/// Handles appending table and creating array of data to pass on for processing
  function generateRowHost(data)
  {
    console.log(data);
    vid = data[0]['visitorid'];
    dictHosts[data[0]['visitorid']] = [data[0]['fname'],data[0]['lname']];
    $('#selHost').val(null).trigger('change'); // clears the selected name from the dropdown box
    $("#hostsTable").append(
      "<div class='col-4 g-0 pb-1'>"+
        "<div class='container border rounded-3 p-2 border-dark' style='width: 15vw; font-size: 0.75em'>"+
          "<div class='row'>"+
            "<div class='col-4'>"+
              "<img width=60 class='rounded-3' src='data:image/png;base64, "+data[0]['photo']+"'/>"+
            "</div>"+
            "<div class='col'>"+
              "<div class='row'>"+
                "<div class='col text-end'>"+
                  "<i class='bi bi-pencil-square'></i>"+
                  "<button class='btn btn-link-secondary btn-sm' type='button' value='"+vid+"' onclick='$(this).parent().parent().parent().parent().parent().parent().remove();'><i class='bi bi-trash3'></i></button>"+
                "</div>"+
              "</div>"+
              "<div class='row'>"+
                "<div class='col'>"+
                  data[0]['fname'] + " " + data[0]['lname']+
                  "<br />"+
                  "company"+
                "</div>"+
              "</div>"+
            "</div>"+
          "</div>"+
        "</div>"+
      "</div>"
      );
  };



/// Appends offcanvas edit form
  function editVisitor(id)
  {
    var company = "";
    $.ajax({
      url:"ajax/getVisitorRecord.php",
      type: "post",
      data:{pid:id},
      success:function(data){
        $("#editVisitorForm").append(
          "<div class='mb-3'>"+
          "<label for='dialogFormfname' class='form-label'>First Name</label>"+
          "<input class='form-control' type='text' id='dialogFormfname' placeholder='"+data[0]['fname']+"' aria-label='default input example required>"+
          "</div>"+

          "<div class='mb-3'>"+
          "<label for='dialogFormlname' class='form-label'>Last Name</label>"+
          "<input class='form-control' type='text' id='dialogFormlname' placeholder='"+data[0]['lname']+"' aria-label='default input example required>"+
          "</div>"+

          "<div class='mb-3'>"+
          "<label for='selCompany' class='form-label'>Company Name</label>"+
          "<select id='selCompany' style='width: 100%; z-index:9000;' data-dropdown-parent='#modal_edit'></select>"+
          "<input type='hidden' id='dialogFormcompanyID' value='"+data[0]['cid']+"'>"+ // hidden cid used in db update if company unchanged
          "</div>"+

          "<div class='mb-3'>"+
          "<label for='dialogFormcname' class='form-label'>Company Name</label>"+
          "<input class='form-control' type='text' id='dialogFormcname' placeholder='"+data[0]['cname']+"' aria-label='default input example>"+
          "<input type='hidden' id='dialogFormcompanyID' value='"+data[0]['cid']+"'>"+ // company name box doesn't show without this??
          "<input type='hidden' >"+ // company name box doesn't show without this??
          "</div>"
          );
          $("#editVisitorForm").attr("value",id); // sets the forms value to the visitor id so we know what to change when editing
          company = data[0]['cname'];
          console.info(data)},
          dataType:"json"}
        );

        /// When offcanvas has loaded initlise company search box
        $("#modal_edit").on('shown.bs.modal', () => {
          // Generates company search bar contents
          $("#selCompany").select2({
           ajax: {
            url: "ajax/getCompanyRecord.php",
            type: "post",
            data:{pid:id},
            dataType: 'json',
            delay: 250,
            data: function (params) {
             return {
               searchTerm: params.term // search term
             };
            },
            processResults: function (response) {
              return {
                 results: response
              };
            },
            cache: true
          },
           placeholder: company,
           language: {
             noResults: function(){
               return $("<a href='http://google.com/'>Visitor Not Found Create New...</a>");
             }
           }
         });
        });

  };

/// Delete button from table of visitors
  function deleteVisitor(key)
  {
    delete dictVisitors[key]; // removes the selected visitor from the array
  };

/// Intercept submit button
  function submitVisitors()
  {
    $('#list_visitors').val(JSON.stringify(dictVisitors)); // adds the list of visitors to a hidden value before form submition
    $('#list_hosts').val(JSON.stringify(dictHosts)); // adds the list of hosts to a hidden value before form submition
  };

/// Edits the vistor from offcanvas form
  function editConfirm()
  {
    //alert("You a winner");
    //alert($("#dialogFormfname").attr("placeholder"))
    visitorID = "#row"+$("#editVisitorForm").attr("value");
    pid = $("#editVisitorForm").attr("value"); // used in ajax update visitor request

    // Check if we need input value or placeholder
    if ($("#dialogFormfname").val() == "")
    {
      fname = $("#dialogFormfname").attr("placeholder"); // gets the current value for the first name from the input box
    }
    else
    {
      fname = $("#dialogFormfname").val(); // gets the current value for the first name from the input box
    };
    if ($("#dialogFormlname").val() == "")
    {
      lname = $("#dialogFormlname").attr("placeholder"); // gets the current value for the last name from the input box
    }
    else
    {
      lname = $("#dialogFormlname").val(); // gets the current value for the last name from the input box
    };
    if ($("#selCompany").text() == "")
    {
      cname = $("#selCompany").next().find('.select2-selection__placeholder').text(); // if no new company selected uses the placeholder i.e original cname
      cnameID = $("#dialogFormcompanyID").val();
    }
    else
    {
      cname = $("#selCompany").text(); // grabs the new text name from the select box (val is the cid)
      cnameID = $("#selCompany").val();
    };
    $(visitorID+" .fname").text(fname); // changes the first name in the table on the main page
    $(visitorID+" .lname").text(lname); // changes the last name in the table on the main page
    $(visitorID+" .cname").text(cname); // changes the company name in the table on the main page

    $.ajax({
      url:"ajax/updateVisitor.php",
      type: "post",
      data:{pid:pid, fname:fname, lname:lname, cname:cnameID},
      success:function(data){
          console.info(data)}
        });

    modal_close(); // closes (clears form) the edit modal
  };

/// Close edit modal form button
  function modal_close()
  {
    $("#editVisitorForm").empty();
  };

  </script>
</head>
<body>
<div class="box">

  <?php include("shared/menu.php") ?>

  <div class="content p-3">
    <h1 class="display-3">PreBook Visitors</h1>
    <form method="post" action="prebook_success.php" class="needs-validation">
      <div class="row justify-content-end">
        <div class="col-2">
          <button type="submit" class="btn btn-secondary" onclick="submitVisitors()">
            <i class="bi bi-check-square"></i> PreBook Visit
          </button>
        </div>
      </div>
      <div class="row">
        <div class="col">
        <fieldset class="border rounded-3 p-3 border-dark" >
          <legend class="float-none w-auto px-3 text-start">Visitation Details</legend>
          <div class="row">
            <div class="p-0 col">
              <label for="location" class="form-label">Visitation Name</label>
            </div>
          </div>
            <div class="row">
              <div class="p-0 col">
                <input type="text" name="visitname" class="form-control" value="<?php echo($_SESSION['fname']." ".$_SESSION['lname']." Visitation Created ".date("d/m/Y")); ?>" required/>
              </div>
            </div>
            <div class="row">
              <div class="p-2 col">
                <label class="form-label">Visitation Date & Time</label>
              </div>
            </div>
            <div class="row">
              <div class="p-2 col">
                <label for="sdate" class="form-label">Start Date:</label>
              </div>
              <div class="p-0 col d-flex">
                <input type="date" id="sdate" name="sdate" class="form-control"/>
              </div>
              <div class="p-2 col">
                <label for="stime" class="form-label">Start Time:</label>
              </div>
              <div class="p-0 col d-flex">
                <input type="time" id="stime" name="stime" class="form-control"/>
              </div>
            </div>
            <div class="row">
              <div class="p-2 col">
                <label for="edate" class="form-label">End Date:</label>
              </div>
              <div class="p-0 col d-flex">
                <input type="date" id="edate" name="edate" class="form-control"/>
              </div>
              <div class="p-2 col">
                <label for="etime" class="form-label">End Time:</label>
              </div>
              <div class="p-0 col d-flex">
                <input type="time" id="etime" name="etime" class="form-control"/>
              </div>
            </div>
            <div class="row">
              <div class="p-2 col">
                <label for="location" class="form-label">Visit Location</label>
              </div>
            </div>
            <div class="row">
              <div class="p-0 col">
                <select class="form-select" name="location" placeholder="Visit Name">
                  <option>Meeting Room 1</option>
                  <option>Meeting Room 2</option>
                  <option>Meeting Room 3</option>
                </select>
              </div>
            </div>
        </fieldset>
      </div>

      <div class="col d-flex align-items-stretch">
        <fieldset class="border rounded-3 p-3 border-dark w-100">
          <legend class="float-none w-auto px-3 text-start">Host Details</legend>
          <div class="row">
            <div class="p-0 col">
              <label for="selHost" class="form-label">Select Hosts</label>
            </div>
          </div>
          <div class="row">
            <div class="p-0 col d-flex">
              <select id="selHost" class="form-select"></select>
            </div>
          </div>
            <div class="row p-1">
              <div class="col">
                <!--- START Host card div table -->
                <div class="row justify-content-start" id="hostsTable">
                </div>
                <!--- END Host card div table -->
                <input type="hidden" name="hosts" id="list_hosts" value=""/>
              </div>
            </div>
          </div>
        </fieldset>
      </div>

      <div class="row">
        <div class="col">
          <fieldset class="border rounded-3 p-3 border-dark">
            <legend class="float-none w-auto px-3 text-start">Visitor Details</legend>
            <div class="row">
              <div class="col">
                <label for="selUser" class="form-label">Select Visitors</label>
              </div>
            </div>
            <div class="row">
              <div class="p-0 col d-flex">
                <select id="selUser" class="form-select"></select>
              </div>
              <div class="row">
                <div class="col">
                  <table id="vistorsTable" class="table table-striped">
                    <thead>
                      <tr>
                        <th>
                          First Name
                        </th>
                        <th>
                          Last Name
                        </th>
                        <th>
                          Company Name
                        </th>
                        <th>
                          Edit Visitor
                        </th>
                        <th>
                          Delete Visitor
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <input type="hidden" name="visitors" id="list_visitors" value=""/>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </fieldset>
        </div>
      </div>
    </form>


      <div class="modal fade" id="modal_edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="modal_close()"></button>
            </div>
            <div class="modal-body" id="modalbody">
              <form id="editVisitorForm" class="needs-validation">
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="modal_close()">Close</button>
              <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="editConfirm()">Save changes</button>
            </div>
          </div>
        </div>
    </div>

  </div>

</div>
<!-- Bootstrap java file  -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
</body>
</html>
