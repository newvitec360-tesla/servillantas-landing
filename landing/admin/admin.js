const tabs = document.querySelectorAll('.tab-link');
const panels = document.querySelectorAll('.panel');
const toast = document.getElementById('toast');
const jsonOutput = document.getElementById('jsonOutput');

const demoContent = {
  brand: {
    logoMain: 'assets/img/logo-servillantas.png',
    logoHours: 'assets/img/logo-24-horas.png'
  },
  header: {
    menu: [
      { label: 'Inicio', href: '#inicio', visible: true },
      { label: 'Nosotros', href: '#nosotros', visible: true },
      { label: 'Servicios', href: '#servicios', visible: true },
      { label: 'Aliados', href: '#aliados', visible: true },
      { label: 'Contacto', href: '#contacto', visible: true }
    ],
    cta: { label: 'Solicitar servicio', url: 'https://wa.me/573107922579', icon: 'calendar' }
  },
  hero: {
    slides: [
      {
        title: 'En Servillantas 24 Horas, trabajamos a su servicio',
        description: 'Atención confiable para vehículos livianos y pesados en Bucaramanga, con calidad, rapidez y compromiso',
        image: {
          desktop: 'assets/img/hero-bg.jpg',
          mobile: 'assets/img/hero-bg-mobile.jpg'
        },
        primaryCta: { label: 'Solicitar servicio', url: 'https://wa.me/573107922579' },
        secondaryCta: { label: 'Nuestros servicios', url: '#servicios' },
        visible: true,
        order: 1
      }
    ]
  },
  heroHighlights: [
    { title: 'Atención 24 horas', subtitle: 'Siempre disponibles', image: 'assets/img/mini-24h.jpg', visible: true, order: 1 },
    { title: 'Equipo especializado', subtitle: 'Tecnología de punta', image: 'assets/img/mini-equipo.jpg', visible: true, order: 2 },
    { title: 'Para livianos y pesados', subtitle: 'Todos los vehículos', image: 'assets/img/mini-pesados.jpg', visible: true, order: 3 }
  ],
  about: {
    kicker: 'Nosotros',
    title: 'Más que llantas, somos tu aliado en el camino',
    description: 'En Servillantas El Puente ofrecemos soluciones integrales para el cuidado y mantenimiento de tu vehículo. Contamos con personal calificado, equipos de última tecnología y atención 24 horas para brindarte seguridad y tranquilidad en cada viaje.',
    image: 'assets/img/about-building.jpg',
    cta: { label: 'Conócenos más', href: '#contacto' }
  },
  values: [
    { icon: 'target', title: 'Misión', description: 'Brindar soluciones integrales en servicios de llantas y mantenimiento automotriz, con calidad, honestidad y atención 24 horas.' },
    { icon: 'eye', title: 'Visión', description: 'Ser la serviteca líder en Bucaramanga y la región, reconocida por excelencia, innovación y compromiso.' },
    { icon: 'people', title: 'Historia', description: 'Nacimos con el propósito de ofrecer un servicio confiable y oportuno para conductores y empresas de transporte.' }
  ],
  services: [
    { number: '01', title: 'Montallantas y reparación', description: 'Montaje profesional, reparación de llantas y solución de pinchazos.', image: 'assets/img/service-montallantas.jpg', icon: 'tire', visible: true, order: 1 },
    { number: '02', title: 'Alineación y balanceo', description: 'Mayor estabilidad, seguridad y vida útil para tus llantas.', image: 'assets/img/service-alineacion.jpg', icon: 'balance', visible: true, order: 2 },
    { number: '03', title: 'Cambio de aceite', description: 'Aceites de alta calidad para el óptimo rendimiento de tu motor.', image: 'assets/img/service-aceite.jpg', icon: 'oil', visible: true, order: 3 },
    { number: '04', title: 'Cambio de fluidos', description: 'Renovamos los fluidos esenciales para el buen funcionamiento de tu vehículo.', image: 'assets/img/service-fluidos.jpg', icon: 'drop', visible: true, order: 4 },
    { number: '05', title: 'Carga de nitrógeno', description: 'Mejora la presión, estabilidad y rendimiento de tus llantas.', image: 'assets/img/service-nitrogeno.jpg', icon: 'n2', visible: true, order: 5 },
    { number: '06', title: 'Venta de llantas', description: 'Marcas reconocidas para todo tipo de vehículos y necesidades.', image: 'assets/img/service-llantas.jpg', icon: 'tire', visible: true, order: 6 },
    { number: '07', title: 'Rines', description: 'Variedad de diseños y tamaños con la mejor calidad.', image: 'assets/img/service-rines.jpg', icon: 'rim', visible: true, order: 7 },
    { number: '08', title: 'Línea SPA automotriz', description: 'Productos especializados para el cuidado y embellecimiento de tu vehículo.', image: 'assets/img/service-spa.jpg', icon: 'spa', visible: true, order: 8 },
    { number: '09', title: 'Re-encauche', description: 'Solución económica y ecológica que extiende la vida útil de tus llantas.', image: 'assets/img/service-reencauche.jpg', icon: 'retread', visible: true, order: 9 }
  ],
  allies: {
    kicker: 'Aliados que nos respaldan',
    title: 'Trabajamos junto a las mejores empresas de transporte',
    stripImage: 'assets/img/alliances-strip.png',
    items: []
  },
  emergencyCta: {
    preTitle: '¿Necesitas ayuda ahora?',
    title: '¡Estamos disponibles 24 horas!',
    description: 'Llámanos o escríbenos y recibe atención inmediata.',
    button: { label: 'Solicitar servicio', url: 'https://wa.me/573107922579' }
  },
  contact: {
    title: 'Febtamos para servirte',
    phones: ['310 792 2579', '637 1102'],
    address: 'Calle 70 No. 20W-38 Local 3, Bucaramanga, Santander',
    email: 'servillantaselpuente2014@gmail.com',
    schedule: 'Servicio 24 horas\nLunes a Domingo',
    mapImage: 'assets/img/map-location.jpg',
    mapUrl: 'https://maps.google.com/?q=Servillantas+El+Puente+Bucaramanga',
    socials: {
      whatsapp: 'https://wa.me/573107922579',
      facebook: '#',
      instagram: '#'
    }
  },
  business: {
    nit: '91.350.017-7',
    copyright: '© 2024 Servillantas El Puente. Todos los derechos reservados.'
  },
  seo: {
    title: 'Servillantas El Puente | Servicio 24 horas en Bucaramanga',
    description: 'Servicio de montallantas, alineación, balanceo, cambio de aceite, venta de llantas y atención 24 horas para vehículos livianos y pesados en Bucaramanga.',
    keywords: 'servillantas, llantas, Bucaramanga, montallantas, alineación, balanceo, 24 horas',
    ogImage: 'assets/img/og-servillantas.jpg'
  }
};

