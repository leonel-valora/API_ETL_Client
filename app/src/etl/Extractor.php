<?php

namespace App\ETL;

use App\ETL\Exceptions\ExtractorException;
use GuzzleHttp\Client;
use Symfony\Component\Yaml\Yaml;

class Extractor
{
    private $config;

    public function __construct($configPath)
    {
        $this->config = Yaml::parseFile($configPath);
    }

    public function makeRequest()
    {
        $config = ['base_uri' => $this->config['base_uri']];
        $config = array_filter($config);

        $httpClient = new Client($config);

        $url = $this->config['endpoint'];
        $method = $this->config['method'] ?? 'GET';
        $params = $this->config['params'] ?? [];
        $query = $this->config['query_string'] ?? [];
        $headers = $this->config['headers'] ?? [];
        $body = $this->config['body'] ?? [];

        foreach ($params as $key => $value) {
            $url = str_replace("{{$key}}", $value, $url);
        }

        $options = [
            'query' => $query,
            'headers' => $headers,
        ];

        if ($method === 'GET') {
            $response = $httpClient->request($method, $url, $options);
        } elseif ($method === 'POST') {
            $options['body'] = $body;
            $response = $httpClient->request($method, $url, $options);
        }

        $statusCode = $response->getStatusCode();
        $content = $response->getBody()->getContents();

        if ($statusCode === 200) {
            return json_decode($content, true);
        } else {
            throw new ExtractorException("Request failed with status code: $statusCode");
        }
    }
}
