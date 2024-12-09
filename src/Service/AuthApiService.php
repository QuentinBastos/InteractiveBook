<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AuthApiService
{

    private string $baseUrl;
    private string $apiSecret;

    public function __construct(
        private readonly HttpClientInterface $httpClient,
        string                               $baseUrl,
        string                               $apiSecret,
        string                               $projectId,
    )
    {
        $this->baseUrl = $baseUrl;
        $this->apiSecret = $apiSecret;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function generateText(string $prompt, array $conversation = []): array
    {
        $model = 'gpt-3.5-turbo';
        $maxTokens = 200;
        $conversation[] = ['role' => 'user', 'content' => $prompt];

        try {
            $response = $this->httpClient->request('POST', $this->baseUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiSecret,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => $model,
                    'messages' => $conversation,
                    'max_tokens' => $maxTokens,
                ],
            ]);

            $responseArray = $response->toArray();

            $conversation[] = [
                'role' => 'assistant',
                'content' => $responseArray['choices'][0]['message']['content']
            ];
            return ['conversation' => $conversation, 'response' => $responseArray];

        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }


    public function getApiSecret(): string
    {
        return $this->apiSecret;
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }
}