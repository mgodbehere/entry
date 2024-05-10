<?php
include("login/active.php"); // checks logged in status
include("ajax/config.php"); // gets sql connection details
 ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>MHF - EPOS</title>
  <!-- Bootstrap style sheet  -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
  <!-- Our style sheet  -->
  <link rel="stylesheet" href="css/main.css">
  <!-- Jquery java file  -->
  <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>

  <!-- Select2 style sheet & java file  -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <script>
  <?php
  // this detemines what page has been selected by looking at the url then it assigns that to javascript variable for processing
  if(isset($_GET['action']))
  {
    switch($_GET['action'])
    {
    case "host":
      echo("const type='host'");
      $page_title = "Host Management";
      break;
    case "visitation":
      echo("const type='visitation'");
      $page_title = "Visitation Management";
      break;
    case "company":
      echo("const type='company'");
      $page_title = "Company Management";
      break;
    default:
      echo("const type='visitor'");
      $page_title = "Visitor Management";
    }
  }
  else
  {
    echo("const type='none'");
    $page_title = "Visitor Management";
  }
   ?>
  // functions used in page
  // type is the table to be used in the request

  function ajax_query(query, type)
  {
    $.ajax({
      url:"ajax/getSearchRecordAll.php",
      type: "post",
      data:{search_str:query,search_type:type},
      success:function(data){
         console.log(data);displaySearch(data)},
          dataType:"json"}
        );
  }
  function test()
  {
    console.log("test");
    //header("Location: ./management_edit.php?id='"+id+"'&type='"+id+"'");
  }

  // takes sql query data then displays it
  function displaySearch(data)
  {
  //    $("#test").empty();
  //alert(type);
    $("#visitorsTable").find("tbody").empty();
    // depending on type format data 3 ways 1. like it is 2. change headings show company name only 3. change headings show first and last name

    // setup table headings for each page
    switch(data[0]['type'])
    {
      case "company":
        $("#visitorsTable").find("thead").empty();
        $("#visitorsTable").find("thead").append("<tr><th>Company Name</th><th>Edit Comapny</th></tr>");
        break;
      case "host":
        $("#visitorsTable").find("thead").empty();
        $("#visitorsTable").find("thead").append("<tr><th>Photo</th><th>Username</th><th>First Name</th><th>Last Name</th><th>Edit Host</th></tr>");
        break;
      case "visitation":
        $("#visitorsTable").find("thead").empty();
        $("#visitorsTable").find("thead").append("<tr><th>Name</th><th>Hosts</th><th>Visitors</th><th>Visit Date</th><th>Valid</th><th>Edit Vistation</th></tr>");
        break;
      default:
        $("#visitorsTable").find("thead").empty();
        $("#visitorsTable").find("thead").append("<tr><th>Photo</th><th>First Name</th><th>Last Name</th><th>Company Name</th><th>Edit Visitor</th></tr>");
        break;
    }
    for(i in data)
    {
      if(data[i]['type'] == 'company')
      {
        $("#visitorsTable").append(
          "<tr><td class='align-middle'>"+data[i]['name']+"</td><td class='align-middle'><a class='btn btn-light' href='management_edit.php?id="+data[i]['id']+"&type="+data[i]['type']+"' role='button'><i class='bi bi-pencil-square'></i> Edit</a></td>"+
          "</tr>"
        );
      }
      else if(data[i]['type'] == 'host')
      {
        $("#visitorsTable").append(
          "<tr><td class='align-middle'><img width=60 class='rounded-3' src='data:image/png;base64,"+data[i]['photo']+"'/></td><td class='align-middle'>"+data[i]['uname']+"</td><td class='align-middle'>"+data[i]['fname']+"</td><td class='align-middle'>"+data[i]["lname"]+"</td><td class='align-middle'><a class='btn btn-secondary' href='management_edit.php?id="+data[i]['id']+"&type="+data[i]['type']+"' role='button'><i class='bi bi-pencil-square'></i> Edit</a></td>"+
          "</tr>"
        );
      }
      else if(data[i]['type'] == 'visitation')
      {
        $("#visitorsTable").append(
          "<tr><td class='align-middle'>"+data[i]['name']+"</td><td class='align-middle'>"+data[i]['hosts']+"</td><td class='align-middle'>"+data[i]["visitors"]+"</td><td class='align-middle'>"+data[i]["date_start"]+"</td><td class='align-middle'>"+data[i]["valid"]+"</td><td class='align-middle'><a class='btn btn-secondary' href='management_edit.php?id="+data[i]['id']+"&type="+data[i]['type']+"' role='button'><i class='bi bi-pencil-square'></i> Edit</a></td>"+
          "</tr>"
        );
      }
      else
      {
        console.log(data[i]);
        $("#visitorsTable").append(
          "<tr><td class='align-middle'><img width=60 class='rounded-3' src='data:image/png;base64,"+data[i]['photo']+"'/></td><td class='align-middle'>"+data[i]['fname']+"</td><td class='align-middle'>"+data[i]["lname"]+"</td><td class='align-middle'>"+data[i]["cname"]+"</td><td class='align-middle'><a class='btn btn-secondary' href='management_edit.php?id="+data[i]['id']+"&type="+data[i]['type']+"' role='button'><i class='bi bi-pencil-square'></i> Edit</a></td>"+
          "</tr>"
        );
      }
    }
  }
  /// Start document ready
  $(document).ready(function(){
    /// Bootstrap configs
    $(function () {
        $('[data-toggle="tooltip"]').tooltip({placement: "right"})
    });

    console.log(type);
    ajax_query("",type);

   /// End document ready
  });

  // search box ajax query
  function search(string)
  {
    // if no input is in the search box we do nothing
    if(string.length == 0)
    {
      // when all text deleted from search box we show all results
      ajax_query("",type);
      return;
    }
    else
    {
      // send out the query via ajax
      ajax_query(string,type);
    }
  }
  </script>
