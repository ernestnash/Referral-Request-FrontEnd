<?php

namespace App\utils;
use Illuminate\Support\Facades\Http;


class KMHFLService
{
    protected $mykmhflkey = "";
    public function __construct()
    {
        $token = KmhflTokenGenerator::tokenGenerator();
        $this->mykmhflkey = $token;

    }

    public function facilitiesFromServiceId($serviceId){
        $response = $this->facilityIdsFromService($serviceId);
       $results = $response['results'];

        $facilities = [];
        $facilityIds = [];
        foreach ($results as $item) {
            $facilityId = $item['facility'];
            // Use the facility ID to fetch facility information
            $facilityInfo = $this->facilitiesFromFacilityIds($facilityId);
            $facilities[] = $facilityInfo;
            $facilityIds = $facilityId;
        }

        return $facilities;
    }


    public function facilitiesFromFacilityIds($facilityId){
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->mykmhflkey,
        ])
            ->get('http://api.kmhfltest.health.go.ke/api/facilities/facilities', [
                'format' => 'json',
                'id' => $facilityId,
                'county_name' => 'Mombasa'
            ]);

        if ($response->failed()) {
            $error = $response->body();
            // Handle the error
        } else {
            $jsonResponse = $response->json();
            return $jsonResponse;
        }

    }


    public function facilityIdsFromService($serviceId){
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.$this->mykmhflkey,
        ])
            ->get('http://api.kmhfltest.health.go.ke/api/facilities/facility_services', [
                'format' => 'json',
//                'service' => $serviceId,
                'service' => 'd9703f2f-2c5e-48a1-8b88-679796e4fea5',
            ]);

        if ($response->failed()) {
            $error = $response->body();
            // Handle the error
        } else {
            return $response->json();
        }
    }





    public function countyIdFromName($county_name){

        $response = Http::withHeaders([
            'Authorization' => 'Bearer dP6McfKHK3sbOUwCrrVcmrcnDxqlB9',
        ])
            ->get('http://api.kmhfltest.health.go.ke/api/common/counties', [
                'format' => 'json',
                'name' => 'Nairobi',
            ]);

        if ($response->failed()) {
            $error = $response->body();
        } else {
            return $response->json();
        }

    }

}
