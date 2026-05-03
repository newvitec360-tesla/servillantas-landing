# 1. Update landing_gestor.php
php_file = 'app/Views/Desktop/configuracion/landing_gestor.php'
with open(php_file, 'r') as f:
    html = f.read()

html = html.replace('<button class="primary-btn">Publicar cambios</button>', '<button id="publishBtn" class="primary-btn">Publicar cambios</button>')

with open(php_file, 'w') as f:
    f.write(html)

# 2. Update landing_admin.js
js_file = 'public/assets/js/landing_admin.js'
with open(js_file, 'r') as f:
    js = f.read()

js_publish = """
const pubBtn = document.getElementById('publishBtn');
if(pubBtn) {
  pubBtn.addEventListener('click', async () => {
    try {
      const res = await fetch('/api/admin/landing-content/publish', { method: 'POST' });
      const data = await res.json();
      if(data.status === 'ok') {
        showToast('Cambios publicados a producción');
      } else {
        showToast('Error al publicar');
      }
    } catch (e) {
      showToast('Fallo al conectar');
    }
  });
}
"""
js += js_publish

with open(js_file, 'w') as f:
    f.write(js)

print("Publish button wired")
