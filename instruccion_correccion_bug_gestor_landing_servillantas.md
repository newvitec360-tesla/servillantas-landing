# INSTRUCCIÓN TÉCNICA ESTRICTA  
# Corrección del bug de guardado, conexión API y publicación del gestor de landing

**Proyecto:** Servillantas El Puente / Landing Page  
**Módulo:** Gestor de contenido de landing  
**Prioridad:** Alta / Crítica  
**Tipo de error:** Persistencia de datos, conexión con API, publicación y mensajes inconsistentes  
**Responsables:** Equipo de desarrollo backend, frontend y QA  

---

## 1. Contexto general

Actualmente existe un gestor visual para administrar el contenido de una landing page.

El gestor permite modificar información como:

- Header y menú
- Banner principal
- Tarjetas destacadas
- Sección Nosotros
- Misión / Visión / Historia
- Servicios
- Aliados
- Franja 24 horas
- Contacto y mapa
- SEO y redes
- Publicación final

El problema detectado es que el sistema muestra mensajes que indican una supuesta conexión con base de datos, pero el comportamiento real evidencia que los datos se están guardando localmente en el navegador o en un JSON temporal.

Esto genera una inconsistencia funcional grave porque el usuario cree que los cambios están guardados en base de datos, cuando realmente no existe confirmación real de conexión con API ni persistencia en backend.

---

## 2. Evidencia observada en los pantallazos

### Pantallazo 1 — Sección “Nosotros”

En la parte superior del gestor aparece el mensaje:

> **Gestor conectado: Los cambios se guardan en la base de datos. Usa "Guardar borrador" para guardar sin publicar, y "Publicar cambios" cuando estés listo.**

Sin embargo, al guardar aparece una notificación en la parte inferior derecha que dice:

> **Guardado en navegador (sin conexión a API)**

Esto es una contradicción directa.

Si el sistema está guardando en navegador, entonces **no está conectado realmente a la API ni a la base de datos**.

Por lo tanto, el gestor **no debe mostrar el mensaje “Gestor conectado”** mientras no exista una validación real contra el backend.

---

### Pantallazo 2 — Sección “Publicar”

En la pantalla de publicación aparece el flujo:

1. Guardar borrador
2. Previsualizar
3. Publicar

Al intentar publicar, aparece la notificación:

> **Falló la conexión al publicar**

Esto confirma que el wizard de publicación no está conectado correctamente con el backend, la API o la base de datos.

---

## 3. Diagnóstico técnico

El gestor actualmente parece funcionar como un **mockup o prototipo visual** que guarda información en el navegador, posiblemente usando:

- `localStorage`
- `sessionStorage`
- Un JSON local
- Un estado temporal en frontend
- Un archivo JSON exportable

Pero la interfaz comunica como si ya existiera:

- API conectada
- Base de datos conectada
- Guardado real de borradores
- Publicación real en producción

Esa comunicación es incorrecta y debe corregirse de inmediato.

---

## 4. Problema exacto a corregir

El problema no es únicamente visual.

El bug está en la lógica completa de:

- Estado de conexión
- Guardado de borrador
- Publicación
- Vista previa
- Sincronización con base de datos
- Mensajes de éxito y error
- Validación de API
- Manejo de errores

Actualmente se está informando al usuario que el gestor está conectado cuando realmente no existe una conexión funcional con la API.

---

# 5. Instrucción obligatoria para el equipo de desarrollo

A partir de esta instrucción, el equipo debe corregir el gestor para que tenga una separación real entre:

1. **Modo local / mockup**
2. **Borrador guardado en base de datos**
3. **Publicación final en producción**

No se debe volver a mezclar estos estados.

---

## 6. Estado 1: Modo local / mockup

Este estado aplica cuando:

- No existe API conectada.
- La API no responde.
- La base de datos no responde.
- El sistema solo está guardando datos en navegador.
- El sistema solo está usando JSON local.

### Mensaje permitido

Cuando el gestor esté en modo local, debe mostrarse claramente:

```text
🔴 Modo local: los cambios solo están guardados en este navegador. No se han enviado a la base de datos.
```

### Mensajes prohibidos en este estado

