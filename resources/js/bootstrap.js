import axios from 'axios';

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

if (csrfToken) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
}

import Swal from 'sweetalert2'

window.Swal = Swal;

const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer);
    },
});

window.toast = {
    success(message, options = {}) {
        Toast.fire({
            icon: 'success',
            title: message,
            ...options,
        });
    },
    error(message, options = {}) {
        Toast.fire({
            icon: 'error',
            title: message,
            ...options,
        });
    },
    info(message, options = {}) {
        Toast.fire({
            icon: 'info',
            title: message,
            ...options,
        });
    },
    warning(message, options = {}) {
        Toast.fire({
            icon: 'warning',
            title: message,
            ...options,
        });
    },
};

import select2 from 'select2';

select2($);

const Select2AttachBody = $.fn.select2?.amd?.require('select2/dropdown/attachBody');
const Select2Utils = $.fn.select2?.amd?.require('select2/utils');

if (Select2AttachBody && Select2Utils && !Select2AttachBody.prototype.__jpScrollPatch) {
    Select2AttachBody.prototype._attachPositioningHandler = function (decorated, container) {
        const self = this;
        const scrollEvent = 'scroll.select2.' + container.id;
        const resizeEvent = 'resize.select2.' + container.id;
        const orientationEvent = 'orientationchange.select2.' + container.id;
        const $watchers = this.$container.parents().filter(Select2Utils.hasScroll);

        $watchers.each(function () {
            Select2Utils.StoreData(this, 'select2-scroll-position', {
                x: $(this).scrollLeft(),
                y: $(this).scrollTop(),
            });
        });

        $watchers.on(scrollEvent, function () {
            const position = Select2Utils.GetData(this, 'select2-scroll-position') || {
                x: $(this).scrollLeft(),
                y: $(this).scrollTop(),
            };

            Select2Utils.StoreData(this, 'select2-scroll-position', position);
            $(this).scrollTop(position.y);
        });

        $(window).on(scrollEvent + ' ' + resizeEvent + ' ' + orientationEvent, function () {
            self._positionDropdown();
            self._resizeDropdown();
        });
    };

    Select2AttachBody.prototype.__jpScrollPatch = true;
}

import flatpickr from "flatpickr";

window.flatpickr = flatpickr;

import Quill from 'quill';
import 'quill/dist/quill.snow.css'; // Quill styles
import "quill-mention/autoregister";

// Optionally import the mention styles
import 'quill-mention/dist/quill.mention.min.css';


window.Quill = Quill;