function showToast(message) {
  toast.textContent = message;
  toast.classList.add('show');
  setTimeout(() => toast.classList.remove('show'), 2200);
}

function activateTab(tabName) {
  tabs.forEach(tab => tab.classList.toggle('active', tab.dataset.tab === tabName));
  panels.forEach(panel => panel.classList.toggle('active', panel.id === `tab-${tabName}`));
  window.location.hash = tabName;
}

tabs.forEach(tab => {
  tab.addEventListener('click', () => activateTab(tab.dataset.tab));
});

if (window.location.hash) {
  const initial = window.location.hash.replace('#', '');
  if (document.getElementById(`tab-${initial}`)) activateTab(initial);
}

document.querySelectorAll('input[type="file"]').forEach(input => {
  input.addEventListener('change', event => {
    const file = event.target.files?.[0];
    if (!file) return;
    const holder = event.target.closest('.upload-box, .edit-card, .image-side, .slide-card');
    const img = holder?.querySelector('img');
    if (!img) {
      showToast(`Archivo seleccionado: ${file.name}`);
      return;
    }
    img.src = URL.createObjectURL(file);
    showToast(`Preview cargado: ${file.name}`);
  });
});

function collectFormSnapshot() {
  const fields = Array.from(document.querySelectorAll('[data-bind]'));
  const snapshot = {};
  
  fields.forEach(field => {
    const key = field.dataset.bind;
    let value = field.value;
    if (field.type === 'file') value = field.files?.[0]?.name || '[sin archivo nuevo]';
    
    // Convert dot-notation path to nested object
    // e.g. "about.title" → snapshot.about.title = value
    const parts = key.split('.');
    let current = snapshot;
    for (let i = 0; i < parts.length - 1; i++) {
      const part = parts[i];
      const nextPart = parts[i + 1];
      // If next part is a number, create an array; otherwise create an object
      if (current[part] === undefined) {
        current[part] = /^\d+$/.test(nextPart) ? [] : {};
      }
      current = current[part];
    }
    current[parts[parts.length - 1]] = value;
  });
  
  return snapshot;
}

// === Estado de conexión ===
let isLocalMode = true;

// Guardar borrador — guarda en API y también en localStorage como respaldo
async function saveDraft() {
  const snapshot = collectFormSnapshot();
  
  // Siempre backup local
  localStorage.setItem('servillantas-gestor-borrador', JSON.stringify({ savedAt: new Date().toISOString(), fields: snapshot }));
  
  if (isLocalMode) {
    showToast('⚠️ Guardado localmente en este navegador. No está sincronizado con base de datos.');
    return;
  }

  try {
    const res = await fetch('/index.php?r=/landing-gestor/borrador', {
      method: 'POST',
      credentials: 'same-origin',
      headers: { 
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json'
      },
      body: JSON.stringify(snapshot)
    });
    const data = await res.json();
    if (data.success) {
      showToast('🟢 Borrador guardado en base de datos correctamente.');
    } else {
      console.error('[LANDING_SAVE_ERROR]', data.message);
      showToast('🔴 No se pudo guardar el borrador en la base de datos.');
    }
  } catch (e) {
    console.error('[LANDING_SAVE_CRITICAL]', e);
    showToast('🔴 No se pudo guardar el borrador en la base de datos.');
  }
}

