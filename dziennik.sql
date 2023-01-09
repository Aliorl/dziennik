-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 12 Maj 2022, 20:44
-- Wersja serwera: 10.4.16-MariaDB
-- Wersja PHP: 7.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `dziennik`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `dostep`
--

CREATE TABLE `dostep` (
  `id_dostepu` int(11) NOT NULL,
  `id_uzytkownika` int(11) NOT NULL,
  `id_modulu` int(11) NOT NULL,
  `id_uprawnien` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `name` mediumtext COLLATE utf8_polish_ci NOT NULL,
  `event` longtext COLLATE utf8_polish_ci NOT NULL,
  `type` enum('regular','todo','appointment') COLLATE utf8_polish_ci NOT NULL,
  `start_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `end_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `events`
--

INSERT INTO `events` (`id`, `name`, `event`, `type`, `start_date`, `end_date`) VALUES
(1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'Vestibulum eu tortor nisi. Mauris venenatis pretium condimentum. Curabitur sit amet mi in lacus varius sagittis ut vel nunc. Nulla eget arcu leo. Curabitur ut purus ut mauris facilisis mattis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Pellentesque vulputate hendrerit ipsum, quis cursus eros sodales vitae. Curabitur nisi nisl, faucibus vehicula suscipit vel, rhoncus a elit. Duis imperdiet faucibus aliquam. Aenean ullamcorper luctus arcu vel dignissim. Nam vehicula vehicula malesuada. ', 'regular', '2013-04-08 16:44:09', '2013-04-12 11:23:32'),
(2, 'werty fsdfs ', 'sdf sdaf dsaf sadf sdffgdfgfdg34rgf sfwef dsxfEFRFDVSDsfg dfsgsdfb dfgdf vv dfvb dfvv ev dfvsdf dsf', 'todo', '2013-03-31 22:22:32', '2013-04-10 14:23:43'),
(3, 'Aenean viverra, metus ac lobortis lacinia, ligu', 'Cras semper bibendum sapien eu suscipit. In erat purus, congue et molestie id, rutrum sit amet purus. Aenean tempor eleifend aliquet. Ut cursus neque ut sapien lobortis et malesuada justo adipiscing. Pellentesque habitant morbi tristique senectus et netus et m', 'appointment', '2013-04-04 22:21:58', '2013-04-16 22:00:00'),
(4, 'Proin vitae est mauris, vitae euismod lacus', 'Vestibulum eu tortor nisi. Mauris venenatis pretium condimentum. Curabitur sit amet mi in lacus varius sagittis ut vel nunc. Nulla eget arcu leo. Curabitur ut purus ut mauris facilisis mattis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Pellentesque vulputate hendrerit ipsum, quis cursus eros sodales vitae. Curabitur nisi nisl, faucibus vehicula suscipit vel, rhoncus a elit. Duis imperdiet faucibus aliquam. Aenean ullamcorper luctus arcu vel dignissim. Nam vehicula vehicula malesuada. ', 'todo', '2013-04-07 22:24:32', '2013-04-08 00:00:00'),
(5, 'Donec faucibus mattis sem et dignissim.', 'Proin vitae est mauris, vitae euismod lacus. Vivamus ullamcorper mauris dapibus quam viverra sed gravida urna ornare. Nullam porta, purus ac pellentesque interdum, diam turpis mattis nibh, ac placerat est felis non turpis. Nunc mi augue, mattis vel auctor non, convallis quis velit. Phasellus venenatis, tellus sit amet adipiscing varius, magna libero scelerisque mauris, vel consectetur mauris mauris vitae lectus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Praesent eu purus enim, at elementum lectus. Nam', 'regular', '2013-04-07 22:25:36', '2013-04-08 22:00:00');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `grupy`
--

CREATE TABLE `grupy` (
  `id_grupy` int(11) NOT NULL,
  `nazwa_grupy` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `grupy`
--

INSERT INTO `grupy` (`id_grupy`, `nazwa_grupy`) VALUES
(1, '1'),
(2, '2'),
(3, '3');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `kierunki`
--

CREATE TABLE `kierunki` (
  `id_kierunku` int(11) NOT NULL,
  `nazwa_kierunku` varchar(105) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `kierunki`
--

INSERT INTO `kierunki` (`id_kierunku`, `nazwa_kierunku`) VALUES
(1, 'Administracja'),
(2, 'Informatyka'),
(3, 'Logistyka');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `modul`
--

CREATE TABLE `modul` (
  `id_modulu` int(11) NOT NULL,
  `opis_modulu` enum('studenta','prowadzacego','','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `nieobecnosci`
--

CREATE TABLE `nieobecnosci` (
  `id_nieobecnosci` int(11) NOT NULL,
  `id_studenta` int(11) NOT NULL,
  `id_zajec` int(11) NOT NULL,
  `data_nieobecnosci` date NOT NULL,
  `dzien_tygodnia` enum('poniedziałek','wtorek','środa','czwartek','') NOT NULL,
  `godzina_od` time NOT NULL,
  `godzina_do` time NOT NULL,
  `id_prowadzacego` int(11) NOT NULL,
  `czy_nieobecny` char(1) NOT NULL,
  `typ_nieobecnosci` enum('spóźnienie','usprawiedliwiona','nieusprawiedliwiona','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `oceny`
--

CREATE TABLE `oceny` (
  `id_oceny` int(11) NOT NULL,
  `id_studenta` int(11) NOT NULL,
  `id_prowadzacego` int(11) NOT NULL,
  `id_stopnia` int(11) NOT NULL,
  `id_rodzaju_ocen` int(11) NOT NULL,
  `id_zajec` int(11) NOT NULL,
  `id_semestru` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `oceny`
--

INSERT INTO `oceny` (`id_oceny`, `id_studenta`, `id_prowadzacego`, `id_stopnia`, `id_rodzaju_ocen`, `id_zajec`, `id_semestru`) VALUES
(35, 1017, 1107, 4, 1, 205, 1),
(44, 1000, 1101, 2, 1, 205, 1),
(84, 1002, 1104, 5, 1, 205, 1),
(91, 1000, 1107, 3, 1, 312, 1),
(94, 1000, 1107, 2, 1, 211, 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `plan_zajec`
--

CREATE TABLE `plan_zajec` (
  `id_planu_zajec` int(11) NOT NULL,
  `id_grupy` int(11) NOT NULL,
  `id_prowadzacego` int(11) NOT NULL,
  `id_zajec` int(11) NOT NULL,
  `dzien_tygodnia` enum('poniedziałek','wtorek','środa','czwartek','') NOT NULL,
  `godzina_od` time NOT NULL,
  `godzina_do` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `prowadzacy`
--

CREATE TABLE `prowadzacy` (
  `id_prowadzacego` int(11) NOT NULL,
  `imie` varchar(20) NOT NULL,
  `nazwisko` varchar(20) NOT NULL,
  `stopien_naukowy` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `prowadzacy`
--

INSERT INTO `prowadzacy` (`id_prowadzacego`, `imie`, `nazwisko`, `stopien_naukowy`) VALUES
(1101, 'Jan', 'Kowalski', 'dr'),
(1102, 'Zbigniew', 'Orlikowski', 'dr'),
(1103, 'Grażyna', 'Malinowska', 'dr'),
(1104, 'Krystyna', 'Kwaśniewska', 'dr'),
(1105, 'Mirosław', 'Sępiński', 'dr'),
(1106, 'Boguslaw', 'Tarczyński', 'dr'),
(1107, 'Przemysław', 'Jankowski', 'mgr');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `rodzaje_ocen`
--

CREATE TABLE `rodzaje_ocen` (
  `id_rodzaju_ocen` int(11) NOT NULL,
  `rodzaj_oceny` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `rodzaje_ocen`
--

INSERT INTO `rodzaje_ocen` (`id_rodzaju_ocen`, `rodzaj_oceny`) VALUES
(1, 'aktywność'),
(2, 'test'),
(3, 'praca zaliczeniowa');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `semestry`
--

CREATE TABLE `semestry` (
  `id_semestru` int(11) NOT NULL,
  `numer_semestru` smallint(6) NOT NULL,
  `numer_roku` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `semestry`
--

INSERT INTO `semestry` (`id_semestru`, `numer_semestru`, `numer_roku`) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 3, 2),
(4, 4, 2),
(5, 5, 3),
(6, 6, 3);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `stopnie`
--

CREATE TABLE `stopnie` (
  `id_stopnia` int(11) NOT NULL,
  `stopien` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `stopnie`
--

INSERT INTO `stopnie` (`id_stopnia`, `stopien`) VALUES
(2, 2),
(3, 3),
(4, 4),
(5, 5);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `studenci`
--

CREATE TABLE `studenci` (
  `id_studenta` int(11) NOT NULL,
  `imieS` varchar(20) NOT NULL,
  `nazwiskoS` varchar(20) NOT NULL,
  `email` varchar(40) NOT NULL,
  `id_grupy` int(11) NOT NULL,
  `id_kierunku` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `studenci`
--

INSERT INTO `studenci` (`id_studenta`, `imieS`, `nazwiskoS`, `email`, `id_grupy`, `id_kierunku`) VALUES
(1000, 'Aneta', 'Bluszczyk', 'a.bluszczyk@przykladowymail.com', 1, 1),
(1002, 'Piotr', 'Czajkowski', 'piotr.czajkowski@przykladowymail.com', 1, 1),
(1003, 'Anna', 'Kisiel', 'a.kisiel@przykladowymail.com', 1, 1),
(1004, 'Adam', 'Pietrzyk', 'a.pietrzyk@przykladowymail.com', 1, 1),
(1005, 'Bartłomiej', 'Tomczyk', 'tomczyk@przykladowymail.com', 1, 1),
(1006, 'Agnieszka', 'Wejcherowska', 'a.wejcherowska@przykladowymail.com', 1, 1),
(1007, 'Małgorzata', 'Zbigniew', 'm.zbigniew@przykladowymail.com', 1, 1),
(1008, 'Paweł', 'Zawadzki', 'zawadzki@przykladowymail.com', 1, 1),
(1009, 'Natalia', 'Kowalska', 'natalia.kowalska@przykladowymail.com', 1, 1),
(1011, 'Sławomir', 'Pietrzykowski', 'pietrzykowski@przykladowymail.com', 2, 2),
(1012, 'Anna', 'Wilk', 'a.wilk@przykladowymail.com', 2, 2),
(1013, 'Przemysław', 'Wąsowicz', 'wąsowicz@przykladowymail.com', 2, 2),
(1014, 'Aneta', 'Nowak', 'a.nowak@przykladowymail.com', 2, 2),
(1015, 'Bartłomiej', 'Słowik', 'b.slowik@przykladowymail.com', 2, 2),
(1016, 'Dagmara', 'Jędrzejewska', 'd.jedrzejewska@przykladowymail.com', 2, 2),
(1017, 'Mariusz', 'Borkowski', 'mariusz.borkowski@przykladowymail.com', 2, 2),
(1018, 'Andrzej', 'Orzech', 'a.orzech@przykladowymail.com', 2, 2),
(1019, 'Żaneta', 'Ostrowska', 'zaneta.ostrowska@przykladowymail.com', 2, 2),
(1020, 'Seweryn', 'Malinowski', 's.malinowski@przykladowymail.com', 3, 3),
(1022, 'Adam', 'Orlik', 'adam.orlik@przykladowymail.com', 3, 3),
(1023, 'Agnieszka', 'Szymańska', 'a.szymanska@przykladowymail.com', 3, 3),
(1024, 'Paweł', 'Brzeziński', 'p.brzezinski@przykladowymail.com', 3, 3),
(1025, 'Edyta', 'Niedzielska', 'e.niedzielska@przykladowymail.com', 3, 3),
(1026, 'Weronika', 'Orzechowska', 'w.orzechowska@przykladowymail.com', 3, 3),
(1027, 'Tomasz', 'Listkowski', 't.listkowski@przykladowymail.com', 3, 3),
(1028, 'Maciej', 'Skoczylas', 'm.skoczylas@przykladowymail.com', 3, 3),
(1029, 'Alicja', 'Majewska', 'a.majewska@przykladowymail.com', 3, 3);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `tematy_zajec`
--

CREATE TABLE `tematy_zajec` (
  `id_tematu` int(11) NOT NULL,
  `temat` varchar(105) NOT NULL,
  `id_prowadzacego` int(11) NOT NULL,
  `id_zajec` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `tematy_zajec`
--

INSERT INTO `tematy_zajec` (`id_tematu`, `temat`, `id_prowadzacego`, `id_zajec`) VALUES
(10001, 'Pojęcie administracji', 1101, 201),
(10002, 'Adminisracja publiczna', 1101, 201),
(10003, 'Prawo administracyjne', 1101, 201),
(10004, 'Organy administraci publicznej', 1101, 201),
(10005, 'Centralizacja i decentralizacja', 1101, 201),
(10011, 'Organizacja stanowiska pracy', 1101, 204),
(10012, 'Ergonomia w dyrektywach i normach ', 1101, 204),
(10013, 'Bezpieczeństwa pracy  w małych i średnich przedsiębiorstwach', 1101, 204),
(10014, 'Ergonomia oprogramowania ', 1101, 204),
(10015, 'Zasady ergonomii na stanowiskach komputerowych', 1101, 204),
(10016, 'Zastosowanie zasad ergonomii w przedsiębiorstwie', 1101, 204),
(20001, 'Front-end i Back-end', 1107, 301),
(20002, 'HTML i CSS', 1107, 301),
(20003, 'Najpopularniej języki programowania', 1107, 301),
(20004, 'Narzędzia programisty', 1107, 301),
(20005, 'Aplikacje webowe', 1107, 301),
(30001, 'Własność intelektualna i jej rodzaje', 1101, 100),
(30002, 'Patenty i prawo autorskie', 1101, 100),
(30003, 'Prawo własności przemysłowej', 1101, 100),
(30005, 'Ochrona własności intelektualnej - prawo wyłączne', 1101, 100),
(30006, 'Ochrona własności intelektualnej - wiedza utajona', 1101, 100),
(30011, 'Czym się zajmuje ekonomia?', 1102, 102),
(30012, 'Geneza ekonomii i różnorodność poglądów ekonomicznych', 1102, 102),
(30013, 'Ekonomia klasyczna i liberalizm', 1102, 102),
(30014, 'Metody badań ekonomicznych, kategorie i prawa ekonomiczne', 1102, 102),
(30015, 'Potrzeby ludzkie, produkcja i praca, czynniki produkcji', 1102, 102),
(30016, 'Proces gospodarowania, podmioty i decyzje gospodarcze', 1102, 102),
(30017, 'Racjonalność gospodarowania i rachunek ekonomiczny', 1102, 102),
(30021, 'Definicja logistyki', 1105, 101),
(30022, 'Zadania, zakres, istota logistyki', 1105, 101),
(30023, 'Sfery zastosowania logistyki', 1105, 101),
(30024, 'Istota zarządzania logistycznego', 1105, 101),
(30025, 'Logistyka w przedsiębiorstwie', 1105, 101),
(30026, 'Transport w systemach logistycznych', 1105, 101),
(30028, 'Logistyka w łańcuchu dostaw', 1105, 101),
(30189, 'Administracja rządowa centralna', 1104, 205),
(30190, 'Administracja rządowa terenowa', 1104, 205),
(30191, 'Ministrowie i wojewodowie', 1104, 205),
(30192, 'Inspekcje', 1104, 205),
(30193, 'Kontrole, straże, służby, agencje', 1104, 205),
(30194, 'Urzędy', 1104, 205),
(30195, 'Państwowy zasób kadrowy', 1104, 205),
(30196, 'Organy administracji publicznej', 1101, 203),
(30197, 'Akt administracyjny', 1101, 203),
(30198, 'Rodzaje norm prawa administracyjnego materialnego', 1101, 203),
(30199, 'Kryterium przedmiotowe prawa administracyjnego materialnego', 1101, 203),
(30200, 'Zalecenia ergonomii', 1101, 112),
(30201, 'Ergonomia w miejscu pracy i jej znaczenie', 1101, 112),
(30202, 'Wytyczne w sprawie uregulowania kartotek skomputeryzowanych danych osobowych', 1101, 200),
(30203, 'Dyrektywy w sprawie przetwarzania danych osobowych', 1101, 200),
(30204, 'Podział prawa administracyjnego', 1101, 201),
(30205, 'Teoria państwa i prawa', 1101, 202),
(30206, 'Prawo konstytucyjne', 1101, 202),
(30209, 'Prawo karne', 1101, 202),
(30210, 'Prawo cywilne', 1101, 202),
(30213, 'Prawo administracyjne', 1101, 202),
(30214, 'Organy publiczne', 1104, 207),
(30215, 'Rola samorządów', 1104, 207),
(30216, 'Algorytmika', 1107, 302),
(30217, 'Typy i struktury danych', 1107, 302),
(30218, 'Budowa komputerów', 1106, 308),
(30219, 'Podzespoły', 1106, 308),
(30220, 'Struktura i działanie procesora', 1106, 308),
(30221, 'Pamięć wewnętrzna', 1106, 308),
(30222, 'Pamięć zewnętrzna', 1106, 308),
(30223, 'Pamięć podręczna', 1106, 308),
(30224, 'Wspieranie systemu operacyjnego', 1106, 308),
(30225, 'Budowa bazy danych', 1107, 312),
(30226, 'Rodzaje bazy danych', 1107, 312),
(30227, 'Bazy relacyjne', 1107, 312),
(30228, 'Bazy obietowe', 1107, 312),
(30229, 'Bazy relacyjno-obiektowe', 1107, 312),
(30230, 'Kryptografia', 1106, 311),
(30233, 'Zabezpieczanie danych', 1106, 311),
(30234, 'Analiza złożoności algorytmów', 1107, 302),
(30235, 'Szyfrowanie i kompresja danych', 1107, 302),
(30236, 'Algorytmy sortowania i wyszukiwania', 1107, 302),
(30237, 'Definicje e-Administracji', 1104, 211),
(30238, 'Podstawy prawne e-Administracji ', 1104, 211),
(30239, 'Części składowe infrastruktury logistycznej', 1105, 110),
(30240, 'Rola infrastruktury logistycznej', 1105, 110),
(30241, 'Struktura podmiotowa kanału dystrybucji', 1105, 115),
(30242, 'Rodzaje pośredników i ich funkcje', 1105, 115),
(30243, 'Logistyka dystrybucji a marketing', 1105, 115),
(30244, 'Wskaźniki logistyki zaopatrzenia', 1105, 107),
(30245, 'Kryteria wyboru dostawców', 1105, 107),
(30246, 'Fazy przepływu materiałów', 1105, 107),
(30247, 'Pojęcie transportu', 1105, 111),
(30248, 'Pojęcie ekonomiki transportu', 1105, 111),
(30249, 'Proces transportowy', 1105, 111),
(30250, 'Klasyfikacje transportu', 1105, 111),
(30251, 'Charakterystyka gałęzi transportu', 1105, 111),
(30252, 'Podstawowe defi nicje z zakresu logistyki ', 1105, 101),
(30253, 'Wykorzystanie danych i informacji w logistyce ', 1105, 101),
(30254, 'Odpady w logistyce', 1105, 101),
(30255, 'Systemy zarządzania magazynem', 1103, 116),
(30256, 'Kontrola zapasów', 1103, 116),
(30257, 'Zarządzanie zapasami', 1103, 116),
(30258, 'Optymalizacja zapasów', 1103, 116),
(30259, 'Analiza i ocena materiałów i towarów', 1103, 105),
(30260, 'Wykorzystanie funkcji opakowań', 1103, 105),
(30261, 'Fazy cyklu życia produktu', 1103, 106),
(30262, 'Wprowadzanie na rynek', 1103, 106),
(30263, 'Dojrzałość rynkowa', 1103, 106),
(30264, 'Rodzaje polityk cenowych', 1103, 106),
(30265, 'Metoda WCM', 1103, 108),
(30266, 'Planowanie produkcji', 1103, 108),
(30267, 'Kierowanie i kontrolowanie działań produkcyjnych', 1103, 108),
(30268, 'Opakowania w systemach logistycznych', 1103, 109),
(30269, 'Rodzaje jednostek ładunkowych', 1103, 109),
(30270, 'Opakowania transportowe', 1103, 109),
(30271, 'Geneza normalizacji systemów jakości', 1103, 114),
(30272, 'Nowelizacja norm ISO 9000', 1103, 114),
(30273, 'Ewolucja systemów jakości', 1103, 114),
(30274, 'Metodyka PRINCE II', 1103, 114),
(30275, 'Znaczenie informatyki dla logistyki', 1106, 113),
(30276, 'Wspomaganie pracy przedsiębiorstwa', 1106, 113),
(30277, 'Przeznaczenie sieci komputerowej', 1106, 310),
(30278, 'Użytkowe cechy sieci komputerowej', 1106, 310),
(30279, 'Składniki cechy sieci komputerowej', 1106, 310),
(30280, 'Budowa systemu operacyjnego', 1106, 309),
(30281, 'Sieciowy system operacyjny', 1106, 309),
(30282, 'Jądro systemu operacyjnego', 1106, 309),
(30283, 'Podstawy technik informatycznych', 1106, 300),
(30284, 'Technologia informaycjna', 1106, 300),
(30285, 'Omówienie budowy programów komputerowych', 1107, 301),
(30286, 'Paradygmat programowania', 1107, 305),
(30287, 'Obiekty i klasy', 1107, 305),
(30288, 'Programowanie klasowe kontra prototypowe', 1107, 305),
(30289, 'Podstawowe założenia paradygmatu obiektowego', 1107, 305),
(30290, 'Elementarna charakterystyka popularnych języków obiektowych', 1107, 305),
(30291, 'Debugowanie', 1107, 307),
(30292, 'Język skryptowy', 1107, 307),
(30293, 'Konsolidacja', 1107, 307),
(30294, 'Język HTML5 i CSS3', 1107, 306),
(30295, 'Język JavaScript', 1107, 306),
(30296, 'Język PHP ', 1107, 306),
(30297, 'Biblioteki JS', 1107, 306),
(30298, 'Istota zarządzania', 1102, 104),
(30299, 'Funkcje zarządzania', 1102, 104),
(30300, 'Zasady zarządzania wg Fayola', 1102, 104),
(30301, 'Cechy zarządzania wg Druckera', 1102, 104),
(30302, 'Cechy charakterystyczne organizacji', 1102, 104),
(30304, 'Czym jest marketing?', 1102, 103),
(30305, 'Koncepcja marketingowa', 1102, 103),
(30306, 'Koncepcja marketingu społecznego', 1102, 103),
(30307, 'Podział badań marketingowych', 1102, 103),
(30308, 'Psychologia zarządzania', 1104, 206),
(30309, 'Metody zarządzania publicznego', 1104, 206),
(30310, 'Regulacje postępowania administracyjnego', 1104, 209),
(30311, 'Zakres regulacji Kodeksu postępowania administracyjnego', 1104, 209),
(30312, 'Zasady postępowania administracyjnego', 1104, 209),
(30313, 'Rodzaje postępowania administracyjnego', 1104, 209),
(30314, 'Istota zamówień publicznych', 1104, 210),
(30315, 'Koncesja na roboty budowlane lub usługi', 1104, 210),
(30316, 'Platformy elektronicznych zamówień publicznych', 1104, 210),
(30317, 'Naruszenia prawa zamówień publicznych', 1104, 210),
(30318, 'Zasady tworzenia i stosowania prawa', 1101, 212),
(30319, 'Tworzenie a stanowienie prawa', 1101, 212),
(30328, 'Algorytm', 1107, 302);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `uprawnienia`
--

CREATE TABLE `uprawnienia` (
  `id_uprawnien` int(11) NOT NULL,
  `opis_uprawnien` enum('przeglądanie','dodawanie','edycja','usuwanie') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `uwagi`
--

CREATE TABLE `uwagi` (
  `id_uwagi` int(11) NOT NULL,
  `id_studenta` int(11) NOT NULL,
  `id_prowadzacego` int(11) NOT NULL,
  `tresc` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `uwagi`
--

INSERT INTO `uwagi` (`id_uwagi`, `id_studenta`, `id_prowadzacego`, `tresc`) VALUES
(1, 1000, 1107, 'Ambitna, zawsze przygotowana do zajęć'),
(3, 1017, 1107, 'Zawsze przygotowany zajęć, aktywny'),
(4, 1003, 1101, 'Ambitna, zaangażowana, bardzo wnikliwa'),
(5, 1009, 1101, 'Myśli analitycznie, zaangażowana, brak zastrzeżeń'),
(6, 1024, 1101, 'Solidny, rzeczowy, zawsze przygotowany do zajęć');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `uzytkownicy`
--

CREATE TABLE `uzytkownicy` (
  `id_uzytkownika` int(11) NOT NULL,
  `imie` varchar(20) NOT NULL,
  `nazwisko` varchar(20) NOT NULL,
  `login` varchar(40) NOT NULL,
  `haslo` varchar(60) NOT NULL,
  `id_dostepu` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `uzytkownicy`
--

INSERT INTO `uzytkownicy` (`id_uzytkownika`, `imie`, `nazwisko`, `login`, `haslo`, `id_dostepu`) VALUES
(1, 'Jan', 'Kowalski', 'Kowalski', '650e8df6bfa8745919d428c42e39e8e1', 2),
(2, 'Anna', 'Wilk', 'Wilk', '650e8df6bfa8745919d428c42e39e8e1', 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `zajecia`
--

CREATE TABLE `zajecia` (
  `id_zajec` int(11) NOT NULL,
  `nazwa_zajec` varchar(60) NOT NULL,
  `rodzaj_zajec` enum('ćwiczenia','wykład','seminarium','') NOT NULL,
  `id_kierunku` int(11) NOT NULL,
  `id_prowadzacego` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `zajecia`
--

INSERT INTO `zajecia` (`id_zajec`, `nazwa_zajec`, `rodzaj_zajec`, `id_kierunku`, `id_prowadzacego`) VALUES
(101, 'Podstawy logistyki', 'wykład', 3, 1105),
(102, 'Podstawy ekonomii', 'wykład', 3, 1102),
(103, 'Podstawy marketingu i badań marketingowych', 'wykład', 3, 1102),
(104, 'Podstawy zarządzania', 'wykład', 3, 1102),
(105, 'Towaroznawstwo i materiałoznawstwo', 'ćwiczenia', 3, 1103),
(106, 'Zarządzanie cyklem życia wyrobu', 'ćwiczenia', 3, 1103),
(107, 'Logistyka zaopatrzenia', 'ćwiczenia', 3, 1105),
(108, 'Zarządzanie produkcją i usługami', 'seminarium', 3, 1103),
(109, 'Opakowania i zabezpieczenie ładunku', 'ćwiczenia', 3, 1103),
(110, 'Infrastruktura logistyczna', 'wykład', 3, 1105),
(111, 'Organizacja i ekonomika transportu', 'seminarium', 3, 1105),
(112, 'Ergonomia i bezpieczeństwo pracy', 'wykład', 3, 1101),
(113, 'Systemy informatyczne w logistyce', 'ćwiczenia', 3, 1106),
(114, 'Normalizacja i zarządzanie jakością', 'ćwiczenia', 3, 1103),
(115, 'Logistyka dystrybucji ', 'wykład', 3, 1105),
(116, 'Magazynowanie i zarządzanie zapasami ', 'seminarium', 3, 1103),
(200, 'Ochrona danych osobowych', 'wykład', 1, 1101),
(201, 'Wprowadzenie do prawa administracyjnego', 'wykład', 1, 1101),
(202, 'Podstawy prawa', 'wykład', 1, 1101),
(203, 'Prawo administracyjne materialne', 'wykład', 1, 1101),
(205, 'Administracja rządowa i służba cywilna', 'wykład', 1, 1104),
(206, 'Psychologia zarządzania w administracji', 'ćwiczenia', 1, 1104),
(207, 'Administracja samorządowa', 'ćwiczenia', 1, 1104),
(209, 'Postępowanie administracyjne i sądowo-adminsitracyjne', 'ćwiczenia', 1, 1104),
(210, 'Zamówienia publiczne', 'ćwiczenia', 1, 1104),
(211, 'e-Administracja', 'seminarium', 1, 1104),
(212, 'Tworzenia i stosowania prawa', 'seminarium', 1, 1101),
(300, 'Techniki informatyczne', 'ćwiczenia', 2, 1106),
(301, 'Podstawy programowania', 'ćwiczenia', 2, 1107),
(302, 'Algorytmy i struktury danych', 'ćwiczenia', 2, 1107),
(303, 'Ochrona własności intelektualnej', 'wykład', 2, 1101),
(305, 'Programowanie obiektowe', 'ćwiczenia', 2, 1107),
(306, 'Programowanie aplikacji internetowych', 'ćwiczenia', 2, 1107),
(307, 'Programowanie zaawansowane', 'seminarium', 2, 1107),
(308, 'Architektura komputerów', 'wykład', 2, 1106),
(309, 'Systemy operacyjne', 'wykład', 2, 1106),
(310, 'Sieci komputerowe', 'wykład', 2, 1106),
(311, 'Bezpieczeństwo w systemach i sieciach komputerowych', 'wykład', 2, 1106),
(312, 'Bazy danych', 'seminarium', 2, 1107);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `dostep`
--
ALTER TABLE `dostep`
  ADD UNIQUE KEY `id_uzytkownika` (`id_uzytkownika`),
  ADD UNIQUE KEY `id_uprawnien` (`id_uprawnien`),
  ADD UNIQUE KEY `id_modulu` (`id_modulu`);

--
-- Indeksy dla tabeli `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `grupy`
--
ALTER TABLE `grupy`
  ADD PRIMARY KEY (`id_grupy`);

--
-- Indeksy dla tabeli `kierunki`
--
ALTER TABLE `kierunki`
  ADD PRIMARY KEY (`id_kierunku`);

--
-- Indeksy dla tabeli `modul`
--
ALTER TABLE `modul`
  ADD PRIMARY KEY (`id_modulu`);

--
-- Indeksy dla tabeli `nieobecnosci`
--
ALTER TABLE `nieobecnosci`
  ADD PRIMARY KEY (`id_nieobecnosci`),
  ADD UNIQUE KEY `id_zajec` (`id_zajec`),
  ADD UNIQUE KEY `id_prowadzacego` (`id_prowadzacego`),
  ADD UNIQUE KEY `id_studenta` (`id_studenta`);

--
-- Indeksy dla tabeli `oceny`
--
ALTER TABLE `oceny`
  ADD PRIMARY KEY (`id_oceny`);

--
-- Indeksy dla tabeli `plan_zajec`
--
ALTER TABLE `plan_zajec`
  ADD PRIMARY KEY (`id_planu_zajec`),
  ADD UNIQUE KEY `id_prowadzacego` (`id_prowadzacego`),
  ADD UNIQUE KEY `id_zajec` (`id_zajec`),
  ADD UNIQUE KEY `id_grupy` (`id_grupy`);

--
-- Indeksy dla tabeli `prowadzacy`
--
ALTER TABLE `prowadzacy`
  ADD PRIMARY KEY (`id_prowadzacego`);

--
-- Indeksy dla tabeli `rodzaje_ocen`
--
ALTER TABLE `rodzaje_ocen`
  ADD PRIMARY KEY (`id_rodzaju_ocen`);

--
-- Indeksy dla tabeli `semestry`
--
ALTER TABLE `semestry`
  ADD PRIMARY KEY (`id_semestru`);

--
-- Indeksy dla tabeli `stopnie`
--
ALTER TABLE `stopnie`
  ADD PRIMARY KEY (`id_stopnia`);

--
-- Indeksy dla tabeli `studenci`
--
ALTER TABLE `studenci`
  ADD PRIMARY KEY (`id_studenta`),
  ADD KEY `id_grupy` (`id_grupy`),
  ADD KEY `id_kierunku` (`id_kierunku`);

--
-- Indeksy dla tabeli `tematy_zajec`
--
ALTER TABLE `tematy_zajec`
  ADD PRIMARY KEY (`id_tematu`),
  ADD KEY `id_prowadzacego` (`id_prowadzacego`),
  ADD KEY `id_zajec` (`id_zajec`);

--
-- Indeksy dla tabeli `uprawnienia`
--
ALTER TABLE `uprawnienia`
  ADD PRIMARY KEY (`id_uprawnien`);

--
-- Indeksy dla tabeli `uwagi`
--
ALTER TABLE `uwagi`
  ADD PRIMARY KEY (`id_uwagi`);

--
-- Indeksy dla tabeli `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  ADD PRIMARY KEY (`id_uzytkownika`);

--
-- Indeksy dla tabeli `zajecia`
--
ALTER TABLE `zajecia`
  ADD PRIMARY KEY (`id_zajec`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT dla tabeli `grupy`
--
ALTER TABLE `grupy`
  MODIFY `id_grupy` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT dla tabeli `kierunki`
--
ALTER TABLE `kierunki`
  MODIFY `id_kierunku` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT dla tabeli `modul`
--
ALTER TABLE `modul`
  MODIFY `id_modulu` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `nieobecnosci`
--
ALTER TABLE `nieobecnosci`
  MODIFY `id_nieobecnosci` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `oceny`
--
ALTER TABLE `oceny`
  MODIFY `id_oceny` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT dla tabeli `plan_zajec`
--
ALTER TABLE `plan_zajec`
  MODIFY `id_planu_zajec` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `prowadzacy`
--
ALTER TABLE `prowadzacy`
  MODIFY `id_prowadzacego` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11008;

--
-- AUTO_INCREMENT dla tabeli `rodzaje_ocen`
--
ALTER TABLE `rodzaje_ocen`
  MODIFY `id_rodzaju_ocen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT dla tabeli `semestry`
--
ALTER TABLE `semestry`
  MODIFY `id_semestru` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT dla tabeli `stopnie`
--
ALTER TABLE `stopnie`
  MODIFY `id_stopnia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT dla tabeli `studenci`
--
ALTER TABLE `studenci`
  MODIFY `id_studenta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10034;

--
-- AUTO_INCREMENT dla tabeli `tematy_zajec`
--
ALTER TABLE `tematy_zajec`
  MODIFY `id_tematu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30333;

--
-- AUTO_INCREMENT dla tabeli `uprawnienia`
--
ALTER TABLE `uprawnienia`
  MODIFY `id_uprawnien` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `uwagi`
--
ALTER TABLE `uwagi`
  MODIFY `id_uwagi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT dla tabeli `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  MODIFY `id_uzytkownika` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT dla tabeli `zajecia`
--
ALTER TABLE `zajecia`
  MODIFY `id_zajec` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=313;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `dostep`
--
ALTER TABLE `dostep`
  ADD CONSTRAINT `dostep_ibfk_1` FOREIGN KEY (`id_uzytkownika`) REFERENCES `uzytkownicy` (`id_uzytkownika`),
  ADD CONSTRAINT `dostep_ibfk_2` FOREIGN KEY (`id_uprawnien`) REFERENCES `uprawnienia` (`id_uprawnien`);

--
-- Ograniczenia dla tabeli `modul`
--
ALTER TABLE `modul`
  ADD CONSTRAINT `modul_ibfk_1` FOREIGN KEY (`id_modulu`) REFERENCES `dostep` (`id_modulu`);

--
-- Ograniczenia dla tabeli `nieobecnosci`
--
ALTER TABLE `nieobecnosci`
  ADD CONSTRAINT `nieobecnosci_ibfk_1` FOREIGN KEY (`id_studenta`) REFERENCES `studenci` (`id_studenta`);

--
-- Ograniczenia dla tabeli `oceny`
--
ALTER TABLE `oceny`
  ADD CONSTRAINT `oceny_ibfk_1` FOREIGN KEY (`id_semestru`) REFERENCES `semestry` (`id_semestru`);

--
-- Ograniczenia dla tabeli `plan_zajec`
--
ALTER TABLE `plan_zajec`
  ADD CONSTRAINT `plan_zajec_ibfk_1` FOREIGN KEY (`id_zajec`) REFERENCES `zajecia` (`id_zajec`);

--
-- Ograniczenia dla tabeli `tematy_zajec`
--
ALTER TABLE `tematy_zajec`
  ADD CONSTRAINT `tematy_zajec_ibfk_1` FOREIGN KEY (`id_prowadzacego`) REFERENCES `prowadzacy` (`id_prowadzacego`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
