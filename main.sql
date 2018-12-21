CREATE DATABASE IF NOT EXISTS `sobrecostos`;

CREATE TABLE IF NOT EXISTS `items` (
  `ID_ITEM` CHAR(6) NOT NULL,
  `ID_REFERENCIA` CHAR(15) DEFAULT NULL,
  `DESCRIPCION` CHAR(40) DEFAULT NULL,
  `ID_LINEA2` CHAR(6) DEFAULT NULL,
  `ID_GRUPO2` CHAR(6) DEFAULT NULL,
  `UNIMED_INV_1` CHAR(3) DEFAULT NULL,
  `UNIMED_EMPAQ` CHAR(3) DEFAULT NULL,
  `FACTOR_EMPAQ` decimal(20,4) DEFAULT NULL,
  `PESO` decimal(20,4) DEFAULT NULL,
  `VOLUMEN` decimal(20,4) DEFAULT NULL,
  `ULTIMO_COSTO_ED` decimal(20,4) DEFAULT NULL,
  `FECHA_INGRESO` CHAR(8) DEFAULT NULL,
  `LOTE` CHAR(2) DEFAULT 'NO',
  `IVA` FLOAT(20,2) DEFAULT '0.00',
  PRIMARY KEY (`ID_ITEM`),
  KEY `ID_REFERENCIA` (`ID_REFERENCIA`),
  KEY `DESCRIPCION` (`DESCRIPCION`)
) ENGINE=InnoDB DEFAULT CHARSET=LATIN1;

CREATE TABLE IF NOT EXISTS `cod_barras` (
  `ID_ITEMS` CHAR(6) NOT NULL,
  `ID_CODBAR` CHAR(15) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `UNIMED_VENTA` CHAR(3) DEFAULT NULL,
  PRIMARY KEY (`ID_CODBAR`),
  KEY `BAR_ITEM` (`ID_ITEMS`),
  CONSTRAINT `BAR_ITEM` FOREIGN KEY (`ID_ITEMS`) REFERENCES `items` (`ID_ITEM`)
) ENGINE=InnoDB DEFAULT CHARSET=LATIN1;

