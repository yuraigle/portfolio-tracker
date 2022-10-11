CREATE TABLE `asset_types` (
    `id` int(11) NOT NULL,
    `name` varchar(50) NOT NULL DEFAULT '',
    PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8mb4;

CREATE TABLE `assets` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `ticker` varchar(10) NOT NULL,
    `price` decimal(20,6),
    `currency` varchar(3) NOT NULL DEFAULT 'USD',
    `updated_at` datetime,
    `upd_url` varchar(250),
    `upd_type` varchar(50),
    `upd_xpath` varchar(250),
    `is_active` tinyint(4) NOT NULL DEFAULT 0,
    `type` int(11),
    PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8mb4;

CREATE TABLE `portfolio_assets` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `portfolio_id` int(11),
    `asset_id` int(11),
    `amount` decimal(20,6) NOT NULL DEFAULT 0.000000,
    PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8mb4;

CREATE TABLE `portfolios` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(50) NOT NULL,
    `created_at` datetime NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8mb4;

INSERT INTO `asset_types` VALUES (1,'Акции США');
INSERT INTO `asset_types` VALUES (2,'Акции РФ');
INSERT INTO `asset_types` VALUES (3,'Крипта');
INSERT INTO `asset_types` VALUES (4,'Кэш');

INSERT INTO `assets` VALUES (1,'VT',NULL,'USD',NULL,'https://query1.finance.yahoo.com/v8/finance/chart/VT?interval=1d','json','$.chart.result[0].meta.regularMarketPrice',1,1);
INSERT INTO `assets` VALUES (3,'TSPX',NULL,'USD',NULL,'https://www.tinkoff.ru/invest/etfs/TSPX/',NULL,NULL,1,1);
INSERT INTO `assets` VALUES (4,'TECH',NULL,'USD',NULL,'https://www.tinkoff.ru/invest/etfs/TECH/',NULL,NULL,1,1);
INSERT INTO `assets` VALUES (5,'TBIO',NULL,'USD',NULL,'https://www.tinkoff.ru/invest/etfs/TBIO/',NULL,NULL,1,1);
INSERT INTO `assets` VALUES (6,'EQMX',NULL,'RUB',NULL,'https://www.tinkoff.ru/invest/etfs/EQMX/',NULL,NULL,1,2);
INSERT INTO `assets` VALUES (7,'SBERP',NULL,'RUB',NULL,'https://www.tinkoff.ru/invest/stocks/SBERP/',NULL,NULL,1,2);
INSERT INTO `assets` VALUES (8,'TCSG',NULL,'RUB',NULL,'https://www.investing.com/equities/tcs-group-holding-plc?cid=1153662',NULL,NULL,1,2);
INSERT INTO `assets` VALUES (9,'TMOS',NULL,'RUB',NULL,'https://www.tinkoff.ru/invest/etfs/TMOS/',NULL,NULL,1,2);
INSERT INTO `assets` VALUES (10,'USD',NULL,'RUB',NULL,'https://query1.finance.yahoo.com/v8/finance/chart/RUB=X?interval=1d','json','$.chart.result[0].meta.regularMarketPrice',1,4);
INSERT INTO `assets` VALUES (20,'BTC',NULL,'USD',NULL,'https://query1.finance.yahoo.com/v8/finance/chart/BTC-USD?interval=1d','json','$.chart.result[0].meta.regularMarketPrice',1,3);
INSERT INTO `assets` VALUES (21,'ETH',NULL,'USD',NULL,'https://query1.finance.yahoo.com/v8/finance/chart/ETH-USD?interval=1d','json','$.chart.result[0].meta.regularMarketPrice',1,3);
INSERT INTO `assets` VALUES (30,'BNB',NULL,'USD',NULL,'https://query1.finance.yahoo.com/v8/finance/chart/BNB-USD?interval=1d','json','$.chart.result[0].meta.regularMarketPrice',1,3);
INSERT INTO `assets` VALUES (35,'XMR',NULL,'USD',NULL,'https://query1.finance.yahoo.com/v8/finance/chart/XMR-USD?interval=1d','json','$.chart.result[0].meta.regularMarketPrice',1,3);
INSERT INTO `assets` VALUES (40,'MATIC',NULL,'USD',NULL,'https://query1.finance.yahoo.com/v8/finance/chart/MATIC-USD?interval=1d','json','$.chart.result[0].meta.regularMarketPrice',1,3);
INSERT INTO `assets` VALUES (45,'DOT',NULL,'USD',NULL,'https://query1.finance.yahoo.com/v8/finance/chart/DOT-USD?interval=1d','json','$.chart.result[0].meta.regularMarketPrice',1,3);

INSERT INTO `portfolios` (`name`) VALUES ('IB');
INSERT INTO `portfolios` (`name`) VALUES ('ИИС');
INSERT INTO `portfolios` (`name`) VALUES ('Binance');
INSERT INTO `portfolios` (`name`) VALUES ('Exodus');