Mientras el sistema esté en modo local, queda prohibido mostrar:

```text
Gestor conectado
```

```text
Los cambios se guardan en la base de datos
```

```text
Borrador guardado en base de datos
```

```text
Cambios publicados correctamente
```

### Comportamiento permitido

En modo local, el gestor puede permitir:

- Editar campos
- Guardar temporalmente en navegador
- Exportar JSON
- Previsualizar datos locales

Pero debe dejar claro que esos datos **no están sincronizados con la base de datos**.

---

## 7. Estado 2: Borrador guardado en base de datos

Este estado aplica cuando el usuario presiona **Guardar borrador** y el sistema envía correctamente la información al backend.

El guardado debe realizarse mediante una petición real a la API.

### Endpoint sugerido

```http
POST /api/landing/draft
```

### El frontend debe enviar la estructura completa del contenido

Ejemplo de estructura base:

```json
{
  "brand": {},
  "header": {},
  "menu": {},
  "banner": {},
  "featuredCards": [],
  "about": {},
  "missionVisionHistory": {},
  "services": [],
  "allies": [],
  "schedule24h": {},
  "contact": {},
  "map": {},
  "seo": {},
  "socialNetworks": {},
  "updatedAt": "2026-04-30T00:00:00.000Z"
}
```

### Respuesta esperada si el guardado fue exitoso

```json
{
  "success": true,
  "message": "Borrador guardado en base de datos correctamente.",
  "draftId": 1,
  "updatedAt": "2026-04-30T00:00:00.000Z"
}
```

### Mensaje permitido en frontend

```text
🟢 Borrador guardado en base de datos correctamente.
```

### Respuesta esperada si falla

```json
{
  "success": false,
  "message": "No se pudo guardar el borrador en la base de datos.",
  "error": "Detalle técnico del error"
}
```

### Mensaje permitido en frontend si falla

```text
🔴 No se pudo guardar el borrador en la base de datos. Los cambios no fueron sincronizados.
```

---

## 8. Estado 3: Publicación final

Este estado aplica cuando el usuario presiona **Publicar cambios**.

Publicar no debe ser lo mismo que guardar en navegador.

Publicar debe ser un proceso real que toma el borrador validado y lo envía a producción o lo marca como contenido activo de la landing.

### Endpoint sugerido

```http
POST /api/landing/publish
```

### El endpoint debe hacer obligatoriamente lo siguiente

1. Validar que exista un borrador guardado en base de datos.
2. Validar que los campos obligatorios estén completos.
3. Validar que las imágenes requeridas estén disponibles.
4. Validar que el contenido tenga estructura correcta.
5. Guardar o actualizar el contenido publicado.
6. Marcar el contenido como versión activa.
7. Devolver una respuesta clara de éxito o error.
8. Registrar logs de la operación.

### Respuesta esperada si la publicación fue exitosa

```json
{
  "success": true,
  "message": "Cambios publicados correctamente en la landing.",
  "publishedAt": "2026-04-30T00:00:00.000Z"
}
```

### Mensaje permitido en frontend

```text
🟢 Cambios publicados correctamente en la landing.
```

### Respuesta esperada si falla la publicación

```json
{
  "success": false,
  "message": "No se pudo publicar. Verifique la conexión con la API o la base de datos.",
  "error": "Detalle técnico del error"
}
```

### Mensaje permitido en frontend si falla

```text
🔴 No se pudo publicar. Verifique la conexión con la API o la base de datos.
```

---

# 9. Regla obligatoria sobre el mensaje “Gestor conectado”

El mensaje:

```text
Gestor conectado
```

solo puede mostrarse si existe una verificación real contra la API y la base de datos.

No se permite mostrar este mensaje por defecto.

No se permite mostrar este mensaje solo porque cargó el frontend.

No se permite mostrar este mensaje solo porque existe un JSON.

No se permite mostrar este mensaje solo porque funciona `localStorage`.

---

## 10. Healthcheck obligatorio

El frontend debe validar la conexión real antes de mostrar el estado del gestor.

### Endpoint sugerido

```http
GET /api/landing/health
```

### Respuesta esperada

