<?php
// src/Service/RestCountriesApi.php
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class RestCountriesApi
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Checks if a specific country exists
     *
     * @param string $countryName
     * @return boolean return true if country does exist, false if it doesn't
     */
    public function checkCountry(string $countryName): bool
    {
        //? Api request
        $response = $this->client->request(
            'GET',
            'https://restcountries.com/v2/name/' . $countryName
        );
        
        //? Checking status code
        $statusCode = $response->getStatusCode();
        
        return $statusCode === 200;
    }
}