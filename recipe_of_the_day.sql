-- MySQL dump 10.13  Distrib 8.0.45, for Linux (x86_64)
--
-- Host: localhost    Database: recipe_of_the_day
-- ------------------------------------------------------
-- Server version	8.0.45-0ubuntu0.22.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Fast Food','2025-11-30 17:57:31'),(2,'Dessert','2025-11-30 17:57:31'),(3,'Soup','2025-11-30 17:57:31'),(4,'Drink','2025-11-30 17:57:31'),(5,'Salad','2025-11-30 17:57:31'),(6,'Vegan Dishes','2025-12-31 01:43:16'),(7,'Side Dishes','2025-12-31 15:29:14'),(8,'Snacks','2025-12-31 15:29:35'),(9,'Main Dishes','2025-12-31 15:30:01');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comments` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `recipe_id` int unsigned NOT NULL,
  `TEXT` text NOT NULL,
  `rating` tinyint NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_recipe` (`recipe_id`),
  KEY `idx_user` (`user_id`),
  KEY `idx_rating` (`rating`),
  CONSTRAINT `fk_comments_recipe` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_comments_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `comments_chk_1` CHECK ((`rating` between 1 and 5))
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
INSERT INTO `comments` VALUES (1,1,1,'Amazing recipe, very tasty!',5,'2025-11-30 18:06:53'),(2,2,2,'A little too sweet for me.',3,'2025-11-30 18:06:53'),(3,1,3,'Simple and healthy.',4,'2025-11-30 18:06:53'),(4,3,1,'Good but needed more seasoning.',3,'2025-11-30 18:06:53'),(5,2,5,'Very fresh and light!',5,'2025-11-30 18:06:53'),(6,15,1,'O cianno fatto beine!',5,'2025-12-31 13:03:49'),(7,14,9,'Amazing recipe!',5,'2026-01-01 01:47:06'),(8,18,24,'It was so good',5,'2026-01-02 16:30:26');
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `daily_recipe`
--

DROP TABLE IF EXISTS `daily_recipe`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `daily_recipe` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `recipe_id` int unsigned NOT NULL,
  `date` date NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `DATE` (`date`),
  KEY `fk_daily_recipe` (`recipe_id`),
  CONSTRAINT `fk_daily_recipe` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `daily_recipe`
--

LOCK TABLES `daily_recipe` WRITE;
/*!40000 ALTER TABLE `daily_recipe` DISABLE KEYS */;
INSERT INTO `daily_recipe` VALUES (1,1,'2025-01-01','2025-11-30 18:08:35'),(2,2,'2025-01-02','2025-11-30 18:08:35'),(3,3,'2025-01-03','2025-11-30 18:08:35'),(4,4,'2025-01-04','2025-11-30 18:08:35'),(5,5,'2025-01-05','2025-11-30 18:08:35'),(6,2,'2025-12-30','2025-12-30 18:02:23'),(7,2,'2025-12-31','2025-12-31 00:18:39'),(8,9,'2026-01-01','2026-01-01 01:32:18'),(25,24,'2026-01-02','2026-01-02 11:35:01'),(26,16,'2026-01-03','2026-01-03 00:00:01'),(27,5,'2026-01-04','2026-01-04 00:00:01'),(28,35,'2026-01-05','2026-01-05 00:00:01'),(29,22,'2026-01-06','2026-01-06 00:00:01'),(30,18,'2026-01-07','2026-01-07 00:00:01'),(31,12,'2026-01-08','2026-01-08 00:00:01'),(32,35,'2026-01-09','2026-01-09 00:00:02'),(33,20,'2026-01-10','2026-01-10 00:00:01'),(34,5,'2026-01-11','2026-01-11 00:00:01'),(35,4,'2026-01-12','2026-01-12 00:00:02'),(36,18,'2026-01-13','2026-01-13 00:00:01'),(37,11,'2026-01-14','2026-01-14 00:00:01'),(38,4,'2026-01-15','2026-01-15 00:00:02'),(39,18,'2026-01-16','2026-01-16 00:00:02'),(40,11,'2026-01-17','2026-01-17 00:00:01'),(41,8,'2026-01-18','2026-01-18 00:00:01'),(42,25,'2026-01-19','2026-01-19 00:00:02'),(43,1,'2026-01-20','2026-01-20 00:00:01'),(44,29,'2026-01-21','2026-01-21 00:00:01'),(45,23,'2026-01-22','2026-01-22 00:00:02'),(46,12,'2026-01-23','2026-01-23 00:00:02'),(47,30,'2026-01-24','2026-01-24 00:00:01'),(48,24,'2026-01-25','2026-01-25 00:00:01'),(49,10,'2026-01-26','2026-01-26 00:00:01'),(50,18,'2026-01-27','2026-01-27 00:00:01'),(51,4,'2026-01-28','2026-01-28 00:00:01'),(52,30,'2026-01-29','2026-01-29 00:00:01'),(53,34,'2026-01-30','2026-01-30 00:00:01'),(54,35,'2026-01-31','2026-01-31 00:00:01'),(55,23,'2026-02-01','2026-02-01 00:00:01'),(56,20,'2026-02-02','2026-02-02 00:00:02'),(57,37,'2026-02-03','2026-02-03 00:00:01'),(58,31,'2026-02-04','2026-02-04 00:00:01'),(59,31,'2026-02-05','2026-02-05 00:00:01'),(60,9,'2026-02-06','2026-02-06 00:00:01'),(61,5,'2026-02-07','2026-02-07 00:00:02'),(62,15,'2026-02-08','2026-02-08 00:00:01'),(63,35,'2026-02-09','2026-02-09 00:00:01'),(64,33,'2026-02-10','2026-02-10 00:00:01'),(65,31,'2026-02-11','2026-02-11 00:00:01'),(66,21,'2026-02-12','2026-02-12 00:00:01'),(67,14,'2026-02-13','2026-02-13 00:00:01'),(68,24,'2026-02-14','2026-02-14 00:00:01'),(69,9,'2026-02-15','2026-02-15 00:00:01'),(70,36,'2026-02-16','2026-02-16 00:00:01'),(71,27,'2026-02-17','2026-02-17 00:00:01'),(72,2,'2026-02-18','2026-02-18 00:00:01');
/*!40000 ALTER TABLE `daily_recipe` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `favorites`
--

DROP TABLE IF EXISTS `favorites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `favorites` (
  `user_id` int unsigned NOT NULL,
  `recipe_id` int unsigned NOT NULL,
  `added_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`,`recipe_id`),
  KEY `fk_fav_recipe` (`recipe_id`),
  CONSTRAINT `fk_fav_recipe` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_fav_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `favorites`
