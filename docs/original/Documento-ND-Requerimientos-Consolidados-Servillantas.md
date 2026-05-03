# Documento ND — Requerimientos Consolidados y Lineamientos Base de Plataforma  
## Proyecto: Plataforma Integral de Gestión de Cartera, Crédito y Recaudo — Servillantas

> Documento consolidado a partir de **Sesión Creativa 01** y **Sesión Creativa 02**.  
> Propósito: servir como **insumo base para diseño UX/UI, arquitectura de software, modelado de base de datos y planeación de desarrollo**.

---

## 1. Contexto general del proyecto

Servillantas necesita dejar atrás un modelo operativo basado en **Excel, archivos físicos y seguimiento manual** para pasar a una **plataforma centralizada** que le permita:

- consolidar la cartera histórica,
- ubicar rápidamente documentos físicos,
- gestionar el cobro de manera organizada,
- automatizar comunicaciones,
- registrar trazabilidad completa por cliente,
- facilitar el recaudo,
- y convertir la información dispersa en un activo útil para la toma de decisiones.

La plataforma **no debe entenderse solo como un sistema de mensajería o cobranza**, sino como una herramienta integral para la **gestión de cartera, control documental, seguimiento operativo, análisis de riesgo, recaudo y soporte a decisiones de crédito futuro**.

---

## 2. Necesidad real del cliente

Después de conmutar ambas sesiones, la necesidad real del cliente se resume así:

### 2.1 Lo que realmente necesita
Una plataforma que permita:

1. **Centralizar toda la información de cartera y crédito** en un solo lugar.
2. **Migrar y limpiar datos históricos** provenientes de Excel.
3. **Relacionar lo digital con lo físico**, indicando dónde está cada carpeta o soporte documental.
4. **Consolidar deuda por cliente**, aunque provenga de distintos talonarios o conceptos.
5. **Clasificar el riesgo de cobranza** y priorizar automáticamente a los casos más críticos.
6. **Registrar todo el historial de gestión**, compromisos, pagos y contactos.
7. **Automatizar comunicaciones segmentadas** por riesgo, antigüedad y comportamiento.
8. **Ofrecer una experiencia digital de consulta y pago** para el deudor.
9. **Medir resultados**: clics, pagos, efectividad de mensajes, recaudo recuperado, cartera pendiente.
10. **Generar insumos para futuras decisiones de crédito**, evitando seguir otorgando crédito sin criterio.

### 2.2 Lo que el cliente quiere
Además de resolver el problema operativo, el cliente quiere:

- ver la deuda en tiempo real;
- identificar rápidamente a quién cobrar primero;
- facilitar al máximo que el deudor pague;
- saber quién sí responde y quién no;
- reducir dependencia de trabajo manual;
- y profesionalizar la cartera con soporte jurídico y documental.

---

## 3. Visión del producto

Construir una **plataforma web administrativa + experiencia de recaudo para deudores**, escalable y segura, compuesta por módulos conectados entre sí.

### 3.1 Resultado esperado
Un ecosistema digital donde Servillantas pueda:

- cargar y depurar su cartera,
- consultar expedientes,
- gestionar cobro,
- ejecutar campañas,
- recibir pagos,
- analizar comportamiento,
- y usar esa información para disminuir el riesgo financiero del negocio.

---

## 4. Objetivos del sistema

### 4.1 Objetivo general
Diseñar e implementar una plataforma integral para la gestión de cartera, crédito, recaudo, trazabilidad y soporte documental de Servillantas.

### 4.2 Objetivos específicos

- Unificar la data histórica de cartera en una estructura normalizada.
- Mantener trazabilidad completa por cliente, deuda, gestión y pago.
- Asociar cada registro digital con sus soportes físicos y jurídicos.
- Priorizar la gestión según nivel de riesgo y probabilidad de recuperación.
- Mejorar el recaudo a través de automatización y canales digitales.
- Permitir consulta y pago directo por parte del deudor.
- Generar inteligencia útil para futuras decisiones de otorgamiento de crédito.

---

## 5. Alcance del proyecto

Este documento cubre el **alcance funcional y lógico** de la plataforma base.

