
<!--
Below need to be added to php that needs the visitor editing modal
<script src="shared/modal_edit_visitor.js" charset="utf-8"></script>
include("shared/modal_edit_visitor.php")
-->

<!--- Edit visitor interface popup window --->

<div class="modal fade" id="modal_edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-fullscreen">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="modal_edit_close()"></button>
      </div>
      <div class="modal-body" id="modalbody">
        <form id="editVisitorForm" class="needs-validation">
        </form>
      </div>
      <div class="modal-footer">
        <button id="print" type="button" class="btn btn-secondary">Print QR Code</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="modal_edit_close()">Close</button>
        <button type="button" class="btn btn-selCompany" data-bs-dismiss="modal" onclick="editConfirm()">Save changes</button>
      </div>
    </div>
  </div>
</div>
<script src="shared/printThis.js" charset="utf-8"></script>
<script>
$('#print').on("click", function () {
$('.print-qr').printThis({
 base: ""
});
});
</script>
