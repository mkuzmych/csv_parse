<?php 
		
require_once('IpStack.php');
require_once('GeoNames.php');

class CsvParse
{
	/**
	 * Get formated data for cals
	 * @param $cals array of calls
	 * @return $result formated array for table
	 *	'count_same_continent'
	 *	'duration_same_continent'
	 * 	'count_all'
	 *	'duration_all'
	 */
	public function getData(array $cals)
	{

		// for quiq response - need bulk operations with subscriptions
		$ipstack = new IpStack();
		$ip_continents = array();

		// get Bulk tlds continent
		$geonames = new GeoNames();
		$code_continents = $geonames->getTldsContinent();

		foreach ($cals as $cal) {

			// 0 - Customer ID
			// 1 - Call Date
			// 2 - Duration in seconds
			// 3 - Dialed Phone Number
			// 4 - Customer IP that initiated the call. 

			// get phone continent
			foreach($code_continents as $code=>$continent){
				if(strpos($cal[3], strval($code)) === 0){	
					$current_phone_continent = $continent;
					break;
				}
			}

			// get current continent for ip - minimize get request for api
			if(empty($ip_continents[$cal[4]])){
				$ip_continents[$cal[4]] = $ipstack->getContinentByIp($cal[4]);	
			}
			$current_ip_continent = $ip_continents[$cal[4]];
			
			// same continent data	
			$same_continent_count = $current_phone_continent == $current_ip_continent ? 1 : 0;
			$same_continent_duration = $current_phone_continent == $current_ip_continent ? $cal[2] : 0;

			// create or update exist result array for customer id
			if(empty($result[$cal[0]])){
				$result[$cal[0]] = [
					'count_same_continent' => $same_continent_count,
					'duration_same_continent' => $same_continent_duration,
					'count_all' => 1,
					'duration_all' => $cal[2],
				];
			} else {
				$result[$cal[0]]['count_same_continent'] = $result[$cal[0]]['count_same_continent'] + $same_continent_count;
				$result[$cal[0]]['duration_same_continent'] = $result[$cal[0]]['duration_same_continent'] + $same_continent_duration;
				$result[$cal[0]]['count_all'] = $result[$cal[0]]['count_all'] + 1;
				$result[$cal[0]]['duration_all'] = $result[$cal[0]]['duration_all'] + $cal[2];
			}
		}

		return $result;
	}

	/**
	 * Get table for user show
	 * @param $cals array of calls
	 */
	public function getTableHtml(array $cals)
	{
		$data = $this->getData($cals);
		
		echo "<table><tr><th>CustomerId</th><th>Number of calls within the same continent</th><th>Total Duration of calls within the same continent</th><th>Total number of all calls</th><th>The total duration of all calls</th></tr>";
		
		foreach($data as $customer_id=>$customer_data){
			echo "<tr>";
				echo "<td>".$customer_id."</td>";
				foreach($customer_data as $value){
					echo "<td>".$value."</td>";
				}
			echo "</tr>";
		}
		
		echo "</table>";
	}
}				
?>