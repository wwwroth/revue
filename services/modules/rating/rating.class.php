<?php

/**
 * Rating class for rating methods.
 * @package - revueApi
 */
 
class rating extends venue
{
	public function __construct() {
		
	}
	
	public function ratingAdd($params) {
		/**
		 *	Check if venueId is numeric and has a value.
		 *	Check if rating has a value.
		*/
		if(strlen($params['venueId']) == 0 || !is_numeric($params['venueId'])) return false;
		if(strlen($params['rating']) == 0) return false;
		
		/*
		 *	Prepare mysqli insert values.
		*/
		$insertData = array(
			'venue_venueID' => $params['venueId'],
			'rating' => $params['rating'],
		);
		
		/*
		 *	Create new database object and run query.
		*/
		$this->mysqliConnect();	
		if($this->db->insert('rating', $insertData)) {
			return array("status" => "OK");
		} else {
			return false;
		}
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