<?php

/**
 * Venue class for venue methods.
 * @package - revueApi
 */
 
class venue extends base
{
	public function __construct() {
		
	}
	
	public function venueAdd($params) {
		/**
		 *	Check if venueName has been entered.
		 *	Check to see if the email address is valid.
		 *	Check to see if zipcode is numeric and 5 digits.
		*/
	 	if(strlen($params['venueName']) == 0) return false;
		if(!$this->validEmail($params['emailAddress'])) return false;
		if(!is_numeric($params['zipcode']) || strlen($params['zipcode']) !== 5) return false;
		
		/*
		 *	Using Google Geocode grab the json results from their webservice with provided zipcode.
		 *	If for some reason the service fails make the formatted address 'N/A'
		*/
		$geocodeData = @json_decode(file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?address=" . $params['zipcode'] . "&sensor=true"));
		$formatted_address = $geocodeData->results[0]->formatted_address;
		if(strlen($formatted_address) == 0) $formatted_address = "N/A";
		
		/*
		 *	Prepare mysqli insert values.
		*/
		$insertData = array(
			'venueName' => $params['venueName'],
			'emailAddress' => $params['emailAddress'],
			'zipcode' => $params['zipcode'],
			'location' => $formatted_address
		);
		
		/*
		 *	Create new database object and run query.
		*/
		$this->mysqliConnect();	
		if($this->db->insert('venue', $insertData)) {
			return array("status" => "OK");
		} else {
			return false;
		}
		 
	}
	
	public function venueList() {
		/**
		 *	Create database object and fetch venues.
		*/
		$this->mysqliConnect();	
		$venues = $this->db->get("venue");
		
		/**
		 *	 Set status to OK.
		*/
		$venuesData = array("status" => "OK");
		
		/*
		 *	Loop through fetched rows and build and return array.
		*/
		foreach($venues as $venue) {
			$numberOfRatings = $this->countRatings($venue['venueID']);
			$averageRating = $this->calculateAverageRating($venue['venueID']);
			$venue["id"] = $venue["venueID"];
			$venue["numberOfRatings"] = $numberOfRatings;
			$venue["averageRating"] = $averageRating;
			$venuesData["venues"][] = $venue; 
		}
				
		return $venuesData;
	}
	
	private function calculateAverageRating($venueID) {
		$params = array($venueID);	
		
		$result = $this->db->rawQuery("
			SELECT
				AVG(rating) as averageRating
			FROM
				rating
			WHERE
				venue_venueID = ?
		", $params);
		
		return round($result[0]['averageRating'], 2);
	}
	
	private function countRatings($venueID) {
		$params = array($venueID);
		
		$result = $this->db->rawQuery("
			SELECT
				count(*) as numberOfRatings
			FROM
				rating
			WHERE
				venue_venueID = ?
		", $params);
				
		return $result[0]['numberOfRatings'];
	}
	
}

?>