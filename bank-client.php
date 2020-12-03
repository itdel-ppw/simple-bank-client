<?php
require_once "vendor/autoload.php";

use GuzzleHttp\Client;

// Prepare an HTTP Client object.
// The bearer token identifies the bank account
$client = new Client([
    'base_uri' => 'http://localhost',
    'timeout'  => 2.0,
    'headers' => [
        'Authorization' => 'Bearer aflBEUbZ3CBVhPNJmb3Gkbg3QUU7Fu2j8jFGlg2p'
    ]
]);

// See the detail of an account.
// The result is different when the request is authenticated
// and when it is not.
// See [the documentation](https://github.com/itdel-ppw/simple-bank/docs/account-detail.md) for detail information.
$response = $client->request('GET', '/accounts/jaksem');

if ($response->getStatusCode() !== 200) {
    echo("something went wrong.");
    die;
}

// Since the response is JSON encoded, it has to be decoded.
$account = json_decode($response->getBody()->getContents());
print_r($account);

// Retrieve all transactions related to the customer
// by utilizing HATEOAS info from the previous result.
// See [the documentation](https://github.com/itdel-ppw/simple-bank/docs/transaction-list.md) for detail information.
$response = $client->request('GET', $account->data->transactions_uri);

if ($response->getStatusCode() !== 200) {
    echo("something went wrong.");
    die;
}

// Since the response is JSON encoded, it has to be decoded.
$transactions = json_decode($response->getBody()->getContents());
print_r($transactions);

// Retrieve a transaction related to the customer
// by utilizing HATEOAS info from the previous result.
// See [the documentation](https://github.com/itdel-ppw/simple-bank/docs/transaction-detail.md) for detail information.
$response = $client->request('GET', $transactions->data[0]->transaction_uri);

if ($response->getStatusCode() !== 200) {
    echo("something went wrong.");
    die;
}

$transaction = json_decode($response->getBody()->getContents());
print_r($transaction);

// Write your own codes...