### Incluye:
- módulo administrativo,
- módulo de clientes y deudas,
- módulo documental,
- módulo de seguimiento de cobranza,
- motor de comunicaciones,
- landing / portal de consulta y pago para deudores,
- módulo de analítica,
- reglas de negocio,
- requerimientos de base de datos,
- lineamientos UX,
- y criterios no funcionales.

### No incluye en este documento:
- diseño visual final,
- estimación económica,
- cronograma detallado,
- especificación técnica por API,
- desarrollo de código,
- ni wireframes de alta fidelidad.

---

## 6. Principios de diseño del sistema

1. **Centralización**: toda la información debe vivir en un único sistema.
2. **Trazabilidad**: cada acción debe quedar registrada.
3. **Prioridad operativa**: lo urgente debe verse primero.
4. **Separación por roles**: vista gerencial ≠ vista operativa.
5. **Facilidad de recaudo**: pagar debe ser fácil y rápido.
6. **Soporte jurídico**: la plataforma debe contemplar documentos críticos.
7. **Escalabilidad**: debe crecer en volumen de datos, usuarios y automatizaciones.
8. **Cumplimiento legal**: protección de datos, habeas data y control de accesos.

---

## 7. Actores / tipos de usuario

## 7.1 Administrador general
Responsable de configurar, supervisar y controlar toda la plataforma.

**Puede:**
- ver toda la cartera,
- administrar usuarios,
- parametrizar reglas,
- aprobar cargas masivas,
- ver dashboards globales,
- auditar acciones.

## 7.2 Analista de cartera
Usuario operativo principal.

**Puede:**
- consultar clientes,
- actualizar datos,
- registrar gestiones,
- revisar documentos,
- marcar verificaciones,
- segmentar cartera,
- disparar comunicaciones.

## 7.3 Coordinador / Gerencia
Usuario de visualización ejecutiva.

**Puede:**
- revisar indicadores,
- ver recaudo,
- analizar riesgo,
- monitorear efectividad,
- consultar evolución por periodos.

## 7.4 Gestor jurídico / apoyo legal
Usuario enfocado en casos con soporte legal.

**Puede:**
- revisar documentos jurídicos,
- filtrar por garantías,
- identificar casos aptos para proceso,
- consultar estado documental.

## 7.5 Deudor / Cliente externo
Usuario final que recibe enlace o ingresa por identificador.

**Puede:**
- consultar su deuda,
- validar saldo,
- ver resumen,
- realizar pago o abono,
- enviar comprobante si aplica.

---

## 8. Macro módulos de la plataforma

1. **Módulo de autenticación y control de acceso**
2. **Módulo de carga, migración y validación de datos**
3. **Módulo maestro de clientes**
4. **Módulo de cartera / obligaciones**
5. **Módulo documental y soportes**
6. **Módulo de gestión de cobranza**
7. **Módulo de comunicaciones**
8. **Módulo de recaudo y pagos**
9. **Módulo de dashboard y analítica**
10. **Módulo de parametrización y reglas**
11. **Módulo de auditoría y trazabilidad**
12. **Portal / landing del deudor**

---

## 9. Requerimientos funcionales consolidados

## 9.1 Autenticación y roles

### RF-001. Inicio de sesión seguro
La plataforma debe permitir autenticación segura de usuarios internos.

### RF-002. Gestión de roles y permisos
Debe existir control por roles, con acceso diferenciado según perfil.

### RF-003. Sesiones auditables
Toda sesión iniciada debe quedar registrada con fecha, hora, usuario e IP.

---

## 9.2 Carga, migración y validación de datos

### RF-004. Carga de archivos Excel
El sistema debe permitir cargar archivos históricos desde Excel.

### RF-005. Proceso ETL
La plataforma debe ejecutar un proceso de extracción, transformación y carga para estandarizar los datos.

### RF-006. Validación de estructura
Antes de importar, el sistema debe validar columnas esperadas, formatos, duplicados, campos nulos y valores inconsistentes.

### RF-007. Bitácora de importación
Cada carga debe generar un resumen:
- registros válidos,
- registros con error,
- advertencias,
- duplicados,
- registros actualizados,
- registros nuevos.

