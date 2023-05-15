<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class CallApiService
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * This function call Google API with a variable from search GoogleBooksController
     * 
     * @param string $variable
     * @return array
     */
    public function getBooks(string $variable): array
    {
        $response = $this->client->request(
            'GET',   
            'https://www.googleapis.com/books/v1/volumes?q=' . $variable
        );

        return $response->toArray();
    }
}
