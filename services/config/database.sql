--
-- Database: `nf_revue`
--

-- --------------------------------------------------------

--
-- Table structure for table `rating`
--

CREATE TABLE `rating` (
  `ratingID` int(11) NOT NULL AUTO_INCREMENT,
  `rating` float NOT NULL,
  `venue_venueID` int(11) NOT NULL,
  PRIMARY KEY (`ratingID`),
  KEY `venue_venueID` (`venue_venueID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `reviewID` int(11) NOT NULL AUTO_INCREMENT,
  `venue_venueID` int(11) NOT NULL,
  `author` varchar(50) NOT NULL,
  `reviewText` blob NOT NULL,
  PRIMARY KEY (`reviewID`),
  KEY `venue_venueID` (`venue_venueID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `venue`
--

CREATE TABLE `venue` (
  `venueID` int(11) NOT NULL AUTO_INCREMENT,
  `venueName` varchar(50) NOT NULL,
  `emailAddress` varchar(50) NOT NULL,
  `zipcode` varchar(5) NOT NULL,
  `location` varchar(150) NOT NULL,
  PRIMARY KEY (`venueID`),
  KEY `zipcode` (`zipcode`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;