<?php

declare(strict_types=1);

namespace App;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\StreamFactory;

final readonly class HttpClient implements ClientInterface
{
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $content = file_get_contents((string) $request->getUri());
        if ($content === false) {
            /** @psalm-suppress PossiblyNullArgument */
            /** @psalm-suppress PossiblyNullArrayAccess */
            throw new RuntimeException(error_get_last()["message"]);
        }

        return (new ResponseFactory())->createResponse()->withBody(
            (new StreamFactory())->createStream($content)
        );
    }
}
