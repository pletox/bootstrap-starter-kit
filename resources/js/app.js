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

window.escapeHtml = function (value) {
    return $('<div>').text(value ?? '').html();
};

import {createIcons, icons} from "lucide";

window.refreshLucideIcons = function () {
    createIcons({icons});
};

if ('serviceWorker' in navigator) {
    window.addEventListener('load', function () {
        navigator.serviceWorker.register('/service-worker.js').catch(() => {});
    });
}

window.deferredInstallPrompt = null;
window.pwaInstallPrompt = null;

window.addEventListener('beforeinstallprompt', function (event) {
    event.preventDefault();
    window.deferredInstallPrompt = event;
    window.pwaInstallPrompt = event;
    window.dispatchEvent(new CustomEvent('pwa-install-ready'));
});

window.addEventListener('appinstalled', function () {
    window.deferredInstallPrompt = null;
    window.pwaInstallPrompt = null;
    window.dispatchEvent(new CustomEvent('pwa-installed'));
});

window.promptPwaInstall = async function () {
    if (!window.pwaInstallPrompt) {
        return null;
    }

    const promptEvent = window.pwaInstallPrompt;
    promptEvent.prompt();

    const choice = await promptEvent.userChoice;
    window.deferredInstallPrompt = null;
    window.pwaInstallPrompt = null;

    return choice;
};

let developerDocsHighlighterPromise = null;

function loadDeveloperDocsHighlighter() {
    if (!developerDocsHighlighterPromise) {
        developerDocsHighlighterPromise = Promise.all([
            import('highlight.js/lib/common'),
            import('highlight.js/styles/github.css'),
        ]).then(([module]) => module.default);
    }

    return developerDocsHighlighterPromise;
}

function detectDocsCodeLanguage(code) {
    const value = code.trim();

    if (!value) {
        return null;
    }

    if (value.startsWith('<') || value.includes('<x-') || value.includes('</x-')) {
        return 'xml';
    }

    if (value.startsWith('{') || value.startsWith('[')) {
        return 'json';
    }

    if (value.includes('<?php') || value.includes('public function') || value.includes('Route::') || value.includes('Category::')) {
        return 'php';
    }

    if (value.startsWith('php artisan') || value.startsWith('npm run')) {
        return 'bash';
    }

    if (value.includes('$(function') || value.includes('axios.') || value.includes('const ') || value.includes('let ')) {
        return 'javascript';
    }

    return null;
}

async function copyTextToClipboard(text) {
    if (navigator.clipboard && window.isSecureContext) {
        await navigator.clipboard.writeText(text);
        return;
    }

    const textarea = document.createElement('textarea');
    textarea.value = text;
    textarea.setAttribute('readonly', '');
    textarea.style.position = 'fixed';
    textarea.style.left = '-9999px';

    document.body.appendChild(textarea);
    textarea.select();
    document.execCommand('copy');
    textarea.remove();
}

async function initDeveloperDocsCodeBlocks() {
    const codeBlocks = document.querySelectorAll('.developer-docs-article pre > code');

    if (!codeBlocks.length) {
        return;
    }

    const hljs = await loadDeveloperDocsHighlighter();

    codeBlocks.forEach((code) => {
        const pre = code.parentElement;

        if (!pre || pre.dataset.docsCodeReady === 'true') {
            return;
        }

        const rawCode = code.textContent;
        const language = detectDocsCodeLanguage(rawCode);

        if (language && hljs.getLanguage(language)) {
            code.innerHTML = hljs.highlight(rawCode, {language}).value;
            code.classList.add('hljs', `language-${language}`);
        } else {
            code.innerHTML = hljs.highlightAuto(rawCode).value;
            code.classList.add('hljs');
        }

        const wrapper = document.createElement('div');
        wrapper.className = 'developer-docs-code-block';
        pre.parentNode.insertBefore(wrapper, pre);
        wrapper.appendChild(pre);

        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'developer-docs-code-copy';
        button.innerHTML = '<i data-lucide="copy" class="w-4 h-4"></i><span>Copy</span>';
        button.addEventListener('click', async () => {
            await copyTextToClipboard(rawCode);

            button.classList.add('copied');
            button.innerHTML = '<i data-lucide="check" class="w-4 h-4"></i><span>Copied</span>';
            window.refreshLucideIcons();

            setTimeout(() => {
                button.classList.remove('copied');
                button.innerHTML = '<i data-lucide="copy" class="w-4 h-4"></i><span>Copy</span>';
                window.refreshLucideIcons();
            }, 1600);
        });

        wrapper.appendChild(button);
        pre.dataset.docsCodeReady = 'true';
    });
}

document.addEventListener('DOMContentLoaded', function () {
    window.refreshLucideIcons();
    initDeveloperDocsCodeBlocks();
});

document.addEventListener('livewire:navigating', function () {
    $.fn.dataTable.tables({visible: true, api: true}).destroy();
    $('[data-jp-editor]').jpEditorDestroy();
});

document.addEventListener('livewire:navigated', function () {
    window.refreshLucideIcons();
    initDeveloperDocsCodeBlocks();

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
