import axios from 'axios';

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

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

import flatpickr from "flatpickr";

window.flatpickr = flatpickr;

import Quill from 'quill';
import 'quill/dist/quill.snow.css'; // Quill styles
import "quill-mention/autoregister";

// Optionally import the mention styles
import 'quill-mention/dist/quill.mention.min.css';


window.Quill = Quill;