### RF-008. Marcación de registros problemáticos
Los datos incompletos o incoherentes deben quedar marcados para revisión manual.

---

## 9.3 Gestión maestra de clientes

### RF-009. Crear cliente
Debe ser posible crear registros manuales de cliente.

### RF-010. Consultar cliente
Debe existir ficha consolidada por cliente.

### RF-011. Editar cliente
Debe permitirse actualizar información de contacto y referencia.

### RF-012. Múltiples medios de contacto
Un cliente debe poder tener:
- varios teléfonos,
- varios correos,
- contactos alternativos,
- empresa asociada como referencia informativa.

### RF-013. Identificación múltiple
La búsqueda debe poder hacerse por:
- cédula,
- NIT,
- placa,
- nombre,
- teléfono.

---

## 9.4 Gestión de cartera / obligaciones

### RF-014. Crear obligación o deuda
El sistema debe registrar una o varias deudas por cliente.

### RF-015. Consolidación de deuda
Debe consolidar saldos de distintos conceptos o talonarios bajo un saldo total exigible por cliente.

### RF-016. Visualización por concepto
También debe permitir desagregar la deuda por origen:
- RP,
- COP,
- REN,
- FE,
- u otras categorías parametrizables.

### RF-017. Fecha de último abono
Debe registrar y resaltar este campo como dato crítico para segmentación.

### RF-018. Antigüedad de deuda
Debe calcular automáticamente antigüedad en días, meses y años.

### RF-019. Estado de deuda
Cada obligación debe tener estados parametrizables, por ejemplo:
- vigente,
- vencida,
- crítica,
- en gestión,
- en acuerdo,
- pagada,
- parcialmente pagada,
- castigada,
- fallecido,
- jurídico.

### RF-020. Historial de movimientos
Toda modificación de saldo debe quedar registrada.

---

## 9.5 Segmentación de riesgo

### RF-021. Clasificación por niveles
La cartera debe clasificarse al menos en:
- S1 / Nivel 1,
- S2 / Nivel 2,
- S3 / Nivel 3.

### RF-022. Priorización automática
Los casos S3 deben aparecer prioritariamente en listados y tableros.

### RF-023. Segmentación por antigüedad
Debe poder cruzarse el riesgo con la antigüedad de deuda.

### RF-024. Segmentación por contacto disponible
Debe diferenciar entre:
- contactable,
- contacto incompleto,
- deudor inalcanzable.

### RF-025. Segmentación por intención
Debe poder marcar clientes con señales de interés, por ejemplo: abrió varias veces el enlace pero no pagó.

---

## 9.6 Módulo documental y soportes

### RF-026. Registro de soportes documentales
Debe registrarse la existencia o ausencia de documentos clave:
- solicitud de crédito,
- cédula,
- pagaré,
- letra de cambio,
- habeas data,
- contrato / autorización,
- otros anexos.

### RF-027. Ubicación física del expediente
Debe existir un campo visible con la ubicación exacta de la carpeta física.

### RF-028. Estado de vigencia documental
La plataforma debe permitir registrar vigencia, estado y observaciones de soportes.

### RF-029. Adjuntos digitales
Debe permitir adjuntar archivos digitalizados por cliente y obligación.

### RF-030. Filtro por fortaleza jurídica
Debe poder filtrarse cartera según soporte documental existente.

---

## 9.7 Gestión de cobranza

### RF-031. Historial de gestiones
Debe existir una bitácora cronológica por cliente con:
- fecha,
- hora,
- usuario,
- canal,
- resultado,
- observaciones.

### RF-032. Registro de compromisos
Cada gestión debe poder dejar:
- promesa de pago,
- fecha compromiso,
- monto prometido,
- observaciones.

### RF-033. Reprogramación de seguimiento
La plataforma debe permitir agendar próximo contacto o seguimiento.

### RF-034. Última gestión visible
La ficha del cliente debe mostrar claramente la última gestión realizada.

### RF-035. Resultado tipificado
Los resultados deben ser categorizables, por ejemplo:
- contestó,
- no contestó,
- número errado,
- pagó,
- promete pago,
- requiere visita,
- caso jurídico,
- fallecido.

