$.easyAjax = (options) => {
    var defaults = {
        type: 'GET',
        container: 'body',
        blockUI: true,
        disableButton: false,
        buttonSelector: "[type='submit']",
        dataType: "json",
        messagePosition: "toastr",
        errorPosition: "field",
        hideElements: false,
        redirect: true,
        data: {},
        file: false,
        onComplete: false,
    };

    var opt = defaults;

    // Extend user-set options over defaults
    if (options) {
        opt = $.extend(defaults, options);
    }

    if (opt.disableButton !== false) {
        $(opt.container).find(opt.buttonSelector).addClass('disabled');
    }

    if (opt.blockUI !== false) {
        $(opt.container).addClass('disabled-block');
    }

    $(opt.container).find(".is-invalid").removeClass("is-invalid");

    axios({
        method: opt.type,
        url: opt.url,
        data: opt.data,
        headers: {
            "Content-Type": opt.file ? "multipart/form-data" : "application/json",
        },
        onUploadProgress: (e) => {

        }
    }).then((response) => {
        if (response.data.message !== '')


            if (response.data.message !== '')
                toastr.success(response.data.message, '', {timeOut: 3000})


        if (opt.onComplete && typeof opt.onComplete === "function") {
            opt.onComplete(response);
        }

        if (opt.disableButton !== false) {
            $(opt.container).find(opt.buttonSelector).removeClass('disabled');
        }

        if (opt.blockUI !== false) {
            $(opt.container).removeClass('disabled-block');
        }

        window.dispatchEvent(new CustomEvent('button-loading', {
            detail: {id: $(opt.container).find(opt.buttonSelector).attr('id'), state: false}
        }));
    }).catch((error) => {
            console.log('error...');
            //emit failed event etc...

            var response = error.response;


            if (response.data.errors !== undefined) {
                var keys = Object.keys(response.data.errors);

                var errors = response.data.errors;

                $(opt.container).find(".is-invalid").removeClass("is-invalid");

                for (var i = 0; i < keys.length; i++) {
                    // Escape dot that comes with error in array fields
                    var key = keys[i].replace(".", '\\.');
                    var formarray = keys[i];
                    // If the response has form array
                    if (formarray.indexOf('.') > 0) {
                        var array = formarray.split('.');
                        errors[keys[i]] = errors[keys[i]];
                        key = array[0] + '[' + array[1] + ']';
                    }

                    var ele = $(opt.container).find("[name='" + key + "']");

                    // If cannot find by name, then find by id
                    if (ele.length == 0) {
                        ele = $(opt.container).find("#" + key);
                    }

                    var grp = ele.closest(".form-group");
                    ele.addClass('is-invalid');
                    grp.find('.invalid-feedback').html(errors[keys[i]])
                }

                if (keys.length > 0) {
                    var element = $("[name='" + keys[0] + "']");
                    if (element.length > 0) {
                        $("html, body").animate({scrollTop: element.offset().top - 150}, 200);
                    }
                    element.focus();
                }
            } else {
                showErrorModal(response.data || "An unexpected error occurred!");


                toastr.error('Something went wrong! Please reload', '', {timeOut: 3000})
            }

            if (opt.disableButton !== false) {
                $(opt.container).find(opt.buttonSelector).removeClass('disabled');
            }

            if (opt.blockUI !== false) {
                $(opt.container).removeClass('disabled-block');
            }

            window.dispatchEvent(new CustomEvent('button-loading', {
                detail: {id: $(opt.container).find(opt.buttonSelector).attr('id'), state: false}
            }));
        }
    );
}

// Function to show a Livewire-style error popup
// Function to show a Bootstrap error modal
function showErrorModal(errorResponse) {
    let modal = $('#error-modal');

    if (modal.length === 0) {
        $('body').append(`
            <div class="modal  fade" id="error-modal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content ">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title">Error</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div id="error-modal-message" class="text-danger"></div>
                        </div>

                    </div>
                </div>
            </div>
        `);
        modal = $('#error-modal');
    }

    let messageContainer = $('#error-modal-message');

    // Evaluate the error response
    if (typeof errorResponse === 'string') {
        // If the response is plain text
        messageContainer.html(errorResponse);
    } else if (typeof errorResponse === 'object') {
        // If the response is JSON
        let formattedMessage = "";

        if (errorResponse.message) {
            formattedMessage += `<strong>${errorResponse.message}</strong><br>`;
        }

        if (errorResponse.errors) {
            formattedMessage += "<ul>";
            Object.values(errorResponse.errors).forEach(messages => {
                messages.forEach(msg => {
                    formattedMessage += `<li>${msg}</li>`;
                });
            });
            formattedMessage += "</ul>";
        }

        messageContainer.html(formattedMessage);
    } else {
        // If unknown type, just display as text
        messageContainer.text("An unexpected error occurred!");
    }

    let bootstrapModal = new bootstrap.Modal(modal[0]);
    bootstrapModal.show();
}
