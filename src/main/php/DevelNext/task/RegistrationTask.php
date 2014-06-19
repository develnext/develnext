<?php
namespace develnext\task;

use develnext\Manager;
use develnext\util\AsyncTask;
use php\net\HttpClient;
use php\net\HttpEntity;
use php\net\HttpResponse;

class RegistrationTask extends AsyncTask {
    /** @var HttpResponse */
    protected $response;

    /**
     * @param array $args
     */
    public function doInBackground(array $args = []) {
        $client = new HttpClient();
        $client->setHeaders(['Content-Type' => 'application/json']);

        $this->response = $client->post('http://third.muloqot.uz:8080/api/other/stats/subscribers')
            ->execute(new HttpEntity('{"max": 10, "tag": "Gap"}'));
    }

    public function onPostExecute() {
        Manager::getInstance()->flash($this->response->getEntity()->toString());
    }
}
