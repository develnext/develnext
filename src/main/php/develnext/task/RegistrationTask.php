<?php
namespace develnext\task;

use develnext\client\ServiceClient;
use develnext\Manager;
use develnext\util\AsyncTask;
use php\format\JsonProcessor;
use php\net\HttpClient;

class RegistrationTask extends AsyncTask {
    /** @var ServiceClient */
    protected $client;

    /**
     * @param array $args
     */
    public function doInBackground(array $args = []) {
        $this->client->call('account/register', $args);
        $r = $this->client->asJSend();
    }

    public function onPostExecute() {
        Manager::getInstance()->flash($this->response->getEntity()->toString());
    }
}
