<?php

namespace App\AppRinger;
use SKAgarwal\GoogleApi\PlacesApi;
use App\Config\AppConfig;

class GooglePlace
{
    public static function getPlaces($q)
    {
        $googlePlaces = new PlacesApi(AppConfig::getGoogleApiKey());
        $response = $googlePlaces->placeAutocomplete($q);
        return $response;
    }
}
