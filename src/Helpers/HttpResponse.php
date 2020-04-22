<?php
namespace App\Helpers;

use Symfony\Component\HttpFoundation\Response;
use App\Serializers\EntitySerializer;

class HttpResponse
{
    public static $headers = [
    'Content-Type' => 'application/json'
    ];

    public function send($data, $responseCode = Response::HTTP_OK, $shouldSerialize = true) :Response
    {
        return new Response(
            $shouldSerialize ? EntitySerializer::serialize($data) : $data,
            $responseCode,
            self::$headers
        );
    }
}
