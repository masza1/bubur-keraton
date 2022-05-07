/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;





INSERT INTO `item_stocks` (`id`, `name`, `type`, `created_at`, `updated_at`) VALUES
(1, 'Styrofoam', '1', '2022-04-19 10:52:08', '2022-04-19 10:52:08');
INSERT INTO `item_stocks` (`id`, `name`, `type`, `created_at`, `updated_at`) VALUES
(2, 'Paper Bowl', '1', '2022-04-19 10:52:08', '2022-04-19 10:52:08');
INSERT INTO `item_stocks` (`id`, `name`, `type`, `created_at`, `updated_at`) VALUES
(3, 'Bowl Mini', '1', '2022-04-19 10:52:08', '2022-04-19 10:52:08');
INSERT INTO `item_stocks` (`id`, `name`, `type`, `created_at`, `updated_at`) VALUES
(4, 'Hydro Coco', '1', '2022-04-19 10:52:08', '2022-04-19 10:52:08'),
(5, 'Susu', '1', '2022-04-19 10:52:08', '2022-04-19 10:52:08'),
(6, 'Air Mineral', '1', '2022-04-19 10:52:08', '2022-04-19 10:52:08'),
(7, 'Telur Ayam Kampung', '2', '2022-04-19 10:52:08', '2022-04-19 10:52:08'),
(8, 'Telur Asin', '2', '2022-04-19 10:52:08', '2022-04-19 10:52:08');

INSERT INTO `items` (`id`, `name`, `price`, `created_at`, `updated_at`) VALUES
(1, 'Bubur Ayam - Original', 10000, '2022-04-19 10:52:08', '2022-04-19 10:52:08');
INSERT INTO `items` (`id`, `name`, `price`, `created_at`, `updated_at`) VALUES
(2, 'Bubur Ayam - Nugget', 13000, '2022-04-19 10:52:08', '2022-04-19 10:52:08');
INSERT INTO `items` (`id`, `name`, `price`, `created_at`, `updated_at`) VALUES
(3, 'Bubur Ayam - Adipati', 17000, '2022-04-19 10:52:08', '2022-04-19 10:52:08');
INSERT INTO `items` (`id`, `name`, `price`, `created_at`, `updated_at`) VALUES
(4, 'Bubur Ayam - Senopati', 20000, '2022-04-19 10:52:08', '2022-04-19 10:52:08'),
(5, 'Bubur Ayam - Telur Asin', 16000, '2022-04-19 10:52:08', '2022-04-19 10:52:08'),
(6, 'Bubur Ayam - Ati Empela', 17000, '2022-04-19 10:52:08', '2022-04-19 10:52:08'),
(7, 'Putri Keraton', 12000, '2022-04-19 10:52:08', '2022-04-19 10:52:08'),
(8, 'Putri Keraton Tanpa Ice Cream', 8000, '2022-04-19 10:52:08', '2022-04-19 10:52:08'),
(9, 'Putri Keraton - Froot Loop', 15000, '2022-04-19 10:52:08', '2022-04-19 10:52:08'),
(10, 'Putri Keraton - Oreo', 15000, '2022-04-19 10:52:08', '2022-04-19 10:52:08'),
(11, 'Putri Keraton - Froot Oreo', 18000, '2022-04-19 10:52:08', '2022-04-19 10:52:08'),
(12, 'Polos', 7000, '2022-04-19 10:52:08', '2022-04-19 10:52:08'),
(13, 'Air Mineral', 3000, '2022-04-19 10:52:08', '2022-04-19 10:52:08'),
(14, 'Paper Bowl', 2000, '2022-04-19 10:52:08', '2022-04-19 10:52:08'),
(15, 'Susu Diamond 200ml', 6000, '2022-04-19 10:52:08', '2022-04-19 10:52:08'),
(16, 'Extra Ayam', 5000, '2022-04-19 10:52:08', '2022-04-19 10:52:08'),
(17, 'Extra Nugget', 3000, '2022-04-19 10:52:08', '2022-04-19 10:52:08'),
(18, 'Extra Telur Ayam Kampung', 5000, '2022-04-19 10:52:08', '2022-04-19 10:52:08'),
(19, 'Extra Telur Asin', 4000, '2022-04-19 10:52:08', '2022-04-19 10:52:08'),
(20, 'Extra Ati Empela', 5000, '2022-04-19 10:52:08', '2022-04-19 10:52:08');





INSERT INTO `stock_reductions` (`id`, `stock_id`, `expense`, `created_at`, `updated_at`) VALUES
(3, 1, 3, '2022-04-19 18:02:49', '2022-04-19 18:02:49');
INSERT INTO `stock_reductions` (`id`, `stock_id`, `expense`, `created_at`, `updated_at`) VALUES
(4, 2, 2, '2022-04-19 18:02:49', '2022-04-19 18:02:49');
INSERT INTO `stock_reductions` (`id`, `stock_id`, `expense`, `created_at`, `updated_at`) VALUES
(5, 4, 5, '2022-04-19 18:02:49', '2022-04-19 18:02:49');
INSERT INTO `stock_reductions` (`id`, `stock_id`, `expense`, `created_at`, `updated_at`) VALUES
(6, 1, 3, '2022-04-19 18:02:49', '2022-04-19 18:02:49');

INSERT INTO `stocks` (`id`, `item_stock_id`, `stock`, `created_at`, `updated_at`) VALUES
(1, 1, 100, '2022-04-19 17:58:23', '2022-04-19 17:58:23');
INSERT INTO `stocks` (`id`, `item_stock_id`, `stock`, `created_at`, `updated_at`) VALUES
(2, 2, 10, '2022-04-19 17:58:23', '2022-04-19 17:58:23');
INSERT INTO `stocks` (`id`, `item_stock_id`, `stock`, `created_at`, `updated_at`) VALUES
(4, 3, 132, '2022-04-19 17:58:23', '2022-04-19 17:58:23');

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Operator Bubur Keraton', 'buburkeraton@gmail.com', '2022-04-19 10:52:07', '$2y$10$6nufL6aHCl.pmuVTD7yDfeMFJrUEZDtDqBn5mw8rAqsP4zxFgrWlG', 'ClBmbUCTul', '2022-04-19 10:52:08', '2022-04-19 10:52:08');



/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;