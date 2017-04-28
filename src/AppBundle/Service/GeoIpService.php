<?php

namespace AppBundle\Service;

use GeoIp2\Database\Reader;

class GeoIpService
{
    /**
     * @param integer $ip
     * @return string
     */
    public function getCity($ip)
    {
        try {
            $reader = new Reader('GeoLite2-City.mmdb');
            $record = $reader->city($ip);
        }
        catch (\Exception $e) {
            return 'Brno,CZ';
        }

        return $record->city->name.','.$record->country->isoCode;
    }

}