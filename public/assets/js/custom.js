// Setup CSRF token for all AJAX requests
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Toastrs function for notifications
function toastrs(title, message, type) {
    if (typeof toastr !== 'undefined') {
        toastr[type](message, title);
    } else {
        alert(title + ': ' + message);
    }
}

// Modal functionality
$(document).on('click', 'a[data-ajax-popup="true"], button[data-ajax-popup="true"], div[data-ajax-popup="true"]', function () {
    var title = $(this).data('title');
    var size = ($(this).data('size') == '') ? 'md' : $(this).data('size');
    var url = $(this).data('url');
    $("#commonModal .modal-title").html(title);
    $("#commonModal .modal-dialog").addClass('modal-' + size);
    $.ajax({
        url: url,
        success: function (data) {
            $('#commonModal .body').html(data);
            $("#commonModal").modal('show');
        },
        error: function (xhr) {
            var errorMsg = xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error : 'An error occurred';
            toastrs('Error', errorMsg, 'error');
        }
    });
});