<?php
$live_rate_codes = array( 
	array( 
		"type" 	=> "is_auspost_based",
		"label" => "Australia Post",
		"rates"	=> array(
			array(
				"code" 	=> "AUS_PARCEL_REGULAR",
				"label" => "Parcel Post" 
			),
			array(
				"code" => "AUS_PARCEL_EXPRESS",
				"label" => "Express Post" ),
			array(
				"code" => "AUS_PARCEL_REGULAR_SATCHEL_500G", 
				"label" => "Parcel Post Small Satchel"
			),
			array(
				"code" => "AUS_PARCEL_EXPRESS_SATCHEL_500G", 
				"label" => "Express Post Small Satchel"
			),
			array(
				"code" => "AUS_PARCEL_REGULAR_SATCHEL_3KG", 
				"label" => "Parcel Post Medium Satchel"
			),
			array(
				"code" => "AUS_PARCEL_EXPRESS_SATCHEL_3KG", 
				"label" => "Express Post Medium (3Kg) Satchel"
			),
			array(
				"code" => "AUS_PARCEL_REGULAR_SATCHEL_5KG", 
				"label" => "Parcel Post Large Satchel"
			),
			array(
				"code" => "AUS_PARCEL_EXPRESS_SATCHEL_5KG", 
				"label" => "Express Post Large (5Kg) Satchel"
			),
			array(
				"code" => "INT_PARCEL_COR_OWN_PACKAGING", 
				"label" => "International Courier"
			),
			array(
				"code" => "INT_PARCEL_EXP_OWN_PACKAGING", 
				"label" => "International Express"
			),
			array(
				"code" => "INT_PARCEL_STD_OWN_PACKAGING", 
				"label" => "International Standard"
			),
			array(
				"code" => "INT_PARCEL_AIR_OWN_PACKAGING", 
				"label" => "International Economy Air"
			),
			array(
				"code" => "INT_PARCEL_SEA_OWN_PACKAGING", 
				"label" => "International Economy Sea"
			)
		)
	),
	array(
		"type"	=> "is_canadapost_based",
		"label"	=> "Canada Post",
		"rates" => array(
			array(
				"code" => "DOM.DT",
				"label" => "DOM - Delivered Tonight"
			),
			array(
				"code" => "DOM.EP",
				"label" => "DOM - Expedited Parcel"
			),
			array(
				"code" => "DOM.LIB",
				"label" => "DOM - Library Books"
			),
			array(
				"code" => "DOM.PC",
				"label" => "DOM - Priority"
			),
			array(
				"code" => "DOM.RP",
				"label" => "DOM - Regular Parcel"
			),
			array(
				"code" => "DOM.XP",
				"label" => "DOM - Xpresspost"
			),
			array(
				"code" => "DOM.XP.CERT",
				"label" => "DOM - Xpresspost Certified"
			),
			array(
				"code" => "USA.EP",
				"label" => "USA - Expedited Parcel"
			),
			array(
				"code" => "USA.PW.ENV",
				"label" => "USA - Priority Worldwide Envelope"
			),
			array(
				"code" => "USA.PW.PAK",
				"label" => "USA - Priority Woldwide Pak"
			),
			array(
				"code" => "USA.PW.PARCEL",
				"label" => "USA - Priority Woldwide Parcel"
			),
			array(
				"code" => "USA.SP.AIR",
				"label" => "USA - Small Packet Air"
			),
			array(
				"code" => "USA.TP",
				"label" => "USA - Tracked Packet"
			),
			array(
				"code" => "USA.TP.LVM",
				"label" => "USA - Tracked Packet (LVM)"
			),
			array(
				"code" => "USA.XP",
				"label" => "USA - Xpresspost"
			),
			array(
				"code" => "INT.IP.AIR",
				"label" => "INT - Parcel Air"
			),
			array(
				"code" => "INT.IP.SURF",
				"label" => "INT - Parcel Surface"
			),
			array(
				"code" => "INT.PW.ENV",
				"label" => "INT - Priority Worldwide Envelope"
			),
			array(
				"code" => "INT.PW.PAK",
				"label" => "INT - Priority Worldwide Pak"
			),
			array(
				"code" => "INT.PW.PARCEL",
				"label" => "INT - Priority Worldwide Parcel"
			),
			array(
				"code" => "INT.SP.AIR",
				"label" => "INT - Small Packet Air"
			),
			array(
				"code" => "INT.SP.SURF",
				"label" => "INT - Small Packet Surface"
			),
			array(
				"code" => "INT.TP",
				"label" => "INT - Tracked Packet"
			),
			array(
				"code" => "INT.XP",
				"label" => "INT - Xpresspost"
			)
		)
	),
	array(
		"type"	=> "is_dhl_based",
		"label"	=> "DHL",
		"rates" => array(
			array(
				"code" => "A",
				"label" => "Auto Reversals"
			),
			array(
				"code" => "2",
				"label" => "B2C"
			),
			array(
				"code" => "I",
				"label" => "Break Bulk Economy"
			),
			array(
				"code" => "B",
				"label" => "Break Bulk Express"
			),
			array(
				"code" => "Z",
				"label" => "Destination Charges"
			),
			array(
				"code" => "O",
				"label" => "Dom Express 10:30"
			),
			array(
				"code" => "G",
				"label" => "Domestic Economy Select"
			),
			array(
				"code" => "N",
				"label" => "Domestic Express"
			),
			array(
				"code" => "1",
				"label" => "Domestic Express 12:00"
			),
			array(
				"code" => "H",
				"label" => "Economy Select"
			),
			array(
				"code" => "9",
				"label" => "Europack"
			),
			array(
				"code" => "E",
				"label" => "Express 9:00"
			),
			array(
				"code" => "L",
				"label" => "Express 10:30"
			), 
			array(
				"code" => "T",
				"label" => "Express 12:00"
			),
			array(
				"code" => "7",
				"label" => "Express Easy"
			),
			array(
				"code" => "X",
				"label" => "Express Envelope"
			),
			array(
				"code" => "D",
				"label" => "Express Worldwide"
			),
			array(
				"code" => "F",
				"label" => "Freight Worldwide"
			),
			array(
				"code" => "R",
				"label" => "Globalmail Business"
			),
			array(
				"code" => "4",
				"label" => "Jetline"
			),
			array(
				"code" => "J",
				"label" => "Jumbo Bulk"
			),
			array(
				"code" => "0",
				"label" => "Logistics Services"
			),
			array(
				"code" => "C",
				"label" => "Medical Express"
			),
			array(
				"code" => "S",
				"label" => "Same Day"
			),
			array(
				"code" => "6",
				"label" => "Secureline"
			),
			array(
				"code" => "5",
				"label" => "Sprintline"
			)
		)
	),
	array(
		"type"	=> "is_fedex_based",
		"label" => "FedEx",
		"rates" => array(
			array(
				"code" => "FEDEX_1_DAY_FREIGHT",
				"label" => "FedEx 1 Day Freight"
			),
			array(
				"code" => "FEDEX_2_DAY",
				"label" => "FedEx 2 Day"
			),
			array(
				"code" => "FEDEX_2_DAY_AM",
				"label" => "FedEx 2 Day AM"
			),
			array(
				"code" => "FEDEX_2_DAY_FREIGHT",
				"label" => "FedEx 2 Day Freight"
			),
			array(
				"code" => "FEDEX_3_DAY_FREIGHT",
				"label" => "FedEx 3 Day Freight"
			),
			array(
				"code" => "FEDEX_EXPRESS_SAVER",
				"label" => "FedEx Express Saver"
			),
			array(
				"code" => "EUROPE_FIRST_INTERNATIONAL_PRIORITY",
				"label" => "FedEx Europe First International Priority"
			),
			array(
				"code" => "FEDEX_FIRST_FREIGHT",
				"label" => "FedEx First Freight"
			),
			array(
				"code" => "FEDEX_FREIGHT_ECONOMY",
				"label" => "FedEx Freight Economy"
			),
			array(
				"code" => "FEDEX_GROUND",
				"label" => "FedEx Ground"
			),
			array(
				"code" => "GROUND_HOME_DELIVERY",
				"label" => "FedEx Ground Home Delivery"
			),
			array(
				"code" => "FEDEX_FREIGHT_PRIORITY",
				"label" => "FedEx Freight Priority"
			),
			array(
				"code" => "FIRST_OVERNIGHT",
				"label" => "FedEx First Overnight"
			),
			array(
				"code" => "INTERNATIONAL_ECONOMY",
				"label" => "International Economy"
			),
			array(
				"code" => "INTERNATIONAL_ECONOMY_FREIGHT",
				"label" => "International Economy Freight"
			),
			array(
				"code" => "INTERNATIONAL_FIRST",
				"label" => "International First"
			),
			array(
				"code" => "INTERNATIONAL_PRIORITY",
				"label" => "International Priority"
			),
			array(
				"code" => "INTERNATIONAL_PRIORITY_FREIGHT",
				"label" => "International Priority Freight"
			),
			array(
				"code" => "PRIORITY_OVERNIGHT",
				"label" => "Priority Overnight"
			),
			array(
				"code" => "SMART_POST",
				"label" => "Smart Post"
			),
			array(
				"code" => "STANDARD_OVERNIGHT",
				"label" => "Standard Overnight"
			)
		)
	),
	array(
		"type"	=> "is_ups_based",
		"label"	=> "UPS",
		"rates" => array(
			array(
				"code" => "01",
				"label" => "UPS Next Day Air"
			),
			array(
				"code" => "02",
				"label" => "UPS Second Day Air"
			),
			array(
				"code" => "03",
				"label" => "UPS Ground"
			),
			array(
				"code" => "07",
				"label" => "UPS Worldwide ExpressSM"
			),
			array(
				"code" => "08",
				"label" => "UPS Worldwide ExpeditedSM"
			),
			array(
				"code" => "11",
				"label" => "UPS Standard"
			),
			array(
				"code" => "12",
				"label" => "UPS Three-Day Select"
			),
			array(
				"code" => "13",
				"label" => "UPS Next Day Air Saver"
			),
			array(
				"code" => "14",
				"label" => "UPS Next Dair Air Early A.M."
			),
			array(
				"code" => "54",
				"label" => "UPS Worldwide Express PlusSM"
			),
			array(
				"code" => "59",
				"label" => "UPS Second Day Air A.M."
			),
			array(
				"code" => "65",
				"label" => "UPS Saver"
			),
			array(
				"code" => "96",
				"label" => "UPS Worldwide Express Freight"
			),
			array(
				"code" => "65",
				"label" => "UPS Saver"
			),
			array(
				"code" => "07",
				"label" => "UPS Express"
			),
			array(
				"code" => "54",
				"label" => "UPS Worldwide Express PlusSM"
			),
			array(
				"code" => "08",
				"label" => "UPS Worldwide ExpeditedSM"
			),
			array(
				"code" => "11",
				"label" => "UPS Standard"
			),
			array(
				"code" => "92",
				"label" => "UPS SurePost Standard Parcel"
			),
			array(
				"code" => "93",
				"label" => "UPS SurePost Parcel Select"
			),
			array(
				"code" => "94",
				"label" => "UPS SurePost Bound Printed Matter"
			),
			array(
				"code" => "95",
				"label" => "UPS SurePost Media Mail"
			),
		)
	),
	array(
		"type"	=> "is_usps_based",
		"label" => "USPS",
		"rates" => array(
			array(
				"code" => "STANDARD POST",
				"label" => "Retail Ground"
			),
			array(
				"code" => "PRIORITY",
				"label" => "Priority"
			),
			array(
				"code" => "PRIORITY COMMERCIAL",
				"label" => "Priority Commercial"
			),
			array(
				"code" => "PRIORITY HFP COMMERCIAL",
				"label" => "Priority HFP Commercial"
			),
			array(
				"code" => "PRIORITY HFP CPP",
				"label" => "Priority HFP CPP"
			),
			array(
				"code" => "EXPRESS",
				"label" => "Express"
			),
			array(
				"code" => "EXPRESS COMMERCIAL",
				"label" => "Express Commercial"
			),
			array(
				"code" => "EXPRESS CPP",
				"label" => "Express CPP"
			),
			array(
				"code" => "EXPRESS SH",
				"label" => "Express SH"
			),
			array(
				"code" => "EXPRESS SH COMMERCIAL",
				"label" => "Express SH Commercial"
			),
			array(
				"code" => "EXPRESS HFP CPP",
				"label" => "Express HFP CPP"
			),
			array(
				"code" => "MEDIA",
				"label" => "Media"
			),
			array(
				"code" => "LIBRARY",
				"label" => "Library"
			),
			array(
				"code" => "ALL",
				"label" => "All"
			),
			array(
				"code" => "ONLINE",
				"label" => "Online"
			),
			array(
				"code" => "PLUS",
				"label" => "Plus"
			),
			array(
				"code" => "FIRST CLASS STAMPED LETTER",
				"label" => "First Class Stamped Letter"
			),
			array(
				"code" => "FIRST CLASS RETAIL",
				"label" => "First Class Package Service Retail"
			),
			array(
				"code" => "FIRST CLASS LARGE ENVELOPE",
				"label" => "First Class Large Envelope"
			),
			array(
				"code" => "FIRST CLASS COMMERCIAL",
				"label" => "First Class Commercial"
			),
			array(
				"code" => "FIRST CLASS HFP COMMERCIAL",
				"label" => "First Class HFP Commercial"
			),
			array(
				"code" => "USPS GXG",
				"label" => "Global Express Guaranteed"
			),
			array(
				"code" => "USPS GROUND ADVANTAGE",
				"label" => "USPS Ground Advantage"
			),
			array(
				"code" => "USPS GROUND ADVANTAGE CUBIC",
				"label" => "USPS Ground Advantage Cubic"
			),
		)
	)
);
?>