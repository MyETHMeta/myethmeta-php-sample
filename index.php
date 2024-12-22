<?php

// Set CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

// on GET, redirect to http://localhost:1234
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    header('Location: http://localhost:1234');
    exit;
}

require 'vendor/autoload.php';

use SWeb3\SWeb3;
use SWeb3\SWeb3_Contract;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$sweb3 = new SWeb3('https://rpc.gnosischain.com');
$sweb3->chainId = 100;
$sweb3->setPersonalData($_ENV['SIGNER_ADDRESS'], $_ENV['SIGNER_PRIVATE_KEY']);

$abi = file_get_contents("myethmeta-abi.json");
$contract = new SWeb3_contract($sweb3, '0x63ba8dfaeba09a63c1bcb47a46229f14707af995', $abi);

/*
$res = $contract->call('getMetaURI', ['0x5e8ba2ae8d293e73248448ebe39840aba6bd2269']); 
var_dump($res);
*/

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rawData = file_get_contents("php://input");
    $jsonData = json_decode($rawData, true);
    $message = $jsonData['message'];
    $signature = $jsonData['signature'];
    $extra_data = ['nonce' => $sweb3->personal->getNonce()];
    $result = $contract->send('setMetaURIMetaTX', [$message['owner'], $message['uri'], $message['nonce'], $signature],  $extra_data);
    var_dump($result);
    exit;
}