---

## 9.8 Módulo de comunicaciones

### RF-036. Envío masivo y segmentado
La plataforma debe poder enviar comunicaciones filtradas por segmentos.

### RF-037. Canales soportados
Debe contemplar al menos:
- correo electrónico,
- SMS,
- posibilidad de integración futura con WhatsApp.

### RF-038. Plantillas parametrizables
Los mensajes deben construirse con plantillas editables.

### RF-039. Tono por nivel de riesgo
Debe existir lógica distinta para:
- comunicación preventiva,
- comunicación persuasiva,
- advertencia jurídica.

### RF-040. Restricción por habeas data
No debe permitirse mensajería masiva a clientes sin autorización válida cuando aplique normativamente.

### RF-041. Trazabilidad de comunicación
Debe registrarse:
- enviado,
- entregado,
- abierto,
- clic,
- rebote,
- spam,
- fallido.

### RF-042. Enlaces únicos por cliente
Cada mensaje debe poder incluir un enlace corto y único asociado al cliente.

---

## 9.9 Portal / landing del deudor

### RF-043. Acceso por identificador
El cliente debe poder ingresar con:
- cédula/NIT,
- placa,
- o enlace único enviado.

### RF-044. Vista resumida de deuda
Debe mostrar de forma simple:
- nombre / identificación,
- saldo total,
- conceptos,
- pagos realizados,
- saldo pendiente.

### RF-045. Diseño móvil primero
La experiencia debe estar optimizada para celular.

### RF-046. Llamado a la acción claro
Debe destacar los botones principales:
- pagar ahora,
- hacer abono,
- enviar comprobante,
- contactar asesor.

### RF-047. Confirmación postpago
Después del pago o validación, debe verse el saldo actualizado o estado de verificación.

### RF-048. Transparencia de abonos
Si el pago es parcial, debe visualizarse el nuevo saldo restante.

---

## 9.10 Recaudo y pagos

### RF-049. Integración con pasarelas
La plataforma debe contemplar integración con medios como:
- PSE,
- Nequi,
- tarjeta,
- Baloto u otros definidos.

### RF-050. Registro de abonos manuales
Debe poder registrarse pago manual cuando el recaudo venga por fuera del sistema.

### RF-051. Verificación humana
Debe existir opción de marcar comprobantes como:
- pendiente,
- validado,
- rechazado.

### RF-052. Conciliación de pagos
Los pagos deben impactar el saldo y quedar trazados.

---

## 9.11 Dashboard y analítica

### RF-053. Dashboard ejecutivo
Debe mostrar métricas globales de cartera y recaudo.

### RF-054. Indicadores mínimos
- deuda total,
- saldo recuperado,
- cartera pendiente,
- porcentaje de recuperación,
- número de deudores,
- distribución por riesgo,
- efectividad por canal,
- pagos por periodo.

### RF-055. Filtros de análisis
Debe permitir filtrar por:
- fechas,
- nivel de riesgo,
- antigüedad,
- gestor,
- estado,
- contacto disponible,
- tipo de soporte,
- tipo de deuda.

### RF-056. Ranking operativo
Debe permitir ver:
- mayores deudores,
- casos críticos,
- mejores recuperaciones,
- campañas más efectivas.

### RF-057. Analítica de comportamiento
Debe registrar y analizar el recorrido digital:
mensaje → clic → ingreso → pago / no pago.

---

## 9.12 Parametrización

### RF-058. Catálogos editables
Deben parametrizarse:
- estados,
- tipos de documentos,
- tipos de gestión,
- niveles de riesgo,
- canales,
- resultados,
- plantillas,
- conceptos de deuda.

### RF-059. Reglas configurables
La administración debe poder ajustar reglas sin tocar código en los puntos clave que sea viable parametrizar.

---

## 9.13 Auditoría

### RF-060. Bitácora completa
Toda acción relevante debe quedar registrada.

### RF-061. Historial por registro
El sistema debe saber quién creó, editó o eliminó información.

### RF-062. Evidencia de cambio
Debe haber trazabilidad de cambios críticos en saldo, estado, nivel de riesgo, pagos y soportes.

---

## 10. Casos de uso principales

