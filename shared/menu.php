<?php $img_path = isset($_SESSION['photo']) ? "data:image/png;base64,".$_SESSION['photo']: "images/pro.png";
?>
<nav class="navbar navbar-dark bg-dark">
  <div class="d-flex justify-content-start">
    <a class="navbar-brand" href="#">
      <img src="images/logo_entry.svg" height="30" class="d-inline-block align-top" alt="">
      <!---vEntree VMS FoxEntree VMS--->
    </a>
  </div>
  <div class="d-flex justify-content-end">
    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="offcanvas offcanvas-end text-bg-dark" tabindex="-1" id="offcanvasDarkNavbar" aria-labelledby="offcanvasDarkNavbarLabel">

      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasDarkNavbarLabel">Select an action</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <ul class="navbar-nav justify-content-end flex-grow-1 pe-0">
          <li class="nav-item">
            <div class="d-flex flex-row mb-3"><div class="p-1"><img src='<?php echo($img_path) ?>'/></div><div class="p-1"><div class="p-1"><a class="btn btn-secondary btn-sm" role="button" href="dashboard.php"><i class="bi bi-clipboard-data"></i> My Dashboard</a></div><div class="p-1"><a class="btn btn-secondary btn-sm" role="button" href="login/logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></div></div></div>
          </li>
          <hr>
          <li class="nav-item">
            <a class="nav-link active" href="prebook.php"><div class="d-flex flex-row mb-0"><div class="p-1"><i class="bi bi-person-plus-fill menu-icon"></i></div><div class="mt-auto p-1"><p>Prebook Visitor</p></div></div></a>
          </li>
          <hr>
          <li class="nav-item">
            <a class="nav-link active" href="managment.php?action=company"><div class="d-flex flex-row mb-0"><div class="p-1"><i class="bi bi-buildings menu-icon"></i></div><div class="mt-auto p-1"><p>Company Managment</p></div></div></a>
          </li>
          <hr>
          <li class="nav-item">
            <a class="nav-link active" href="managment.php?action=visitor"><div class="d-flex flex-row mb-0"><div class="p-1"><i class="bi bi-person-vcard menu-icon"></i></div><div class="mt-auto p-1"><p>Visitor Managment</p></div></div></a>
          </li>
          <hr>
          <li class="nav-item">
            <a class="nav-link active" href="managment.php?action=host"><div class="d-flex flex-row mb-0"><div class="p-1"><i class="bi bi-people menu-icon"></i></div><div class="mt-auto p-1"><p>Host Managment</p></div></div></a>
          </li>
          <hr>
          <li class="nav-item">
            <a class="nav-link active" href="managment.php?action=visitation"><div class="d-flex flex-row mb-0"><div class="p-1"><i class="bi bi-airplane menu-icon"></i></div><div class="mt-auto p-1"><p>Visitation Managment</p></div></div></a>
          </li>
          <hr>
          <li class="nav-item">
            <a class="nav-link active" href="expected.php"><div class="d-flex flex-row mb-0"><div class="p-1"><i class="bi bi-calendar3 menu-icon"></i></div><div class="mt-auto p-1"><p>Expected Visitors</p></div></div></a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</nav>
