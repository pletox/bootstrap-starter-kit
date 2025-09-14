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
import "./extendJquery.js"

import {createIcons, icons} from "lucide";

document.addEventListener('livewire:navigating', function () {
    $.fn.dataTable.tables({visible: true, api: true}).destroy();
});

document.addEventListener('livewire:navigated', function () {
    createIcons({icons});

    const sidebarToggle = document.querySelector("#sidebar-toggle");
    if (sidebarToggle) {
        sidebarToggle.addEventListener("click", function () {
            document.querySelector("#sidebar").classList.toggle("collapsed");
            document.querySelector("body").classList.toggle("sidebar-collapsed");
            document.querySelector("#sidebarBackdrop").classList.toggle("show");
        });
    }

    const sidebarBackdrop = document.querySelector("#sidebarBackdrop");
    if (sidebarBackdrop) {
        sidebarBackdrop.addEventListener("click", function () {
            document.querySelector("#sidebar").classList.toggle("collapsed");
            document.querySelector("body").classList.toggle("sidebar-collapsed");
            document.querySelector("#sidebarBackdrop").classList.toggle("show");
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

// Requires jQuery and Bootstrap 5
(function ($) {
    // Unique id generator
    function uid() {
        return Math.random().toString(36).slice(2, 9);
    }

    // On show: move menu to body and position absolutely
    $(document).on('show.bs.dropdown', '.dropdown', function (e) {
        const $dropdown = $(this);
        const $menu = $dropdown.find('.dropdown-menu').first();
        if (!$menu.length) return;

        // avoid double-appending
        if ($menu.data('appended-to-body')) return;

        const id = uid();
        $dropdown.attr('data-dropdown-id', id);
        $menu.attr('data-dropdown-id', id);

        // store original parent & next sibling so we can restore later
        $menu.data('original-parent', $menu.parent());
        $menu.data('original-next', $menu.next().get(0) || null);

        // append to body
        $('body').append($menu);

        // temporarily make it visible & absolutely positioned for accurate measurement
        $menu.css({
            position: 'absolute',
            display: 'block',
            visibility: 'hidden',   // keep invisible during measurement to avoid flicker
            left: 0,
            top: 0
        });

        // compute geometry
        const toggleEl = $dropdown.find('[data-bs-toggle="dropdown"], .dropdown-toggle').get(0);
        const toggleRect = toggleEl.getBoundingClientRect();
        const menuRect = $menu.get(0).getBoundingClientRect();
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const scrollLeft = window.pageXOffset || document.documentElement.scrollLeft;

        // default below the toggle
        let top = toggleRect.bottom + scrollTop;
        let left = toggleRect.left + scrollLeft;

        // keep inside viewport horizontally
        if (left + menuRect.width > scrollLeft + window.innerWidth) {
            left = Math.max(scrollLeft + 8, scrollLeft + window.innerWidth - menuRect.width - 8);
        }
        // if it would go outside bottom of viewport, try placing above
        if (top + menuRect.height > scrollTop + window.innerHeight) {
            top = toggleRect.top + scrollTop - menuRect.height;
            // if still doesn't fit, clamp inside viewport
            if (top < scrollTop) top = scrollTop + 8;
        }

        // apply final positioning and restore visibility
        $menu.css({
            left: `${left}px`,
            top: `${top}px`,
            visibility: '',
            display: ''   // let bootstrap handle the display class (.show etc.)
        });

        $menu.data('appended-to-body', true);
        $menu.css('z-index', 1060); // above DataTables layers
    });

    // On hide: return menu back to original position and clear styles
    $(document).on('hidden.bs.dropdown', '.dropdown', function (e) {
        const $dropdown = $(this);
        const id = $dropdown.attr('data-dropdown-id');
        if (!id) return;

        const $menu = $(`.dropdown-menu[data-dropdown-id="${id}"]`);
        if (!$menu.length) return;

        const $origParent = $menu.data('original-parent');
        const origNext = $menu.data('original-next');

        // reset inline styles we set
        $menu.css({
            left: '',
            top: '',
            position: '',
            zIndex: '',
            visibility: '',
            display: ''
        });

        // restore into original parent / position
        if ($origParent && $origParent.length) {
            if (origNext) {
                $origParent.get(0).insertBefore($menu.get(0), origNext);
            } else {
                $origParent.append($menu);
            }
        }

        // cleanup
        $menu.removeAttr('data-dropdown-id');
        $dropdown.removeAttr('data-dropdown-id');
        $menu.removeData('original-parent original-next appended-to-body');
    });
})(jQuery);