## CU-01. Importar cartera histórica
**Actor:** Administrador / Analista  
**Flujo:**
1. Usuario carga archivo.
2. Sistema valida estructura.
3. Sistema detecta errores, duplicados y vacíos.
4. Usuario corrige o aprueba.
5. Sistema importa datos y genera bitácora.

## CU-02. Consultar ficha integral del cliente
**Actor:** Analista  
**Flujo:**
1. Busca cliente por cédula, placa o teléfono.
2. Sistema muestra perfil consolidado.
3. Visualiza datos, deuda, documentos, gestiones y estado.

## CU-03. Registrar gestión de cobro
**Actor:** Analista  
**Flujo:**
1. Abre cliente.
2. Registra llamada, mensaje o visita.
3. Indica resultado.
4. Agrega observación y compromiso.
5. Agenda próximo seguimiento si aplica.

## CU-04. Priorizar cartera crítica
**Actor:** Analista / Coordinador  
**Flujo:**
1. Ingresa a bandeja de trabajo.
2. Filtra o ve automáticamente S3 primero.
3. Ejecuta gestión sobre casos más urgentes.

## CU-05. Ejecutar campaña segmentada
**Actor:** Administrador / Analista autorizado  
**Flujo:**
1. Crea segmento.
2. Selecciona plantilla.
3. Valida cumplimiento de autorizaciones.
4. Ejecuta envío.
5. Revisa trazabilidad de resultados.

## CU-06. Consultar deuda por parte del deudor
**Actor:** Deudor  
**Flujo:**
1. Ingresa por enlace o identificador.
2. Sistema valida identidad mínima.
3. Muestra resumen de deuda.
4. Ofrece opciones de pago.

## CU-07. Realizar pago o abono
**Actor:** Deudor  
**Flujo:**
1. Selecciona medio de pago.
2. Realiza pago.
3. Sistema registra transacción o comprobante.
4. Actualiza saldo o deja en verificación.

## CU-08. Validar comprobante manualmente
**Actor:** Analista  
**Flujo:**
1. Revisa comprobante.
2. Valida valor y soporte.
3. Aprueba o rechaza.
4. Sistema actualiza saldo y estado.

## CU-09. Consultar ubicación de expediente físico
**Actor:** Analista / Jurídico  
**Flujo:**
1. Abre ficha del cliente.
2. Consulta campo de ubicación física.
3. Solicita o recupera carpeta.

## CU-10. Preparar caso jurídico
**Actor:** Jurídico  
**Flujo:**
1. Filtra clientes con soportes válidos.
2. Revisa pagaré, letra y demás documentos.
3. Determina viabilidad del proceso.

## CU-11. Identificar deudores inalcanzables
**Actor:** Analista / Coordinador  
**Flujo:**
1. Filtra sin teléfono/correo válido.
2. Exporta listado.
3. Deriva a visita o cobro tradicional.

## CU-12. Tomar decisiones de crédito futuro
**Actor:** Gerencia / Crédito  
**Flujo:**
1. Consulta comportamiento histórico.
2. Revisa reincidencia, pagos, mora y riesgo.
3. Define si restringe o aprueba crédito futuro.

---

## 11. Reglas de negocio consolidadas

## RN-001. La persona natural es el deudor principal
La empresa asociada funciona como dato informativo o de ubicación, salvo soporte expreso que indique otra responsabilidad.

## RN-002. S3 tiene máxima prioridad operativa
Todo cliente clasificado como S3 debe aparecer destacado y priorizado.

## RN-003. La fecha del último abono es crítica
Este campo debe usarse como uno de los principales disparadores de segmentación y prioridad.

## RN-004. La deuda debe consolidarse por cliente
Aunque existan distintos conceptos, el sistema debe ser capaz de mostrar un saldo único exigible.

## RN-005. También debe conservarse el detalle del origen
La consolidación no elimina el detalle por documento o tipo de obligación.

## RN-006. La letra de cambio puede elevar prioridad jurídica
Si existe letra firmada con huella, el caso puede clasificarse como de alta recuperabilidad jurídica incluso si faltan otros soportes.

