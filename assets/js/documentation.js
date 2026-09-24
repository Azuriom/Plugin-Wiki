(function () {
    'use strict';

    //
    // Sticky offset — detect the theme's sticky header height and set
    // --wiki-docs-sticky-top on :root so the sidebars stick below it.
    //

    function findStickyHeaderHeight() {
        const candidates = [
            'header.fixed-top',
            '.navbar.fixed-top',
            'nav.fixed-top',
            'header.sticky-top',
            '.navbar.sticky-top',
            'header',
        ];
        for (const sel of candidates) {
            const el = document.querySelector(sel);
            if (!el) continue;
            const cs = window.getComputedStyle(el);
            if (cs.position === 'fixed' || cs.position === 'sticky') {
                return el.getBoundingClientRect().bottom + 16;
            }
        }
        // Some themes keep the <header> static and fix a bar inside it.
        const header = document.querySelector('header');
        if (header) {
            for (const el of header.querySelectorAll('*')) {
                const cs = window.getComputedStyle(el);
                if ((cs.position === 'fixed' || cs.position === 'sticky') && el.offsetHeight > 0 && el.offsetHeight <= 160) {
                    return el.getBoundingClientRect().bottom + 16;
                }
            }
        }
        return null;
    }

    function applyStickyOffset() {
        const height = findStickyHeaderHeight();
        if (height !== null) {
            document.documentElement.style.setProperty('--wiki-docs-sticky-top', height + 'px');
        }
    }

    //
    // Table of contents — built from the page headings, highlighted on scroll.
    //

    let scrollTickScheduled = false;
    let currentHeadings = [];

    function getStickyOffset() {
        const raw = getComputedStyle(document.documentElement).getPropertyValue('--wiki-docs-sticky-top');
        const px = parseInt(raw, 10);
        return Number.isFinite(px) ? px : 60;
    }

    function updateActiveToc() {
        if (currentHeadings.length === 0) return;
        const threshold = getStickyOffset() + 40;

        let active = currentHeadings[0];
        for (const h of currentHeadings) {
            if (h.getBoundingClientRect().top <= threshold) {
                active = h;
            } else {
                break;
            }
        }

        const tocList = document.querySelector('#wiki-toc .wiki-docs-toc-list');
        if (!tocList) return;
        tocList.querySelectorAll('a.nav-link.active').forEach(el => el.classList.remove('active'));
        const link = tocList.querySelector(`a.nav-link[href="#${active.id}"]`);
        if (link) link.classList.add('active');
    }

    function onScroll() {
        if (scrollTickScheduled) return;
        scrollTickScheduled = true;
        requestAnimationFrame(() => {
            scrollTickScheduled = false;
            updateActiveToc();
        });
    }

    function buildToc() {
        const content = document.getElementById('wiki-content');
        const tocList = document.querySelector('#wiki-toc .wiki-docs-toc-list');
        if (!content || !tocList) return;

        tocList.innerHTML = '';
        currentHeadings = [];

        const tocNav = document.getElementById('wiki-toc');

        const headings = content.querySelectorAll('h2, h3');
        if (headings.length < 2) {
            if (tocNav) tocNav.style.display = 'none';
            return;
        }
        if (tocNav) tocNav.style.display = '';

        headings.forEach((h, i) => {
            if (!h.id) h.id = 'wiki-h-' + i;
            const li = document.createElement('li');
            li.className = 'nav-item';
            const a = document.createElement('a');
            a.className = 'nav-link';
            if (h.tagName === 'H3') a.classList.add('wiki-docs-toc-h3');
            a.href = '#' + h.id;
            a.textContent = h.textContent;
            li.appendChild(a);
            tocList.appendChild(li);
            currentHeadings.push(h);
        });

        updateActiveToc();
    }

    //
    // Search — debounced AJAX against the wiki search route.
    //

    let inflight = null;
    let debounceTimer = null;
    let focusedIndex = -1;
    let searchEmptyStateHtml = null;

    function performSearch(query) {
        if (inflight) inflight.abort();

        const container = document.getElementById('wikiSearchResults');
        if (!container || !container.dataset.url) return;

        if (!query || query.length === 0) {
            container.innerHTML = searchEmptyStateHtml;
            focusedIndex = -1;
            return;
        }

        const controller = new AbortController();
        inflight = controller;

        fetch(container.dataset.url + '?q=' + encodeURIComponent(query), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            signal: controller.signal,
        })
            .then(r => r.text())
            .then(html => {
                container.innerHTML = html;
                focusedIndex = -1;
            })
            .catch(err => {
                if (err.name !== 'AbortError') console.error(err);
            });
    }

    function getFocusableResults() {
        return document.querySelectorAll('#wikiSearchResults a.list-group-item-action');
    }

    function setFocused(index) {
        const items = getFocusableResults();
        items.forEach(el => el.classList.remove('focused'));
        if (index < 0 || index >= items.length) {
            focusedIndex = -1;
            return;
        }
        focusedIndex = index;
        items[index].classList.add('focused');
        items[index].scrollIntoView({ block: 'nearest' });
    }

    function bindSearch() {
        const input = document.getElementById('wikiSearchInput');
        const modal = document.getElementById('wikiSearchModal');
        if (!input || !modal) return;

        searchEmptyStateHtml = document.getElementById('wikiSearchResults')?.innerHTML;

        input.addEventListener('input', (ev) => {
            clearTimeout(debounceTimer);
            const q = ev.target.value.trim();
            debounceTimer = setTimeout(() => performSearch(q), 200);
        });

        input.addEventListener('keydown', (ev) => {
            const items = getFocusableResults();
            if (ev.key === 'ArrowDown') {
                ev.preventDefault();
                setFocused(Math.min(focusedIndex + 1, items.length - 1));
            } else if (ev.key === 'ArrowUp') {
                ev.preventDefault();
                setFocused(Math.max(focusedIndex - 1, 0));
            } else if (ev.key === 'Enter' && focusedIndex >= 0) {
                ev.preventDefault();
                items[focusedIndex].click();
            }
        });

        modal.addEventListener('shown.bs.offcanvas', () => input.focus());

        document.getElementById('wikiSearchResults')?.addEventListener('click', (ev) => {
            if (ev.target.closest('a') && window.bootstrap?.Offcanvas) {
                window.bootstrap.Offcanvas.getOrCreateInstance(modal).hide();
            }
        });

        document.addEventListener('keydown', (ev) => {
            const isMac = navigator.platform.toUpperCase().indexOf('MAC') >= 0;
            const ctrl = isMac ? ev.metaKey : ev.ctrlKey;

            if (ctrl && ev.key.toLowerCase() === 'k') {
                if (window.bootstrap?.Offcanvas) {
                    ev.preventDefault();
                    window.bootstrap.Offcanvas.getOrCreateInstance(modal).show();
                }
            }
        });
    }

    //
    // SPA-like navigation — swap the article content without a full reload,
    // so the tree state (expanded categories, scroll) is preserved.
    //

    function escapeRegex(s) {
        return s.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }

    function pagePathPattern() {
        const content = document.getElementById('wiki-content');
        if (!content || !content.dataset.indexUrl) return null;

        const basePath = new URL(content.dataset.indexUrl, window.location.origin)
            .pathname.replace(/\/$/, '');

        return new RegExp('^' + escapeRegex(basePath) + '/[^/]+/[^/]+/?$');
    }

    function isInternalWikiLink(a, pattern) {
        if (!a || a.target === '_blank') return false;
        if (a.hasAttribute('download')) return false;
        if (a.origin !== window.location.origin) return false;
        if (a.hash && a.pathname === window.location.pathname) return false;
        return pattern.test(a.pathname);
    }

    function updateTreeActive(url) {
        const tree = document.getElementById('wiki-tree');
        if (!tree) return;

        const path = new URL(url, window.location.origin).pathname;
        tree.querySelectorAll('.wiki-docs-tree-page.active').forEach(el => el.classList.remove('active'));

        const newLink = tree.querySelector(`a[href$="${path}"]`);
        if (!newLink) return;

        const item = newLink.closest('.wiki-docs-tree-page');
        if (item) item.classList.add('active');

        let parent = newLink.parentElement;
        while (parent && parent !== tree) {
            if (parent.matches('ul.collapse') && !parent.classList.contains('show')) {
                parent.classList.add('show');
                const trigger = parent.parentElement?.querySelector('[data-bs-toggle="collapse"]');
                if (trigger) trigger.setAttribute('aria-expanded', 'true');
            }
            parent = parent.parentElement;
        }
    }

    function swapContent(html, url) {
        const doc = new DOMParser().parseFromString(html, 'text/html');
        const incoming = doc.getElementById('wiki-content');
        const current = document.getElementById('wiki-content');
        if (!incoming || !current) {
            window.location.href = url;
            return;
        }

        current.innerHTML = incoming.innerHTML;

        const incomingTitle = doc.querySelector('title')?.textContent;
        if (incomingTitle) document.title = incomingTitle;

        updateTreeActive(url);
        buildToc();
        window.scrollTo({ top: 0, behavior: 'auto' });
    }

    async function spaNavigate(url, fromPopState) {
        try {
            const response = await fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' },
            });
            if (!response.ok) {
                window.location.href = url;
                return;
            }
            const html = await response.text();
            if (!fromPopState) {
                window.history.pushState({ wikiDocs: true }, '', url);
            }
            swapContent(html, url);
        } catch (err) {
            window.location.href = url;
        }
    }

    function bindSpaNav() {
        const pattern = pagePathPattern();
        if (!pattern) return;

        // Mark the initial history entry so navigating back to it
        // from a pushState entry also triggers a content swap.
        window.history.replaceState({ wikiDocs: true }, '', window.location.href);

        document.body.addEventListener('click', (ev) => {
            if (ev.defaultPrevented) return;
            if (ev.button !== 0) return;
            if (ev.metaKey || ev.ctrlKey || ev.shiftKey || ev.altKey) return;

            const a = ev.target.closest('a');
            if (!isInternalWikiLink(a, pattern)) return;

            ev.preventDefault();
            spaNavigate(a.href, false);
        });

        window.addEventListener('popstate', (ev) => {
            if (ev.state && ev.state.wikiDocs) {
                spaNavigate(window.location.href, true);
            }
        });
    }

    function init() {
        applyStickyOffset();
        window.addEventListener('resize', applyStickyOffset);
        // Re-measure shortly after load in case the navbar settles (fonts, icons).
        setTimeout(applyStickyOffset, 250);

        bindSearch();
        bindSpaNav();
        buildToc();
        window.addEventListener('scroll', onScroll, { passive: true });
        window.addEventListener('resize', onScroll, { passive: true });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
