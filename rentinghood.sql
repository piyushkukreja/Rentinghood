-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 05, 2018 at 05:37 PM
-- Server version: 10.1.28-MariaDB
-- PHP Version: 7.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rentinghood`
--

DELIMITER $$
--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `haversine` (`lat1` DECIMAL(8,6), `lng1` DECIMAL(8,6), `lat2` DECIMAL(8,6), `lng2` DECIMAL(8,6)) RETURNS DECIMAL(8,6) BEGIN
    DECLARE R INT;
    DECLARE dLat DECIMAL(30,15);
    DECLARE dLng DECIMAL(30,15);
    DECLARE a1 DECIMAL(30,15);
    DECLARE a2 DECIMAL(30,15);
    DECLARE a DECIMAL(30,15);
    DECLARE c DECIMAL(30,15);
    DECLARE d DECIMAL(30,15);

    SET R = 3959; -- Earth's radius in miles
    SET dLat = RADIANS( lat2 ) - RADIANS( lat1 );
    SET dLng = RADIANS( lng2 ) - RADIANS( lng1 );
    SET a1 = SIN( dLat / 2 ) * SIN( dLat / 2 );
    SET a2 = SIN( dLng / 2 ) * SIN( dLng / 2 ) * COS( RADIANS( lat1 )) * COS( RADIANS( lat2 ) );
    SET a = a1 + a2;
    SET c = 2 * ATAN2( SQRT( a ), SQRT( 1 - a ) );
    SET d = R * c;
    RETURN d;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'books', NULL, NULL),
(2, 'clothes', NULL, NULL),
(3, 'vehicles', NULL, NULL),
(4, 'real_estate', NULL, NULL),
(5, 'bags', NULL, NULL),
(6, 'furniture', NULL, NULL),
(7, 'appliances', NULL, NULL),
(8, 'hardware', NULL, NULL),
(9, 'gaming_consoles', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(4, '2018_02_02_000003_create_categories_table', 1),
(5, '2018_02_02_000004_create_subcategories_table', 1),
(10, '2014_10_12_100000_create_password_resets_table', 2),
(15, '2018_02_02_000005_create_users_table', 3),
(16, '2018_02_02_000006_create_products_table', 3),
(17, '2018_02_02_000007_create_product_pictures_table', 3),
(18, '2018_02_02_000008_create_transactions_table', 3),
(20, '2018_05_06_150011_add_privileges_users', 4),
(21, '2018_05_06_145831_create_notes_table', 5),
(22, '2018_06_04_175244_add_verified_products_table', 6);

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `id` int(10) UNSIGNED NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `admin_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notes`
--

INSERT INTO `notes` (`id`, `note`, `user_id`, `admin_id`, `created_at`, `updated_at`) VALUES
(3, 'Checking notes on this account..', 3, 3, '2018-05-08 05:47:34', '2018-05-08 05:47:34'),
(4, 'Yep it works', 3, 3, '2018-05-08 05:48:54', '2018-05-08 05:48:54');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subcategory_id` int(10) UNSIGNED NOT NULL,
  `lender_id` int(10) UNSIGNED NOT NULL,
  `availability` tinyint(1) NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration` enum('0','1','2') COLLATE utf8mb4_unicode_ci NOT NULL,
  `rate_1` int(10) UNSIGNED NOT NULL,
  `rate_2` int(10) UNSIGNED NOT NULL,
  `rate_3` int(10) UNSIGNED NOT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lat` decimal(9,6) NOT NULL,
  `lng` decimal(9,6) NOT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `verified` tinyint(3) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `subcategory_id`, `lender_id`, `availability`, `description`, `duration`, `rate_1`, `rate_2`, `rate_3`, `address`, `lat`, `lng`, `image`, `created_at`, `updated_at`, `verified`) VALUES
