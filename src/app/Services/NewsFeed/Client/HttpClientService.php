<?php

namespace App\Services\NewsFeed\Client;

use App\Services\NewsFeed\Client\Interfaces\NewsFeedProvider;
use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response as ResponseCode;

class HttpClientService
{
    protected string $apiName;

    protected string $apiUrl;

    protected array $apiHeaders;
    public function __construct(
        NewsFeedProvider $provider
    ) {
        $this->apiName = $provider->getHeaderName();
        $this->apiUrl = $provider->getUrl();
        $this->apiHeaders = $provider->getHeaders();
    }
    public function makeRequest(string $action = 'get', array $params = []): array
    {
        try {
            $response = Http::acceptJson()
                ->retry(3, 100)
                ->withQueryParameters($this->apiHeaders)
                ->$action($this->apiUrl, $params);

            if ($response->failed()) {
                throw new Exception('Error occurred while fetching the data.');
            }

            return $this->decodeResponse($response);
        } catch (Exception $error) {
            return $this->decodeErrorResponse($error);
        }
    }

    /**
     * @throws \JsonException
     */
    public function decodeResponse(Response $response): array
    {
        logger()->channel()->info("[{$this->apiName} API - Response] - " . $this->apiUrl, [
            'PID' => getmypid(),
            'response' => $response->ok(),
        ]);

        return ['status' => ResponseCode::HTTP_OK, 'data' => json_decode($response->body(), true, 512, JSON_THROW_ON_ERROR)];
    }

    public function decodeErrorResponse(Exception $error): array
    {
        logger()->channel()->error("[{$this->apiName} API - Request] - " . $this->apiUrl, [
            'PID' => getmypid(),
            'message' => $error->getMessage(),
        ]);

        return ['status' => ResponseCode::HTTP_BAD_REQUEST, 'error' => 'Unexpected error: ' . $error->getMessage()];
    }
}
