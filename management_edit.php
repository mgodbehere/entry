<?php
include("login/active.php");
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
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.css" rel="stylesheet" />
  <?php
  /// checks if a post request has been made from the form on the page then processes it
  if($_SERVER['REQUEST_METHOD'] == 'POST')
  {
    if(!isset($_POST['btn_del']))
    {
      include("ajax/config.php");
      switch($_GET['type'])
      {
        case "host":
          $table = "users";
          $col = ["username", "firstname", "lastname", "password"];
          $uid = "uid";
          $_POST['pass'] = $_POST['pass'] != ""? password_hash($_POST['pass'], PASSWORD_DEFAULT) : get_record($table, $id, $uid)['password']; // here we check if the password has been edited if not we get the current hashed password, otherwise we hash the new password
          break;

        case "company":
          $table = "company";
          $col = ["name"];
          $uid = "cid";
          break;

        case "visitation":
          $table = "visitation";
          $col = ["name", "date_start", "date_end", "location", "time_start", "time_end", "list_hosts", "list_visitors"];
          $uid = "vid";
          break;

        default:
          $table = "people";
          $col = ["firstname", "lastname", "cid"];
          $uid = "pid";
          break;
      }
      $id = $_GET['id'];

      $type = $_POST['type']; // holding type here so we have referece after we delete it
      unset($_POST['type']); // deleting type so when we loop array we don't have extra value

      if(isset($_POST['cname'])) // if cname exists then we don't want to use the placeholder value used for visitors
      {
        unset($_POST['placeholder_company']);
      }

      $i = 0; // used to get item from array

      foreach($_POST as $item)
      {
        $col[$i] = $col[$i]."='".$item."'";
        $i++;
        //echo($item."<br>");
      }
      $s = join(",", $col);

      mysqli_query($con, "UPDATE $table SET $s WHERE $uid = '$id'");
    }
    else
    {
      include("ajax/config.php");
      // Only for deleting visiations at the momenet
      $id = $_GET['id'];
      mysqli_query($con, "UPDATE visitation SET valid=0 WHERE vid = '$id'");
      header("Location:managment.php?action=visitation");
    }
  }

  // Generate form dependant on url defaults to visitor form if problem with url
  if(isset($_GET['type']))
  {
    switch($_GET['type'])
    {
      case "company":
        $tab1_name = "Edit Company";
        $tab2_name = "Visitors";
        $table = id_map($_GET['type'])['table'];
        $id = $_GET['id'];
        $uid = id_map($_GET['type'])['id'];
        $data = get_record($table, $id, $uid);
        $form = '<div class="row">
                  <div class="col">
                    <label for="cname" class="form-label">Company Name</label>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <input type="text" name="cname" class="form-control" value="'.$data['name'].'" required/><input type="hidden" name="type" value="company"/>
                  </div>
                </div>';
        $tab2 = "";

        // get data for tab2
        $d = get_record_object("people", $id, "cid");
        while($row = mysqli_fetch_array($d))
        {
          $tab2 = $tab2 . $row['firstname'] . "<br />";
        }
        break;
      case "host":
        $tab1_name = "Edit Users";
        $tab2_name = "Visitations";
        $table = id_map($_GET['type'])['table'];
        $id = $_GET['id'];
        $uid = id_map($_GET['type'])['id'];
        $data = get_record($table, $id, $uid);
        $form = '<div class="row">
                  <div class="col">
                    <label for="uname" class="form-label">Username</label>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <input type="text" name="uname" class="form-control" value="'.$data['username'].'" required/>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <label for="fname" class="form-label">First Name</label>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <input type="text" name="fname" class="form-control" value="'.$data['firstname'].'" required/>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <label for="lname" class="form-label">Last Name</label>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <input type="text" name="lname" class="form-control" value="'.$data['lastname'].'" required/>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <label for="pass" class="form-label">Password</label>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <input type="password" name="pass" class="form-control" value=""/><input type="hidden" name="type" value="host"/>
                  </div>
                </div>';
        $tab2 = "";
        break;
      case "visitation":
        $tab1_name = "Edit Visitation";
        $tab2_name = "Visitors";
        $table = id_map($_GET['type'])['table'];
        $id = $_GET['id'];
        $uid = id_map($_GET['type'])['id'];
        $data = get_record($table, $id, $uid);
        $btn_del = '<button type="submit" class="btn btn-secondary" value="del" name="btn_del">Delete</button>';
        $form = '<div class="row">
                  <div class="col">
                    <label for="cname" class="form-label">Visitation Name</label>
                  </div>
                  <div class="col-2">
                    <label for="cname" class="form-label">Start Date</label>
                  </div>
                  <div class="col-2">
                    <label for="cname" class="form-label">End Date</label>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <input type="text" name="vname" class="form-control" value="'.$data['name'].'" required/>
                  </div>
                  <div class="col-2">
                    <input type="date" name="date_start" class="form-control" value="'.$data['date_start'].'"/>
                  </div>
                  <div class="col-2">
                    <input type="date" name="date_end" class="form-control" value="'.$data['date_end'].'"/>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <label for="cname" class="form-label">Meeting Location</label>
                  </div>
                  <div class="col-2">
                    <label for="cname" class="form-label">Start Time</label>
                  </div>
                  <div class="col-2">
                    <label for="cname" class="form-label">End Time</label>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <input type="text" name="location" class="form-control" value="'.$data['location'].'"/>
                  </div>
                  <div class="col-2">
                    <input type="time" name="time_start" class="form-control" value="'.$data['time_start'].'"/>
                  </div>
                  <div class="col-2">
                    <input type="time" name="time_end" class="form-control" value="'.$data['time_end'].'"/>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <label for="cname" class="form-label">Hosts</label>
                  </div>
                  <div class="col">
                    <label for="cname" class="form-label">Visitors</label>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <input type="text" name="hosts" class="form-control" value="'.$data['list_hosts'].'" required/>
                  </div>
                  <div class="col">
                    <input type="text" name="visitors" class="form-control" value="'.$data['list_visitors'].'" required/><input type="hidden" name="type" value="visitation"/>
                  </div>
                </div>
                ';
        $tab2 = "";

        // get data for tab2
        $d = get_record_object("people", $id, "cid");
        while($row = mysqli_fetch_array($d))
        {
          $tab2 = $tab2 . $row['firstname'] . "<br />";
        }
        break;
      default:
        $tab1_name = "Edit Visitors";
        $tab2_name = "Visitations";
        $table = id_map($_GET['type'])['table'];
        $id = $_GET['id'];
        $uid = id_map($_GET['type'])['id'];
        $data = get_record($table, $id, $uid);
        $companyName = get_record("company", $data['cid'], "cid");
        $form = '<div class="row">
                  <div class="col">
                    <label for="fname" class="form-label">First Name</label>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <input type="text" name="fname" class="form-control" value="'.$data['firstname'].'" required/>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <label for="lname" class="form-label">Last Name</label>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <input type="text" name="lname" class="form-control" value="'.$data['lastname'].'" required/>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <label for="pass" class="form-label">Company</label>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <select name="cname" id="selCompany" style="width: 100%; z-index:9000;"></select>
                    <input type="hidden" name="type" value="visitor"/><input type="hidden" name="placeholder_company" value="'.$companyName['cid'].'"/>
                  </div>
                </div>';
        $tab2 = "";
        break;
    }
  }
  ?>
  <script>
  /// Start document ready
  $(document).ready(function(){
    /// Bootstrap configs
    $(function () {
        $('[data-toggle="tooltip"]').tooltip({placement: "right"})
    });

    $("#selCompany").select2({
     ajax: {
      url: "ajax/getCompanyRecord.php",
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
     placeholder: "<?php echo($companyName['name']) ?>",
     language: {
       noResults: function(){
         return $("<a href='http://google.com/'>Visitor Not Found Create New...</a>");
       }
     }
   });

   /// End document ready
  });
  </script>
</head>
<body>
<div class="box">

  <?php include("shared/menu.php") ?>

  <div class="content p-3">
    <h1>Edit & Manage</h1>

    </p>This is the management page for the host, visitor or company. It needs to contain a form so that the respective can be edit and the changes saved to the database, it will also need to have tab/s for showing all visitors associted with it for companies, and all visitations for visitors and hosts</p>

    <div class="row">
      <div class="col-12">
        <ul class="nav nav-tabs border-dark">
          <li class="nav-item">
            <a href="#home" class="nav-link active" data-bs-toggle="tab"><?php echo($tab1_name) ?></a>
          </li>
          <li class="nav-item">
            <a href="#profile" class="nav-link" data-bs-toggle="tab"><?php echo($tab2_name) ?></a>
          </li>
        </ul>
        <fieldset class="border border-top-0 rounded-bottom p-3 border-dark" >
          <div class="tab-content">

            <!--- TAB 1 --->
            <div class="tab-pane fade show active" id="home">
              <form action="<?php echo htmlentities($_SERVER['PHP_SELF']."?type=".$_GET['type'])."&id=".$_GET['id']; ?>" method="post" class="needs-validation">
                <?php echo($form); ?>
                <div class="row p-2 justify-content-end">
                  <div class="col-3">
                    <?php
                    $del = isset($btn_del) ? $btn_del: "";
                    echo($del);
                    ?>
                    <button type="submit" class="btn btn-secondary">Save Changes</button>
                  </div>
                </div>
              </form>
            </div>

            <!--- TAB 2 --->
            <div class="tab-pane fade" id="profile">
                <p><?php
                  echo($tab2);
                ?></p>
            </div>

          </div>
        </fieldset>
      </div>
    </div>


  </div>

</div>
<!-- Bootstrap java file  -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
</body>
</html>
