-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 06 Sty 2020, 15:51
-- Wersja serwera: 10.1.38-MariaDB
-- Wersja PHP: 7.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `tester`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `question` text COLLATE utf8_polish_ci NOT NULL,
  `answer` text COLLATE utf8_polish_ci NOT NULL,
  `good` int(11) NOT NULL,
  `allAnswers` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `questions`
--

INSERT INTO `questions` (`id`, `question`, `answer`, `good`, `allAnswers`) VALUES
(6, 'Jak nazywa się miejscowość w której urodził się Jezus?', 'Betlejem', 3, 12),
(9, 'Jak nazywa się miejscowość słynnej bitwy Polaków z Krzyżakami?', 'Grunwald', 6, 14),
(10, 'Jak nazywała pierwsza stolica polski?', 'Gniezno', 4, 11),
(11, 'Jak nazywa się miejscowość z najstarszą kopalnią soli w Polsce?', 'Wieliczka', 2, 9),
(12, 'Jak nazywa się miasto wybudowane na początku lat 20. XX wieku?', 'Gdynia', 4, 13),
(13, 'Jak nazywa się miasto na Ukrainie utracone po IIWŚ?', 'Lwów', 2, 8),
(14, 'Jak nazywa się miasto zyskane po IIWŚ?', 'Szczecin', 0, 9),
(15, 'Jak nazywa się stolica Polski?', 'Warszawa', 4, 12),
(16, 'Jak nazywa się miejsce masowego mordu Polskich oficerów podczas IIWŚ?', 'Katyń', 3, 9),
(17, 'Jak nazywa się miejscowość ostatniej bitwy Napoleona?', 'Waterloo', 4, 12),
(18, 'Jak nazywa się miasto będące Europejską stolicą mody?', 'Mediolan', 3, 10),
(19, 'Jak nazywa się stolica Katalonii?', 'Barcelona', 4, 10),
(20, 'Jak nazywał się kiedyś Kaliningrad?', 'Królewiec', 3, 11),
(21, 'Jak nazywał się Wołgograd?', 'Stalingrad', 1, 11),
(22, 'Jak nazywała się stolica Imperium Rosyjskiego w latach 1712-1914', 'Petersburg', 1, 7),
(23, 'Jak nazywa się miasto będące miejscem protestów w 2019 roku?', 'Hongkong', 4, 12),
(24, 'Jak nazywa się stolica Turcji?', 'Ankara', 2, 7),
(25, 'Jak nazywa się miejscowość gdzie znajdował się dworek Jana Kochanowskiego?', 'Czarnolas', 2, 11),
(26, 'Jak nazywa się miasto w którym urodził się Napoleon?', 'Ajaccio', 3, 13),
(27, 'Jak potocznie nazywa się aglomeracja Gdańska, Gdynii i Sopotu?', 'Trójmiasto', 2, 12),
(28, 'Jak nazywa się stolica Egiptu?', 'Kair', 6, 14),
(29, 'Jak nazywa się dawno Polskie miasto znajdujące się obecnie na Białorusi?', 'Grodno', 1, 6),
(30, 'Jak nazywa się stolica Estonii?', 'Tallin', 3, 9),
(31, 'Jak nazywa się stolica Australii?', 'Canberra', 2, 2),
(32, 'Jak nazywa się miasto z najsłynniejszą operą w Australii?', 'Sydney', 2, 4),
(33, 'Jak nazywa się drugie co do wielkości miasto w Polsce?', 'Kraków', 1, 1),
(34, 'Jak nazywa się miasto w USA z wieżowcami głównie w stylu Art Deco?', 'Nowy Jork', 3, 6),
(35, 'Jak nazywa się miasto będące stolicą wysokobudżetowego kina?', 'Los Angeles', 4, 5);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` varchar(60) COLLATE utf8_polish_ci NOT NULL,
  `pass` varchar(60) COLLATE utf8_polish_ci NOT NULL,
  `isAdmin` tinyint(4) NOT NULL,
  `questions` int(11) NOT NULL,
  `goodAnswers` int(11) NOT NULL,
  `email` text COLLATE utf8_polish_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`id`, `pass`, `isAdmin`, `questions`, `goodAnswers`, `email`) VALUES
('aaaaaa', '$2y$10$/tkfm4hq3WRHQbquFDvQE.DXrtoyWDIzAthrs.OwUIximYf9lxkNy', 0, 50, 37, 'test@test.pl'),
('admin', '$2y$10$0djIWzSmKHolCor1MtuZQuT/Jf2/XEEymfjA4ONBE8BMC7C8Y4v76', 1, 50, 30, 'aksman.bartlomiej@gmail.com'),
('aksmano', '$2y$10$G0M3zkH1ZtJj7Gw4az2Gre.TXO5qz7lpWB.x7vbp/HitjQtneIUIC', 0, 53, 35, 'supabar@wp.pl'),
('bbbbbb', '$2y$10$mC0/iU6kzUOdLl5dBHRSk.E8Bk3xzfqn8LL9Zi3lXx6acPT7leHWu', 0, 70, 58, 'test@test.pl'),
('cccccc', '$2y$10$cENJQCQQZLiHnWHe9PIm7euMunODBBO8DKaPO68pVar3GroK0K2v2', 0, 60, 23, 'test@test.pl'),
('dddddd', '$2y$10$jlpYn/qaDaxM/A7/inCaau.63oMN/WFhaO4FWaY.o38X0..Eku41i', 0, 150, 132, 'test@test.pl'),
('eeeeee', '$2y$10$WwESITTpRb7J6LhdT9MuiumacCh7N.N/zoA6SaTn6US3AHMU62gd2', 0, 100, 97, 'test@test.pl'),
('ffffff', '$2y$10$MRV7ccL/vbn50a6/rE2TF.POtP8jjADkinqT7zZ08AKjE9n3W/2wy', 0, 20, 3, 'test@test.pl'),
('gggggg', '$2y$10$UpbfVbQKXxYt4LCsBY7zoOpkWdAZuFoQhHU80n7qKuT8MPIpJo.MG', 0, 10, 4, 'test@test.pl'),
('hhhhhh', '$2y$10$frsC0cCuFQY.60jGtXPR0u3gni43mI59rbRQJjvbSJdY2hdTsOmLa', 0, 60, 51, 'test@test.pl'),
('iiiiii', '$2y$10$Lv7BCCq9.SZqHpNsbztUo.xn6jxdl8KHMjXGUOpUy/K1bWwK6LJJu', 0, 20, 11, 'test@test.pl'),
('jjjjjj', '$2y$10$ReXbED9c9F7XT0ERlx5Sie1GRWb2Q1sqvBKashQ28wh9PP1mjEpMa', 0, 80, 51, 'test@test.pl');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