document.getElementById('saveAllBtn').addEventListener('click', saveDraft);

const saveDraftBtn = document.getElementById('saveDraftBtn');
if (saveDraftBtn) saveDraftBtn.addEventListener('click', saveDraft);

document.getElementById('exportJsonBtn').addEventListener('click', () => {
  jsonOutput.textContent = JSON.stringify(demoContent, null, 2);
  activateTab('publicar');
  showToast('JSON generado');
});

document.getElementById('copyJsonBtn').addEventListener('click', async () => {
  try {
    await navigator.clipboard.writeText(jsonOutput.textContent);
    showToast('JSON copiado al portapapeles');
  } catch (error) {
    showToast('No se pudo copiar automáticamente');
  }
});

// Publicar cambios a producción
const publishBtn = document.getElementById('publishBtn');
if (publishBtn) {
  publishBtn.addEventListener('click', async () => {
    if (isLocalMode) {
      showToast('🔴 No se puede publicar en modo local. Verifique la conexión.');
      return;
    }
    if (!confirm('¿Estás seguro de publicar los cambios a producción?')) return;
    try {
      const res = await fetch('/index.php?r=/landing-gestor/publicar', { 
        method: 'POST',
        credentials: 'same-origin',
        headers: {
          'Content-Type': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json'
        }
      });
      const data = await res.json();
      if (data.success) {
        showToast('🟢 Cambios publicados correctamente en la landing.');
      } else {
        console.error('[LANDING_PUBLISH_ERROR]', data.message);
        showToast('🔴 No se pudo publicar. Verifique la conexión.');
      }
    } catch (e) {
      console.error('[LANDING_PUBLISH_CRITICAL]', e);
      showToast('🔴 No se pudo publicar. Verifique la conexión.');
    }
  });
}

// === Healthcheck + carga de borrador al entrar ===
window.addEventListener('DOMContentLoaded', async () => {
  const statusBar = document.getElementById('connection-status-bar');
  const statusText = document.getElementById('connection-status-text');
  
  // Paso 1: Healthcheck
  try {
    const healthRes = await fetch('/index.php?r=/landing-gestor/health', {
      credentials: 'same-origin'
    });
    const healthData = await healthRes.json();
    
    if (healthData.success && healthData.database === 'connected') {
      isLocalMode = false;
      if (statusText) {
        statusText.innerHTML = '🟢 <b>Gestor conectado a la base de datos.</b> Los cambios se guardarán en el servidor.';
        statusText.style.color = '#10b981';
      }
      if (statusBar) statusBar.style.borderLeft = '4px solid #10b981';
      // Paso 2: Cargar borrador desde API
      await loadDraftFromAPI();
    } else {
      throw new Error('DB disconnected');
    }
  } catch (e) {
    console.error('[LANDING_HEALTH_ERROR]', e);
    isLocalMode = true;
    if (statusText) {
      statusText.innerHTML = '🔴 <b>Sin conexión con la API.</b> Los cambios solo se guardarán temporalmente en este navegador.';
      statusText.style.color = '#ef4444';
    }
    if (statusBar) statusBar.style.borderLeft = '4px solid #ef4444';
    loadDraftFromLocal();
  }
});

async function loadDraftFromAPI() {
  try {
    const res = await fetch('/index.php?r=/landing-gestor/borrador', {
      credentials: 'same-origin'
    });
    const data = await res.json();
    if (data.success && data.data && Object.keys(data.data).length > 0) {
      populateFields(data.data);
      showToast('📋 Borrador cargado desde la BD');
    } else {
      loadDraftFromLocal();
    }
  } catch (e) {
    console.error('[LANDING_LOAD_ERROR]', e);
    loadDraftFromLocal();
  }
}

function loadDraftFromLocal() {
  try {
    const raw = localStorage.getItem('servillantas-gestor-borrador');
    if (raw) {
      const parsed = JSON.parse(raw);
      if (parsed.fields) populateFields(parsed.fields);
      showToast('Borrador cargado desde caché local');
    }
  } catch (e) {
    console.error('[LANDING_LOCAL_ERROR]', e);
  }
}

function populateFields(content) {
  const fields = Array.from(document.querySelectorAll('[data-bind]'));
  fields.forEach(field => {
    const path = field.dataset.bind.split('.');
    let val = content;
    for (const p of path) {
      if (val === undefined) break;
      val = val[p];
    }
    if (val !== undefined && field.type !== 'file') {
      field.value = val;
    }
  });
  window.demoContent = content;
}
