/* Columbia Basin Chiropractic — site interactions (no dependencies). */
(function () {
  'use strict';
  var d = document, root = d.documentElement;
  var $ = function (s, c) { return (c || d).querySelector(s); };
  var $$ = function (s, c) { return Array.prototype.slice.call((c || d).querySelectorAll(s)); };
  var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var base = (d.body.getAttribute('data-base') || '/').replace(/\/?$/, '/');

  /* ---------- Header: scrolled state ---------- */
  var onScroll = function () { root.classList.toggle('is-scrolled', window.scrollY > 24); };
  onScroll();
  window.addEventListener('scroll', onScroll, { passive: true });

  /* ---------- Desktop menus (hover + click + keyboard) ---------- */
  var items = $$('[data-menu] > li');
  var closeAll = function (except) {
    items.forEach(function (li) {
      if (li === except) return;
      li.classList.remove('is-open');
      var b = $('button.menu__link', li);
      if (b) b.setAttribute('aria-expanded', 'false');
    });
  };
  items.forEach(function (li) {
    var btn = $('button.menu__link', li);
    if (!btn) return;
    var t;
    var open = function () { clearTimeout(t); closeAll(li); li.classList.add('is-open'); btn.setAttribute('aria-expanded', 'true'); };
    var close = function () { li.classList.remove('is-open'); btn.setAttribute('aria-expanded', 'false'); };
    btn.addEventListener('click', function (e) { e.stopPropagation(); li.classList.contains('is-open') ? close() : open(); });
    li.addEventListener('mouseenter', function () { if (window.matchMedia('(hover: hover)').matches) open(); });
    li.addEventListener('mouseleave', function () { if (window.matchMedia('(hover: hover)').matches) t = setTimeout(close, 160); });
    li.addEventListener('focusout', function (e) { if (!li.contains(e.relatedTarget)) close(); });
  });
  d.addEventListener('click', function (e) { if (!e.target.closest('[data-menu]')) closeAll(); });
  d.addEventListener('keydown', function (e) { if (e.key === 'Escape') { closeAll(); closeMnav(); } });

  /* ---------- Mobile menu ---------- */
  var mnav = $('[data-mnav]'), burger = $('[data-burger]');
  function openMnav() {
    if (!mnav) return;
    mnav.hidden = false;
    requestAnimationFrame(function () { mnav.classList.add('is-open'); });
    burger && burger.setAttribute('aria-expanded', 'true');
    d.body.style.overflow = 'hidden';
    var c = $('[data-mnav-close]', mnav); c && c.focus();
  }
  function closeMnav() {
    if (!mnav || mnav.hidden) return;
    mnav.classList.remove('is-open');
    burger && burger.setAttribute('aria-expanded', 'false');
    d.body.style.overflow = '';
    setTimeout(function () { mnav.hidden = true; }, 320);
    burger && burger.focus();
  }
  burger && burger.addEventListener('click', openMnav);
  mnav && $('[data-mnav-close]', mnav).addEventListener('click', closeMnav);
  mnav && $$('a', mnav).forEach(function (a) { a.addEventListener('click', function () { closeMnav(); }); });

  /* ---------- Live "Open now" status (clinic time: America/Los_Angeles) ---------- */
  function clinicNow() {
    try {
      var parts = new Intl.DateTimeFormat('en-US', { timeZone: 'America/Los_Angeles', weekday: 'short', hour: '2-digit', minute: '2-digit', hourCycle: 'h23' }).formatToParts(new Date());
      var o = {}; parts.forEach(function (p) { o[p.type] = p.value; });
      var day = { Mon: 1, Tue: 2, Wed: 3, Thu: 4, Fri: 5, Sat: 6, Sun: 7 }[o.weekday];
      return { day: day, min: (+o.hour % 24) * 60 + (+o.minute) };
    } catch (e) { var n = new Date(); return { day: n.getDay() || 7, min: n.getHours() * 60 + n.getMinutes() }; }
  }
  var toMin = function (s) { var p = s.split(':'); return +p[0] * 60 + +p[1]; };
  var fmt = function (s) { var m = toMin(s), h = Math.floor(m / 60), mm = m % 60; return (h % 12 || 12) + (mm ? ':' + (mm < 10 ? '0' : '') + mm : '') + ' ' + (h < 12 ? 'AM' : 'PM'); };
  var dayNames = ['', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
  $$('[data-status]').forEach(function (el) {
    var hours; try { hours = JSON.parse(el.getAttribute('data-hours') || '{}'); } catch (e) { return; }
    if (!Object.keys(hours).length) return;
    var now = clinicNow(), today = hours[now.day] || [], text = '', open = false;
    for (var i = 0; i < today.length; i++) {
      if (now.min >= toMin(today[i][0]) && now.min < toMin(today[i][1])) { open = true; text = 'Open now · until ' + fmt(today[i][1]); }
    }
    if (!open) {
      for (var k = 0; k < 8 && !text; k++) {
        var dd = ((now.day - 1 + k) % 7) + 1, list = hours[dd] || [];
        for (var j = 0; j < list.length; j++) {
          if (k > 0 || toMin(list[j][0]) > now.min) { text = 'Closed · opens ' + (k === 0 ? 'today' : k === 1 ? 'tomorrow' : dayNames[dd]) + ' ' + fmt(list[j][0]); break; }
        }
      }
    }
    el.classList.toggle('is-open', open);
    var t = $('[data-status-text]', el); if (t && text) t.textContent = text;
  });
  /* Highlight today in hours tables ("Monday", "Wednesday – Friday", "Sat – Sun") */
  (function () {
    var names = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'], today = clinicNow().day;
    $$('[data-hours-table] tr').forEach(function (tr) {
      var s = (tr.getAttribute('data-days') || '').toLowerCase().split(/\s*[–-]\s*|\s*,\s*|\s+to\s+/), days = [];
      var idx = s.map(function (x) { return names.indexOf(x.slice(0, 3)) + 1; }).filter(Boolean);
      if (idx.length === 2 && /[–-]|to/.test(tr.getAttribute('data-days'))) { for (var i = idx[0]; i <= idx[1]; i++) days.push(i); } else days = idx;
      if (days.indexOf(today) !== -1) tr.classList.add('is-today');
    });
  })();

  /* ---------- Reveal on scroll ---------- */
  var reveals = $$('[data-reveal]');
  if ('IntersectionObserver' in window && !reduce) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) { if (en.isIntersecting) { en.target.classList.add('is-in'); io.unobserve(en.target); } });
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });
    reveals.forEach(function (el) { io.observe(el); });
  } else reveals.forEach(function (el) { el.classList.add('is-in'); });

  /* ---------- Card spotlight ---------- */
  $$('[data-spot]').forEach(function (c) {
    c.addEventListener('pointermove', function (e) {
      var r = c.getBoundingClientRect();
      c.style.setProperty('--mx', (e.clientX - r.left) + 'px');
      c.style.setProperty('--my', (e.clientY - r.top) + 'px');
    });
  });

  /* ---------- FAQ accordion ---------- */
  $$('[data-accordion] .faq__q button').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var panel = d.getElementById(btn.getAttribute('aria-controls'));
      var open = btn.getAttribute('aria-expanded') === 'true';
      btn.setAttribute('aria-expanded', String(!open));
      if (panel) panel.hidden = open;
      var item = btn.closest('.faq__item'); item && item.classList.toggle('is-open', !open);
    });
  });

  /* ---------- Spine explorer ---------- */
  $$('[data-explorer]').forEach(function (ex) {
    var show = function (key) {
      $$('[data-panel]', ex).forEach(function (p) { p.classList.toggle('is-active', p.getAttribute('data-panel') === key); });
      $$('[data-area]', ex).forEach(function (b) { var on = b.getAttribute('data-area') === key; b.classList.toggle('is-active', on); b.setAttribute('aria-pressed', String(on)); });
      $$('[data-region]', ex).forEach(function (g) { g.classList.toggle('is-active', g.getAttribute('data-region') === key); });
    };
    $$('[data-area]', ex).forEach(function (b) {
      b.addEventListener('click', function () { show(b.getAttribute('data-area')); });
      b.addEventListener('mouseenter', function () { if (window.matchMedia('(hover: hover)').matches) show(b.getAttribute('data-area')); });
    });
    $$('[data-region]', ex).forEach(function (g) { g.addEventListener('click', function () { show(g.getAttribute('data-region')); }); });
    var first = $('[data-area].is-active', ex); if (first) show(first.getAttribute('data-area'));
  });

  /* ---------- Reviews carousel ---------- */
  $$('[data-carousel]').forEach(function (c) {
    var track = $('.carousel__track', c), scope = c.closest('section') || d;
    var step = function (dir) { var card = $('.review', track); var w = card ? card.getBoundingClientRect().width + 16 : track.clientWidth; track.scrollBy({ left: dir * w, behavior: reduce ? 'auto' : 'smooth' }); };
    var p = $('[data-carousel-prev]', scope), n = $('[data-carousel-next]', scope);
    p && p.addEventListener('click', function () { step(-1); });
    n && n.addEventListener('click', function () { step(1); });
  });

  /* ---------- In-page navigation scrollspy ---------- */
  var toc = $('[data-toc]');
  if (toc && 'IntersectionObserver' in window) {
    var links = $$('a', toc), map = {};
    links.forEach(function (a) { map[a.getAttribute('href').slice(1)] = a; });
    var spy = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (en.isIntersecting && map[en.target.id]) {
          links.forEach(function (a) { a.classList.remove('is-active'); });
          var a = map[en.target.id]; a.classList.add('is-active');
          var bar = a.parentNode; bar.scrollTo({ left: a.offsetLeft - bar.clientWidth / 2 + a.clientWidth / 2, behavior: reduce ? 'auto' : 'smooth' });
        }
      });
    }, { rootMargin: '-35% 0px -60% 0px' });
    Object.keys(map).forEach(function (id) { var s = d.getElementById(id); s && spy.observe(s); });
  }

  /* ---------- YouTube: load the player on click ---------- */
  $$('[data-yt]').forEach(function (box) {
    var b = $('button', box);
    b && b.addEventListener('click', function () {
      var f = d.createElement('iframe');
      f.src = 'https://www.youtube-nocookie.com/embed/' + encodeURIComponent(box.getAttribute('data-yt')) + '?autoplay=1&rel=0';
      f.title = b.getAttribute('aria-label') || 'Video';
      f.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
      f.allowFullscreen = true;
      box.innerHTML = ''; box.appendChild(f);
    });
  });

  /* ---------- Forms: submit without leaving the page ---------- */
  $$('[data-form]').forEach(function (form) {
    var status = $('.form__status', form);
    form.addEventListener('submit', function (e) {
      if (!window.fetch || !window.FormData) return;
      e.preventDefault();
      if (!form.checkValidity()) {
        form.reportValidity && form.reportValidity();
        return;
      }
      form.classList.add('is-sending');
      status.className = 'form__status'; status.textContent = 'Sending…';
      fetch(form.action, { method: 'POST', body: new FormData(form), headers: { Accept: 'application/json' }, credentials: 'same-origin' })
        .then(function (r) { return r.json().catch(function () { return { ok: false, error: 'Something went wrong. Please call us instead.' }; }); })
        .then(function (res) {
          form.classList.remove('is-sending');
          if (res.ok) {
            status.className = 'form__status is-ok'; status.textContent = res.message || 'Thank you! We will be in touch shortly.';
            $$('input:not([type=hidden]):not([type=checkbox]), textarea, select', form).forEach(function (f) { f.value = ''; });
            $$('input[type=checkbox]', form).forEach(function (f) { f.checked = false; });
            if (window.gtag) window.gtag('event', 'generate_lead', { form: ($('[name=_form]', form) || {}).value });
          } else { status.className = 'form__status is-err'; status.textContent = res.error || 'Please check the form and try again.'; }
        })
        .catch(function () { form.classList.remove('is-sending'); status.className = 'form__status is-err'; status.textContent = 'Network error. Please try again or call us.'; });
    });
  });

  /* ---------- Editors: "Assign image" chips on every image slot ---------- */
  var editor = false;
  try { editor = localStorage.getItem('site_editor') === '1'; } catch (e) {}
  if (editor && window.fetch) {
    fetch(base + 'admin/api.php?r=auth.me', { credentials: 'same-origin', headers: { Accept: 'application/json' } })
      .then(function (r) { return r.json(); })
      .then(function (res) {
        var u = res && (res.user || (res.data && res.data.user));
        if (!u) { try { localStorage.removeItem('site_editor'); } catch (e) {} return; }
        $$('[data-slot]').forEach(function (f) {
          var label = f.getAttribute('data-slot-label') || 'Image';
          var a = d.createElement('a');
          a.className = 'slot-chip';
          a.href = base + 'admin/#/media?slot=' + encodeURIComponent(f.getAttribute('data-slot')) + '&label=' + encodeURIComponent(label);
          a.target = '_blank'; a.rel = 'noopener';
          a.title = (f.classList.contains('ph--empty') ? 'Empty image slot: ' : 'Change image: ') + label;
          a.innerHTML = '<span>' + (f.classList.contains('ph--empty') ? '＋ Assign image' : '✎ Change image') + ' · ' + label.replace(/[<>&"]/g, '') + '</span>';
          a.addEventListener('click', function (e) { e.stopPropagation(); });
          f.appendChild(a);
        });
      }).catch(function () {});
  }
})();
