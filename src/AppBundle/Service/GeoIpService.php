<?php

namespace AppBundle\Service;

use GeoIp2\Database\Reader;

class GeoIpService
{
    public function getCity($ip)
    {
        $reader = new Reader('GeoLite2-City.mmdb');

//        $record = $reader->city($ip);
        $record = $reader->city('46.39.178.136');

        return $record->city->name;
    }

}