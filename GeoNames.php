<?php 

class GeoNames
{
    public function __construct()
    {
        $this->url = 'http://download.geonames.org/export/dump/countryInfo.txt';
    }

    /**
     * Get continents telephone codes
     * @return array tlds_continent
     */
    public function getTldsContinent()
    {
        $result = file_get_contents($this->url, false);

        $tlds_continent = array();

        foreach(preg_split("/((\r?\n)|(\r\n?))/", $result) as $line){
            if (strpos($line, '#') !== 0 && $line) {
                $formated_array = explode("\t", $line);
                if(preg_replace('/[^0-9]/', '', $formated_array[12])){
                    $tlds_continent[preg_replace('/[^0-9]/', '', $formated_array[12])] = $formated_array[8];
                }
            }
        }

        return $tlds_continent;
    }
}

?>