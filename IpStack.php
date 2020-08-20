<?php 
	
class IpStack
{
	function __construct()
	{
       $this->apiKey = 'd9f000dbc0237078dfb39bf8033d244c';
       $this->url = 'http://api.ipstack.com/';
    }

    /**
     * Get continent by ip
     * @return string continent_code
     */
    public function getContinentByIp(string $ip)
    {
		$json = file_get_contents($this->url.$ip.'?access_key='.$this->apiKey.'', false);
		$api_result = json_decode($json, true);

		return $api_result['continent_code'];
    }
}

?>