--

LOCK TABLES `favorites` WRITE;
/*!40000 ALTER TABLE `favorites` DISABLE KEYS */;
INSERT INTO `favorites` VALUES (1,1,'2025-12-31 12:59:34'),(1,2,'2025-11-30 18:05:33'),(1,9,'2026-01-01 01:49:31'),(2,3,'2025-11-30 18:05:33'),(2,9,'2026-01-01 01:49:31'),(3,1,'2025-11-30 18:05:33'),(3,9,'2026-01-01 01:49:31'),(4,5,'2025-11-30 18:05:33'),(4,9,'2026-01-01 01:49:31'),(5,9,'2026-01-01 01:49:31'),(12,2,'2025-12-31 15:30:29'),(14,8,'2026-01-01 01:32:53'),(14,9,'2026-01-01 01:46:21'),(14,21,'2026-01-01 01:54:19'),(14,31,'2026-01-01 14:21:27'),(18,24,'2026-01-02 16:30:11');
/*!40000 ALTER TABLE `favorites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
INSERT INTO `messages` VALUES (1,'Deneme','deneme@mail.com','Den','deneme','2025-12-31 14:10:52'),(2,'Deneme2','deneme@mail.com','Deneme','deneme','2025-12-31 14:13:03'),(3,'Toprak','Huawei23@gmail.com','testmessage','testmessageforcontact','2026-01-01 03:31:42'),(4,'Toprak','Huawei23@gmail.com','testtest','testmaessagesentforadmin','2026-01-01 11:05:12'),(5,'Toprak','info@technovasolutions.com','fasfafa','afafafa','2026-01-01 11:19:59');
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recipes`
--

DROP TABLE IF EXISTS `recipes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `recipes` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `description` text,
  `ingredients` text NOT NULL,
  `steps` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `prep_time` int unsigned NOT NULL,
  `difficulty` tinyint NOT NULL,
  `category_id` int unsigned NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_title` (`title`),
  KEY `idx_category` (`category_id`),
  CONSTRAINT `fk_recipes_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recipes`
--

LOCK TABLES `recipes` WRITE;
/*!40000 ALTER TABLE `recipes` DISABLE KEYS */;
INSERT INTO `recipes` VALUES (1,'Spaghetti Bolognese','Classic Italian pasta meal.','pasta, meat, tomato','Boil pasta; Cook sauce','uploads/recipes/spaghetti_bolognese.webp',30,2,1,'2025-11-30 18:03:49','2025-12-31 02:41:08'),(2,'Chocolate Cake','Moist dark chocolate cake.','flour, sugar, cocoa','Mix; Bake','uploads/recipes/chocolate_cake.webp',45,3,2,'2025-11-30 18:03:49','2025-12-31 03:41:16'),(3,'Lentil Soup','Healthy lentil soup.','lentils, water, salt','Boil; Simmer','uploads/recipes/lentil_soup.jpg',25,1,3,'2025-11-30 18:03:49','2025-12-31 03:36:03'),(4,'Iced Coffee','Cold refreshing coffee.','coffee, ice, milk','Mix all','uploads/recipes/iced_coffee.webp',5,1,4,'2025-11-30 18:03:49','2025-12-31 03:33:11'),(5,'Caesar Salad','Fresh Caesar salad.','lettuce, chicken, sauce','Mix ingredients','uploads/recipes/caesar_salad.jpg',15,1,5,'2025-11-30 18:03:49','2025-12-31 03:29:44'),(8,'Grilled Chicken Breast','Juicy grilled chicken with herbs','chicken breast, olive oil, garlic, rosemary, thyme, lemon','Marinate chicken with herbs and oil\r\nGrill for 6-7 minutes per side\r\nLet rest before serving','uploads/recipes/1767202419_47125t2.webp',25,1,9,'2025-12-31 15:45:31','2025-12-31 20:33:39'),(9,'Beef Stroganoff','Creamy beef with mushrooms','beef strips, mushrooms, onion, sour cream, flour, beef broth','Brown beef in pan\r\nSauté mushrooms and onions\r\nAdd cream and simmer','uploads/recipes/1767202198_Stroganoff-e1730198746409.jpg',40,2,9,'2025-12-31 15:45:31','2025-12-31 20:29:58'),(10,'Vegetable Stir Fry','Colorful Asian style vegetables','broccoli, carrots, bell peppers, soy sauce, garlic, ginger, sesame oil','Heat wok with oil\r\nStir fry vegetables quickly\r\nAdd sauce and toss','uploads/recipes/1767202062_Thai-Vegetable-Stir-Fry-with-Lime-and-Ginger_done.png',15,1,6,'2025-12-31 15:45:31','2025-12-31 20:27:42'),(11,'Baked Salmon','Tender oven-baked salmon','salmon fillet, lemon, dill, butter, salt, pepper','Season salmon with herbs\r\nBake at 180°C for 20 minutes\r\nServe with lemon wedges','uploads/recipes/1767201968_salmon.jpg',30,2,9,'2025-12-31 15:45:31','2025-12-31 20:26:08'),(12,'Chicken Curry','Spicy Indian curry','chicken, curry powder, coconut milk, onion, tomato, garlic','Cook onions until golden\r\nAdd spices and chicken\r\nSimmer in coconut milk','uploads/recipes/1767201797_currychicken.jpg',45,2,9,'2025-12-31 15:45:31','2025-12-31 20:23:17'),(13,'Mushroom Risotto','Creamy Italian rice dish','arborio rice, mushrooms, parmesan, white wine, chicken stock, butter','Toast rice in butter\r\nAdd stock gradually while stirring\r\nFinish with cheese','uploads/recipes/1767201705_mushroom_risotto.jpg',35,3,7,'2025-12-31 15:45:31','2025-12-31 20:21:45'),(14,'Tacos al Pastor','Mexican style pork tacos','pork, pineapple, tortillas, onion, cilantro, lime','Marinate pork overnight\r\nGrill until charred\r\nServe in warm tortillas','uploads/recipes/1767201637_Tacos-al-Pastor.webp',60,2,9,'2025-12-31 15:45:31','2025-12-31 20:20:37'),(15,'Shrimp Scampi','Garlic butter shrimp','shrimp, garlic, butter, white wine, parsley, lemon','Sauté garlic in butter\r\nAdd shrimp and cook\r\nFinish with wine and lemon','uploads/recipes/1767201429_Ranch-Shrimp-Scampi.jpg',20,1,9,'2025-12-31 15:45:31','2025-12-31 20:17:09'),(16,'Beef Lasagna','Layered pasta with meat sauce','lasagna sheets, ground beef, tomato sauce, mozzarella, ricotta, parmesan','Make meat sauce\r\nLayer with cheese and pasta\r\nBake until golden','uploads/recipes/1767201306_Classic_beef_lasagne.jpg',90,3,9,'2025-12-31 15:45:31','2025-12-31 20:15:06'),(17,'Thai Green Curry','Spicy Thai coconut curry','chicken, green curry paste, coconut milk, bamboo shoots, basil, fish sauce','Fry curry paste in oil\r\nAdd chicken and coconut milk\r\nSimmer with vegetables','uploads/recipes/1767201234_thai-green-chicken-curry-198183-1.jpg',35,2,9,'2025-12-31 15:45:31','2025-12-31 20:13:54'),(18,'Tiramisu','Classic Italian coffee dessert','ladyfingers, mascarpone, espresso, cocoa powder, eggs, sugar','Dip ladyfingers in coffee\r\nLayer with mascarpone cream\r\nDust with cocoa','uploads/recipes/1767201056_Tiramisu_1426.avif',30,2,2,'2025-12-31 15:45:31','2025-12-31 20:10:56'),(19,'Cheesecake','Creamy New York style','cream cheese, sugar, eggs, graham crackers, butter, vanilla','Make crust with crackers\r\nMix filling and pour\r\nBake in water bath','uploads/recipes/1767200997_cheesecake.jpg',120,3,2,'2025-12-31 15:45:31','2025-12-31 20:09:57'),(20,'Brownies','Fudgy chocolate brownies','chocolate, butter, sugar, eggs, flour, cocoa powder','Melt chocolate and butter\r\nMix with other ingredients\r\nBake until set','uploads/recipes/1767200762_Lunch-Lady-Brownies-BFK-12-1-of-1.jpg',40,1,2,'2025-12-31 15:45:31','2025-12-31 20:06:02'),(21,'Apple Pie','Traditional apple pie','apples, pie crust, sugar, cinnamon, butter, lemon juice','Prepare pie dough\r\nFill with spiced apples\r\nBake until golden','uploads/recipes/1767200397_classic-apple-pie-recipe-10-1.jpg',90,2,2,'2025-12-31 15:45:31','2025-12-31 19:59:57'),(22,'Crème Brûlée','French vanilla custard','cream, egg yolks, sugar, vanilla bean','Make custard mixture\r\nBake in ramekins\r\nCaramelize sugar on top','uploads/recipes/1767200317_creme_brulle.jpg',60,3,2,'2025-12-31 15:45:31','2025-12-31 19:58:37'),(23,'Panna Cotta','Italian cream dessert','cream, sugar, gelatin, vanilla, berry sauce','Heat cream with sugar\r\nAdd gelatin\r\nChill until set','uploads/recipes/1767200238_panna_cotta.jpg',20,2,2,'2025-12-31 15:45:31','2025-12-31 19:57:18'),(24,'Lemon Tart','Tangy lemon dessert','lemon juice, eggs, sugar, butter, tart shell','Make lemon curd filling\r\nPour into baked shell\r\nChill before serving','uploads/recipes/1767189380_106-lemon-tart.jpg',50,2,2,'2025-12-31 15:45:31','2025-12-31 16:56:20'),(25,'Chocolate Mousse','Light and airy chocolate','dark chocolate, eggs, cream, sugar','Melt chocolate\r\nFold in whipped cream\r\nChill until set','uploads/recipes/1767189321_mouse.jpg',25,2,2,'2025-12-31 15:45:31','2025-12-31 16:55:21'),(26,'Tomato Soup','Classic creamy tomato','tomatoes, cream, onion, garlic, basil, vegetable stock','Sauté onions and garlic\r\nAdd tomatoes and stock\r\nBlend until smooth','uploads/recipes/1767189273_tomato.jpg',30,1,3,'2025-12-31 15:45:31','2025-12-31 16:54:33'),(27,'Chicken Noodle Soup','Comforting chicken soup','chicken, noodles, carrots, celery, onion, chicken broth','Simmer chicken in broth\r\nAdd vegetables\r\nCook noodles separately','uploads/recipes/1767189223_noodlesoupp.jpg',45,1,3,'2025-12-31 15:45:31','2025-12-31 16:53:43'),(28,'French Onion Soup','Caramelized onion soup','onions, beef broth, bread, gruyere cheese, butter, thyme','Caramelize onions slowly\r\nAdd broth and simmer\r\nTop with cheese toast','uploads/recipes/1767189128_13309-rich-and-simple-french-onion-soup-DDMFS-4x3-31ad7eaa56234d20ae35c3940fd03f36.jpg',60,2,3,'2025-12-31 15:45:31','2025-12-31 16:52:08'),(29,'Minestrone','Italian vegetable soup','mixed vegetables, pasta, beans, tomato, garlic, parmesan','Sauté vegetables\r\nAdd broth and beans\r\nCook pasta in soup','uploads/recipes/1767189058_minestrone_23211_16x9.jpg',40,1,3,'2025-12-31 15:45:31','2025-12-31 16:50:58'),(30,'Butternut Squash Soup','Creamy autumn soup','butternut squash, onion, cream, vegetable stock, nutmeg','Roast squash until tender\r\nBlend with stock\r\nAdd cream to finish','uploads/recipes/1767189001_RESIZEDdairy-free-butternut-squash-soup-19.jpg',50,2,3,'2025-12-31 15:45:31','2025-12-31 16:50:01'),(31,'Mojito','Refreshing mint cocktail','white rum, mint leaves, lime, sugar, soda water','Muddle mint and lime\r\nAdd rum and sugar\r\nTop with soda water','uploads/recipes/1767188818_mojito.jpg',5,1,4,'2025-12-31 15:45:31','2025-12-31 16:46:58'),(32,'Smoothie Bowl','Healthy breakfast bowl','banana, berries, yogurt, granola, honey','Blend frozen fruit with yogurt\r\nPour into bowl\r\nTop with granola','uploads/recipes/1767188773_smoothie-bowl-recipes-GettyImages-1298159858-8fe42e942259498eb9baa6e830dd148a.jpg',10,1,4,'2025-12-31 15:45:31','2025-12-31 16:46:13'),(33,'Hot Chocolate','Rich creamy hot chocolate','milk, dark chocolate, sugar, vanilla, whipped cream','Heat milk gently\r\nMelt chocolate in milk\r\nTop with cream','uploads/recipes/1767188403_hot_chocolate.jpg',10,1,4,'2025-12-31 15:45:31','2025-12-31 16:40:03'),(34,'Mango Lassi','Indian yogurt drink','mango, yogurt, milk, sugar, cardamom','Blend all ingredients\r\nServe chilled\r\nGarnish with mint','uploads/recipes/1767188649_5pDvFPyuY0hDzoUMzSQLrR.webp',5,1,4,'2025-12-31 15:45:31','2025-12-31 16:44:09'),(35,'Greek Salad','Mediterranean classic','cucumber, tomato, feta, olives, onion, olive oil, oregano','Chop vegetables\r\nAdd feta and olives\r\nDress with oil and herbs','uploads/recipes/1767188594_Simply-Recipes-Easy-Greek-Salad-LEAD-2-4601eff771fd4de38f9722e8cafc897a.jpg',15,1,5,'2025-12-31 15:45:31','2025-12-31 16:43:14'),(36,'Quinoa Salad','Healthy grain salad','quinoa, cucumber, tomato, parsley, lemon, olive oil','Cook quinoa and cool\r\nMix with chopped vegetables\r\nDress with lemon juice','uploads/recipes/1767188506_177-quinoa-salad-with-lemon-dijon-vinaigrette.webp',20,1,5,'2025-12-31 15:45:31','2025-12-31 16:41:46'),(37,'Caprese Salad','Italian tomato mozzarella','tomatoes, mozzarella, basil, olive oil, balsamic vinegar','Slice tomatoes and cheese\r\nArrange with basil\r\nDrizzle with oil','uploads/recipes/caprese_salad.jpg',10,1,5,'2025-12-31 15:45:31','2025-12-31 16:32:06');
/*!40000 ALTER TABLE `recipes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `PASSWORD` varchar(255) NOT NULL,
  `role` tinyint NOT NULL DEFAULT '0',
  `is_banned` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_username` (`username`),
  UNIQUE KEY `unique_email` (`email`),
  KEY `idx_role` (`role`),
  KEY `idx_is_banned` (`is_banned`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'bariscanaslan','bariscanaslan@outlook.com','$2y$10$ZdWbIeR0yxfDcCJPqQb8Y.v8cvTsNCrMhjucLUIjVqeGLSXWuhoEq',0,0,'2025-11-30 18:01:54','2025-12-31 12:32:20'),(2,'yigitavar','yigitavar@gmail.com','$2y$10$ZdWbIeR0yxfDcCJPqQb8Y.v8cvTsNCrMhjucLUIjVqeGLSXWuhoEq',0,0,'2025-11-30 18:01:54','2025-12-31 12:32:23'),(3,'toprakkamburoglu','toprakzenc@outlook.com','$2y$10$ZdWbIeR0yxfDcCJPqQb8Y.v8cvTsNCrMhjucLUIjVqeGLSXWuhoEq',1,0,'2025-11-30 18:01:54','2025-12-31 12:32:26'),(4,'nargizhuseyinova','nargizazerbaijan@gmail.com','$2y$10$ZdWbIeR0yxfDcCJPqQb8Y.v8cvTsNCrMhjucLUIjVqeGLSXWuhoEq',0,0,'2025-11-30 18:01:54','2026-01-02 14:58:07'),(5,'zisantunceli','ziso@outlook.com','$2y$10$ZdWbIeR0yxfDcCJPqQb8Y.v8cvTsNCrMhjucLUIjVqeGLSXWuhoEq',0,1,'2025-11-30 18:01:54','2025-12-31 12:32:31'),(6,'albertozhoken','albert.ozhoken@khas.edu.tr','$2y$10$ZdWbIeR0yxfDcCJPqQb8Y.v8cvTsNCrMhjucLUIjVqeGLSXWuhoEq',2,0,'2025-11-30 18:38:57','2025-12-31 12:32:33'),(12,'Zeynep Kucuk','zzeynep.kucukk@icloud.com','$2y$10$QkypqYiqPjtlqP8o4UM2h.PNZ3CVm3arBevUt/bA0MhH.K4c1D9uG',0,0,'2025-12-30 19:30:55','2025-12-30 19:30:55'),(13,'Mauro icardi','mauroicardi@outlook.com','$2y$10$ZdWbIeR0yxfDcCJPqQb8Y.v8cvTsNCrMhjucLUIjVqeGLSXWuhoEq',0,0,'2025-12-30 19:31:30','2025-12-30 19:31:30'),(14,'Osimhen','osimhen@outlook.com','$2y$10$l3cw470eH/eIawNAFNtICOQ/Qj0ykxPywhad6WtHopInJ4SgrpZXm',0,0,'2025-12-30 19:31:52','2026-01-02 14:18:13'),(15,'Fatih Terim','fatih.terim@gmail.com','$2y$10$ps/vphasgPLiI6zvbVhqE.1ainvxCyV8MW3LBE3TgTy9k0sO1WKlS',0,0,'2025-12-30 19:32:33','2025-12-30 19:32:33'),(16,'James Bond','james.bond@outlook.com','$2y$10$kS2JClYprzT01qCLle1y5OAHFeoFUUVfg2y8AhWiWd/Nbe3olL76e',0,0,'2025-12-30 19:33:01','2025-12-30 19:33:01'),(17,'yunusakgun','yunusakgun@outlook.com','$2y$10$3.YCwKBcVeRwDBE9G2XbYuVeEE6Jq3CgumtEEs8FFLmrRLWZ14ruK',0,0,'2025-12-31 13:19:16','2025-12-31 13:19:16'),(18,'zişan','zisan@outlook.com','$2y$10$EGUlmv7SuIu4JCwPBB0YleKNp1vUk0ypw4vHouyDmVvke7p0VwFgq',0,0,'2026-01-02 16:29:01','2026-01-02 16:29:01');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-02-18 22:11:36
