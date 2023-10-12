<?php

namespace App\Http\Controllers;

trait ApiController
{
    public function sendApiResponse($result, $statusCode)
    {
        return $result
            ->response()
            ->setStatusCode($statusCode);
    }
}