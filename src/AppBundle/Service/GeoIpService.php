<?php

namespace AppBundle\Service;

use GeoIp2\Database\Reader;

class GeoIpService
{
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