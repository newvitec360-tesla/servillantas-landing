# Matriz Requerimientos vs Código - Servillantas

Cruce del `Documento-ND-Requerimientos-Consolidados-Servillantas.md` con la implementación física en la carpeta `servillantas_mvc`.

## Matriz de Cobertura Funcional

| Código RF / Módulo | Requerimiento | Existe Visualmente | Existe Técnicamente | Nivel de Avance | Riesgo | Prioridad |
|--------------------|---------------|-------------------|--------------------|-----------------|--------|-----------|
| **RF-001** | Login Seguro | Sí (login.php) | No (Falta Auth logic) | UI Demo | Alto | Alta |
| **RF-004** | Carga Excel | No | Parcial (`EtlImportService`) | Base | Alto | Alta |
| **RF-014** | Crear Obligación | Parcial | Sí (Schema) | Base | Medio | Alta |
| **RF-021** | Riesgo S1/S2/S3 | No visible | Sí (`RiskScoringService`) | Base | Medio | Media |
| **RF-026** | Soportes Documentales | Sí | Sí (Schema) | Base | Bajo | Media |
| **RF-031** | Historial Gestiones | Sí | Sí (Schema) | Base | Medio | Alta |
| **RF-036** | Campañas Segmentadas| Sí | Sí (Schema) | Base | Alto | Media |
| **RF-049** | Pagos / Pasarela | Parcial | Sí (Schema) | Base | Alto | Baja (Fase 2) |
| **RF-053** | Dashboard Ejecutivo | Sí | No (Data estática) | UI Demo | Medio | Media |
| **RF-060** | Auditoría | No | Sí (Schema) | Base | Alto | Alta |

## Resumen de Cobertura
- **Base de Datos**: Cumple ~95% con los RFs descritos.
- **Interfaz (Desktop/Mobile)**: Cumple ~60% a nivel estático (Faltan validaciones y modales de formularios complejos).
- **Controladores / Backend**: Cumple ~10%. La arquitectura existe, pero la programación lógica (Query Builders, SQL INSERTS, Validaciones, Middlewares) está vacía.