```json
{
  "success": true,
  "api": "connected",
  "database": "connected",
  "timestamp": "2026-04-30T00:00:00.000Z"
}
```

### Si esta respuesta es correcta, el frontend puede mostrar:

```text
🟢 Gestor conectado a la base de datos.
```

### Si la API no responde o la base de datos falla, el frontend debe mostrar:

```text
🔴 Sin conexión con la API. Los cambios solo se guardarán temporalmente en este navegador.
```

---

# 11. Reglas de mensajes del sistema

Los mensajes del frontend deben corresponder exactamente al estado real del sistema.

## Mensajes permitidos según estado

| Estado real | Mensaje permitido |
|---|---|
| API conectada y base de datos conectada | 🟢 Gestor conectado a la base de datos |
| API caída o sin respuesta | 🔴 Sin conexión con la API |
| Guardado solo en navegador | ⚠️ Guardado localmente en este navegador. No está sincronizado con base de datos |
| Guardando en backend | 🟡 Guardando cambios en base de datos... |
| Borrador guardado en backend | 🟢 Borrador guardado en base de datos correctamente |
| Error al guardar | 🔴 Error al guardar en base de datos |
| Publicando | 🟡 Publicando cambios... |
| Publicado correctamente | 🟢 Cambios publicados correctamente en la landing |
| Error al publicar | 🔴 No se pudo publicar. Verifique la conexión con la API o la base de datos |

---

# 12. Mensajes prohibidos

Queda prohibido mostrar mensajes de éxito si no existe confirmación real del backend.

No se debe mostrar:

```text
Gestor conectado
```

si no pasó el healthcheck.

No se debe mostrar:

```text
Los cambios se guardan en la base de datos
```

si realmente se están guardando en el navegador.

No se debe mostrar:

```text
Cambios publicados correctamente
```

si el endpoint de publicación falló.

No se debe mostrar:

```text
Borrador guardado
```

si el backend no confirmó el guardado.

---

# 13. Comportamiento esperado del botón “Guardar borrador”

El botón **Guardar borrador** debe seguir este flujo:

1. Recoger todos los datos actuales del gestor.
2. Validar estructura mínima del JSON.
3. Enviar los datos a `POST /api/landing/draft`.
4. Esperar respuesta del backend.
5. Si el backend responde éxito, mostrar mensaje de éxito.
6. Si el backend falla, mostrar mensaje de error.
7. Si no hay conexión, guardar localmente solo como respaldo temporal e informar claramente que no se sincronizó.

---

## 14. Flujo correcto de guardado

```text
Usuario edita contenido
↓
Usuario presiona “Guardar borrador”
↓
Frontend valida datos
↓
Frontend llama POST /api/landing/draft
↓
Backend guarda en base de datos
↓
Backend responde success true
↓
Frontend muestra “Borrador guardado en base de datos correctamente”
```

---

## 15. Flujo incorrecto actual que debe eliminarse

```text
Usuario edita contenido
↓
Usuario presiona “Guardar borrador”
↓
Frontend guarda en localStorage
↓
Frontend muestra o mantiene mensaje de gestor conectado
```

Ese flujo es incorrecto si se presenta como guardado en base de datos.

---

# 16. Comportamiento esperado del botón “Vista previa”

La vista previa debe tener dos modos claros:

## Modo conectado

Si existe backend funcional, la vista previa debe cargar el borrador desde la base de datos.

Endpoint sugerido:

```http
GET /api/landing/draft
```

## Modo local

Si no existe backend funcional, la vista previa puede cargar datos del navegador, pero debe mostrar una alerta visible:

```text
⚠️ Vista previa local: estos cambios no están guardados en base de datos.
```

---

# 17. Comportamiento esperado del botón “Publicar cambios”

El botón **Publicar cambios** debe seguir este flujo:

1. Validar conexión con API.
2. Validar conexión con base de datos.
3. Confirmar que existe un borrador guardado en base de datos.
4. Validar campos obligatorios.
5. Llamar `POST /api/landing/publish`.
6. Esperar respuesta real del backend.
7. Mostrar éxito solo si el backend confirma.
8. Mostrar error si falla cualquier paso.

---

