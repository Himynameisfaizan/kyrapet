(function () {
  const $ = (s, r = document) => r.querySelector(s), $$ = (s, r = document) => [...r.querySelectorAll(s)];
  const fine = matchMedia('(pointer:fine)').matches, calm = matchMedia('(prefers-reduced-motion:reduce)').matches;

  /* nav: sticky shadow, mobile menu, dropdown */
  const nav = $('#nav'), menu = $('#menu'), burger = $('#burger');
  addEventListener('scroll', () => nav && nav.classList.toggle('stuck', scrollY > 10), { passive: true });
  if (burger) burger.addEventListener('click', () => { const o = menu.classList.toggle('open'); burger.setAttribute('aria-expanded', o) });
  $$('#menu a').forEach(a => a.addEventListener('click', () => { menu.classList.remove('open'); burger && burger.setAttribute('aria-expanded', false) }));
  $$('.dd-wrap').forEach(w => {
    const b = $('.dd-toggle', w);
    b.addEventListener('click', e => { e.stopPropagation(); const o = w.classList.toggle('open'); b.setAttribute('aria-expanded', o) });
    document.addEventListener('click', e => { if (!w.contains(e.target)) { w.classList.remove('open'); b.setAttribute('aria-expanded', false) } });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') w.classList.remove('open') })
  });

  /* scroll progress */
  const prog = $('#prog');
  addEventListener('scroll', () => { const m = document.documentElement.scrollHeight - innerHeight; if (prog) prog.style.transform = 'scaleX(' + (m > 0 ? scrollY / m : 0) + ')' }, { passive: true });

  /* headline word-by-word */
  $$('.split').forEach(h => { h.innerHTML = h.textContent.trim().split(/\s+/).map((w, i) => '<span class="w" style="--i:' + i + '">' + w + '</span>').join(' ') });

  /* lazy YouTube */
  $$('.vid[data-yt]').forEach(b => b.addEventListener('click', () => {
    const f = document.createElement('iframe'); f.src = 'https://www.youtube-nocookie.com/embed/' + b.dataset.yt + '?autoplay=1&rel=0&playsinline=1';
    f.allow = 'autoplay; encrypted-media; picture-in-picture'; f.allowFullscreen = true; f.title = 'Customer video';
    b.innerHTML = ''; b.appendChild(f); b.style.cursor = 'default';
  }, { once: true }));

  /* review slider (home) */
  const rt = $('#rtrack');
  if (rt) {
    const step = () => rt.firstElementChild.getBoundingClientRect().width + 20, nx = $('#rnext'), pv = $('#rprev');
    nx.onclick = () => { rt.scrollLeft + step() >= rt.scrollWidth - rt.clientWidth - 5 ? rt.scrollTo({ left: 0, behavior: 'smooth' }) : rt.scrollBy({ left: step(), behavior: 'smooth' }) };
    pv.onclick = () => rt.scrollBy({ left: -step(), behavior: 'smooth' });
    const auto = setInterval(() => nx.click(), 5000); rt.addEventListener('pointerenter', () => clearInterval(auto));
  }

  /* reveal on scroll, rating bars, counters */
  const io = new IntersectionObserver(es => es.forEach(e => {
    if (e.isIntersecting) {
      e.target.classList.add('in');
      $$('.bar i', e.target).forEach(b => b.style.width = b.dataset.w + '%'); io.unobserve(e.target)
    }
  }), { threshold: .1 });
  $$('.rv').forEach((el, i) => { el.style.transitionDelay = (i % 4) * 80 + 'ms'; io.observe(el) });
  const co = new IntersectionObserver(es => es.forEach(e => {
    if (!e.isIntersecting) return; co.unobserve(e.target);
    const t = parseFloat(e.target.dataset.count), d = e.target.dataset.count.includes('.') ? 1 : 0, s = performance.now();
    (function tick(n) { const p = Math.min((n - s) / 1500, 1); e.target.textContent = (t * (1 - Math.pow(1 - p, 3))).toFixed(d); if (p < 1) requestAnimationFrame(tick) })(s)
  }), { threshold: .6 });
  $$('[data-count]').forEach(el => co.observe(el));

  /* mouse effects */
  if (fine && !calm) {
    const hero = $('.hero'), himg = $('.hero-img');
    if (hero && himg) hero.addEventListener('pointermove', e => {
      const x = e.clientX / innerWidth - .5, y = e.clientY / innerHeight - .5;
      himg.style.translate = (x * -20) + 'px ' + (y * -14) + 'px';
      $$('.fb', hero).forEach((f, i) => f.style.translate = (x * (i ? 34 : -34)) + 'px ' + (y * 22) + 'px')
    });
    $$('.card,.fcard,.rc,.panel,.bcard').forEach(c => {
      c.addEventListener('pointermove', e => {
        const r = c.getBoundingClientRect(), x = (e.clientX - r.left) / r.width - .5, y = (e.clientY - r.top) / r.height - .5;
        c.style.transition = 'transform .12s'; c.style.transform = 'perspective(800px) rotateX(' + (-y * 6) + 'deg) rotateY(' + (x * 6) + 'deg) translateY(-6px)'
      });
      c.addEventListener('pointerleave', () => { c.style.transition = ''; c.style.transform = '' })
    });
  }

  /* generic filter: chips + search + gender + sort */
  const grid = $('#grid');
  if (grid) {
    const items = $$('[data-cat]', grid), chips = $$('.fchips button'), q = $('#q'), g = $('#fGender'), so = $('#fSort'), cnt = $('#cnt'), empty = $('#empty');
    let cat = 'all';
    const apply = () => {
      const t = q ? q.value.trim().toLowerCase() : '', gv = g ? g.value : 'all'; let n = 0;
      items.forEach(el => {
        const ok = (cat === 'all' || el.dataset.cat.split('|').includes(cat)) && (!t || (el.dataset.text || '').includes(t)) && (gv === 'all' || el.dataset.gender === gv);
        el.classList.toggle('gone', !ok); if (ok) n++
      });
      if (so) { [...items].sort((a, b) => so.value === 'az' ? (a.dataset.text || '').localeCompare(b.dataset.text || '') : (+a.dataset.order) - (+b.dataset.order)).forEach(el => grid.appendChild(el)) }
      if (cnt) cnt.textContent = n + ' result' + (n === 1 ? '' : 's');
      if (empty) empty.classList.toggle('show', n === 0)
    };
    chips.forEach(c => c.addEventListener('click', () => { chips.forEach(x => x.classList.remove('active')); c.classList.add('active'); cat = c.dataset.v; apply() }));
    [q, g, so].forEach(el => el && el.addEventListener('input', apply));
    const pre = new URLSearchParams(location.search).get('breed');
    if (pre) { const c = chips.find(x => x.dataset.v === pre); if (c) c.click() } else apply();
  }

  /* lightbox (gallery) */
  const lb = $('#lb');
  if (lb) {
    const img = $('img', lb); let list = [], idx = 0;
    const show = i => { idx = (i + list.length) % list.length; img.src = list[idx].href; img.alt = list[idx].querySelector('img').alt };
    $$('.gitem').forEach(a => a.addEventListener('click', e => { e.preventDefault(); list = $$('.gitem').filter(x => !x.classList.contains('gone')); show(list.indexOf(a)); lb.classList.add('open'); document.body.style.overflow = 'hidden' }));
    const close = () => { lb.classList.remove('open'); document.body.style.overflow = '' };
    $('.x', lb).onclick = close; $('.pv', lb).onclick = () => show(idx - 1); $('.nx', lb).onclick = () => show(idx + 1);
    lb.addEventListener('click', e => { if (e.target === lb) close() });
    addEventListener('keydown', e => { if (!lb.classList.contains('open')) return; if (e.key === 'Escape') close(); if (e.key === 'ArrowRight') show(idx + 1); if (e.key === 'ArrowLeft') show(idx - 1) });
  }
})();
