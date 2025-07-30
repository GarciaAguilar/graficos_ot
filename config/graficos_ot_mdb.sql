/*
 Navicat Premium Data Transfer

 Source Server         : mariaDB
 Source Server Type    : MariaDB
 Source Server Version : 100432
 Source Host           : localhost:3306
 Source Schema         : graficos_ot

 Target Server Type    : MariaDB
 Target Server Version : 100432
 File Encoding         : 65001

 Date: 30/07/2025 17:00:02
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for ordent
-- ----------------------------
DROP TABLE IF EXISTS `ordent`;
CREATE TABLE `ordent`  (
  `id_orden` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_ot` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tipo_mantenimiento` tinyint(4) NOT NULL,
  `estado_ot` tinyint(4) NOT NULL,
  `fecha_creacion` timestamp(0) NOT NULL DEFAULT current_timestamp,
  `fecha_limite` date NOT NULL,
  `fecha_inicio` datetime(0) NOT NULL,
  `fecha_fin` datetime(0) NOT NULL,
  `tiempo_total` int(11) NOT NULL,
  `prioridad` tinyint(4) NULL DEFAULT NULL,
  `id_equipo` int(11) NOT NULL,
  `id_item` int(11) NULL DEFAULT NULL,
  `ubicacion_especifica` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `sucursal` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `zona_piso` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `id_tecnico` int(11) NULL DEFAULT NULL,
  `id_proveedor` int(11) NULL DEFAULT NULL,
  `modo` tinyint(4) NULL DEFAULT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `causa_principal` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `solucion_ejecutada` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `aprobado_por` int(11) NULL DEFAULT NULL,
  `eliminado_por` int(11) NULL DEFAULT NULL,
  `fecha_eliminado` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id_orden`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 21 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ordent
-- ----------------------------
INSERT INTO `ordent` VALUES (1, 'OT-001', 1, 6, '2025-07-30 09:59:39', '2025-07-01', '2025-06-30 09:00:00', '2025-07-02 11:30:00', 3030, 3, 101, NULL, 'Planta 1 - Línea A', 'Sucursal Central', 'Planta Baja', 201, NULL, 0, 'Cambio de motor eléctrico.', 'Desgaste mecánico.', 'Motor reemplazado y calibrado.', 301, NULL, NULL);
INSERT INTO `ordent` VALUES (2, 'OT-002', 0, 5, '2025-07-30 09:59:39', '2025-07-03', '2025-07-02 08:00:00', '2025-07-04 08:45:00', 2925, 2, 102, NULL, 'Zona de carga', 'Sucursal Norte', 'Primer piso', 202, NULL, 0, 'Revisión de sistema hidráulico.', 'Rutina preventiva.', 'Presión verificada, sin novedades.', 302, NULL, NULL);
INSERT INTO `ordent` VALUES (3, 'OT-003', 1, 3, '2025-07-30 09:59:39', '2025-07-05', '2025-07-04 10:00:00', '2025-07-07 12:00:00', 4440, 1, 103, NULL, 'Área de empaque', 'Sucursal Sur', 'Nivel 2', 203, NULL, 0, 'Fuga en manguera de presión.', 'Daño por presión excesiva.', NULL, NULL, NULL, NULL);
INSERT INTO `ordent` VALUES (4, 'OT-004', 0, 6, '2025-07-30 09:59:39', '2025-06-28', '2025-06-27 14:00:00', '2025-06-29 14:25:00', 2905, 0, 104, NULL, 'Recepción principal', 'Sucursal Este', 'Lobby', 204, NULL, 0, 'Inspección de luminaria LED.', 'Plan de mantenimiento mensual.', 'Todo dentro de parámetros.', 301, NULL, NULL);
INSERT INTO `ordent` VALUES (5, 'OT-005', 1, 7, '2025-07-30 09:59:39', '2025-07-02', '2025-07-01 15:00:00', '2025-07-03 16:15:00', 2955, 3, 105, NULL, 'Planta 2 - Zona técnica', 'Sucursal Central', 'Subsuelo', 205, NULL, 0, 'Reparación de compresor.', 'Sobrecalentamiento del motor.', 'Componente reemplazado pero sin aprobación.', NULL, NULL, NULL);
INSERT INTO `ordent` VALUES (6, 'OT-006', 1, 5, '2025-07-30 09:59:39', '2025-07-04', '2025-07-03 10:30:00', '2025-07-05 11:20:00', 2930, 1, 106, 301, 'Baño mujeres', 'Sucursal Norte', 'Nivel 1', 206, NULL, 0, 'Reparación de secador de manos.', 'Cableado interno dañado.', 'Reemplazo de fusible y prueba exitosa.', 302, NULL, NULL);
INSERT INTO `ordent` VALUES (7, 'OT-007', 0, 6, '2025-07-30 09:59:39', '2025-07-10', '2025-07-09 09:00:00', '2025-07-12 09:30:00', 4350, 2, 107, NULL, 'Cuarto eléctrico', 'Sucursal Sur', 'Piso Técnico', 207, NULL, 0, 'Rutina eléctrica mensual.', 'Checklist programado.', 'Todo correcto, sin anomalías.', 303, NULL, NULL);
INSERT INTO `ordent` VALUES (8, 'OT-008', 1, 6, '2025-07-30 09:59:39', '2025-07-06', '2025-07-05 16:00:00', '2025-07-08 17:10:00', 4390, 2, 108, NULL, 'Área refrigerada', 'Sucursal Este', 'Subnivel -2', 208, NULL, 0, 'Falla de enfriamiento.', 'Sensor de temperatura averiado.', 'Sensor cambiado, funcionando normal.', 301, NULL, NULL);
INSERT INTO `ordent` VALUES (9, 'OT-009', 0, 7, '2025-07-30 09:59:39', '2025-07-15', '2025-07-14 07:00:00', '2025-07-16 07:25:00', 2905, 1, 109, NULL, 'Zona de carga externa', 'Sucursal Central', 'Exteriores', 209, NULL, 0, 'Verificación de cámara de vigilancia.', 'Plan semanal de prevención.', 'Todo en orden.', 304, NULL, NULL);
INSERT INTO `ordent` VALUES (10, 'OT-010', 1, 6, '2025-07-30 09:59:39', '2025-07-07', '2025-07-06 12:00:00', '2025-07-09 13:15:00', 4395, 3, 110, NULL, 'Laboratorio 3', 'Sucursal Norte', 'Nivel 3', 210, NULL, 0, 'Intervención de bomba dosificadora.', 'Obstrucción por minerales.', 'Bomba limpia y calibrada.', 305, NULL, NULL);
INSERT INTO `ordent` VALUES (11, 'OT-011', 1, 6, '2025-07-30 10:00:28', '2025-07-08', '2025-07-07 09:30:00', '2025-07-10 11:10:00', 4420, 3, 111, NULL, 'Pasillo técnico', 'Sucursal Sur', 'Zona A', 0, 401, 1, 'Cambio de panel electrónico.', 'Falla en tarjeta madre.', 'Contratista realizó reemplazo completo.', 301, NULL, NULL);
INSERT INTO `ordent` VALUES (12, 'OT-012', 1, 5, '2025-07-30 10:00:28', '2025-07-09', '2025-07-08 10:00:00', '2025-07-11 10:45:00', 4365, 2, 112, 302, 'Baño hombres', 'Sucursal Este', 'Primer Nivel', 0, 402, 1, 'Reparación de grifo.', 'Obstrucción por sarro.', 'Cambio de cartucho interno.', 302, NULL, NULL);
INSERT INTO `ordent` VALUES (13, 'OT-013', 1, 3, '2025-07-30 10:00:28', '2025-07-10', '2025-07-09 14:00:00', '2025-07-12 16:00:00', 4440, 3, 113, NULL, 'Zona de lavados', 'Sucursal Central', 'Subsuelo', 0, 403, 1, 'Fuga en drenaje principal.', 'Rotura por presión externa.', NULL, NULL, NULL, NULL);
INSERT INTO `ordent` VALUES (14, 'OT-014', 1, 2, '2025-07-30 10:00:28', '2025-07-12', '2025-07-11 13:00:00', '2025-07-14 15:00:00', 4440, 2, 114, NULL, 'Cuarto de calderas', 'Sucursal Norte', 'Planta Técnica', 211, NULL, 0, 'Sustitución de válvula de seguridad.', 'Válvula corroída.', NULL, NULL, NULL, NULL);
INSERT INTO `ordent` VALUES (15, 'OT-015', 1, 9, '2025-07-30 10:00:28', '2025-06-25', '2025-06-24 10:00:00', '2025-06-27 12:00:00', 4440, 3, 115, NULL, 'Pasillo central', 'Sucursal Sur', 'PB', 212, NULL, 0, 'Solicitud fue duplicada por error.', NULL, NULL, NULL, 301, '2025-06-24 11:00:00');
INSERT INTO `ordent` VALUES (16, 'OT-016', 0, 3, '2025-07-30 10:00:28', '2025-07-11', '2025-07-10 15:00:00', '2025-07-13 17:00:00', 4440, 0, 116, NULL, 'Cuarto frío', 'Sucursal Este', 'Nivel -1', 213, NULL, 0, 'Verificación por checklist.', 'Checklist mensual.', NULL, NULL, NULL, NULL);
INSERT INTO `ordent` VALUES (17, 'OT-017', 1, 5, '2025-07-30 10:00:28', '2025-07-13', '2025-07-12 09:00:00', '2025-07-15 09:45:00', 4365, 1, 117, NULL, 'Zona de archivo', 'Sucursal Central', 'Nivel 2', 214, NULL, 0, 'Falla en cerradura electrónica.', 'Desgaste por uso continuo.', 'Mecanismo rearmado.', 305, NULL, NULL);
INSERT INTO `ordent` VALUES (18, 'OT-018', 0, 8, '2025-07-30 10:00:28', '2025-07-14', '2025-07-13 07:00:00', '2025-07-16 07:25:00', 4345, 1, 118, NULL, 'Control de acceso', 'Sucursal Norte', 'Nivel 1', 215, NULL, 0, 'Revisión de lector biométrico.', 'Validación rutinaria.', 'Sin incidencias.', 302, NULL, NULL);
INSERT INTO `ordent` VALUES (19, 'OT-019', 1, 7, '2025-07-30 10:00:28', '2025-07-15', '2025-07-14 14:30:00', '2025-07-17 15:20:00', 4370, 2, 119, 303, 'Depósito externo', 'Sucursal Sur', 'Patio', 216, NULL, 0, 'Reparación de puerta corrediza.', 'Guía inferior deformada.', 'Se ajustó y lubricó mecanismo.', 304, NULL, NULL);
INSERT INTO `ordent` VALUES (20, 'OT-020', 1, 7, '2025-07-30 10:00:28', '2025-07-16', '2025-07-15 08:00:00', '2025-07-18 09:10:00', 4390, 3, 120, NULL, 'Cuarto de servidores', 'Sucursal Este', 'Nivel 3', 217, NULL, 0, 'Fallo de ventilación forzada.', 'Acumulación de polvo en filtros.', 'Filtros reemplazados y monitoreo establecido.', 305, NULL, NULL);

SET FOREIGN_KEY_CHECKS = 1;
