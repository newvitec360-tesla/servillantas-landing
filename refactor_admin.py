import os

# 1. Update landing_gestor.php
php_file = 'app/Views/Desktop/configuracion/landing_gestor.php'
with open(php_file, 'r') as f:
    html = f.read()

html = html.replace('href="admin.css"', 'href="/assets/css/landing_admin.css"')
html = html.replace('src="admin.js"', 'src="/assets/js/landing_admin.js"')
html = html.replace('src="../assets/img/', 'src="/landing/assets/img/')
html = html.replace('href="../desktop/desktop.html"', 'href="/landing/desktop/desktop.html"')
html = html.replace('href="../mobile/mobile.html"', 'href="/landing/mobile/mobile.html"')

with open(php_file, 'w') as f:
    f.write(html)

# 2. Update landing_admin.js
js_file = 'public/assets/js/landing_admin.js'
with open(js_file, 'r') as f:
    js = f.read()

# Replace the save flow
js_save_old = """document.getElementById('saveAllBtn').addEventListener('click', () => {
  const snapshot = collectFormSnapshot();
  localStorage.setItem('servillantas-gestor-borrador', JSON.stringify({ savedAt: new Date().toISOString(), fields: snapshot }, null, 2));
  showToast('Borrador guardado en este navegador');
});"""

js_save_new = """document.getElementById('saveAllBtn').addEventListener('click', async () => {
  const snapshot = collectFormSnapshot();
  try {
    const res = await fetch('/api/admin/landing-content/draft', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(snapshot)
    });
    const data = await res.json();
    if(data.status === 'ok') {
      showToast('Borrador guardado exitosamente');
    } else {
      showToast('Error al guardar borrador');
    }
  } catch (e) {
    showToast('Fallo la conexión');
  }
});"""

js = js.replace(js_save_old, js_save_new)

# Add load flow at the end
js_load = """

// Load draft from API
window.addEventListener('DOMContentLoaded', async () => {
  try {
    const res = await fetch('/api/admin/landing-content/draft');
    const data = await res.json();
    if(data.status === 'ok' && Object.keys(data.data).length > 0) {
      // populate fields
      const content = data.data;
      const fields = Array.from(document.querySelectorAll('[data-bind]'));
      fields.forEach(field => {
        const path = field.dataset.bind.split('.');
        let val = content;
        for(const p of path) {
           if(val === undefined) break;
           val = val[p];
        }
        if(val !== undefined && field.type !== 'file') {
            field.value = val;
        }
      });
      showToast('Borrador cargado desde la BD');
      window.demoContent = content; // update global ref for export JSON button
    }
  } catch (e) {
    console.error('No se pudo cargar el borrador', e);
  }
});
"""
js += js_load

with open(js_file, 'w') as f:
    f.write(js)

print("Refactor complete")
