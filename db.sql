-- --------------------------------------------------------
-- Хост:                         127.0.0.1
-- Версия сервера:               5.5.38-log - MySQL Community Server (GPL)
-- ОС Сервера:                   Win32
-- HeidiSQL Версия:              8.3.0.4694
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Дамп структуры базы данных mobil
CREATE DATABASE IF NOT EXISTS `mobil` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `mobil`;


-- Дамп структуры для таблица mobil.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned DEFAULT '0',
  `alias` varchar(50) NOT NULL,
  `title` varchar(30) NOT NULL,
  `handler` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы mobil.categories: ~5 rows (приблизительно)
DELETE FROM `categories`;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` (`id`, `parent_id`, `alias`, `title`, `handler`) VALUES
	(60, 0, '0', 'Главная', 'page'),
	(61, 0, 'uslugi', 'Услуги', 'page'),
	(62, 0, 'kontakty', 'Контакты', 'page'),
	(63, 0, 'galereya', 'Галерея', 'gallery'),
	(64, 0, 'dopolnitelnaya-informaciya', 'Дополнительная информация', 'page');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;


-- Дамп структуры для таблица mobil.files
CREATE TABLE IF NOT EXISTS `files` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `alias` varchar(50) NOT NULL DEFAULT '0' COMMENT 'Псевдоним',
  `filename` varchar(50) NOT NULL DEFAULT '0' COMMENT 'Имя файла',
  `base_dir` varchar(500) NOT NULL DEFAULT '0',
  `base_url` varchar(500) NOT NULL DEFAULT '0',
  `path` varchar(500) NOT NULL DEFAULT '0' COMMENT 'Путь',
  `title` varchar(50) NOT NULL DEFAULT '0' COMMENT 'Название',
  `description` varchar(200) DEFAULT '0' COMMENT 'Описание',
  `attach_table` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'ID присоединенной таблицы',
  `attach_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ID записи',
  PRIMARY KEY (`id`),
  KEY `attach_key` (`attach_table`,`attach_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='Файлы';

-- Дамп данных таблицы mobil.files: ~0 rows (приблизительно)
DELETE FROM `files`;
/*!40000 ALTER TABLE `files` DISABLE KEYS */;
/*!40000 ALTER TABLE `files` ENABLE KEYS */;


-- Дамп структуры для таблица mobil.params
CREATE TABLE IF NOT EXISTS `params` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `type_id` int(10) unsigned NOT NULL COMMENT 'ID типа',
  `table_id` int(11) unsigned NOT NULL COMMENT 'ID таблицы',
  `item_id` int(11) unsigned NOT NULL COMMENT 'ID записи',
  `value` varchar(200) NOT NULL COMMENT 'Значение',
  PRIMARY KEY (`id`),
  KEY `type_value` (`type_id`,`value`),
  KEY `param_value` (`type_id`,`table_id`,`item_id`,`value`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='Параметры';

-- Дамп данных таблицы mobil.params: ~2 rows (приблизительно)
DELETE FROM `params`;
/*!40000 ALTER TABLE `params` DISABLE KEYS */;
INSERT INTO `params` (`id`, `type_id`, `table_id`, `item_id`, `value`) VALUES
	(1, 1, 2, 5, '1012'),
	(2, 1, 2, 5, '150');
/*!40000 ALTER TABLE `params` ENABLE KEYS */;


-- Дамп структуры для таблица mobil.param_types
CREATE TABLE IF NOT EXISTS `param_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(20) NOT NULL COMMENT 'Название параметра',
  `comment` varchar(20) NOT NULL COMMENT 'Комментарий',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='Типы параметров';

-- Дамп данных таблицы mobil.param_types: ~2 rows (приблизительно)
DELETE FROM `param_types`;
/*!40000 ALTER TABLE `param_types` DISABLE KEYS */;
INSERT INTO `param_types` (`id`, `name`, `comment`) VALUES
	(1, 'price', 'Цена'),
	(2, 'type', 'Тип');
/*!40000 ALTER TABLE `param_types` ENABLE KEYS */;


-- Дамп структуры для таблица mobil.resources
CREATE TABLE IF NOT EXISTS `resources` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(10) unsigned NOT NULL,
  `created` int(10) unsigned NOT NULL,
  `alias` varchar(50) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(500) NOT NULL,
  `content` text NOT NULL,
  `keywords` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `category` (`category_id`),
  KEY `created` (`created`),
  CONSTRAINT `FK_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы mobil.resources: ~5 rows (приблизительно)
DELETE FROM `resources`;
/*!40000 ALTER TABLE `resources` DISABLE KEYS */;
INSERT INTO `resources` (`id`, `category_id`, `created`, `alias`, `title`, `description`, `content`, `keywords`) VALUES
	(9, 60, 1443302861, 'main', 'Компания "Мобиль"', 'Основная деятельность ООО "Мобиль" - разработка и производство элементов пассивной безопасности и деталей салона автомобилей. В производстве активно используются сварка пластмасс, литье пластмасс под давлением, дуговая сварка в среде CO2, лазерная резка листовых сталей, холодная листовая штамповка и профилирование листовых сталей. С 2013 года ООО "Мобиль" начала производство и продажу мини-трактора МТМ-10 собственной разработки, а также широкого набора навесных и прицепных орудий для него.', '<p> ООО «Мобиль» российская компания, основной деятельностью которой является разработка и производство элементов пассивной безопасности и деталей салона автомобилей. </p><p> Более 15 лет ООО «Мобиль» поставляет свою продукцию на конвейеры заводов ОАО «АВТОВАЗ»,</p><p>ЗАО «GM-АвтоВАЗ», ОАО «ГАЗ» и ОАО «УАЗ».</p><p> Производственные площади составляют 12 500 м<sup>2</sup>, на которых размещены более 100 единиц оборудования. В арсенале освоенных и используемых технологий следует отметить такие как изготовление изделий из ППУ, сварка пластмасс, литье пластмасс под давлением, производство РТИ, механическая обработка различных материалов, дуговая сварка в среде CO2, лазерная резка листовых сталей, холодная листовая штамповка, а также профилирование листовых сталей методом интенсивной деформации.</p><p> Продукция ООО «Мобиль» неоднократно отмечена дипломами конкурса «100 лучших товаров России» и дипломами с различных автомобильных выставок. Система менеджмента качества на предприятии сертифицирована на соответствие требованиям ISO/TS 16949(производство автокомпонентов) и</p><p>ISO 9001:2008(производство малогабаритной техники) органом сертификации TUVHESSEN(Германия).</p><p> В конце 2010 года в рамках диверсификации производства руководством предприятия было принято решении о начале разработки малогабаритного трактора для использования в фермерских и личных подсобных хозяйствах, а также в системе ЖКХ и на малых производственных площадках различных отраслей промышленности.</p><p> В 2012 году ООО «Мобиль» получило золотую медаль на XIV поволжском агропромышленном форуме за разработку и внедрение малогабаритного трактора в фермерских хозяйствах. В том же году был получен сертификат соответствия техническим регламентам(обязательная сертификация) на малогабаритный трактор МТМ-10. 2013 год ознаменовал старт продаж малогабаритного трактора МТМ-10 разработанного на предприятии ООО «Мобиль».</p><p> Наша компания ведет непрерывный процесс улучшения, модернизации и предлагает потребителям различные инновационные решения, как в производстве малогабаритного трактора МТМ-10, так и в универсализации его использования с различными навесными и прицепными орудиями.</p>', ''),
	(10, 61, 1443303491, 'uslugi', 'Услуги', 'Механическая обработка различных материалов на токарных, фрезерных и шлифовальных станках в Сызрани; изготовление деталей в Сызрани; лазерная резка листового металлопроката в Сызрани; изготовление сварных металлоконструкций в Сызрани; изготовление и проектирование штамповой и нестандартной оснастки в Сызрани; ремонт и модернизация навесных орудий для минитракторов и мотоблоков.', '<p>ООО «Мобиль» оказывает широкий спектр услуг в сфере металлообработки и проектировании различных видов нестандартной оснастки и металлоконструкций. Мы рады предложить своим клиентам такие услуги как:</p><ul><li>Механическая обработка различных материалов с использованием токарных, фрезерных и шлифовальных станков. Также возможно изготовление деталей с применением метода электроэрозионной обработки, в том числе с предварительным проектированием и изготовлением электродов.<span class="redactor-invisible-space"></span></li><li><span class="redactor-invisible-space"> Лазерная резка листового металлопроката толщиной до 12мм включительно.<span class="redactor-invisible-space"></span></span></li><li><span class="redactor-invisible-space"><span class="redactor-invisible-space">Изготовление сварных металлоконструкций по индивидуальным заказам.<span class="redactor-invisible-space"></span></span></span></li><li><span class="redactor-invisible-space"><span class="redactor-invisible-space"><span class="redactor-invisible-space">Изготовление и проектирование штамповой и нестандартной оснастки.<span class="redactor-invisible-space"></span></span></span></span></li><li><span class="redactor-invisible-space"><span class="redactor-invisible-space"><span class="redactor-invisible-space"><span class="redactor-invisible-space">Также предлагаем услуги по ремонту и модернизации навесных орудий для минитракторов и мотоблоков.<span class="redactor-invisible-space"><br></span></span></span></span></span></li></ul>', ''),
	(11, 62, 1444336374, 'kontaktnaya-informaciya', 'Контактная информация', 'Адрес: Самарская обл., г. Сызрань, пос.Елизарово, ул. Жукова 4\r\nТелефон/Факс: (8464) 34-22-04, (8464) 34-57-79, (8464) 98-91-33\r\ne-mail: mail@mtm10.ru', '<p itemscope="" itemtype="http://schema.org/Organization">\r\n	<a itemprop="url" href="http://mobilszr.ru"><strong>ООО "Мобиль"</strong></a>\r\n</p><a itemprop="url" href="http://mobilszr.ru"></a><p><a itemprop="url" href="http://mobilszr.ru"></a>\r\n</p><p itemprop="description">Металлообработка и проектировании различных видов нестандартной оснастки и металлоконструкций. Разработка и производство элементов пассивной безопасности и деталей салона автомобилей. Производство, обслуживание и ремонт мини-трактора МТМ-10, навесных и прицепных агрегатов, мотоблоков.\r\n</p><p itemprop="address" itemscope="" itemtype="http://schema.org/PostalAddress">\r\n	Адрес: 	<span itemprop="addressRegion">Самарская область</span>, г.<span itemprop="addressLocality">Сызрань</span>, \r\n	<span itemprop="streetAddress">пос.Елизарово, ул. Жукова 4</span><br>\r\n	Почтовый адрес: <span itemprop="postalCode">446028</span>, а/я: <span itemprop="postOfficeBoxNumber">321</span></p><p itemprop="address" itemscope="" itemtype="http://schema.org/PostalAddress">{*googlemap*}<br><span itemprop="postOfficeBoxNumber"></span>\r\n</p>', ''),
	(12, 63, 1444155047, 'slajdshou', 'Слайдшоу', '', '', ''),
	(13, 64, 1444335107, 'varianty-domennogo-imeni', 'Варианты доменного имени', '\r\n\r\n\r\n\r\n', '<p>mobilszr.ru</p><p><br><br>ooo-mobil.ru</p><p><br><br>ooomobil.ru</p><p><br><br>mobil-s.com</p>', '');
/*!40000 ALTER TABLE `resources` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
