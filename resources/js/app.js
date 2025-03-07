import './bootstrap';

import * as bootstrap from 'bootstrap';

window.bootstrap = bootstrap;

import DataTable from 'datatables.net-bs5';

window.DataTable = DataTable;

import Handlebars from "handlebars";
window.Handlebars = Handlebars;

import 'datatables.net-responsive-bs5';

import "./easyAjax.js"
import "./easyDelete.js"

import {createIcons, icons} from "lucide";

document.addEventListener('livewire:navigated', function () {
    createIcons({icons});

    $('.form-select').each(function (i, element) {
        $(element).select2({
            dropdownParent: $(element).parent(),
            theme: 'bootstrap-5'
        })
    });

    $('.form-datepicker').each(function (i, element) {
        $(element).flatpickr({
            dateFormat: 'd/m/Y'
        });
    });

    const sidebarToggle = document.querySelector("#sidebar-toggle");
    if (sidebarToggle) {
        sidebarToggle.addEventListener("click", function () {
            document.querySelector("#sidebar").classList.toggle("collapsed");
        });
    }

    const themeToggle = document.querySelector(".theme-toggle");
    if (themeToggle) {
        themeToggle.addEventListener("click", () => {
            toggleLocalStorage();
            toggleRootClass();
        });
    }

    function toggleRootClass() {
        const current = document.documentElement.getAttribute('data-bs-theme');
        const inverted = current == 'dark' ? 'light' : 'dark';
        document.documentElement.setAttribute('data-bs-theme', inverted);
    }

    function toggleLocalStorage() {
        if (isLight()) {
            localStorage.removeItem("light");
        } else {
            localStorage.setItem("light", "set");
        }
    }

    function isLight() {
        return localStorage.getItem("light");
    }

    if (isLight()) {
        toggleRootClass();
    }
});
