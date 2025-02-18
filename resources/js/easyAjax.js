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
        $(opt.buttonSelector).addClass('disabled');
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
            $(opt.buttonSelector).removeClass('disabled');
        }

        if (opt.blockUI !== false) {
            $(opt.container).removeClass('disabled-block');
        }
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
                toastr.error('Something went wrong! Please reload', '', {timeOut: 3000})
            }

            if (opt.disableButton !== false) {
                $(opt.buttonSelector).removeClass('disabled');
            }

            if (opt.blockUI !== false) {
                $(opt.container).removeClass('disabled-block');
            }
        }
    );
}