## RN-007. El pagaré y demás garantías deben influir en la estrategia
La fuerza documental debe afectar filtros, priorización y posible ruta jurídica.

## RN-008. Los fallecidos requieren tratamiento especial
Los registros de personas fallecidas no siguen la misma ruta de cobranza ordinaria; deben marcarse con estrategia jurídica diferenciada.

## RN-009. Sin habeas data no se habilita masivo cuando aplique
La plataforma debe controlar el uso de datos conforme a políticas y autorizaciones registradas.

## RN-010. El cliente sin datos de contacto válidos debe derivarse
Debe marcarse como inalcanzable o requerir gestión física.

## RN-011. Si un cliente interactúa pero no paga, debe marcarse intención
Abrir varias veces el enlace o ingresar repetidamente sin pago es una señal útil de seguimiento.

## RN-012. Todo cambio de saldo debe quedar auditado
Nunca debe perderse la trazabilidad financiera.

---

## 12. Lógica del negocio interpretada

### 12.1 Núcleo del negocio
Servillantas otorga crédito y necesita recuperar cartera sin perder continuidad comercial.  
La plataforma debe ayudar a **cobrar mejor**, pero también a **fiar con más criterio**.

### 12.2 Problema central
Hoy la información existe, pero está:
- dispersa,
- incompleta,
- manual,
- desconectada de sus soportes,
- y sin capacidad analítica.

### 12.3 Lógica operativa deseada
La lógica completa queda así:

1. se carga o registra la deuda;
2. se asocia al cliente;
3. se valida soporte documental;
4. se clasifica riesgo;
5. se segmenta por contacto / antigüedad / comportamiento;
6. se ejecuta gestión manual o automática;
7. el cliente consulta o paga;
8. se actualiza saldo;
9. se mide el resultado;
10. esa información retroalimenta futuras decisiones de crédito.

### 12.4 Valor real del producto
El valor no está solo en guardar información, sino en conectar:

- **dato histórico**,  
- **gestión humana**,  
- **automatización**,  
- **documento legal**,  
- **canal de recaudo**,  
- **analítica de comportamiento**,  
- **y decisión comercial futura**.

---

## 13. Estructura propuesta de base de datos (nivel lógico)

> Esto no reemplaza el modelado técnico final, pero sí define el esqueleto funcional.

## 13.1 Tabla: usuarios
- id
- nombre
- correo
- teléfono
- contraseña_hash
- rol_id
- estado
- último_login
- fecha_creación
- fecha_actualización

## 13.2 Tabla: roles
- id
- nombre
- descripción

## 13.3 Tabla: permisos
- id
- nombre
- código

## 13.4 Tabla: roles_permisos
- id
- rol_id
- permiso_id

## 13.5 Tabla: clientes
- id
- tipo_documento
- numero_documento
- nit
- nombre_completo
- razón_social_referencia
- placa_principal
- referido_por
- estado_localización
- observaciones
- fallecido_flag
- fecha_creación
- fecha_actualización

## 13.6 Tabla: clientes_telefonos
- id
- cliente_id
- tipo
- número
- es_principal
- observación
- estado

## 13.7 Tabla: clientes_correos
- id
- cliente_id
- correo
- es_principal
- estado

## 13.8 Tabla: obligaciones
- id
- cliente_id
- código_interno
- tipo_obligación
- concepto
- origen_talonario
- fecha_generación
- fecha_vencimiento
- valor_inicial
- saldo_actual
- estado_obligación
- nivel_riesgo
- fecha_último_abono
- valor_último_abono
- antigüedad_días
- observaciones

## 13.9 Tabla: pagos
- id
- cliente_id
- obligación_id nullable
- fecha_pago
- valor
- medio_pago
- referencia_transacción
- comprobante_url
- estado_validación
- registrado_por
- fecha_registro

## 13.10 Tabla: soportes_documentales
- id
- cliente_id
- obligación_id nullable
- tipo_soporte
- existe_flag
- vigente_flag
- archivo_url
- observación
- fecha_registro

## 13.11 Tabla: ubicaciones_físicas_expediente
- id
- cliente_id
- descripción_ubicación
- responsable
- fecha_registro

