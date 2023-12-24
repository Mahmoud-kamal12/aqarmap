<?php

namespace App\Helper;

use Symfony\Component\HttpFoundation\JsonResponse;

trait Helper
{
    public static function Response($data = [], string $msg = "Successfully", int $status = 200): JsonResponse
    {
        return new JsonResponse([
            "msg" => $msg,
            "data" => $data
        ] , $status);
    }


}