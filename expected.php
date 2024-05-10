<?php
include("login/active.php");
include("ajax/config.php");
include("shared/functions.php");
?>
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



  <!--- START webcamera style and script --->
    <style>
    #video_container {
      margin: 0px auto;
      width: 500px;
      height: 375px;
      border: 10px #333 solid;
    }
    #videoElement {
      width: 500px;
      height: 375px;
      background-color: #666;
    }
    </style>
    <script src="shared/camera_functions.js" charset="utf-8"></script>
  <!--- END webcamera style and script --->

  <script>
  function ajax_query(query, type)
  {
    $.ajax({
      url:"ajax/getExpected.php",
      type: "post",
      data:{search_str:query,search_type:type},
      success:function(data){
         console.log(data);displaySearch(data)},
          dataType:"json"}
        );
  }
  // takes sql query data then displays it
  function displaySearch(data)
  {
//    $("#test").empty();
//alert(type);
    $("#visitorsTable").find("tbody").empty();
    // depending on type format data 3 ways 1. like it is 2. change headings show company name only 3. change headings show first and last name

    for(i in data)
    {
        var btn_check_in = "<button class='btn btn-secondary d-none' id='btn_check_"+data[i]['id']+"' type='button' onclick=checkin('"+data[i]['vid']+"','"+data[i]['id']+"',1)><i class='bi bi-trash3'></i> CheckIn</button>";
        var btn_check_out = "<button class='btn btn-secondary d-none' id='btn_check_out_"+data[i]['id']+"' type='button' onclick=checkin('"+data[i]['vid']+"','"+data[i]['id']+"',0)><i class='bi bi-trash3'></i> CheckOut</button>";
        var btn_edit_vis = "<button type='button' class='btn btn-secondary' onclick=editVisitor('"+data[i]['id']+"','"+data[i]['vid']+"') data-bs-toggle='modal' data-bs-target='#modal_edit'><i class='bi bi-pencil-square'></i> Edit</button>";
        var btn_photo = ""+
            "<div class='img-container'>"+
              "<img width=60 class='rounded-3' id='vistor_photo_"+data[i]['id']+"' src='data:image/png;base64,"+data[i]['photo']+"'/>"+
              "<input type='hidden' name='base64_str' id='base64_str' value="+data[i]['photo']+"/>"+
              "<a href='' data-bs-toggle='modal' data-bs-target='#modal_photo' data-val='"+data[i]['id']+"' aria-controls='offcanvasBottom'>"+
                "<div class='overlay rounded-3'>"+
                  "<span class='text-over-img'>Take Photo</span>"+
                "</div>"+
              "</a>"+
            "</div>";

          $("#visitorsTable").append(
            "<tr>"+
            "<td class='align-middle'>"+btn_photo+"</td><td class='align-middle'>"+data[i]['fname']+"</td><td class='align-middle'>"+data[i]['lname']+"</td><td class='align-middle'>"+data[i]['cname']+"</td><td class='align-middle'>"+data[i]['vname']+"</td><td class='align-middle'><span id='visitor_state_"+data[i]['id']+"'>"+data[i]['state']+"</span></td>"+
            "<td class='align-middle'>"+btn_check_in + btn_check_out+"</td><td class='align-middle'>"+btn_edit_vis+"</td>"+
            "</tr>"
          );
          show_check_btn(data[i]['id'], data[i]['state']);
    }
  }
  /// Start document ready
  var photo_pid // global variable set when photo is clicked and used in save function
  $(document).ready(function(){
    /// Bootstrap configs
    $(function () {
        $('[data-toggle="tooltip"]').tooltip({placement: "right"});

        $('#modal_photo').on('shown.bs.modal', function () {
          startup();
        });

         // setting up default dates for inputs
         const d = new Date();
         const year = d.getFullYear();
         const month = ("0" + (d.getMonth()+1)).slice(-2);
         const day = d.getDate().toString().padStart(2, "0");;

        ajax_query(year +"-"+ month +"-"+ day,"date")
    });

    /// Grabs pid when photo modal is open then stores it in a varible for use when updating photo on page and in db
    $('#modal_photo').on('show.bs.modal', function (event) {
      photo_pid = $(event.relatedTarget).data('val');
      //alert(photo_pid);
    });

   /// End document ready
  });
  const type = "visitor";
  // search box ajax query
  function search(string, search_type)
  {
    switch(search_type)
    {
      case "date":
        ajax_query(string, search_type);
        break;
      default:
        // if no input is in the search box we do nothing
        if(string.length == 0)
        {
          // when all text deleted from search box we show all results
          ajax_query("", search_type);

        }
        else
        {
          // send out the query via ajax
          ajax_query(string, search_type);
        }
    }
  }

  function search_date(date)
  {
    ajax_query("", "");
  }

  function checkin(vid, pid, type)
  {
    console.log(vid);
    console.log(pid);
    console.log(type);
    $.ajax({
      url:"ajax/checkinVisitor.php",
      type: "post",
      data:{type:type, vid:vid, pid:pid},
      success:function(data){
          console.info(data)}
        });
    //type determines if button should switch back to previous
    show_check_btn(pid, type);
  }

  function show_check_btn(pid, state)
  {
    if(state == "1")
    {
      $("#btn_check_"+pid).addClass('d-none');
      $("#btn_check_out_"+pid).removeClass('d-none');
      $("#visitor_state_"+pid).text("1");
    }
    else
    {
      $("#btn_check_out_"+pid).addClass('d-none');
      $("#btn_check_"+pid).removeClass('d-none');
      $("#visitor_state_"+pid).text("0");
    }
  }

  function modal_close()
  {
    stop();
  }

  function modal_save()
  {
    stop();
    //alert($("#base64_str").val());
    const photo_data = $("#base64_str").val(); // grabs the base64 string from the hidden input in the modal

    //console.log(photo_data);
    $('#vistor_photo_'+photo_pid).attr("src", photo_data); // set the preview photo to the captured one
    $('#modal_vistor_photo_'+photo_pid).attr("src", photo_data); // set the preview photo to the captured one
    $.ajax({
      url:"ajax/checkinVisitor.php",
      type: "post",
      data:{type:"photo",pid:photo_pid,photo_data:photo_data},
      success:function(data){
          console.info(data)}
        });
  }

  </script>