## 13.12 Tabla: gestiones_cobranza
- id
- cliente_id
- obligación_id nullable
- usuario_id
- fecha_gestión
- canal
- resultado
- observación
- compromiso_pago_fecha
- compromiso_pago_valor
- próxima_gestión_fecha

## 13.13 Tabla: campañas
- id
- nombre
- canal
- segmento_definición
- plantilla_id
- fecha_envío
- enviado_por
- estado

## 13.14 Tabla: mensajes_enviados
- id
- campaña_id
- cliente_id
- canal
- destinatario
- mensaje_generado
- url_unica
- estado_envío
- fecha_envío
- fecha_apertura
- fecha_clic
- metadatos

## 13.15 Tabla: plantillas_mensajes
- id
- nombre
- canal
- asunto
- contenido
- nivel_riesgo_aplicable
- estado

## 13.16 Tabla: eventos_analíticos
- id
- cliente_id
- tipo_evento
- canal_origen
- ip
- dispositivo
- user_agent
- url
- fecha_evento
- metadatos

## 13.17 Tabla: estados_paramétricos
- id
- categoría
- código
- nombre
- color
- orden

## 13.18 Tabla: auditoría
- id
- usuario_id
- entidad
- entidad_id
- acción
- valor_anterior
- valor_nuevo
- ip
- fecha

---

## 14. Relaciones lógicas mínimas

- Un **cliente** puede tener muchos teléfonos.
- Un **cliente** puede tener muchos correos.
- Un **cliente** puede tener muchas obligaciones.
- Una **obligación** puede tener muchos pagos.
- Un **cliente** puede tener muchos soportes.
- Un **cliente** puede tener muchas gestiones.
- Una **campaña** puede generar muchos mensajes.
- Un **cliente** puede generar muchos eventos analíticos.
- Todo cambio importante debe apuntar a auditoría.

---

## 15. Requerimientos no funcionales

## RNF-001. Seguridad
- cifrado de contraseñas,
- control de roles,
- sesiones seguras,
- bitácora de auditoría,
- protección de datos sensibles.

## RNF-002. Rendimiento
La plataforma debe responder de forma fluida aun con alto volumen histórico.

## RNF-003. Escalabilidad
La arquitectura debe soportar crecimiento de:
- registros,
- campañas,
- usuarios,
- adjuntos,
- y consultas concurrentes.

## RNF-004. Usabilidad
La experiencia del usuario interno debe priorizar rapidez operativa y lectura clara.

## RNF-005. Responsive
El portal del deudor debe ser mobile-first.

## RNF-006. Trazabilidad
Toda acción relevante debe quedar registrada.

## RNF-007. Integridad de datos
No debe haber pérdida de historial al editar registros.

## RNF-008. Parametrización
Los catálogos clave deben poder gestionarse sin despliegues constantes.

## RNF-009. Disponibilidad
La plataforma debe tener un nivel de disponibilidad adecuado para operación continua.

## RNF-010. Cumplimiento
Debe alinearse a políticas de tratamiento de datos y manejo seguro de información.

---

## 16. Lineamientos UX/UI para el equipo de experiencia

## 16.1 Vista administrativa
Debe priorizar:
- velocidad de búsqueda,
- lectura rápida,
- filtros visibles,
- estados por color,
- acciones frecuentes al alcance.

## 16.2 Ficha del cliente
Debe organizarse por bloques:

1. Datos principales  
2. Contactos  
3. Deuda consolidada  
4. Detalle de obligaciones  
5. Soportes documentales  
6. Ubicación física  
7. Historial de gestiones  
8. Pagos  
9. Analítica / interacción  
10. Acciones rápidas  

## 16.3 Dashboard gerencial
Debe ser visual, resumido y accionable:
- KPIs arriba,
- gráficos de distribución,
- comparativos por periodo,
- listados prioritarios abajo.

## 16.4 Portal del deudor
Debe ser:
- limpio,
- confiable,
- simple,
- sin ruido,
- con CTA principal visible desde el primer pantallazo.

## 16.5 Estados visuales recomendados
- Verde: pagado / recuperado / positivo
- Amarillo: seguimiento / preventivo
- Naranja: riesgo medio
- Rojo: crítico / S3 / jurídico
- Gris: inactivo / sin contacto / no disponible

