--
-- Database: `ProjectDB`
--
DROP DATABASE IF EXISTS `ProjectDB`;

CREATE DATABASE IF NOT EXISTS `ProjectDB` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

-- Select as current database
USE `ProjectDB`;

-- Create parent tables first

--
-- Table structure for table `Customer`
--

CREATE TABLE `Customer` (
  `customerEmail` varchar(50) PRIMARY KEY,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `password` varchar(50) NOT NULL,
  `phoneNumber` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `Customer` (`customerEmail`, `firstName`, `lastName`, `password`, `phoneNumber`) VALUES
('taiMan@gmail.com', 'Tai Man', 'Chan', 'marcus123', '52839183'),
('mlwong@gmail.com', 'Mei Ling', 'Wong', 'kelly123', '52863476');

--
-- Table structure for table `Shop`
--

CREATE TABLE `Shop` (
  `shopID` int(6) PRIMARY KEY AUTO_INCREMENT,
  `address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `Shop` (`shopID`, `address`) VALUES
(1, 'No. 18, 1 / F, Trendy Zone, 580A Nathan Road, Mong Kok'),
(2, 'No. 1047, 10/F, Nan Fung Centre, 264-298 Castle Peak Road, Tsuen Wan');

--
-- Table structure for table `Tenant`
--

CREATE TABLE `Tenant` (
  `tenantID` varchar(50) PRIMARY KEY,
  `name` varchar(255) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `Tenant` (`tenantID`, `name`, `password`) VALUES
('marcus888', 'Marcus', 'it888'),
('florahk', 'Flora', 'secret');

--
-- Table structure for table `showcase`
--

CREATE TABLE `Showcase` (
  `showcaseID` int(10) PRIMARY KEY AUTO_INCREMENT,
  `shopID` int(6) NOT NULL,
  `tenantID` varchar(50) NULL,
   FOREIGN KEY (`shopID`) REFERENCES `Shop` (`shopID`),
   FOREIGN KEY (`tenantID`) REFERENCES `Tenant` (`tenantID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `Showcase` (`showcaseID`, `shopID`, `tenantID`) VALUES
(1, 1, 'marcus888'),
(2, 1, null),
(3, 2, 'florahk'),
(4, 2, 'marcus888');

--
-- Table structure for table `Goods`
--

CREATE TABLE `Goods` (
  `goodsID` int(10) PRIMARY KEY AUTO_INCREMENT,
  `showcaseID` int(10) NOT NULL,
  `goodsName` varchar(255) NOT NULL,
  `stockPrice` decimal(10,1) NOT NULL,
  `remainingStock` int(7) NOT NULL DEFAULT 0,
  `status` int(1) NOT NULL COMMENT 'The goods should include 2 stock status:  \n1. “Available”: Show only the available goods.  \n2. “Unavailable”: The goods has been discontinued or not already for sell.  ',
  FOREIGN KEY (`showcaseID`) REFERENCES `Showcase` (`showcaseID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `Goods` (`goodsID`, `showcaseID`, `goodsName`, `stockPrice`, `remainingStock`, `status`) VALUES
(1, 1, 'Bracelet', '99.5', 8, 1),
(2, 3, 'Ear Rings', '100.0', 1, 1),
(3, 1, 'Phone Case', '130.0', 1, 1),
(4, 3, 'Wallet', '200.0', 3, 1),
(5, 3, 'Ear Phone', '50.0', 5, 2);

--
-- Table structure for table `Orders`
--

CREATE TABLE `Orders` (
  `orderID` int(10) PRIMARY KEY AUTO_INCREMENT,
  `customerEmail` varchar(50) NOT NULL,
  `shopID` int(6) NOT NULL COMMENT 'An order can only order goods from the same shop',
  `orderDateTime` datetime NOT NULL,
  `status` int(1) NOT NULL COMMENT 'The orders should include 3 statuses:  \n1.     “Delivery”: The parts are delivering to shop  \n2.     “Awaiting”: Goods are ready for pick up  \n3.     “Completed”: The goods has been picked up from customer  ',
  FOREIGN KEY (`customerEmail`) REFERENCES `Customer` (`customerEmail`),
  FOREIGN KEY (`shopID`) REFERENCES `Shop` (`shopID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `Orders` (`orderID`, `customerEmail`, `shopID`, `orderDateTime`, `status`) VALUES
(1, 'taiMan@gmail.com', 1, '2020-05-14 07:34:29', 3),
(2, 'taiMan@gmail.com', 2, '2020-06-22 08:25:13', 2);

--
-- Table structure for table `OrderItem`
--

CREATE TABLE `OrderItem` (
  `orderID` int(10),
  `goodsID` int(10),
  `quantity` int(7) NOT NULL,
  `sellingPrice` decimal(10,1) NOT NULL,
  PRIMARY KEY (`orderID`,`goodsID`),
  FOREIGN KEY (`orderID`) REFERENCES `Orders` (`orderID`),
  FOREIGN KEY (`goodsID`) REFERENCES `Goods` (`goodsID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `OrderItem` (`orderID`, `goodsID`, `quantity`, `sellingPrice`) VALUES
(1, 1, 2, '99.5'),
(1, 3, 1, '120.0'),
(2, 2, 2, '200.0');

