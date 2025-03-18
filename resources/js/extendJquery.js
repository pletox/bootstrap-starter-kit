$.fn.loadFragment = function (url, data = {}) {
    let target = this; // The div where content will be loaded

    target.html('<div class="text-center p-3"><span class="spinner-border spinner-border-sm"></span> Loading...</div>');

    $.ajax({
        url: url,
        type: 'GET',
        data: data,
        success: function (response) {
            target.html(response);
        },
        error: function () {
            target.html('<div class="text-danger">Failed to load content.</div>');
        }
    });

    return this;
};
