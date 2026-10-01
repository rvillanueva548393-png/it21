with open("resources/views/layouts/app.blade.php", "r", encoding="utf-8") as f:
    content = f.read()

old_marker = 'START CAPTURE\n        </button>\n        <button id="stopBtn" onclick="stopCapture()"'

if "startCapture" not in content:
    old_block = content[content.find('<div style="margin-top: auto; padding: 20px 0; border-top'):content.find('</script>\n</body>')]
    
content = content.replace(
    'onclick="fetch(\'/api/engine/start\').then(()=>alert(\'Python Capture Engine Started!\'))"',
    'onclick="startCapture()"'
)
content = content.replace(
    'onclick="fetch(\'/api/engine/stop\').then(()=>alert(\'Python Capture Engine Stopped!\'))"',
    'onclick="stopCapture()"'
)

inject_script = """
<script>
function startCapture() {
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
}
</script>
"""

content = content.replace('</body>', inject_script + '</body>')

with open("resources/views/layouts/app.blade.php", "w", encoding="utf-8") as f:
    f.write(content)

print("Done!")
