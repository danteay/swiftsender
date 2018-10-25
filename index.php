<?php

require __DIR__ . '/vendor/autoload.php';

use Slim\Http\Request;
use Slim\Http\Response;
use Libraries\Email;

$app = new \Slim\App;

$app->post("/sendmail", function(Request $req, Response $res) {
    $body = $req->getParsedBody();

    try {
        $client = new Email(
            $body['user'],
            $body['password']
        );

        $client->createMessage($body['subject'], $body['user'], $body['to'])
            ->setBody($body['body'])
            ->send();

        return $res->withJson([
            'code' => 200,
            'message' => 'Ok',
            'status' => 'success'
        ]);
    } catch (\Exception $e) {
        return $res->withJson([
            'code' => 500,
            'message' => $e->getMessage(),
            'status' => 'error'
        ], 500);
    }
});

$app->run();