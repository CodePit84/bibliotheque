<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

// use DateTime;
// use Symfony\Contracts\HttpClient\HttpClientInterface;

class CallApiServiceOld
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getBooks(): array
    {
        // return $this->getApi('FranceLiveGlobalData');
        $response = $this->client->request(
            'GET',
            // 'https://coronavirusapi-france.now.sh/FranceLiveGlobalData'    
            'https://www.googleapis.com/books/v1/volumes?q=1984'
        );

        return $response->toArray();
    }




    // public function getAllData(): array
    // {
    //     return $this->getApi('AllLiveData');
    // }

    // public function getAllDataByDate($date): array
    // {
    //     return $this->getApi('AllDataByDate?date=' . $date);
    // }

    // public function getDepartmentData($department): array
    // {
    //     return $this->getApi('LiveDataByDepartement?Departement=' . $department);
    // }

    // private function getApi(string $var)
    // {
    //     $response = $this->client->request(
    //         'GET',
    //         'https://coronavirusapi-france.now.sh/' . $var
    //     );

    //     return $response->toArray();
    // }
}
