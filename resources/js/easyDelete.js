$.easyDelete = (options) => {
    var defaults = {
        type: 'DELETE',
        disableButton: false,
        buttonSelector: "[type='submit']",
        redirect: true,
        data: {},
        confirm: true,
        confirmationTitle: 'Are you sure?',
        confirmationMessage: 'You won\'t be able to revert this!',
        confirmationButtonText: 'Yes, delete it!',
        onComplete: false,
    };

    var opt = defaults;

    // Extend user-set options over defaults
    if (options) {
        opt = $.extend(defaults, options);
    }

    if (opt.confirm) {
        Swal.fire({
            title: opt.confirmationTitle,
            text: opt.confirmationMessage,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: opt.confirmationButtonText
        }).then((result) => {
            if (result.isConfirmed) {
                if (opt.disableButton !== false) {
                    $(opt.buttonSelector).addClass('disabled');
                }


                axios({
                    method: opt.type,
                    url: opt.url,
                    data: opt.data,
                    onUploadProgress: (e) => {

                    }
                }).then((response) => {


                    toastr.success(response.data.message, '', {timeOut: 3000})


                    if (opt.onComplete && typeof opt.onComplete === "function") {
                        opt.onComplete(response);
                    }

                    if (opt.disableButton !== false) {
                        $(opt.buttonSelector).removeClass('disabled');
                    }


                }).catch((error) => {
                        console.log('error...');
                        //emit failed event etc...

                        toastr.error('Something went wrong! Please reload', '', {timeOut: 3000})


                        if (opt.disableButton !== false) {
                            $(opt.buttonSelector).removeClass('disabled');
                        }


                    }
                );
            }
        });
    } else {
        if (opt.disableButton !== false) {
            $(opt.buttonSelector).addClass('disabled');
        }


        axios({
            method: opt.type,
            url: opt.url,
            data: opt.data,
            onUploadProgress: (e) => {

            }
        }).then((response) => {
            toastr.success(response.data.message, '', {timeOut: 3000})

            if (opt.onComplete && typeof opt.onComplete === "function") {
                opt.onComplete(response);
            }

            if (opt.disableButton !== false) {
                $(opt.buttonSelector).removeClass('disabled');
            }


        }).catch((error) => {
                console.log('error...');
                //emit failed event etc...

                toastr.error('Something went wrong! Please reload', '', {timeOut: 3000})


                if (opt.disableButton !== false) {
                    $(opt.buttonSelector).removeClass('disabled');
                }


            }
        );
    }


}
