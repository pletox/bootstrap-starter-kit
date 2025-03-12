import axios from 'axios';

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import toastr from 'toastr';

window.toastr = toastr;

import Swal from 'sweetalert2'

window.Swal = Swal;

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


