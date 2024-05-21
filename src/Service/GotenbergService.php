<?php
namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\{
    TransportExceptionInterface,
    ClientExceptionInterface,
    RedirectionExceptionInterface,
    ServerExceptionInterface
};

class GotenbergService
{
    private HttpClientInterface $client;
    private ParameterBagInterface $params;

    public function __construct(HttpClientInterface $client, ParameterBagInterface $params)
    {
        $this->client = $client;
        $this->params = $params;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function generatePdfFromUrl(string $url): string
    {

        $response = $this->client->request(
            'POST',
            $this->params->get('gotenberg_url'),
            [
                'headers' => [
                    'Content-Type' => 'multipart/form-data',
                ],
                'body' => [
                    'url' => $url,
                ]
            ]
        );

        if ($response->getStatusCode() !== 200) {
            // Gérer l'erreur ici
            throw new \Exception('Failed to generate PDF from URL.');
        }

        $pdfContent = $response->getContent();

        return $pdfContent;
    }
}