CREATE TABLE IF NOT EXISTS `sedes` (
  `codigo`        CHAR(6) NOT NULL,
  `descripcion`   CHAR(40) DEFAULT NULL,
  `direccion1`    CHAR(40) DEFAULT NULL,
  `direccion2`    CHAR(40) DEFAULT NULL,
  `direccion3`    CHAR(40) DEFAULT NULL,
  `grupo_co`      CHAR(2) DEFAULT NULL,
  `codigo_drog`   CHAR(5) DEFAULT '13803',

  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=LATIN1;

CREATE TABLE IF NOT EXISTS `sobrantes`(
  `item` CHAR(6) NOT NULL,
  `sede` CHAR(6) NOT NULL,
  `factor` FLOAT(5,2) NOT NULL,
  `ultimo_costo` FLOAT(8,2) NOT NULL,
  `inventario_IO` INT(4),
  `rotacion`  INT(4),
  `prom_6_meses` FLOAT(8,2),
  `sobrante` FLOAT(4,2),
  `estado` INT(1) DEFAULT 0,

  PRIMARY KEY(`item`,`sede`),

  CONSTRAINT sobrante_item
	FOREIGN KEY(`item`) 
	REFERENCES `items`(`ID_ITEM`),

  CONSTRAINT sobrante_sede
	FOREIGN KEY(`sede`) 
	REFERENCES `sedes`(`codigo`),

  INDEX(`sobrante`)
) ENGINE=InnoDB DEFAULT CHARSET=LATIN1;

CREATE TABLE IF NOT EXISTS `perfiles`(
	`id_perfil` 		INT(1) NOT NULL AUTO_INCREMENT,
	`des_perfil` 		CHAR(20),

	PRIMARY KEY(`id_perfil`)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `usuarios`(
  `id_usuario` 		INT(10) NOT NULL AUTO_INCREMENT,	
	`nombre` 				VARCHAR(40) COLLATE ucs2_spanish_ci,
	`cedula` 				CHAR(12),
	`usuario` 			CHAR(20),
	`password` 			VARCHAR(60) NOT NULL,
	`perfil` 				INT(1),
  `sede`          CHAR(6) NOT NULL,

	PRIMARY KEY(`id_usuario`),
	UNIQUE(`cedula`),
	UNIQUE(`usuario`),
	
	CONSTRAINT usuario_perfil
	FOREIGN KEY(`perfil`)
	REFERENCES `perfiles`(`id_perfil`),
	
	CONSTRAINT usuario_sedes
	FOREIGN KEY(`sede`)
	REFERENCES `sedes`(`codigo`),	
	
	INDEX (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=LATIN1;

CREATE TABLE IF NOT EXISTS `plaremi`(
	`factura` CHAR(20) NOT NULL,
	`cod_drog` CHAR(5) ,
	`fecha` DATETIME,
	`sede` CHAR(6) NOT NULL,
	
	PRIMARY KEY(`factura`),
	
	CONSTRAINT plaremi_sede
	FOREIGN KEY(`sede`) 
	REFERENCES `sedes`(`codigo`)


) ENGINE=InnoDB DEFAULT CHARSET=LATIN1;

CREATE TABLE IF NOT EXISTS `plaremi_det`(
	`item` CHAR(6) NOT NULL,
   `factura` CHAR(20) NOT NULL,
  	`descripcion` CHAR(40),
   `refcopi` CHAR(15)NOT NULL,
	`pedido` FLOAT(7,2) NOT NULL,
	`costo_desc` FLOAT(8,2) NOT NULL,
	`costo_full` FLOAT(8,2) NOT NULL,
	`iva` FLOAT(4,2) NOT NULL,
	`descuento1` FLOAT(5,4) NOT NULL,
	`cod_barras` CHAR(13) NOT NULL,
	`cod_fab` CHAR(5) NOT NULL,
	`descuento2` FLOAT(5,4) NOT NULL,
	`unidad` CHAR(4) NOT NULL,
	`algo1` INT(10) NOT NULL,
	`algo2` INT(8) NOT NULL,
	`total` FLOAT(7,2) NOT NULL,
	`estado` INT(1) DEFAULT 0,
    
	
	PRIMARY KEY(`item`,`factura`),
	
	CONSTRAINT plaremi_det_plaremi
	FOREIGN KEY(`factura`) 
	REFERENCES `plaremi`(`factura`),
	
	
	CONSTRAINT plaremi_det_item
	FOREIGN KEY(`item`) 
	REFERENCES `items`(`ID_ITEM`)	
	
	
) ENGINE=InnoDB DEFAULT CHARSET=LATIN1;

CREATE TABLE IF NOT EXISTS `plaremi_det_error`(
	`id` INT(10) AUTO_INCREMENT NOT NULL,
   `factura` CHAR(20) NOT NULL,
  	`descripcion` CHAR(40),
   `refcopi` CHAR(15)NOT NULL,
	`pedido` FLOAT(7,2) NOT NULL,
	`costo_desc` FLOAT(8,2) NOT NULL,
	`costo_full` FLOAT(8,2) NOT NULL,
	`iva` FLOAT(4,2) NOT NULL,
	`descuento1` FLOAT(5,4) NOT NULL,
	`cod_barras` CHAR(13) NOT NULL,
	`cod_fab` CHAR(5) NOT NULL,
	`descuento2` FLOAT(5,4) NOT NULL,
	`unidad` CHAR(4) NOT NULL,
	`algo1` INT(10) NOT NULL,
	`algo2` INT(8) NOT NULL,
	`total` FLOAT(7,2) NOT NULL,
	`estado` INT(1) DEFAULT 0,
    
	
	PRIMARY KEY(`id`,`factura`),
	
	CONSTRAINT plaremi_det_error_plaremi
	FOREIGN KEY(`factura`) 
	REFERENCES `plaremi`(`factura`)
	
) ENGINE=InnoDB DEFAULT CHARSET=LATIN1;

CREATE TABLE IF NOT EXISTS `transferencias`(
	`id_transferencia` INT(6) AUTO_INCREMENT NOT NULL,
	`no_transferencia` CHAR(10),
	`origen` CHAR(6) NOT NULL,
	`destino` CHAR(6) NOT NULL,
	`fecha` DATETIME,
	`encargado` INT(10) NOT NULL,
	`estado` INT(1) DEFAULT 0,
	
	
	PRIMARY KEY(`id_transferencia`),
	UNIQUE(no_transferencia),
	
	CONSTRAINT transferencias_origen
	FOREIGN KEY(`origen`) 
	REFERENCES `sedes`(`codigo`),
	
	CONSTRAINT transferencias_destino
	FOREIGN KEY(`destino`) 
	REFERENCES `sedes`(`codigo`),

	CONSTRAINT transferencias_usuario
	FOREIGN KEY(`encargado`) 
	REFERENCES `usuarios`(`id_usuario`),
	
	INDEX(`estado`)
	
) ENGINE=InnoDB DEFAULT CHARSET=LATIN1;

CREATE TABLE IF NOT EXISTS `transferencias_det`(
	`item` CHAR(6) NOT NULL,
	`id_transferencia` INT(6) NOT NULL,
	`plaremi` CHAR(20),
	`pedido` FLOAT(4,2),

	
	PRIMARY KEY(`item`,`id_transferencia`),
	
	CONSTRAINT trans_det_plaremi
	FOREIGN KEY(`item`,`plaremi`) 
	REFERENCES `plaremi_det`(`item`,`factura`),
	
	CONSTRAINT trans_det_trans
	FOREIGN KEY(`id_transferencia`) 
	REFERENCES `transferencias`(`id_transferencia`)	
	
) ENGINE=InnoDB DEFAULT CHARSET=LATIN1;

--
-- Dumping data for table `sedes`
--

/*******************************************************************************************************************************
											INICIALIZA REGISTROS BASE DE DATOS 
********************************************************************************************************************************/
/*
REPLACE INTO perfiles VALUES(-1,"Inactivo"),(1,"Administrador"),(2,"Autorizado"),(3,"Punto de Venta");
UPDATE perfiles SET id_perfil=0 WHERE id_perfil=-1;

REPLACE INTO usuarios(nombre,cedula,usuario,password,perfil,sede) VALUES("Administrador","0","admin","$2y$10$bpNOdujEVRMWB7JtWJX7Y.HPBjVCMSLS/r2YeafW5Mu.wfmyi/iLy",1,"001");

REPLACE INTO `sedes` VALUES 
  ('001',' CENTRO',' CR 2 14 34','','',' 0','13803'),
  ('002',' VERSALLES',' CL 23BN 3N 100','','',' 0','13803'),
  ('003',' CAMINO REAL',' CL 9 51 05','','',' 0','13803'),
  ('004',' ALFONSO LOPEZ',' CL 70 7TBIS 59','','',' 0','13803'),
  ('005',' INGENIO',' CR 85C 15 119','','',' 0','13803'),
  ('006',' VILLA DEL SUR',' CR 42A 26E 41','','',' 0','13803'),
  ('007',' PORTADA',' AV 4OESTE 7 47','','',' 0','13803'),
  ('008',' SAN FERNANDO',' CL 5 37 A 65/67','','',' 0','13803'),
  ('009',' CALIMA LA 14',' CL 70 1 245 L CENTRO COMERCIAL CALIMA','','',' 0','13803'),
  ('010',' PLAZA CAICEDO',' CR 5 12 16 LOCAL 1','','',' 0','13803'),
  ('011',' VALLE LILI',' CR 98B 25 130','','',' 0','13803'),
  ('012',' COSMOCENTRO',' CL 5 50 106 LC 282','','',' 0','13803'),
  ('013',' CALLE 7A',' CL 7 No.30A 12','','',' 0','13803'),
  ('014',' CENTRO SAN JORGE',' CR 2 14 34','','',' 0','13803'),
  ('015',' LA FLORA',' CL 52 N NRO 5B88','','',' 0','13803'),
  ('016',' UNICENTRO LOCAL 362',' CR 100 5 169','','',' 0','13803'),
  ('017',' ALFAGUARA LOCAL 1 66',' CL 2#22175 CENTRO COMERCIAL ALFAGUARA',' A LOCAL 166','','\r','13803'),
  ('018',' ELITE',' CR 7 1452 LOCAL 156 PRIMER PISO',' EDIFICIO COMERCIAL CENTRO ELITE','','\r','13803'),
  ('019',' VILLA COLOMBIA',' CLL 50 12 09 L 102A','','','\r','13803'),
  ('020',' GASTOS ADMINISTRATIVOS POR REPARTIR',' CLL 23BN 3N 100','','','\r','13803'),
  ('021',' VINCULADAS',' CL 23 B N 3 N 100','','','\r','13803'),
  ('022',' CALL CENTER',' CL 23BN 3N 100','','','\r','13803'),
  ('023',' JARDIN PLAZA',' CL 16 98 155','','','\r','13803'),
  ('090',' MEDICAMENTOS',' CR 2 14 26','','','\r','13803'),
  ('091',' COSMETICOS',' CR 2 14 26','','','\r','13803'),
  ('092',' VARIOS',' CR 2 14 26','','','\r','13803'),
  ('093',' REEMPAQUE',' CR 2 14 26','','','\r','13803'),
  ('099',' VENTAS AL POR MAYOR (CREDITOS)',' CR 2 14 34','','','\r','13803'),
  ('100',' CALLE 7A',' CL 7 30A 12','','','\r','13803'),
  ('101',' BOGOTA CHAPINERO',' CLL 53 13 64',' CLL 53 13 52','','\r','13803'),
  ('102',' BOGOTA AUTONORTE',' LOCAL #1 AV CR 45 #97 80','','','\r','13803'),
  ('110',' GASTOS ADMINISTRATIVOS POR DISTRIBUIR',' CLL 7 30A12','','','\r','13803'),
  ('900',' LABORATORIO SAN JORGE LTDA',' CR 2 14 26','','','\r','13803'),
  ('XXX',' C.O PARA CIERRE','','','','\r','13803'
);
*/
/*******************************************************************************************************************************
											PROCEDIMIENTOS FUNCIONES Y TRIGGERS BASE DE DATOS
********************************************************************************************************************************/
DROP PROCEDURE IF EXISTS BuscarItemsTransferencia;
DROP TRIGGER IF EXISTS SetTotalPlaremi;
DROP TRIGGER IF EXISTS CambiarEstadoPlaremi;
DROP TRIGGER IF EXISTS RestablecerEstadoPlaremi;
DROP TRIGGER IF EXISTS CambiarSobrantes;



DELIMITER $$
	CREATE PROCEDURE BuscarItemsTransferencia(IN plaremi CHAR(20),IN encargado INT(10))
	BEGIN
	
		SELECT 
		transferencias_det.item,items.DESCRIPCION AS descripcion,transferencias.id_transferencia,plaremi,transferencias.origen,
		sedes.descripcion AS origensede,plaremi_det.pedido AS solicitado, transferencias_det.pedido,sobrantes.sobrante
		FROM transferencias_det
		INNER JOIN plaremi_det ON (plaremi_det.item=transferencias_det.item AND plaremi_det.factura=transferencias_det.plaremi)
		INNER JOIN transferencias ON transferencias.id_transferencia=transferencias_det.id_transferencia
		INNER JOIN items ON items.ID_ITEM=plaremi_det.item
		INNER JOIN sedes ON sedes.codigo=transferencias.origen
		INNER JOIN sobrantes ON (sobrantes.item=transferencias_det.item AND transferencias.origen=sobrantes.sede)
		WHERE plaremi=plaremi
		AND transferencias.encargado=encargado
		AND transferencias.estado=0;
		
	END 
$$

-- asigna total al insertar registro en la remision
DELIMITER $$
	CREATE TRIGGER SetTotalPlaremi
	BEFORE INSERT ON plaremi_det
	FOR EACH ROW 
	BEGIN

		SET new.total=new.pedido;
				
	END 
$$


-- cambia el estado de los items de la remision
DELIMITER $$
	CREATE TRIGGER CambiarEstadoPlaremi
	AFTER INSERT ON transferencias_det
	FOR EACH ROW 
	BEGIN

		UPDATE plaremi_det
		SET estado=1
		WHERE plaremi_det.item=new.item
    	AND plaremi_det.factura=new.plaremi;
    	
    	UPDATE sobrantes
		SET estado=1
		WHERE sobrantes.item=new.item
    	AND sobrantes.sede=(SELECT origen FROM transferencias WHERE id_transferencia=new.id_transferencia LIMIT 1);
				
	END 
$$

-- cambia el estado de los items de la remision
DELIMITER $$
	CREATE TRIGGER RestablecerEstadoPlaremi
	BEFORE DELETE ON transferencias_det
	FOR EACH ROW 
	BEGIN

		UPDATE plaremi_det
		SET estado=0
		WHERE plaremi_det.item=old.item
    	AND plaremi_det.factura=old.plaremi;
    	
    	UPDATE sobrantes
		SET estado=0
		WHERE sobrantes.item=old.item
    	AND sobrantes.sede=(SELECT origen FROM transferencias WHERE id_transferencia=old.id_transferencia LIMIT 1);

	END 
$$

-- trigger que modifica la cantidad sobrante al crear transferencia
DELIMITER $$
	CREATE TRIGGER CambiarSobrantes
	AFTER UPDATE ON transferencias_det
	FOR EACH ROW 
	BEGIN
		
		UPDATE plaremi_det
		SET estado=0,total=total-new.pedido
		WHERE plaremi_det.item=new.item
    	AND plaremi_det.factura=new.plaremi;
    	
		UPDATE sobrantes
		SET sobrante=sobrante-new.pedido,estado=0
		WHERE sobrantes.item=new.item
    	AND sobrantes.sede=(SELECT origen FROM transferencias WHERE id_transferencia=new.id_transferencia LIMIT 1);
    	
    	
				
	END 
$$

