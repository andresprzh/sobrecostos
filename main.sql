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
  `codigo` CHAR(6) NOT NULL,
  `descripcion` CHAR(40) DEFAULT NULL,
  `direccion1` CHAR(40) DEFAULT NULL,
  `direccion2` CHAR(40) DEFAULT NULL,
  `direccion3` CHAR(40) DEFAULT NULL,
  `grupo_co` CHAR(2) DEFAULT NULL,
  `codigo_drog` CHAR(5) DEFAULT '13803',
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

  PRIMARY KEY(`item`,`sede`),

  CONSTRAINT sobrante_item
	FOREIGN KEY(`item`) 
	REFERENCES `items`(`ID_ITEM`),

  CONSTRAINT sobrante_sede
	FOREIGN KEY(`sede`) 
	REFERENCES `sedes`(`codigo`),

  INDEX(`sobrante`)
) ENGINE=InnoDB DEFAULT CHARSET=LATIN1;

CREATE TABLE IF NOT EXISTS `transferencias`(
	`id_transferencia` INT(6) AUTO_INCREMENT NOT NULL,
	`destino` CHAR(6) NOT NULL,
	`fecha` FLOAT(5,2) NOT NULL,
	`encargado` CHAR(40) NOT NULL,
	
	PRIMARY KEY(`id_transferencia`),
	
	
	CONSTRAINT transferencias_destino
	FOREIGN KEY(`destino`) 
	REFERENCES `sedes`(`codigo`)
	
) ENGINE=InnoDB DEFAULT CHARSET=LATIN1;

CREATE TABLE IF NOT EXISTS `transferencias_det`(
	`item` CHAR(6) NOT NULL,
	`sede` CHAR(6) NOT NULL,
	`pedido` FLOAT(4,2),
	`id_transferencia` INT(6),
	
	PRIMARY KEY(`item`,`sede`),
	
	CONSTRAINT trans_det_item
	FOREIGN KEY(`item`,`sede`) 
	REFERENCES `sobrantes`(`item`,`sede`),
	
	CONSTRAINT trans_det_trans
	FOREIGN KEY(`id_transferencia`) 
	REFERENCES `transferencias`(`id_transferencia`)	
	
) ENGINE=InnoDB DEFAULT CHARSET=LATIN1;

--
-- Dumping data for table `sedes`
--

LOCK TABLES `sedes` WRITE;

REPLACE INTO `sedes` VALUES ('001',' CENTRO',' CR 2 14 34','','',' 0','13803'),
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
('XXX',' C.O PARA CIERRE','','','','\r','13803');

UNLOCK TABLES;