with open("resources/views/layouts/app.blade.php", "r", encoding="utf-8") as f:
    content = f.read()

# Replace the two buttons with a single toggle button
old_buttons = '''        <button id="startBtn" onclick="startCapture()" style="width: 100%; padding: 10px; background: rgba(16, 185, 129, 0.1); color: #10B981; border: 1px solid rgba(16, 185, 129, 0.5); border-radius: 6px; cursor: pointer; margin-bottom: 8px; font-weight: bold;">
            START CAPTURE
        </button>
        <button id="stopBtn" onclick="stopCapture()" style="width: 100%; padding: 10px; background: rgba(239, 68, 68, 0.1); color: #EF4444; border: 1px solid rgba(239, 68, 68, 0.5); border-radius: 6px; cursor: pointer; font-weight: bold;">
            STOP CAPTURE
        </button>'''

new_button = '''        <button id="toggleBtn" onclick="toggleCapture()" style="width: 100%; padding: 10px; background: rgba(16, 185, 129, 0.1); color: #10B981; border: 1px solid rgba(16, 185, 129, 0.5); border-radius: 6px; cursor: pointer; font-weight: bold; transition: 0.3s;">
            START CAPTURE
        </button>'''

content = content.replace(old_buttons, new_button)

# Replace the script block to handle the toggle and launch the cloud engine
old_script = '''function startCapture() {
  fetch('/api/engine/start').then(function(r){ return r.json(); }).then(function(data){
    if(data.status === 'started'){
      alert('Capture engine launched! Check your local terminal.');
    } else {
      showLocalInstructions();
    }
  }).catch(function(){ showLocalInstructions(); });
}
function stopCapture() {
  fetch('/api/engine/stop').then(function(){
    alert('Capture engine stopped.');
  });
}
function showLocalInstructions() {
  alert('IMPORTANT: On Railway, Python must run on your LOCAL laptop!\\n\\nHow to start capturing:\\n1. Open Git Bash on your laptop\\n2. Run: cd ~/NetSentinel/python_capture\\n3. Run: python capture.py\\n\\nOR simply double-click Start_NetSentinel.bat in your NetSentinel folder!');
}'''

new_script = '''let isCapturing = false;

function toggleCapture() {
  const btn = document.getElementById('toggleBtn');
  
  if (!isCapturing) {
    // START
    btn.innerHTML = 'STOP CAPTURE';
    btn.style.background = 'rgba(239, 68, 68, 0.1)';
    btn.style.color = '#EF4444';
    btn.style.borderColor = 'rgba(239, 68, 68, 0.5)';
    isCapturing = true;
    
    fetch('/api/engine/start').then(function(r){ return r.json(); }).then(function(data){
      // Silently start
    }).catch(function(){});
  } else {
    // STOP
    btn.innerHTML = 'START CAPTURE';
    btn.style.background = 'rgba(16, 185, 129, 0.1)';
    btn.style.color = '#10B981';
    btn.style.borderColor = 'rgba(16, 185, 129, 0.5)';
    isCapturing = false;
    
    fetch('/api/engine/stop').then(function(){
      // Silently stop
    });
  }
}'''

content = content.replace(old_script, new_script)

with open("resources/views/layouts/app.blade.php", "w", encoding="utf-8") as f:
    f.write(content)

# Now update routes/web.php to launch the cloud engine on Railway
with open("routes/web.php", "r", encoding="utf-8") as f:
    web = f.read()

# Replace the Windows cmd command with a Linux compatible one for Railway
import re
web = re.sub(r"pclose\(popen\('start \"NetSentinelEngine\" cmd /k.*?\)\);", "pclose(popen('nohup python ' . base_path('python_capture/capture_cloud.py') . ' > /dev/null 2>&1 &', 'r'));", web)
web = re.sub(r"exec\('taskkill /FI \"WINDOWTITLE eq NetSentinelEngine\*\" /T /F'\);", "exec('pkill -f capture_cloud.py');", web)

with open("routes/web.php", "w", encoding="utf-8") as f:
    f.write(web)

print("Toggle button and Cloud Engine integrated!")
