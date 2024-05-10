function editVisitor(id, vid)
{
  var company = "";
  $.ajax({
    url:"ajax/getVisitorRecord.php",
    type: "post",
    data:{pid:id, vid:vid},
    success:function(data){
      $("#editVisitorForm").append(
        "<div class='img-container'>"+
          "<img width=60 class='rounded-3' id='modal_vistor_photo_"+data[0]['visitorid']+"' src='data:image/png;base64,"+data[0]['photo']+"'/>"+
          "<input type='hidden' name='base64_str' id='base64_str' value="+data[0]['photo']+"/>"+
          "<input type='hidden' id='dialogFormphoto' value='"+data[0]['photo']+"'>"+ // hidden base64 str used in db update if company unchanged
          "<a href='' data-bs-toggle='modal' data-bs-target='#modal_photo' data-val='"+data[0]['visitorid']+"' aria-controls='offcanvasBottom'>"+
            "<div class='overlay rounded-3'>"+
              "<span class='text-over-img'>Take Photo</span>"+
            "</div>"+
          "</a>"+
        "</div>"+

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

        "<div class='mb-3 print-qr'>"+
        "<img width=60 class='rounded-3' id='qrcode' src='data:image/png;base64,"+data[0]['qr']+"'/>"+
        "</div>"
        );
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
         placeholder: data[0]['cname'],
         language: {
           noResults: function(){
             return $("<a href='http://google.com/'>Visitor Not Found Create New...</a>");
           }
         }
       });
        $("#editVisitorForm").attr("value",id); // sets the forms value to the visitor id so we know what to change when editing
        company = data[0]['cname'];
        console.info(data)},
        dataType:"json"}
      );
};

/// Edits the vistor from offcanvas form
  function editConfirm()
  {
    //alert("You a winner");
    //alert($("#dialogFormfname").attr("placeholder"))
    visitorID = "#row"+$("#editVisitorForm").attr("value");
    pid = $("#editVisitorForm").attr("value"); // used in ajax update visitor request
    photo = $("#dialogFormphoto").val(); // used in ajax update visitor request

    console.log(photo);

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

    modal_edit_close(); // closes (clears form) the edit modal
  };

/// Close edit modal form button
  function modal_edit_close()
  {
    $("#editVisitorForm").empty();
  };
