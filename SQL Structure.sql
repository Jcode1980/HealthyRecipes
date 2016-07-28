# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: localhost (MySQL 5.6.25)
# Database: healthyrecipes
# Generation Time: 2016-05-20 12:35:35 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table Accreditation
# ------------------------------------------------------------

DROP TABLE IF EXISTS `Accreditation`;

CREATE TABLE `Accreditation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table AdminUser
# ------------------------------------------------------------

DROP TABLE IF EXISTS `AdminUser`;

CREATE TABLE `AdminUser` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` tinytext,
  `login` tinytext,
  `password` tinytext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table Comment
# ------------------------------------------------------------

DROP TABLE IF EXISTS `Comment`;

CREATE TABLE `Comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comment` mediumtext,
  `recipeID` int(11) DEFAULT NULL,
  `userID` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table DieteryCategory
# ------------------------------------------------------------

DROP TABLE IF EXISTS `DieteryCategory`;

CREATE TABLE `DieteryCategory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` tinytext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table DieteryCategoryRecipe
# ------------------------------------------------------------

DROP TABLE IF EXISTS `DieteryCategoryRecipe`;

CREATE TABLE `DieteryCategoryRecipe` (
  `dieteryCategoryID` int(11) NOT NULL,
  `recipeID` int(11) NOT NULL,
  PRIMARY KEY (`dieteryCategoryID`,`recipeID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table FollowingUser
# ------------------------------------------------------------

DROP TABLE IF EXISTS `FollowingUser`;

CREATE TABLE `FollowingUser` (
  `userID` int(11) NOT NULL,
  `userID1` int(11) NOT NULL,
  PRIMARY KEY (`userID`,`userID1`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table HealthBenefitTag
# ------------------------------------------------------------

DROP TABLE IF EXISTS `HealthBenefitTag`;

CREATE TABLE `HealthBenefitTag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `healthDescription` mediumtext,
  `name` tinytext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table HealthBenefitTagIngredient
# ------------------------------------------------------------

DROP TABLE IF EXISTS `HealthBenefitTagIngredient`;

CREATE TABLE `HealthBenefitTagIngredient` (
  `healthBenefitTagID` int(11) NOT NULL,
  `ingredientID` int(11) NOT NULL,
  PRIMARY KEY (`healthBenefitTagID`,`ingredientID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table HealthBenefitTagRecipe
# ------------------------------------------------------------

DROP TABLE IF EXISTS `HealthBenefitTagRecipe`;

CREATE TABLE `HealthBenefitTagRecipe` (
  `healthBenefitTagID` int(11) NOT NULL,
  `recipeID` int(11) NOT NULL,
  PRIMARY KEY (`healthBenefitTagID`,`recipeID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table Ingredient
# ------------------------------------------------------------

DROP TABLE IF EXISTS `Ingredient`;

CREATE TABLE `Ingredient` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` tinytext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table IngredientIngredientType
# ------------------------------------------------------------

DROP TABLE IF EXISTS `IngredientIngredientType`;

CREATE TABLE `IngredientIngredientType` (
  `ingredientID` int(11) NOT NULL AUTO_INCREMENT,
  `ingredientTypeID` int(11) NOT NULL,
  PRIMARY KEY (`ingredientID`,`ingredientTypeID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table IngredientType
# ------------------------------------------------------------

DROP TABLE IF EXISTS `IngredientType`;

CREATE TABLE `IngredientType` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` tinytext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table Like_x
# ------------------------------------------------------------

DROP TABLE IF EXISTS `Like_x`;

CREATE TABLE `Like_x` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `recipeID` int(11) DEFAULT NULL,
  `userID` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table MealType
# ------------------------------------------------------------

DROP TABLE IF EXISTS `MealType`;

CREATE TABLE `MealType` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` tinytext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table MealTypeRecipe
# ------------------------------------------------------------

DROP TABLE IF EXISTS `MealTypeRecipe`;

CREATE TABLE `MealTypeRecipe` (
  `mealTypeID` int(11) NOT NULL,
  `recipeID` int(11) NOT NULL,
  PRIMARY KEY (`mealTypeID`,`recipeID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table NewsFeed
# ------------------------------------------------------------

DROP TABLE IF EXISTS `NewsFeed`;

CREATE TABLE `NewsFeed` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created` datetime DEFAULT NULL,
  `recipeID` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table NewsFeedUser
# ------------------------------------------------------------

DROP TABLE IF EXISTS `NewsFeedUser`;

CREATE TABLE `NewsFeedUser` (
  `newsFeedID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  PRIMARY KEY (`newsFeedID`,`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table NutritionistUserRecommendation
# ------------------------------------------------------------

DROP TABLE IF EXISTS `NutritionistUserRecommendation`;

CREATE TABLE `NutritionistUserRecommendation` (
  `recommendationID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  PRIMARY KEY (`recommendationID`,`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table Rating
# ------------------------------------------------------------

DROP TABLE IF EXISTS `Rating`;

CREATE TABLE `Rating` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comment` mediumtext NOT NULL,
  `recipeID` int(11) DEFAULT NULL,
  `userID` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table Recipe
# ------------------------------------------------------------

DROP TABLE IF EXISTS `Recipe`;

CREATE TABLE `Recipe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` tinytext,
  `recipeDescription` mediumtext,
  `userID` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table RecipeBook
# ------------------------------------------------------------

DROP TABLE IF EXISTS `RecipeBook`;

CREATE TABLE `RecipeBook` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` tinytext,
  `userID` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table RecipeRecipeBook
# ------------------------------------------------------------

DROP TABLE IF EXISTS `RecipeRecipeBook`;

CREATE TABLE `RecipeRecipeBook` (
  `recipeBookID` int(11) NOT NULL,
  `recipeID` int(11) NOT NULL,
  PRIMARY KEY (`recipeBookID`,`recipeID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table Recommendation
# ------------------------------------------------------------

DROP TABLE IF EXISTS `Recommendation`;

CREATE TABLE `Recommendation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `recipeID` int(11) NOT NULL,
  `recommendationReason` tinytext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table UnitOfMeasure
# ------------------------------------------------------------

DROP TABLE IF EXISTS `UnitOfMeasure`;

CREATE TABLE `UnitOfMeasure` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` tinytext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table User
# ------------------------------------------------------------

DROP TABLE IF EXISTS `User`;

CREATE TABLE `User` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created` datetime DEFAULT NULL,
  `given` tinytext,
  `surname` tinytext,
  `login` tinytext,
  `password` tinytext,
  `deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
