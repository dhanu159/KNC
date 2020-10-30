$(document).ready(function () {

    $('#manageTable').DataTable();

    $('.only-decimal').keypress(function (event) {
        return isNumber(event, this)
    });


});

// THE SCRIPT THAT CHECKS IF THE KEY PRESSED IS A NUMERIC OR DECIMAL VALUE.
function isNumber(evt, element) {
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (
        (charCode != 46 || $(element).val().indexOf('.') != -1) && // “.” CHECK DOT, AND ONLY ONE.
        (charCode < 48 || charCode > 57) &&
        (charCode != 13)) {
        return false;
    }
    return true;
}

// Preloader
var preLoader = document.getElementById('Preloader');

function Preloader() {
    preLoader.style.display = 'none';
}

// Popup Notification Message
toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": false,
    "progressBar": false,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
}


//Initialize Select2 Elements
$('.select2').select2()

//Initialize Select2 Elements
$('.select2bs4').select2({
    theme: 'bootstrap4'
})

//Date picker
$('#dtReceivedDate').datetimepicker({
    format: 'L',
    maxDate: new Date()
});


// Alerts
function arcadiaConfirmAlert(message, event, isRefreshPage) {

    Swal.fire({
        title: 'Are you sure?',
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, submit it!'
    }).then((result) => {
        if (result.isConfirmed) {
            var result = event();

            // if (result == true) {
            //     Swal.fire(
            //         'Succeeded !',
            //         'Your request has been successfully processed.',
            //         'success'
            //     ).then((res) => {
            //         if (res.isConfirmed || res.dismiss) {
            //             location.reload();
            //         }
            //     })
            // }else{
            //     toastr["error"]("GRN Saving Error !");
            // }
          
        }
    })
}

function arcadiaSuccessMessage(isRefreshPage = false){
    Swal.fire(
        'Succeeded !',
        'Your request has been successfully processed.',
        'success'
    ).then((res) => {
        if (res.isConfirmed || res.dismiss) {
            if (isRefreshPage == true) {
                location.reload();
            }
        }
    })
}


function arcadiaErrorMessage(Msg,isRefreshPage = false){
    Swal.fire(
        'Something went wrong !',
        Msg,
        'error'
    ).then((res) => {
        if (res.isConfirmed || res.dismiss) {
            if (isRefreshPage == true) {
                location.reload();
            }
        }
    })
}


