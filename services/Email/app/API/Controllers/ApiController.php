<?php

namespace Evaneos\REST\API\Controllers;

use Evaneos\REST\API\Converters\ApiResponseBuilder;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Tolerance\Operation\Callback;
use Tolerance\Operation\Runner\CallbackOperationRunner;
use Tolerance\Operation\Runner\ChainOperationRunner;
use Tolerance\Waiter\CountLimited;
use Tolerance\Waiter\ExponentialBackOff;
use Tolerance\Waiter\SleepWaiter;

class ApiController
{
    /**
     * @var ApiResponseBuilder
     */
    private $responseBuilder;

    /**
     * Constructor.
     *
     * @param ApiResponseBuilder $responseBuilder
     */
    public function __construct(ApiResponseBuilder $responseBuilder)
    {
        $this->responseBuilder = $responseBuilder;
    }

    /**
     * Route root.
     *
     * @return Response
     */
    public function root()
    {
        $waitStrategy = new CountLimited(
            new ExponentialBackOff(
                new SleepWaiter(),
                1
            ),
            10
        );

        $runner = new ChainOperationRunner([
            new CallbackOperationRunner(),
            $waitStrategy
        ]);

        $crawlGoogleOperation = new Callback(function() {
            $client = new Client();
            return $client->get('http://google.com');
        });

        /** @var \GuzzleHttp\Psr7\Response $response */
        $response = $runner->run($crawlGoogleOperation);
        $content = (string) $response->getBody();

        return new JsonResponse([
            'service' => 'email',
            'hostname' => gethostname()
        ]);
    }
}
