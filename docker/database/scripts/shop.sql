ALTER DATABASE shop COLLATE = 'utf8_general_ci';

CREATE TABLE `menu` (
	`pizzaID` INT NOT NULL AUTO_INCREMENT,
	`pizzaName` VARCHAR(40) NOT NULL,
	`imagePath` VARCHAR(40) NOT NULL,
	`pizzaPrice` FLOAT NOT NULL,
	PRIMARY KEY (`pizzaID`)
);

CREATE TABLE `order` (
	`orderID` INT NOT NULL AUTO_INCREMENT,
	`address` VARCHAR(60) COLLATE utf8_unicode_ci NOT NULL,
	`orderTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`orderID`)
);

CREATE TABLE `orderedPizza` (
	`orderedPizzaID` INT NOT NULL AUTO_INCREMENT,
	`orderID` INT NOT NULL,
	`pizzaID` INT NOT NULL,
	`status` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
	PRIMARY KEY (`orderedPizzaID`),
	CONSTRAINT `fk_orderID` FOREIGN KEY (`orderID`) REFERENCES `order` (`orderID`),
	CONSTRAINT `fk_pizzaID` FOREIGN KEY (`pizzaID`) REFERENCES `menu` (`pizzaID`)
);


INSERT INTO `menu` VALUES
	(1, 'Salami', './images/pizza-salami.jpg', 4.49),
	(2, 'Margherita', './images/pizza-margherita.jpg', 3.99),
	(3, 'Hawaii', './images/pizza-hawaii.jpg', 5.50),
	(4, 'Peperoni', './images/pizza-peperoni.jpg', 4.50),
	(5, 'Käse', './images/pizza-käse.jpg', 4.99);