</head>
<body>
<div class="box">

  <?php include("shared/menu.php") ?>

  <div class="content">
    <h1 class="display-3">Expected Visitors</h1>
    <br />
    <label for="search_date">Search Date</label>
    <input type="date" id="search_date" name="search_date" onchange="search($(this).val(), 'date')"/>
    <br />

    <table id="visitorsTable" class="table table-striped">
      <thead>
        <tr>
          <th>Photo</th>
          <th>First Name</th>
          <th>Last Name</th>
          <th>Company Name</th>
          <th>Visit ID</th>
          <th>State</th>

        </tr>
      </thead>
      <tbody>
        <input type="hidden" name="hosts" id="list_hosts" value=""/>
      </tbody>
    </table>

  <!--- Camera interface popup window --->
    <div class="modal fade" id="modal_photo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-fullscreen">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" data-bs-target='#modal_edit' onclick="modal_close()"></button>
          </div>
          <div class="modal-body" id="modalbody">
            <h1>
              Video Capture
            </h1>
          <div id="video_container" style="width:600px; height:600px">
            <video autoplay="true" id="videoElement">
            Allow webcamera when prompted.
            </video>
            <button id="startbutton" class="btn btn-primary">Take photo</button>
            <button id="clearbutton" class="btn btn-primary">Clear photo</button>
          </div>
          <canvas id="canvas">
          </canvas>

          <div class="output">
            <img id="photo" alt="The screen capture will appear in this box.">
          </div>

          </div>
          <div class="modal-footer">
            <button type='button' class='btn btn-secondary' data-bs-toggle='modal' onclick='modal_close()' data-bs-target='#modal_edit'>Discard & Go Back</button>
            <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target='#modal_edit' onclick="modal_save()">Save & Continue</button>
          </div>
        </div>
      </div>
    </div>

  <!--- Edit visitor interface popup window --->
  <script src="shared/modal_edit_visitor.js" charset="utf-8"></script>

  <?php include("shared/modal_edit_visitor.php") ?>

  </div>

</div>
<!-- Bootstrap java file  -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
</body>
</html>