(9, 'Dell 5570', 20, 3, 0, 'i7, 8GB RAM, 1TB Hard-drive', '1', 60, 300, 0, 'Kailash Colony, Ulhasnagar, Maharashtra, India', '19.199734', '73.169287', '68116c1192041e25cd3e78707bf4e334f81251cd9b0d.jpg', NULL, NULL, 0),
(10, 'Crushing it', 3, 4, 1, 'Author - Gary Vaynerchuck', '1', 20, 0, 0, 'Kailash Colony, Ulhasnagar, Maharashtra, India', '19.199734', '73.169287', '873364b15beb8395e27aa362222e25775f662607901f.jpg', NULL, '2018-05-09 07:32:41', 0),
(11, 'Acer Projector', 21, 4, 0, 'Black colored, supports 3D videos,\r\nComes with Complementary white Board stand.', '1', 1000, 4000, 0, 'Ulhasnagar, Maharashtra, India', '19.221512', '73.164463', '5613495ef30d55fd23f6b36dc938c0bb61152fce7e6b.png', NULL, NULL, 0),
(12, 'How to be a Bawse', 3, 5, 1, 'This is by Lilly Singh', '1', 20, 100, 0, 'Ulhasnagar, Maharashtra, India', '19.221512', '73.164463', '882033d1e42d81ddd14f2ddea02697fd056ece507338.jpg', NULL, '2018-06-04 13:43:45', 1),
(13, 'Trekk Bag', 12, 4, 1, 'Colour Green', '1', 50, 350, 0, 'Ulhasnagar, Maharashtra, India', '19.221512', '73.164463', '6764a9bc3565095d1fded7d57aacb46a57d261695201.jpg', NULL, NULL, 0),
(15, 'Camping Chair', 13, 4, 1, 'Colour- Black\r\nfoldable, lightweight, easy to carry.', '1', 30, 200, 0, 'Ulhasnagar, Maharashtra, India', '19.221512', '73.164463', '2033293d260f33245429a6a0ca2407e896de8ead375c.jpg', NULL, NULL, 0),
(16, 'Plastic Chair', 13, 4, 1, 'Good Quality plastic chair\r\nI have about 10 such chairs.', '1', 10, 50, 0, 'Ulhasnagar, Maharashtra, India', '19.221512', '73.164463', '3235cc0a2436a025ae9241972b86763d1cfb1e78987d.jpg', NULL, NULL, 0),
(17, 'Acer Projector', 21, 4, 1, 'Black Acer Projector', '1', 1000, 3000, 0, 'Ulhasnagar, Maharashtra, India', '19.221512', '73.164463', '8626226ddf3efc6a01b5c266b0d42767113ae716d832.png', NULL, NULL, 0),
(18, 'Crush It', 3, 4, 1, 'How to build a business around your passion\r\nBy - Gary Vaynerchuck.', '2', 0, 30, 100, 'Ulhasnagar, Maharashtra, India', '19.221512', '73.164463', '16444c8afc90d24854f2ab050ca241b2e27f5784891c.jpg', NULL, NULL, 0),
(19, 'Zero  to One', 3, 4, 1, 'Startups, Entrepreneurship.\r\nAuthor- Peter Thiel', '2', 0, 40, 120, 'Ulhasnagar, Maharashtra, India', '19.221512', '73.164463', '95063421292f908af7ae3fce4956dbb522ab00c3c8ed.jpg', NULL, NULL, 0),
(20, 'The Man From The Egg', 2, 5, 0, 'This is a book written by Indian Author Sudha Murthy. It\'s a must read to quench your mythological thirst.', '2', 50, 20, 200, 'Ulhasnagar, Maharashtra, India', '19.221512', '73.164463', '5921e16a7e5b2f013c18d88888d28b70c8feca0dbaa5.jpg', NULL, NULL, 0),
(21, 'Diary of a Wimpy Kid', 1, 4, 1, 'Diary of a Wimpy Kid is a series of fiction books written by the American author and cartoonist Jeff Kinney. All the main books are the journals of the main character, Greg Heffley.', '2', 0, 30, 70, 'Ulhasnagar, Maharashtra, India', '19.221512', '73.164463', '6853c41e53cbb3043907e8e99e2bcba8ffb52c1aac1d.jpg', NULL, NULL, 0),
(22, 'You Can win', 3, 4, 1, 'By Shiv khera<br>An easy-to-read, practical, common-sense guide that will take you from ancient wisdom to modern-day thinking, You Can Win helps you establish new goals, develop a new sense of purpose, and generate new ideas about yourself and your future', '2', 0, 60, 80, 'Mumbai,maharashtra,india', '19.076000', '72.877700', 'YouCanwin.jpg', NULL, NULL, 0),
(23, 'Hundred years of soltitude', 1, 4, 1, 'By Gabriel gareia marquez<br>One Hundred Years of Solitude is a landmark 1967 novel by Colombian author Gabriel García Márquez that tells the multi-generational story of the Buendía family,', '2', 0, 60, 80, 'Mumbai,maharashtra,india', '19.076000', '72.877700', 'Hundredyearsofsoltitude.jpg', NULL, NULL, 0),
(24, 'The magic of thinking Big', 3, 4, 1, 'By David j. Schwartz<br>The Magic of Thinking Big, first published in 1959, is a self-help book by David Schwartz. The book, which has sold over 4 million copies, instructs people to set their goals high and think positively to achieve them.', '2', 0, 60, 80, 'Mumbai,maharashtra,india', '19.076000', '72.877700', 'ThemagicofthinkingBig1.jpg', NULL, '2018-05-09 08:33:21', 0),
(25, 'I moved your cheese', 3, 4, 1, 'By Darrel bristow bovey<br>This is the self-help book for people lying on the sofa. This book will tell you how to reap the rewards of being a better person without having to trouble yourself with the unnecessary burden of actually becoming better. ', '2', 0, 60, 80, 'Mumbai,maharashtra,india', '19.076000', '72.877700', 'Imovedyourcheese.jpg', NULL, NULL, 0),
(26, 'The Japanese wife', 1, 4, 1, 'By Kunal basu<br>An Indian man writes to a Japanese woman. She writes back. They fall in love and exchange vows in their letters, then live as man and wife without ever setting eyes on each other – their intimacy of words finally tested by life’s miraculous upheavals.The twelve stories in this collection are about the unexpected. These are chronicles of memory and dreams born at the crossroads of civilizations. They parade a cast of angels and demons rubbing shoulders with those whose lives are never quite as ordinary as they seem.', '2', 0, 60, 80, 'Mumbai,maharashtra,india', '19.076000', '72.877700', 'TheJapanesewife.jpg', NULL, NULL, 0),
(27, 'Strengths finder 2.0', 3, 4, 1, 'By Tom rath<br>Do You Do What You Do Best Every Day?\n\nChances are, you don\'t. From the cradle to the cubicle, we devote more time to fixing our shortcomings than to developing our strengths.\n\nTo help people uncover their talents, Gallup introduced StrengthsFinder in the 2001 management book Now, Discover Your Strengths. The book ignited a global conversation, while StrengthsFinder helped millions discover their top five talents.\n\nIn StrengthsFinder 2.0, Gallup unveils the new and improved version of its popular online assessment. With hundreds of strategies for applying your strengths, StrengthsFinder 2.0 will change the way you look at yourself and the world forever.', '2', 0, 60, 80, 'Mumbai,maharashtra,india', '19.076000', '72.877700', 'Strengthsfinder20.jpg', NULL, NULL, 0),
(28, 'How I thaught my grandmother to read and other stories', 2, 4, 1, 'By Sudha murty<br>How I Taught My Grandmother to Read is a fictional narrative or a short story written by famous Indian prolific fiction author Sudha Murty.', '2', 0, 60, 80, 'Mumbai,maharashtra,india', '19.076000', '72.877700', 'HowIthaughtmygrandmothertoreadandotherstories.jpg', NULL, NULL, 0),
(29, 'Napolean Bonapart', 2, 4, 0, 'By The little corporal<br>', '2', 0, 60, 80, 'Mumbai,maharashtra,india', '19.076000', '72.877700', 'NapoleanBonapart.jpg', NULL, NULL, 0),
(30, 'What they don\'t teach you at Harvard business school', 3, 4, 1, 'By Mark H McCornack<br>A straight-talking must-read of powerful strategies for every executive headed for the top. Written in the same no-nonsense, hard-hitting manner that McCormack brings to his own fast-paced business and management style, this is mandatory reading for executives on every rung of the corporate ladder.', '2', 0, 60, 80, 'Mumbai,maharashtra,india', '19.076000', '72.877700', 'Whattheydon_tteachyouatHarvardbusinessschool.jpg', NULL, NULL, 0),
(31, 'The new Strategic Selling', 34, 4, 1, 'By Robert Miller<br>All of the Miller Heiman strategic sales principles, previously available only through a costly and restricted seminar, and disclosed to business executives, managers, and sales personnel seeking a competitive advantage', '2', 0, 80, 100, 'Mumbai,maharashtra,india', '19.076000', '72.877700', 'ThenewStrategicSelling.jpg', NULL, NULL, 0),
(32, 'The Choice', 1, 4, 1, 'By Nicholas Sparks<br>The Choice is a 2007 novel written by Nicholas Sparks. It was first published on September 24, 2007 by Grand Central Publishing.', '2', 0, 60, 80, 'Mumbai,maharashtra,india', '19.076000', '72.877700', 'TheChoice.jpg', NULL, NULL, 0),
(33, 'Quote me if you can', 34, 4, 1, 'By N.S Rajan<br>This is a book of thoughts by Dr N.S. Rajan, a member of the Group Executive Council and Group Chief Human Resources Officer of Tata Sons. A widely-followed thought leader, Rajan has been studying happiness at work for decades', '2', 0, 60, 80, 'Mumbai,maharashtra,india', '19.076000', '72.877700', 'Quotemeifyoucan.jpg', NULL, NULL, 0),
(34, 'What young India wants', 2, 4, 1, 'By Chetan bhagat<br>What Young India Wants is a non-fiction book by Chetan Bhagat. A compilation of his speeches and essays, it focuses on Indiansociety, politics the youth. The book revolves around Bhagat\'s thoughts and innovations on how to improve the Indian economy through social reforms.', '2', 0, 60, 80, 'Mumbai,maharashtra,india', '19.076000', '72.877700', 'WhatyoungIndiawants.jpg', NULL, NULL, 0),
(35, 'The story of my life', 35, 4, 1, 'By Helen Keller<br>The Story of My Life, first published in 1903, is Helen Keller\'s autobiography detailing her early life, especially her experiences with Anne Sullivan', '2', 0, 60, 80, 'Mumbai,maharashtra,india', '19.076000', '72.877700', 'Thestoryofmylife.jpg', NULL, NULL, 0),
(36, 'It Happened in India', 34, 4, 1, 'By Kishore Biyani<br>Born in a middle class trading family, Kishore Biyani started his career selling stonewash fabric to small shops in Mumbai.', '2', 0, 60, 80, 'Mumbai,maharashtra,india', '19.076000', '72.877700', 'ItHappenedinIndia.jpg', NULL, NULL, 0),
(37, 'Blue Ocean Strategy', 34, 4, 1, 'Blue Ocean Strategy is a marketing theory from a book published in 2005 which was written by W. Chan Kim and Renée Mauborgne, professors at INSEAD and co-directors of the INSEAD Blue Ocean Strategy Institute.', '2', 0, 60, 80, 'Mumbai,maharashtra,india', '19.076000', '72.877700', 'BlueOceanStrategy.jpg', NULL, NULL, 0),
(38, 'The god of small things', 1, 4, 1, 'By Arundhati Roy<br>The God of Small Things is the debut novel of Indian writer Arundhati Roy. It is a story about the childhood experiences of fraternal twins whose lives are destroyed by the \"Love Laws\" that lay down \"who should be loved, and how. And how much.', '2', 0, 60, 80, 'Mumbai,maharashtra,india', '19.076000', '72.877700', 'Thegodofsmallthings.jpg', NULL, NULL, 0),
(39, 'Strategies for the future', 34, 4, 1, 'By Ajeet N Mathur<br>Have you wondered why international business seems magically simple in text books but inescapably complex in reality? With international business pushing horizons, cross border activity is rampant and national boundaries are getting blurred', '2', 0, 60, 80, 'Mumbai,maharashtra,india', '19.076000', '72.877700', 'Strategiesforthefuture.jpg', NULL, NULL, 0),
(40, '2 States', 1, 4, 1, 'By Chetan Bhagat<br>2 States: The Story of My Marriage commonly known as 2 States is a 2009 novel written by Chetan Bhagat.', '2', 0, 30, 50, 'Mumbai,maharashtra,india', '19.076000', '72.877700', '2States.jpg', NULL, NULL, 0),
(41, 'Selected ghost stories', 1, 4, 0, 'The Ghost Stories of M.R. James collects the tales that best illustrate the author\'s quiet mastery of the ghost story form', '2', 0, 60, 80, 'Mumbai,maharashtra,india', '19.076000', '72.877700', 'Selectedghoststories.jpg', NULL, NULL, 0),
(42, 'Great works of Fyodor Dostoevsky', 1, 4, 1, 'The shorter works of one of the world\'s greatest writers, including The Gambler and Notes from Underground The short works of Dostoevsky exist in the very large shadow of his astonishing longer novels', '2', 0, 60, 80, 'Mumbai,maharashtra,india', '19.076000', '72.877700', 'GreatworksofFyodorDostoevsky.jpg', NULL, NULL, 0),
(43, 'Half girlfriend', 1, 4, 1, 'By Chetan Bhagat<br>Half Girlfriend is an Indian English coming of age, young adult romance novel by Indian author Chetan Bhagat. The novel, set in rural Bihar, New Delhi, Patna, and New York, is the story of a Bihari boy in quest of winning over the girl he loves', '2', 0, 60, 80, 'Mumbai,maharashtra,india', '19.076000', '72.877700', 'Halfgirlfriend.jpg', NULL, NULL, 0),
(44, 'Harry Potter and the goblet of fire', 1, 4, 1, 'By J.K Rowling<br>Harry Potter and the Goblet of Fire is a fantasy book written by British author J. K. Rowling and the fourth novel in the Harry Potter series.', '2', 0, 60, 80, 'Mumbai,maharashtra,india', '19.076000', '72.877700', 'HarryPotterandthegobletoffire.jpg', NULL, NULL, 0),
(45, 'Shantaram', 1, 4, 1, 'By Gregory David Roberts<br>Shantaram is a 2003 novel by Gregory David Roberts, in which a convicted Australian bank robber and heroin addict who escaped from Pentridge Prison flees to India. The novel is commended by many for its vivid portrayal of tumultuous life in Bombay.', '2', 0, 60, 80, 'Mumbai,maharashtra,india', '19.076000', '72.877700', 'Shantaram.jpg', NULL, NULL, 0),
(46, 'Harry Potter and the Deathly Hollows', 1, 4, 1, 'By J.k Rowling<br>Harry Potter and the Deathly Hallows is a fantasy novel written by British author J. K. Rowling and the seventh and final novel of the Harry Potter series', '2', 0, 60, 80, 'Mumbai,maharashtra,india', '19.076000', '72.877700', 'HarryPotterandtheDeathlyHollows.jpg', NULL, NULL, 0),
(47, 'God is a gamer', 34, 4, 1, 'By Ravi Subramanian<br>Aditya runs a gaming company that is struggling to break even. A banker slips off a highrise building, plunging to her death. The finance minister has made some promises that he is finding hard to keep', '2', 0, 60, 80, 'Mumbai,maharashtra,india', '19.076000', '72.877700', 'Godisagamer.jpg', NULL, NULL, 0),
(48, 'Chain of custody', 1, 4, 1, 'By Anita nair<br>What does thirteen-year-old Nandita’s disappearance have to do with the murder of a prominent lawyer in a gated community? As Gowda investigates, he is suddenly embroiled in Bangalore’s child-trafficking racket.', '2', 0, 60, 80, 'Mumbai,maharashtra,india', '19.076000', '72.877700', 'Chainofcustody.jpg', NULL, NULL, 0),
(49, 'Simplified Financial Management', 4, 4, 1, 'By Vinay Bhagwat<br>', '2', 0, 60, 80, 'Mumbai,maharashtra,india', '19.076000', '72.877700', 'SimplifiedFinancialManagement.jpg', NULL, NULL, 0),
(50, 'Financial Analysis', 4, 4, 1, 'By Gill & Chatton<br>Written by the author of \"Understanding Financial Statements\", this book provides more advanced, useful information on topics such as forecasting, budgeting techniques, corporate statements, and the use of common stock and debts for capitalization.', '2', 0, 100, 200, 'Mumbai,maharashtra,india', '19.076000', '72.877700', 'FinancialAnalysis.jpg', NULL, NULL, 0),
(51, 'Anywhere but home', 2, 4, 1, 'By Any vaidyanath<br>Anu Vaidyanathan is the first Asian woman to complete the Ultraman Canada: a punishing 10-km swim, a 420-km bike ride and an 84.4-km run. She placed sixth. Which is breathtaking', '2', 0, 60, 80, 'Mumbai,maharashtra,india', '19.076000', '72.877700', 'Anywherebuthome.jpg', NULL, NULL, 0),
(52, 'My India, the India eternal', 2, 4, 1, 'By Swami Vivekananda<br>Our love for India came to birth, I think, when we first heard him say the word, \"India\", in that marvellous voice of his. It seems incredible that so much could have been put into one small word of five letters. There was love, passion, pride, longing, adoration, tragedy, chivalry, and again love. Whole volumes could not have produced such a feeling in others. It had the magic power of creating love in those who heard it.\'', '2', 0, 60, 80, 'Mumbai,maharashtra,india', '19.076000', '72.877700', 'MyIndia,theIndiaeternal.jpg', NULL, NULL, 0),
(53, 'A complete guide for the NRI', 2, 4, 1, 'By Raghu<br>', '2', 0, 60, 80, 'Mumbai,maharashtra,india', '19.076000', '72.877700', 'AcompleteguidefortheNRI.jpg', NULL, NULL, 0),
(54, 'The monk who sold his ferrari', 3, 4, 1, 'By Robin Sharma<br>The Monk Who Sold His Ferrari is a self-help book by Robin Sharma, a writer and motivational speaker. The book is a business fable derived from Sharma\'s personal experiences after leaving his career as a litigation lawyer at the age of 25', '2', 0, 60, 80, 'Mumbai,maharashtra,india', '19.076000', '72.877700', 'Themonkwhosoldhisferrari.jpg', NULL, NULL, 0),
(55, 'Cocktail Time', 1, 4, 1, 'By P. G. Wodehouse<br>Cocktail Time is a comic novel by P. G. Wodehouse, first published in the United Kingdom on June 20, 1958 by Herbert Jenkins, London and in the United States on July 24, 1958 by Simon & Schuster, Inc., New York.', '2', 0, 60, 80, 'Mumbai,maharashtra,india', '19.076000', '72.877700', 'CocktailTime.jpg', NULL, NULL, 0),
(56, 'The great Indian Dream', 2, 4, 1, 'By Malay Chaudhuri<br>This book analyses not only the basic impediments in India s march to glory but has also made an attempt to identify budgetary resources to end the poverty of the masses.', '2', 0, 60, 80, 'Mumbai,maharashtra,india', '19.076000', '72.877700', 'ThegreatIndianDream.jpg', NULL, NULL, 0),
(57, 'Gulliver\'s Travels', 1, 4, 1, 'By Jonathan Swift<br>Gulliver\'s Travels, or Travels into Several Remote Nations of the World. In Four Parts. By Lemuel Gulliver, First a Surgeon, and then a Captain of Several Ships, is a prose satire by Irish writer', '2', 0, 60, 80, 'Mumbai,maharashtra,india', '19.076000', '72.877700', 'Gulliver_sTravels.jpg', NULL, NULL, 0),
(58, 'Body Language', 3, 4, 1, 'By Allan Pease<br>From the book that\'s sold 4 million copies in 17 languages, internationally renowned body language expert Allan Pease tells you how to \"read the thoughts\" of friends, business colleagues, and partners.', '2', 0, 60, 80, 'Mumbai,maharashtra,india', '19.076000', '72.877700', 'BodyLanguage.jpg', NULL, NULL, 0),
(59, 'Chicken soup for the teenage Soul', 3, 4, 1, 'By Jack Canfield<br>Chicken Soup for the Soul is a publishing, consumer goods and media company based in Cos Cob, CT. It is known for the Chicken Soup for the Soul series of books', '2', 0, 60, 80, 'Mumbai,maharashtra,india', '19.076000', '72.877700', 'ChickensoupfortheteenageSoul.jpg', NULL, NULL, 0),
(60, 'And the mountains Echoed', 1, 4, 1, 'By Khaled Hosseine<br>And the Mountains Echoed is the third novel by Afghan-American author Khaled Hosseini. Published in 2013 by Riverhead Books, it deviates from Hosseini\'s style in his first two works through his choice to avoid focusing on any one character.', '2', 0, 60, 80, 'Mumbai,maharashtra,india', '19.076000', '72.877700', 'AndthemountainsEchoed.jpg', NULL, NULL, 0),
(61, 'The Scam', 34, 4, 1, 'By Debashis basu<br>An attempt to analyze the events of the alleged scandal which took place in the Indian stock market during 1992; also includes the story of Scam 2001.', '2', 0, 80, 100, 'Mumbai,maharashtra,india', '19.076000', '72.877700', 'TheScam.jpg', NULL, NULL, 0),
(62, 'Swami and friends', 1, 4, 1, 'By R k narayan<br>Swami and Friends is the first of a trilogy of novels written by R. K. Narayan, English language novelist from India. The novel, Narayan\'s first, is set in British India in a fictional town called Malgudi.', '2', 0, 60, 80, 'Mumbai,maharashtra,india', '19.076000', '72.877700', 'Swamiandfriends.jpg', NULL, NULL, 0),
(63, 'Connect the dots', 34, 4, 1, 'By Rashmi Bansal<br>Mahima Mehra did it.Ranjiv Ramchandani did it.Kalyan Varma did it. Connect the Dots is the story of 20 enterprising individuals without an MBA, who started their own ventures. They were driven by the desire to prove themselves.', '2', 0, 60, 80, 'Mumbai,maharashtra,india', '19.076000', '72.877700', 'Connectthedots.jpg', NULL, NULL, 0),
(64, 'Management thoughts', 34, 4, 1, 'Thinking Is The Most Important Job In Management Whether It S Management Of Your Self, Your Family, Your Employees Or Your Customers. Thinking Is The Hardest Job To Do And Therefore, We All Hate To Do It!', '2', 0, 60, 80, 'Mumbai,maharashtra,india', '19.076000', '72.877700', 'Managementthoughts.jpg', NULL, NULL, 0),
(65, '21st Century positioning', 34, 4, 1, 'By Jack kinder<br>This all time sales classic, provides 67 sales or motivational or instructional messages in five categories: Attitude Condittioners, Work Habit Strengtheners, Selling Skill Developers, Competency Builders, Life Style Disciplines.', '2', 0, 60, 80, 'Mumbai,maharashtra,india', '19.076000', '72.877700', '21stCenturypositioning.jpg', NULL, NULL, 0),
(66, 'India Unbound', 2, 4, 1, 'India Unbound: From Independence to Global Information Age is a 2000 non-fiction book by Gurcharan Das. It is an account of India\'s economic journey after its Independence in 1947.', '2', 0, 60, 80, 'Mumbai,maharashtra,india', '19.076000', '72.877700', 'IndiaUnbound.jpg', NULL, NULL, 0),
(67, 'Effective communication & public speaking', 3, 4, 1, 'By S.K. Mandal<br>Effective communication is the key to success in life. In this competitive age a lot depends on how a person is able to relate to others. This book is about verbal communication and the art of public speaking.', '2', 0, 60, 80, 'Mumbai,maharashtra,india', '19.076000', '72.877700', 'Effectivecommunication_publicspeaking.jpg', NULL, NULL, 0),
(68, 'Creative Thinking and brainstorming', 3, 4, 1, 'By J. Geoffrey Rawlinson<br>JCO-SIONDescription: Brainstorming is probably the best known of all the techniques available for creative problem solving', '2', 0, 60, 80, 'Mumbai,maharashtra,india', '19.076000', '72.877700', 'CreativeThinkingandbrainstorming.jpg', NULL, NULL, 0),
(69, 'Strategic Marketing', 4, 4, 1, 'By Xavier<br>A Guide for Developing Sustainable Competitive Advantage provides a systematic approach for marketing directors and other managers to formulate strategies that can give their organizations the desired competitive edge, as well as help them to forecast and plan for future challenges.', '2', 0, 60, 80, 'Mumbai,maharashtra,india', '19.076000', '72.877700', 'StrategicMarketing.jpg', NULL, NULL, 0),
(70, 'The presentation secrets of Steve Jobs', 3, 4, 1, 'By Carmine Gallo<br>A \"THINK DIFFERENT\" APPROACH TO INNOVATION--Based on the Seven Guiding Principles of Apple CEO Steve JobsIn his acclaimed bestseller The Presentation Secrets of Steve Jobs author Carmine Gallo laid ou', '2', 0, 60, 80, 'Mumbai,maharashtra,india', '19.076000', '72.877700', 'ThepresentationsecretsofSteveJobs.jpg', NULL, NULL, 0),
(71, 'Gandhian Management', 4, 4, 1, 'By Ram <br>Gandhiji carved a philosophy which he nurtured and upheld throughout his life, only to be known later as Gandhian Philosophy', '2', 0, 60, 80, 'Mumbai,maharashtra,india', '19.076000', '72.877700', 'GandhianManagement.jpg', NULL, NULL, 0),
(72, 'The parable of Pipeline', 34, 4, 1, 'By Burke hedges<br>The Parable of the Pipeline: How Anyone Can Build a Pipeline of Ongoing Residual Income in the New Economy by Burke Hedges', '2', 0, 60, 80, 'Mumbai,maharashtra,india', '19.076000', '72.877700', 'TheparableofPipeline.jpg', NULL, NULL, 0),
(73, 'Eat that Frog', 3, 4, 1, 'By Brian tracy<br>In his trademark high-energy style, acclaimed speaker and best selling author Brian Tracy in his book Eat That Frog cuts to the core of what is vital to effective personal time management: decision, discipline, and determination.', '2', 0, 60, 80, 'Mumbai,maharashtra,india', '19.076000', '72.877700', 'EatthatFrog.jpg', NULL, NULL, 0),
(74, 'How to talk to anyone', 3, 4, 1, 'By Lowndes<br>\"You\'ll not only break the ice, you\'ll melt it away with your new skills.\" -- Larry King \"The lost art of verbal communication may be revitalized by Leil Lowndes.\"', '2', 0, 60, 80, 'Mumbai,maharashtra,india', '19.076000', '72.877700', 'Howtotalktoanyone.jpg', NULL, NULL, 0),
(75, 'Strategic Brand Management for B2B market', 34, 4, 1, 'By Sarin<br>This book aims to uncover the hidden and unexploited power of leveraging from the concept of brand and brand building for B2B marketers. It focuses on the need of B2B marketing from the point of view of Indian markets and economic conditions at home. With extensive discussions on the three most respected corporate brands in India Tata, Larsen & Toubro and Infosys the author demonstrates how these companies have created value through brands and how their branding initiatives are benchmarks in their journey to success.', '2', 0, 60, 80, 'Mumbai,maharashtra,india', '19.076000', '72.877700', 'StrategicBrandManagementforB2Bmarket.jpg', NULL, NULL, 0),
(76, '2 states', 1, 4, 1, 'By Chetan bhagat<br>The Story of My Marriage commonly known as 2 States is a 2009 novel written by Chetan Bhagat. It is the story about a couple coming from two different states in India, who face hardships in convincing their parents to approve of their marriage. Bhagat wrote this novel after quitting his job as an investment banker. This is his fourth book after Five Point Someone, One Night @ the Call Center and The Three Mistakes of My Life.', '2', 0, 30, 50, 'Mumbai,maharashtra,india', '19.076000', '72.877700', '2states.jpg', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `product_pictures`
--

CREATE TABLE `product_pictures` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `file_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_pictures`
--

INSERT INTO `product_pictures` (`id`, `product_id`, `file_name`, `created_at`, `updated_at`) VALUES
(7, 9, '68116c1192041e25cd3e78707bf4e334f81251cd9b0d.jpg', NULL, NULL),
(8, 9, '34946c1192041e25cd3e78707bf4e334f81251cd9b0d.jpg', NULL, NULL),
(9, 10, '873364b15beb8395e27aa362222e25775f662607901f.jpg', NULL, NULL),
(10, 11, '5613495ef30d55fd23f6b36dc938c0bb61152fce7e6b.png', NULL, NULL),
(11, 12, '882033d1e42d81ddd14f2ddea02697fd056ece507338.jpg', NULL, NULL),
(12, 13, '6764a9bc3565095d1fded7d57aacb46a57d261695201.jpg', NULL, NULL),
(14, 15, '2033293d260f33245429a6a0ca2407e896de8ead375c.jpg', NULL, NULL),
(15, 16, '3235cc0a2436a025ae9241972b86763d1cfb1e78987d.jpg', NULL, NULL),
(16, 17, '8626226ddf3efc6a01b5c266b0d42767113ae716d832.png', NULL, NULL),
(17, 18, '16444c8afc90d24854f2ab050ca241b2e27f5784891c.jpg', NULL, NULL),
(18, 19, '95063421292f908af7ae3fce4956dbb522ab00c3c8ed.jpg', NULL, NULL),
(19, 19, '33933421292f908af7ae3fce4956dbb522ab00c3c8ed.jpg', NULL, NULL),
(20, 20, '5921e16a7e5b2f013c18d88888d28b70c8feca0dbaa5.jpg', NULL, NULL),
(21, 21, '6853c41e53cbb3043907e8e99e2bcba8ffb52c1aac1d.jpg', NULL, NULL),
(22, 21, '7413c41e53cbb3043907e8e99e2bcba8ffb52c1aac1d.jpg', NULL, NULL),
(23, 22, 'YouCanwin.jpg', NULL, NULL),
(24, 22, 'YouCanwin1.jpg', NULL, NULL),
(25, 23, 'Hundredyearsofsoltitude.jpg', NULL, NULL),
(26, 24, 'ThemagicofthinkingBig.jpg', NULL, NULL),
(27, 24, 'ThemagicofthinkingBig1.jpg', NULL, NULL),
(28, 25, 'Imovedyourcheese.jpg', NULL, NULL),
(29, 26, 'TheJapanesewife.jpg', NULL, NULL),
(30, 27, 'Strengthsfinder20.jpg', NULL, NULL),
(31, 28, 'HowIthaughtmygrandmothertoreadandotherstories.jpg', NULL, NULL),
(32, 28, 'HowIthaughtmygrandmothertoreadandotherstories1.jpg', NULL, NULL),
(33, 29, 'NapoleanBonapart.jpg', NULL, NULL),
(34, 30, 'Whattheydon_tteachyouatHarvardbusinessschool.jpg', NULL, NULL),
(35, 31, 'ThenewStrategicSelling.jpg', NULL, NULL),
(36, 31, 'ThenewStrategicSelling1.jpg', NULL, NULL),
(37, 32, 'TheChoice.jpg', NULL, NULL),
(38, 33, 'Quotemeifyoucan.jpg', NULL, NULL),
(39, 33, 'Quotemeifyoucan1.jpg', NULL, NULL),
(40, 34, 'WhatyoungIndiawants.jpg', NULL, NULL),
(41, 34, 'WhatyoungIndiawants1.jpg', NULL, NULL),
(42, 35, 'Thestoryofmylife.jpg', NULL, NULL),
(43, 36, 'ItHappenedinIndia.jpg', NULL, NULL),
(44, 37, 'BlueOceanStrategy.jpg', NULL, NULL),
(45, 37, 'BlueOceanStrategy1.jpg', NULL, NULL),
(46, 38, 'Thegodofsmallthings.jpg', NULL, NULL),
(47, 39, 'Strategiesforthefuture.jpg', NULL, NULL),
(48, 39, 'Strategiesforthefuture1.jpg', NULL, NULL),
(49, 40, '2States.jpg', NULL, NULL),
(50, 40, '2States1.jpg', NULL, NULL),
(51, 41, 'Selectedghoststories.jpg', NULL, NULL),
(52, 42, 'GreatworksofFyodorDostoevsky.jpg', NULL, NULL),
(53, 43, 'Halfgirlfriend.jpg', NULL, NULL),
(54, 43, 'Halfgirlfriend1.jpg', NULL, NULL),
(55, 44, 'HarryPotterandthegobletoffire.jpg', NULL, NULL),
(56, 44, 'HarryPotterandthegobletoffire1.jpg', NULL, NULL),
(57, 45, 'Shantaram.jpg', NULL, NULL),
(58, 45, 'Shantaram1.jpg', NULL, NULL),
(59, 46, 'HarryPotterandtheDeathlyHollows.jpg', NULL, NULL),
(60, 46, 'HarryPotterandtheDeathlyHollows1.jpg', NULL, NULL),
(61, 47, 'Godisagamer.jpg', NULL, NULL),
(62, 48, 'Chainofcustody.jpg', NULL, NULL),
(63, 49, 'SimplifiedFinancialManagement.jpg', NULL, NULL),
(64, 50, 'FinancialAnalysis.jpg', NULL, NULL),
(65, 51, 'Anywherebuthome.jpg', NULL, NULL),
(66, 52, 'MyIndia,theIndiaeternal.jpg', NULL, NULL),
(67, 53, 'AcompleteguidefortheNRI.jpg', NULL, NULL),
(68, 54, 'Themonkwhosoldhisferrari.jpg', NULL, NULL),
(69, 54, 'Themonkwhosoldhisferrari1.jpg', NULL, NULL),
(70, 55, 'CocktailTime.jpg', NULL, NULL),
(71, 55, 'CocktailTime1.jpg', NULL, NULL),
(72, 56, 'ThegreatIndianDream.jpg', NULL, NULL),
(73, 57, 'Gulliver_sTravels.jpg', NULL, NULL),
(74, 57, 'Gulliver_sTravels1.jpg', NULL, NULL),
(75, 58, 'BodyLanguage.jpg', NULL, NULL),
(76, 59, 'ChickensoupfortheteenageSoul.jpg', NULL, NULL),
(77, 59, 'ChickensoupfortheteenageSoul1.jpg', NULL, NULL),
(78, 60, 'AndthemountainsEchoed.jpg', NULL, NULL),
(79, 61, 'TheScam.jpg', NULL, NULL),
(80, 62, 'Swamiandfriends.jpg', NULL, NULL),
(81, 62, 'Swamiandfriends1.jpg', NULL, NULL),
(82, 63, 'Connectthedots.jpg', NULL, NULL),
(83, 63, 'Connectthedots1.jpg', NULL, NULL),
(84, 64, 'Managementthoughts.jpg', NULL, NULL),
(85, 65, '21stCenturypositioning.jpg', NULL, NULL),
(86, 66, 'IndiaUnbound.jpg', NULL, NULL),
(87, 67, 'Effectivecommunication_publicspeaking.jpg', NULL, NULL),
(88, 68, 'CreativeThinkingandbrainstorming.jpg', NULL, NULL),
(89, 69, 'StrategicMarketing.jpg', NULL, NULL),
(90, 69, 'StrategicMarketing1.jpg', NULL, NULL),
(91, 70, 'ThepresentationsecretsofSteveJobs.jpg', NULL, NULL),
(92, 71, 'GandhianManagement.jpg', NULL, NULL),
(93, 72, 'TheparableofPipeline.jpg', NULL, NULL),
(94, 73, 'EatthatFrog.jpg', NULL, NULL),
(95, 74, 'Howtotalktoanyone.jpg', NULL, NULL),
(96, 75, 'StrategicBrandManagementforB2Bmarket.jpg', NULL, NULL),
(97, 75, 'StrategicBrandManagementforB2Bmarket1.jpg', NULL, NULL),
(98, 76, '2states.jpg', NULL, NULL),
(99, 76, '2states1.jpg', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `subcategories`
--

CREATE TABLE `subcategories` (
  `id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subcategories`
--

INSERT INTO `subcategories` (`id`, `category_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 1, 'fiction', NULL, NULL),
(2, 1, 'non-fiction', NULL, NULL),
(3, 1, 'self-help', NULL, NULL),
(4, 1, 'text_books', NULL, NULL),
(5, 2, 'traditional', NULL, NULL),
(6, 2, 'party_wear', NULL, NULL),
(7, 3, 'cars', NULL, NULL),
(8, 3, 'bikes', NULL, NULL),
(9, 4, 'single_rooms', NULL, NULL),
(10, 4, 'entire_houses', NULL, NULL),
(11, 5, 'luggage_bags', NULL, NULL),
(12, 5, 'trekking_bags', NULL, NULL),
(13, 6, 'chairs', NULL, NULL),
(14, 6, 'tables', NULL, NULL),
(15, 6, 'beds', NULL, NULL),
(16, 6, 'wardrobes', NULL, NULL),
(17, 6, 'sofas', NULL, NULL),
(18, 6, 'bean_bags', NULL, NULL),
(20, 7, 'laptops', NULL, NULL),
(21, 7, 'projectors', NULL, NULL),
(22, 7, 'speakers', NULL, NULL),
(23, 7, 'television_sets', NULL, NULL),
(24, 7, 'personal_computers', NULL, NULL),
(25, 7, 'coffee_machines', NULL, NULL),
(26, 8, 'drill_machines', NULL, NULL),
(28, 8, 'tool_kits', NULL, NULL),
(29, 8, 'ladders', NULL, NULL),
(30, 9, 'playstation_2', NULL, NULL),
(31, 9, 'playstation_3', NULL, NULL),
(32, 9, 'playstation_4', NULL, NULL),
(33, 9, 'xbox', NULL, NULL),
(34, 1, 'business', NULL, NULL),
(35, 1, 'autobiographies', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(10) UNSIGNED NOT NULL,
  `renter_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `status` enum('1','2','3','4','5') COLLATE utf8mb4_unicode_ci NOT NULL,
  `seen` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `renter_id`, `product_id`, `from_date`, `to_date`, `status`, `seen`, `created_at`, `updated_at`) VALUES
(14, 4, 12, '2018-03-26', '2018-03-26', '4', 1, NULL, NULL),
(15, 6, 10, '2018-03-27', '2018-03-27', '5', 1, NULL, NULL),
(16, 3, 11, '2018-03-27', '2018-03-27', '5', 0, NULL, NULL),
(17, 4, 13, '2018-03-26', '2018-03-27', '2', 1, NULL, NULL),
(18, 3, 9, '2018-03-27', '2018-03-29', '5', 1, NULL, NULL),
(19, 4, 11, '2018-03-28', '2018-03-29', '4', 1, NULL, NULL),
(20, 4, 11, '2018-03-30', '2018-03-31', '2', 1, NULL, NULL),
(21, 3, 11, '2018-03-29', '2018-04-03', '2', 1, NULL, NULL),
(22, 4, 13, '2018-03-29', '2018-03-30', '2', 1, NULL, NULL),
(23, 4, 13, '2018-03-29', '2018-03-31', '2', 1, NULL, NULL),
(24, 10, 21, '2018-04-07', '2018-05-07', '5', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact` bigint(20) UNSIGNED NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `aadhaar_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `reviews` int(11) NOT NULL DEFAULT '0',
  `total_rating` int(11) NOT NULL DEFAULT '0',
  `address` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lat` decimal(9,6) NOT NULL,
  `lng` decimal(9,6) NOT NULL,
  `verified` tinyint(3) UNSIGNED NOT NULL,
  `profile_picture` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'avatar.png',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `privileges` int(10) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `contact`, `password`, `aadhaar_id`, `reviews`, `total_rating`, `address`, `lat`, `lng`, `verified`, `profile_picture`, `remember_token`, `created_at`, `updated_at`, `privileges`) VALUES
(3, 'Pankaj', 'Ajwani', 'pankaj.ajwani0409@gmail.com', 8805623480, '$2y$10$HX9PqQFCdCHnP6/g/kIP8erEYanqLoTC0Q1VrfG.GKnNVJ6gUKMyO', '0', 0, 0, 'Kailash Colony, Ulhasnagar, Maharashtra, India', '19.199734', '73.169287', 1, 'avatar.png', 'nhjQXbMGUYb41qsJnE0b6VIvDDYf573bcFZKc1VXOwCHxY7atPt6rILWYarv', '2018-03-26 00:43:18', '2018-05-06 09:22:46', 1),
(4, 'Bhushan', 'Punjabi', 'punjabibhushan@gmail.com', 9765584429, '$2y$10$73O7DHLRuk.j7kvbqTgYQ.K7va6ONn.t119w2O4iMuMVFwUIYa8lC', '0', 0, 0, 'Ulhasnagar, Maharashtra, India', '19.221512', '73.164463', 1, 'avatar.png', 'OHeobQv4qUYHgcGNMtsXEp38cwuPzPl5S2cQzGAHCWeP58VURMOVYtGIdPqh', '2018-03-26 01:04:42', '2018-03-26 01:05:20', 0),
(5, 'Bharti', 'Narang', 'narangbharti1525@gmail.com', 9765707065, '$2y$10$pb/ee9osd6IFZwtYSw4oIeMp6Q95lgWs1jOO62iuRTAkh2nWibnJG', '0', 0, 0, 'Ulhasnagar, Maharashtra, India', '19.221512', '73.164463', 1, 'avatar.png', 'si8fOTDtpkJH259HCXGloWyi3OZSOpBerlyivzlqZEtJgq1ZRRMHrXV3E760', '2018-03-26 02:18:46', '2018-05-06 04:08:00', 0),
(6, 'Mohit', 'Punjabi', 'mohitpunjabi.work@gmail.com', 9324023400, '$2y$10$icVe1tfANHKVJKA/7LVj7OpPkF5NoRhm0WXVMEi4uifxt10joZoLG', '0', 0, 0, 'Mumbai, Maharashtra, India', '19.075984', '72.877656', 1, 'avatar.png', NULL, '2018-03-26 02:23:26', '2018-03-26 02:25:58', 0),
(7, 'Bharti', 'Udasi', 'bhartiudasi06@gmail.com', 9890848683, '$2y$10$UlEz.hnVlQK3odBipK/Q8eUGWoSa8AxREUrzXflQrFsYhVnAXFWpC', '0', 0, 0, 'Mumbai, Maharashtra, India', '19.075984', '72.877656', 1, 'avatar.png', NULL, '2018-03-27 19:39:33', '2018-03-27 19:40:43', 0),
(8, 'Dilip', 'Punjabi', 'pujabid14@gmail.com', 9920591117, '$2y$10$DPUq0AgJQgQ9Zy/5pKHToOhlga0CYvEoNIxnin11m34bsKxNu5wBq', '0', 0, 0, 'Mumbai, Maharashtra, India', '19.075984', '72.877656', 0, 'avatar.png', NULL, '2018-04-03 19:33:52', '2018-04-03 19:33:52', 0),
(9, 'manav', 'punjabi', 'manavpunjabi04@gmail.com', 8999866431, '$2y$10$SrHoug8OC1JwLh/X58nIyeE21sYJYcQ7eFizi6/Ie/S4FFmgqj2Wu', '0', 0, 0, 'Ulhasnagar, Maharashtra, India', '19.221512', '73.164463', 1, 'avatar.png', 'UEnydAVTXwzkD4EnLxrLEuWXtwZt9MIEeBGKd6TugluGzWdswQFwUbN7evtL', '2018-04-04 00:05:40', '2018-04-04 00:06:22', 0),
(10, 'serene', 'arora', 'serene@gmail.com', 9323817755, '$2y$10$/uZQ8I74uDzeyTyKiEX03euz2MNL7ww6zanvRb6TeVrIpQusNSj7a', '0', 0, 0, 'Ulhasnagar, Maharashtra, India', '19.221512', '73.164463', 1, 'avatar.png', '8arRAHXfMclEQmIK4Bjf6xdKofxvaVo0b64z54w5ETdNOIKigweQ7oSCth7A', '2018-04-06 19:13:32', '2018-04-06 19:14:35', 0),
(12, 'Ramakrishna Karthik', 'Narayan', 'ramkarthiknarayan@gmail.com', 9986796526, '$2y$10$6fdQlB5S5/RFgmAUm9Hk9ewdNwtxggwepcOUMtNOm1OEyzNb1EEXG', '0', 0, 0, '3rd Main, Tata Silk Farm, Jayanagar, Bengaluru, Karnataka, India', '12.935132', '77.573823', 1, 'avatar.png', NULL, '2018-04-13 18:05:54', '2018-04-13 18:06:40', 0),
(13, 'Miza', 'A', 'mizacannotstop@gmail.com', 9889898989, '$2y$10$qYzJ1/YYeRz6OKNyX7fJN.EcByaE6XgvAuPGy1Dbo0WRSwVFnyUYy', '0', 0, 0, 'Mumbai, Maharashtra, India', '19.075984', '72.877656', 0, 'avatar.png', 'Wc3WUEwM3rUjG32X4zeffOodTwVO3VqRwCpkFOkQgEt7xqALhftKmpKQcIZW', '2018-04-13 19:39:05', '2018-04-13 19:39:05', 0),
(14, 'Enz', 'Sh', 'tt@gmail.com', 9972800244, '$2y$10$wT/c0By4AMmowMhuDxgR5eFgivfsZiq5eZ0pbuawZ5kwNUdPg.Nny', '0', 0, 0, 'Mumbai, Maharashtra, India', '19.075984', '72.877656', 0, 'avatar.png', NULL, '2018-04-13 22:14:02', '2018-04-13 22:14:02', 0),
(15, 'Kash', 'Shah', 'kashmiraiyer@gmail.com', 9987635545, '$2y$10$hA5LMTlj5tvG0IIKh9hU9uFQHXj7evsUV5EnzS.6IjsG5uhzVDvi6', '0', 0, 0, 'Powai, Mumbai, Maharashtra, India', '19.119677', '72.905081', 1, 'avatar.png', NULL, '2018-04-17 12:53:41', '2018-04-17 12:56:32', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notes_user_id_foreign` (`user_id`),
  ADD KEY `notes_admin_id_foreign` (`admin_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_subcategory_id_foreign` (`subcategory_id`),
  ADD KEY `products_lender_id_foreign` (`lender_id`);

--
-- Indexes for table `product_pictures`
--
ALTER TABLE `product_pictures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_pictures_product_id_foreign` (`product_id`);

--
-- Indexes for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subcategories_category_id_foreign` (`category_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_renter_id_foreign` (`renter_id`),
  ADD KEY `transactions_product_id_foreign` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_contact_unique` (`contact`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `product_pictures`
--
ALTER TABLE `product_pictures`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT for table `subcategories`
--
ALTER TABLE `subcategories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `notes_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `notes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_lender_id_foreign` FOREIGN KEY (`lender_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `products_subcategory_id_foreign` FOREIGN KEY (`subcategory_id`) REFERENCES `subcategories` (`id`);

--
-- Constraints for table `product_pictures`
--
ALTER TABLE `product_pictures`
  ADD CONSTRAINT `product_pictures_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD CONSTRAINT `subcategories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `transactions_renter_id_foreign` FOREIGN KEY (`renter_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