## 18. Flujo correcto de publicación

```text
Usuario presiona “Publicar cambios”
↓
Frontend valida conexión
↓
Frontend llama POST /api/landing/publish
↓
Backend valida borrador
↓
Backend actualiza contenido publicado
↓
Backend responde success true
↓
Frontend muestra “Cambios publicados correctamente en la landing”
```

---

## 19. Flujo incorrecto que debe eliminarse

```text
Usuario presiona “Publicar cambios”
↓
Frontend intenta publicar desde JSON local
↓
No hay conexión real
↓
Aparece error genérico sin trazabilidad
```

Ese flujo debe corregirse porque no garantiza publicación real.

---

# 20. Validaciones mínimas antes de publicar

Antes de permitir la publicación, el sistema debe validar como mínimo:

- Nombre de marca
- Logo principal
- Menú principal
- Banner principal
- Título principal
- Imagen principal
- Sección Nosotros
- Servicios
- Información de contacto
- WhatsApp o teléfono
- Dirección
- SEO básico
- Estado del borrador
- Fecha de última actualización

Si falta información obligatoria, el sistema debe bloquear publicación y mostrar una lista clara de errores.

Ejemplo:

```text
No se puede publicar todavía. Faltan los siguientes campos:
- Imagen principal del banner
- Teléfono de contacto
- Descripción SEO
```

---

# 21. Manejo obligatorio de errores

Los errores no deben mostrarse únicamente como:

```text
Falló la conexión al publicar
```

Ese mensaje es demasiado genérico.

Debe complementarse con una causa entendible para el usuario y un log técnico para el desarrollador.

## Ejemplo de error para usuario

```text
🔴 No se pudo publicar porque la API no respondió. Intente nuevamente o contacte al administrador.
```

## Ejemplo de error técnico en consola

```javascript
console.error("[LANDING_PUBLISH_ERROR]", {
  endpoint: "/api/landing/publish",
  status: error.status,
  message: error.message,
  payload: payload,
  timestamp: new Date().toISOString()
});
```

---

# 22. Logs obligatorios

El sistema debe registrar logs para:

- Intento de conexión a API
- Resultado del healthcheck
- Intento de guardar borrador
- Resultado del guardado
- Intento de publicación
- Resultado de la publicación
- Errores de validación
- Errores de base de datos
- Errores de red

Estos logs deben existir en:

1. Consola del navegador para depuración frontend.
2. Logs del servidor para depuración backend.

---

# 23. Reglas para el JSON exportado

El botón **Exportar JSON** puede mantenerse, pero debe quedar claro que es una herramienta auxiliar.

El JSON exportado no reemplaza la base de datos.

El sistema no debe depender únicamente del JSON exportado para publicar.

### Mensaje sugerido

```text
Exportar JSON genera una copia manual del contenido. Esto no significa que los cambios estén publicados en la base de datos.
```

---

# 24. Recomendación de estructura de base de datos

Se recomienda manejar mínimo dos conceptos:

1. **Borrador**
2. **Contenido publicado**

## Tabla sugerida: `landing_drafts`

Campos sugeridos:

```sql
id
landing_key
content_json
status
created_at
updated_at
updated_by
```

## Tabla sugerida: `landing_published`

Campos sugeridos:

```sql
id
landing_key
content_json
version
published_at
published_by
created_at
updated_at
```

También puede manejarse una sola tabla con estados, pero debe quedar claramente diferenciado qué contenido es borrador y qué contenido está publicado.

---

# 25. Estados recomendados del contenido

```text
draft
published
archived
error
```

---

# 26. Seguridad mínima esperada

Los endpoints de guardado y publicación deben tener protección básica:

- Validación de sesión o token
- Validación de permisos
- Sanitización de datos
- Validación del JSON recibido
- Protección contra payloads incompletos
- Manejo de errores controlado
- No exponer credenciales de base de datos en frontend
- No enviar errores sensibles al usuario final

---

# 27. QA obligatorio

El equipo de QA debe probar como mínimo estos casos:

## Caso 1: API conectada

