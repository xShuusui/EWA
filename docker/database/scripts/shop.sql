ALTER DATABASE shop CHARACTER SET='utf8' COLLATE='utf8_general_ci';

CREATE TABLE `menu` (
	`pizzaName` VARCHAR(40) COLLATE utf8_unicode_ci NOT NULL,
	`imagePath` VARCHAR(40) COLLATE utf8_unicode_ci NOT NULL,
	`pizzaPrice` FLOAT NOT NULL,
	PRIMARY KEY (`pizzaName`)
) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `order` (
	`orderID` INT NOT NULL AUTO_INCREMENT,
	`fullName` VARCHAR(60) COLLATE utf8_unicode_ci NOT NULL,
	`address` VARCHAR(60) COLLATE utf8_unicode_ci NOT NULL,
	`orderTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`orderID`)
) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `orderedPizza` (
	`orderedPizzaID` INT NOT NULL AUTO_INCREMENT,
	`orderID` INT NOT NULL,
	`pizzaName` VARCHAR(40) COLLATE utf8_unicode_ci NOT NULL,
	`status` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
	PRIMARY KEY (`orderedPizzaID`),
	CONSTRAINT `fk_orderID` FOREIGN KEY (`orderID`) REFERENCES `order` (`orderID`),
	CONSTRAINT `fk_pizzaID` FOREIGN KEY (`pizzaName`) REFERENCES `menu` (`pizzaName`)
) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


INSERT INTO `menu` VALUES
	('Salami', './images/pizza-salami.jpg', 4.49),
	('Margherita', './images/pizza-margherita.jpg', 3.99),
	('Hawaii', './images/pizza-hawaii.jpg', 5.50),
	('Peperoni', './images/pizza-peperoni.jpg', 4.50),
	('Käse', './images/pizza-käse.jpg', 4.99);