---

## 17. Arquitectura funcional sugerida para el equipo técnico

### 17.1 Capas recomendadas
- **Frontend administrativo**
- **Frontend portal deudor**
- **Backend API / lógica de negocio**
- **Motor ETL**
- **Motor de comunicaciones**
- **Módulo de analítica**
- **Base de datos relacional**
- **Almacenamiento de archivos**
- **Integraciones externas**

### 17.2 Integraciones probables
- pasarela de pagos,
- proveedor de email,
- proveedor SMS,
- acortador de URL,
- analítica web,
- ERP o sistema contable futuro si aplica.

---

## 18. Priorización por fases recomendada

## Fase 1 — Núcleo operativo
- autenticación,
- clientes,
- obligaciones,
- soporte documental básico,
- historial de gestión,
- filtros,
- dashboard básico,
- importación Excel.

## Fase 2 — Automatización y recaudo
- campañas,
- plantillas,
- landing deudor,
- pagos,
- verificación manual,
- trazabilidad de clics.

## Fase 3 — Inteligencia y madurez
- reglas avanzadas,
- scoring,
- analítica de comportamiento,
- alertas,
- reportes de crédito futuro,
- integraciones más profundas.

---

## 19. Riesgos que el diseño debe contemplar

1. Mala calidad de datos históricos.
2. Duplicidad de clientes por documento o placa.
3. Soportes físicos no localizables.
4. Vacíos documentales que afectan recuperación jurídica.
5. Falta de contacto digital de muchos deudores.
6. Reglas legales sobre tratamiento de datos.
7. Dependencia de validación manual en etapas iniciales.
8. Diferencias entre saldo contable y saldo operativo histórico.

---

## 20. Decisiones de diseño clave para el equipo

1. La plataforma debe construirse sobre una **entidad cliente fuerte**.
2. La **obligación** debe ser una entidad independiente y trazable.
3. Los **documentos** no son un anexo decorativo: son parte central del negocio.
4. La **fecha del último abono** debe ser tratada como campo estratégico.
5. Debe existir **consolidado + detalle**, no uno u otro.
6. La plataforma debe permitir **operación humana** y **automatización** al tiempo.
7. UX de administración y UX del deudor deben pensarse como productos distintos.
8. La data analítica debe servir para **recaudo** y para **decisiones de crédito futuro**.

---

## 21. Definición final del producto a construir

### En una frase:
Una **plataforma integral de gestión de cartera, crédito, soporte documental, cobranza, recaudo y analítica**, diseñada para que Servillantas recupere cartera de forma profesional, escalable y jurídicamente soportada.

### En términos de producto:
No es solo un CRM, no es solo un software de cobro, no es solo una landing de pagos.  
Es un sistema híbrido que une:

- cartera,
- crédito,
- soporte legal,
- operación humana,
- automatización,
- recaudo digital,
- y toma de decisiones.

---

## 22. Entregable esperado para el siguiente paso del equipo

Con base en este documento, el equipo de trabajo debería producir como mínimo:

### Equipo UX/UI
- mapa de navegación,
- arquitectura de información,
- flujos por rol,
- wireframes de dashboard,
- wireframes de ficha cliente,
- wireframes del portal del deudor,
- sistema de estados visuales,
- prototipo navegable inicial.

### Equipo de desarrollo
- modelo entidad-relación,
- diccionario de datos,
- arquitectura técnica,
- backlog por módulos,
- historias de usuario,
- definición de APIs,
- estrategia ETL,
- estrategia de auditoría,
- plan de integraciones.

---

## 23. Conclusión ejecutiva

La sesión 02 aporta una visión más aterrizada del sistema como plataforma operativa y de recaudo; la sesión 01 aporta reglas de negocio de alto valor, especialmente en lo documental, jurídico y logístico. La combinación correcta de ambas no debe producir un sistema limitado a mensajería o dashboard, sino una herramienta robusta donde la deuda pueda entenderse, priorizarse, documentarse, cobrarse, pagarse y analizarse.

Ese es el producto que realmente necesita el cliente.

---
