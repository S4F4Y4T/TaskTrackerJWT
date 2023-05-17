-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 17, 2023 at 10:17 AM
-- Server version: 8.0.33-0ubuntu0.20.04.2
-- PHP Version: 7.4.3-4ubuntu2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `task`
--

-- --------------------------------------------------------

--
-- Table structure for table `refresh_token`
--

CREATE TABLE `refresh_token` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `token` text NOT NULL,
  `exp_time` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `refresh_token`
--

INSERT INTO `refresh_token` (`id`, `user_id`, `token`, `exp_time`) VALUES
(6, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1aWQiOjEsImV4cCI6MTY4NDU3NzcxM30.N2NiYjdlZjM2ODE4ZjBjZjhjYTQ3OTY2MzdiYTE3OTllOWI5M2ZmY2RlNTliZGQ2YmYyNjYwNzMyMjU2MTAzNQ', '1684577713');

-- --------------------------------------------------------

--
-- Table structure for table `task`
--

CREATE TABLE `task` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `title` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `description` text NOT NULL,
  `status` varchar(24) NOT NULL,
  `dateTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `task`
--

INSERT INTO `task` (`id`, `user_id`, `title`, `description`, `status`, `dateTime`) VALUES
(1, 1, 'Task 1', '', 'Pending', '2023-05-13 16:20:19'),
(2, 2, 'Task 1', '', 'Pending', '2023-05-13 16:21:44'),
(5, 1, '', '', '', '2023-05-14 17:35:01'),
(6, 1, '', '', '', '2023-05-14 17:35:57'),
(7, 1, 'new test 1', '', 'Pending', '2023-05-15 13:47:25'),
(8, 3, 'task 1', '', 'Pending', '2023-05-15 14:03:24'),
(10, 3, 'task 3', '', 'Pending', '2023-05-15 14:03:33');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `name` varchar(256) NOT NULL,
  `username` varchar(256) NOT NULL,
  `password` varchar(1024) NOT NULL,
  `status` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `username`, `password`, `status`) VALUES
(1, 'Safayat Mahmud', 'S4F4Y4T', '$2y$10$NkP4e6afItDrdyt2/9kue.K/g/1.ntB7m4yOVK6oF0dFgZW6FIRZ6', 1),
(2, 'Rohsin Al Razu', 'webchoader', '$2y$10$QX2hIqnQyvQYqZKfi9Ky.OMJaMcVirh44pdRQgMNAmeWK/i95tCb6', 1),
(3, 'Prt Vai', 'prt', '$2y$10$AI3G/hPJdMUEXLILz5Ip/.1PrtMTx0TAb0VSVTi1mnFMvl90ZS3ym', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_login`
--

CREATE TABLE `user_login` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `token` varchar(1024) NOT NULL,
  `dateTime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_login`
--

INSERT INTO `user_login` (`id`, `user_id`, `token`, `dateTime`) VALUES
(1, 1, '36dOOjm7cwd9xLOhSWpKaGjnVwdqEeqi5j8SQbX7QqbDAif6Tim3uPDgKltZLF9GnW3Yuzo1M44la04kHUZYBWm12tFrvzKcV1fyhpZMs5BEYH0ygg6BLRUAcxXGvy2w', '2023-05-12 19:33:51'),
(3, 2, 'JF1NeU6N5AoTVefI42F1Y7TnESBGEzvWZgn8sqV5GC8EDKNrsq2ThKrPf6zH9Y9mdtZl9oSwuU4xGOyZlLc2KxVaRM87kACcipX1obL5lnDvLdt3HJYhg3UiQfuwyJwI', '2023-05-13 10:15:24'),
(4, 1, '56G39M8EVgrwWB1jIhyDFYyROjgcpcs69HRhOyS4zQicR4qfGaxLfI2tGeup6PFhiddtVw7Z7omSC2j0KdT3Tlv7LoMzAobnmV5DNqUEiXqrbNEXT03AxWu2r5fzZHbQ', '2023-05-14 10:49:09'),
(5, 1, 'roBb8vpaQUcJP2Q6hegHTdT43XLWX9zeSDBzCUFxakPw7MoOSfIfSnLJWt8X1qxKVCtVEAOFYjA9l7EZCqHgjsrh5Bjs0wovYYbs0ydMVcEkLpKpeu161vRcmJFztD7G', '2023-05-14 10:52:04'),
(6, 1, 'qAOGBYaTNz0O6j36nMJhVP7yhDULDD36tkfk1q4jb2zp7AxK3U9cv9Ful8QVOcwBafn2S2ZPdzow8YoQeigS5t0mBsbt74gvWur9HyxsTKwTiJueJExedpSnWpRjQ154', '2023-05-14 11:30:07'),
(7, 1, '2tHeOLgu4NLKzCQ3q98zAAEIv8h4iVmvXagcYKbEzwoaU6Iww5sJlhTRN3CbNWFhZ0rdsHy1uE17mGrqBpq0ZWBTPQx8afevXki9pDsLWx7gD07b6tdjdTRXGcMQF5DY', '2023-05-14 11:30:54'),
(8, 1, 'azCfNtg8T3NCLOiXLCpOP07XdduoRm85tvSXqAE1JwDZkgarUzPV9fiyLGF2w6UYVA10sMe1eI9TlQBQz632JSBW8Jo2b4HGZVHhaNKsMrwcInudvBq3ycoIMmguROPH', '2023-05-14 11:59:56'),
(9, 1, 'pYkuHSlQFaHE65cf4mh8tPGIAzFWmgTeiUlSiKZYd94Uu6tFOwvDXNJKg20B3C0saZsAvOiMlXSkYqCmj1obudq7gb7TXnoe9NWLdh72O3QBvq8zVHfPLTwrNyCVRD5j', '2023-05-14 12:00:18'),
(10, 1, 'yqJVokKED8COua1aRxfqktRhq1izl08hQEMn3AffLDlQHZ5vEkTrLI7y4KdmeGGjFegXv6gLmB52SUysOcrP7dTxVtjBB4W1SOHsjcD9GwteJvWYpQZ2w6TF5bVxiNXg', '2023-05-14 12:03:04'),
(11, 1, '21wneW57qH5PSbGZTMtRhK2Pal9xrf4U3oXasbix4kjprpN8W9VlAoG0r8eE1CIJcpjySJKAbF79wvuL7nqDx3RsZqBwtHBdQz3nkBaYUCGAKl0664fFzidDIOLWYjyL', '2023-05-14 12:16:07'),
(12, 3, 'CQ2JeBHgG95eyiar745UiFoaKd6hk9fXSmjTbZdBbfx7phoc5CHIGzuJIADKLO39YFFbsizP2Krcs4MWnqwzEs0A0mQEfJu1hCxvUDpl8W0xq7SPBYtNDZgSjnMnERZL', '2023-05-15 07:53:21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `refresh_token`
--
ALTER TABLE `refresh_token`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_login`
--
ALTER TABLE `user_login`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `refresh_token`
--
ALTER TABLE `refresh_token`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `task`
--
ALTER TABLE `task`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_login`
--
ALTER TABLE `user_login`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