</head>
<body>
<div class="box">

  <?php include("shared/menu.php") ?>
  <div class="content p-3">
    <h1 class="display-3"><?php echo($page_title) ?></h1>
    <br />

    <div class="row">
      <div class="col-8">
      <fieldset class="border rounded-3 p-3 border-dark" >
        <legend class="float-none w-auto px-3 text-start">Filter Results</legend>
        <div class="row">
          <div class="p-2 col">
            <label for="search_bar" class="form-label">Search Box</label>
          </div>
        </div>
          <div class="row">
            <div class="p-0 col">
              <input class="form-control" type="text" id="search" name="search_bar" onkeyup="search($(this).val())"/>
            </div>
          </div>
      </fieldset>
    </div>

    <div class="col d-flex align-items-stretch">
      <fieldset class="border rounded-3 p-3 border-dark w-100">
        <legend class="float-none w-auto px-3 text-start">Add New</legend>
          <div class="row">
            <div class="col">
              <a class='btn btn-secondary' href='management_create.php?type=company' role='button'><i class="bi bi-plus"></i> Add Company</a>
              <a class='btn btn-secondary' href='management_create.php?type=visitor' role='button'><i class="bi bi-plus"></i> Add Visitor</a>
            </div>
          </div>
      </fieldset>
    </div>
  </div>

    <div class="row">
      <div class="col">
        <fieldset class="border rounded-3 p-3 border-dark">
          <legend class="float-none w-auto px-3 text-start">Search Results</legend>
          <table id="visitorsTable" class="table table-secondary table-striped">
            <thead>
            </thead>
            <tbody>
              <input type="hidden" name="hosts" id="list_hosts" value=""/>
            </tbody>
          </table>
        </fieldset>
      </div>
    </div>
  </div>

</div>
<!-- Bootstrap java file  -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
</body>
</html>
