CREATE TABLE IF NOT EXISTS `referral_management` (
  `id` int(11) NOT NULL,
  `fixed_status` tinyint(4) NOT NULL,
  `fixed_amt` int(11) NOT NULL,
  `currency` varchar(25) NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT '0',
  `trip_amt` bigint(20) NOT NULL,
  `trip_per` float NOT NULL,
  `rent_amt` bigint(20) NOT NULL,
  `rent_per` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `referral_management` (`id`, `fixed_status`, `fixed_amt`, `currency`, `type`, `trip_amt`, `trip_per`, `rent_amt`, `rent_per`) VALUES
(1, 0, 1000, 'USD', 0, 0, 50, 1000, 50);
