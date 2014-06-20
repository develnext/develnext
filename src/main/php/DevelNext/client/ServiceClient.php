<?php
namespace develnext\client;

use php\format\JsonProcessor;
use php\net\HttpClient;
use php\net\HttpEntity;
use php\net\HttpResponse;

/**
 * Class ServiceClient
 * @package develnext\client
 */
class ServiceClient {
    protected $json;
    protected $httpClient;

    /** @var HttpResponse */
    protected $response;

    public function __construct() {
        $this->json = new JsonProcessor(JsonProcessor::DESERIALIZE_AS_ARRAYS);
        $this->httpClient = new HttpClient();
        $this->httpClient->setHeaders(['Content-Type' => 'application/json']);
    }

    /**
     * @param $url
     * @param array $params
     * @return array|null
     */
    public function call($url, $params = []) {
        $response = $this->httpClient
            ->post($url)
            ->execute(new HttpEntity($this->json->format($params)));

        $this->response = $response;

        if ($this->response->getStatusCode() == 200)
            return $this->json->parse($response->getEntity()->toString('UTF-8'));

        return null;
    }

    /**
     * @throws \Exception
     * @return \php\net\HttpResponse
     */
    public function getResponse() {
        if (!$this->response)
            throw new \Exception("Client is not called yet");

        return $this->response;
    }

    public function isOk() {
        return $this->getResponse()->getStatusCode() == 200;
    }

    public function asJSend() {
        if (!$this->isOk())
            throw new \Exception("Cannot get JSend with non-success answer");

        $data = $this->json->parse($this->getResponse()->getEntity()->toString('UTF-8'));
        return new JSend($data['status'], $data['data'], $data['code']);
    }
}
