<?php
namespace develnext\task;

use develnext\util\AsyncTask;
use php\net\HttpClient;
use php\net\HttpResponse;

class RegistrationTask extends AsyncTask {
    /** @var HttpResponse */
    protected $response;

    /**
     * @param array $args
     */
    public function doInBackground(array $args = []) {
        $client = new HttpClient();
        $this->response = $client->get('http://ya.ru')->execute();
    }

    public function onPostExecute() {
        dump($this->response->getEntity()->toString());
    }
}
