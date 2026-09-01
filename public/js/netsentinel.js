// NetSentinel shared UI behavior
// Safe to include on every page — each block checks the element exists first.

// Filter chip toggling (Threats / Packet History pages)
document.querySelectorAll('.filter-chip').forEach(chip => {
  chip.addEventListener('click', () => {
    chip.parentElement.querySelectorAll('.filter-chip').forEach(c => c.classList.remove('active'));
    chip.classList.add('active');
  });
});

// Settings toggles
document.querySelectorAll('.toggle').forEach(toggle => {
  toggle.addEventListener('click', () => toggle.classList.toggle('on'));
});

// Login background grid pulse (Login page)
const lbg = document.getElementById('login-bg');
if (lbg) {
  function sizeLoginBg() { lbg.width = window.innerWidth; lbg.height = window.innerHeight; }
  sizeLoginBg();
  window.addEventListener('resize', sizeLoginBg);
  const lctx = lbg.getContext('2d');
  let lt = 0;
  function drawLoginBg() {
    const w = lbg.width, h = lbg.height;
    lctx.clearRect(0, 0, w, h);
    lctx.strokeStyle = 'rgba(79,224,199,0.08)';
    lctx.lineWidth = 1;
    const gap = 40;
    for (let x = -((lt * 0.3) % gap); x < w; x += gap) { lctx.beginPath(); lctx.moveTo(x, 0); lctx.lineTo(x, h); lctx.stroke(); }
    for (let y = 0; y < h; y += gap) { lctx.beginPath(); lctx.moveTo(0, y); lctx.lineTo(w, y); lctx.stroke(); }
    lt += 1;
    requestAnimationFrame(drawLoginBg);
  }
  drawLoginBg();
}

// Live pulse waveform (Overview page)
const pc = document.getElementById('pulse');
if (pc) {
  const pctx = pc.getContext('2d');
  let t = 0;
  function drawPulse() {
    const w = pc.width, h = pc.height;
    pctx.clearRect(0, 0, w, h);
    pctx.beginPath();
    pctx.strokeStyle = '#4FE0C7';
    pctx.lineWidth = 2;
    for (let x = 0; x < w; x++) {
      const y = h / 2
        + Math.sin((x + t) * 0.05) * 6
        + Math.sin((x + t) * 0.19) * 3
        + (Math.sin((x + t) * 0.6) * 2 * (Math.sin((x + t) * 0.02)));
      if (x === 0) pctx.moveTo(x, y); else pctx.lineTo(x, y);
    }
    pctx.stroke();
    t += 2.4;
    requestAnimationFrame(drawPulse);
  }
  drawPulse();
}

// Traffic volume chart (Overview page)
const tc = document.getElementById('traffic');
if (tc) {
  const tctx = tc.getContext('2d');
  const w = tc.width, h = tc.height;
  const points = 30;
  const inbound = Array.from({ length: points }, () => 40 + Math.random() * 60);
  const outbound = Array.from({ length: points }, () => 20 + Math.random() * 40);

  function drawLine(data, color) {
    tctx.beginPath();
    tctx.strokeStyle = color;
    tctx.lineWidth = 2.5;
    data.forEach((v, i) => {
      const x = (i / (points - 1)) * (w - 20) + 10;
      const y = h - 20 - (v / 120) * (h - 40);
      if (i === 0) tctx.moveTo(x, y); else tctx.lineTo(x, y);
    });
    tctx.stroke();
  }

  tctx.clearRect(0, 0, w, h);
  tctx.strokeStyle = 'rgba(255,255,255,0.06)';
  tctx.lineWidth = 1;
  for (let i = 0; i <= 4; i++) {
    const y = 20 + i * (h - 40) / 4;
    tctx.beginPath(); tctx.moveTo(10, y); tctx.lineTo(w - 10, y); tctx.stroke();
  }
  drawLine(inbound, '#5B8CFF');
  drawLine(outbound, '#4FE0C7');
}
