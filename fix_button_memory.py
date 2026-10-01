with open("resources/views/layouts/app.blade.php", "r", encoding="utf-8") as f:
    content = f.read()

old_js = """<script>
let isCapturing = false;

function toggleCapture() {
  const btn = document.getElementById('toggleBtn');
  
  if (!isCapturing) {
    // START
    btn.innerHTML = 'STOP CAPTURE';
    btn.style.background = 'rgba(239, 68, 68, 0.1)';
    btn.style.color = '#EF4444';
    btn.style.borderColor = 'rgba(239, 68, 68, 0.5)';
    isCapturing = true;
    
    fetch('/api/engine/start').then(r => r.json()).catch(()=>{});
  } else {
    // STOP
    btn.innerHTML = 'START CAPTURE';
    btn.style.background = 'rgba(16, 185, 129, 0.1)';
    btn.style.color = '#10B981';
    btn.style.borderColor = 'rgba(16, 185, 129, 0.5)';
    isCapturing = false;
    
    fetch('/api/engine/stop').catch(()=>{});
  }
}
</script>"""

new_js = """<script>
let isCapturing = localStorage.getItem('isCapturing') === 'true';

function updateButtonUI() {
  const btn = document.getElementById('toggleBtn');
  if (!btn) return;
  if (isCapturing) {
    btn.innerHTML = 'STOP CAPTURE';
    btn.style.background = 'rgba(239, 68, 68, 0.1)';
    btn.style.color = '#EF4444';
    btn.style.borderColor = 'rgba(239, 68, 68, 0.5)';
  } else {
    btn.innerHTML = 'START CAPTURE';
    btn.style.background = 'rgba(16, 185, 129, 0.1)';
    btn.style.color = '#10B981';
    btn.style.borderColor = 'rgba(16, 185, 129, 0.5)';
  }
}

document.addEventListener('DOMContentLoaded', updateButtonUI);

function toggleCapture() {
  isCapturing = !isCapturing;
  localStorage.setItem('isCapturing', isCapturing);
  updateButtonUI();
  
  if (isCapturing) {
    fetch('/api/engine/start').then(r => r.json()).catch(()=>{});
  } else {
    fetch('/api/engine/stop').catch(()=>{});
  }
}
</script>"""

content = content.replace(old_js, new_js)

with open("resources/views/layouts/app.blade.php", "w", encoding="utf-8") as f:
    f.write(content)