1. Abrir gestor.
2. Ejecutar healthcheck.
3. Confirmar que aparece “Gestor conectado a la base de datos”.
4. Editar una sección.
5. Guardar borrador.
6. Confirmar que el backend responde éxito.
7. Recargar la página.
8. Confirmar que los datos permanecen.

Resultado esperado: datos persistidos en base de datos.

---

## Caso 2: API desconectada

1. Simular API caída.
2. Abrir gestor.
3. Confirmar que no aparece “Gestor conectado”.
4. Editar una sección.
5. Guardar.
6. Confirmar que aparece mensaje de guardado local.

Resultado esperado: el usuario entiende que no se guardó en base de datos.

---

## Caso 3: Error al guardar borrador

1. Simular error en `POST /api/landing/draft`.
2. Presionar “Guardar borrador”.
3. Confirmar que aparece error claro.
4. Confirmar que no aparece mensaje de éxito.

Resultado esperado: no hay falso positivo.

---

## Caso 4: Publicación exitosa

1. Crear o actualizar borrador.
2. Guardar en base de datos.
3. Presionar “Publicar cambios”.
4. Confirmar respuesta exitosa del backend.
5. Abrir la landing pública.
6. Confirmar que los cambios aparecen publicados.

Resultado esperado: publicación real.

---

## Caso 5: Error al publicar

1. Simular caída de API o base de datos.
2. Presionar “Publicar cambios”.
3. Confirmar que aparece error claro.
4. Confirmar que no se muestra mensaje de publicación exitosa.

Resultado esperado: error controlado y sin mensajes falsos.

---

## Caso 6: Vista previa

1. Guardar borrador en base de datos.
2. Abrir vista previa.
3. Confirmar que carga el contenido del borrador.
4. Simular modo local.
5. Confirmar que aparece aviso de vista previa local.

Resultado esperado: el origen de los datos es claro.

---

# 28. Criterios de aceptación

Este bug solo se considera corregido si se cumple todo lo siguiente:

- El mensaje “Gestor conectado” solo aparece después de validar API y base de datos.
- Si la API no responde, el sistema muestra “Sin conexión con la API”.
- Si los cambios se guardan en navegador, se informa claramente que es guardado local.
- “Guardar borrador” guarda realmente en base de datos cuando hay conexión.
- “Publicar cambios” llama un endpoint real de publicación.
- La vista previa diferencia datos de base de datos y datos locales.
- No existen mensajes contradictorios.
- No existen falsos positivos de guardado.
- No existen falsos positivos de publicación.
- Los errores quedan registrados en consola.
- Los errores críticos quedan registrados en backend.
- El JSON exportado se mantiene solo como herramienta auxiliar.
- La landing pública refleja los cambios solo después de una publicación exitosa.
- El sistema no se entrega como funcional mientras publicar falle.

---

# 29. Entregables técnicos esperados

El equipo debe entregar:

1. Endpoint de healthcheck funcional.
2. Endpoint para guardar borrador.
3. Endpoint para consultar borrador.
4. Endpoint para publicar cambios.
5. Validaciones frontend.
6. Validaciones backend.
7. Mensajes corregidos.
8. Manejo correcto de modo local.
9. Logs de errores.
10. Pruebas QA documentadas.

---

# 30. Resumen ejecutivo para el equipo

Actualmente el gestor se comporta como un sistema local que guarda en navegador o JSON, pero la interfaz comunica como si ya estuviera conectado a base de datos.

Eso es incorrecto.

La corrección consiste en separar claramente:

- Lo que está guardado localmente.
- Lo que está guardado como borrador en base de datos.
- Lo que ya fue publicado en la landing pública.

El sistema no debe volver a mostrar mensajes de conexión o éxito si no existe confirmación real del backend.

---

# 31. Instrucción final obligatoria

No se debe entregar este módulo como funcional hasta que se corrija la conexión real entre:

```text
Gestor frontend
↓
API backend
↓
Base de datos
↓
Landing pública
```

El gestor debe ser confiable.

Si guarda en navegador, debe decir que guarda en navegador.

Si guarda en base de datos, debe ser porque la API lo confirmó.

Si publica, debe ser porque el backend actualizó realmente la landing pública.

Cualquier otro comportamiento se considera bug no resuelto.

---
