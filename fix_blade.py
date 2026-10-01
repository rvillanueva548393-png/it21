import re

with open("resources/views/layouts/app.blade.php", "r", encoding="utf-8") as f:
    content = f.read()

# Completely remove the old Engine Control block using regex
pattern = r'<div style="margin-top: auto; padding: 20px 0; border-top: 1px solid rgba\(255,255,255,0\.05\);">\s*<div style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #64748B; margin-bottom: 10px;">Engine Control</div>.*?</div>'
content = re.sub(pattern, "", content, flags=re.DOTALL)

# Also remove the old script block if it exists
script_pattern = r'<script>\s*function startCapture\(\) \{.*?</script>'
content = re.sub(script_pattern, "", content, flags=re.DOTALL)

# Insert the new UI right before </div>\s*<div class="main">
new_ui = """
    <div style="margin-top: auto; padding: 20px 0; border-top: 1px solid rgba(255,255,255,0.05);">
        <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #64748B; margin-bottom: 10px;">Engine Control</div>
        <button id="toggleBtn" onclick="toggleCapture()" style="width: 100%; padding: 10px; background: rgba(16, 185, 129, 0.1); color: #10B981; border: 1px solid rgba(16, 185, 129, 0.5); border-radius: 6px; cursor: pointer; font-weight: bold; transition: 0.3s;">
            START CAPTURE
        </button>
    </div>
"""

content = re.sub(r'(</div>\s*<div class="main">)', r'\n' + new_ui + r'\n\1', content)

new_script = """
<script>
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
</script>
</body>
"""

content = content.replace('</body>', new_script)

with open("resources/views/layouts/app.blade.php", "w", encoding="utf-8") as f:
    f.write(content)
print("Regex replace done!")
