<?php

namespace ApiBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BaseController
 *
 * @package ApiBundle\Controller
 */
abstract class BaseController extends Controller
{
    /**
     * @return JsonResponse
     */
    protected function authenticationFailed()
    {
        return $this->error(['Authentication Failed'], 403);
    }

    /**
     * @return JsonResponse
     */
    protected function resourceNotFound()
    {
        return $this->error(['Not Found'], 404);
    }

    /**
     * @param array $messages
     * @param int   $code
     *
     * @return JsonResponse
     */
    protected function error(array $messages=[], $code=400)
    {
        return new JsonResponse([
            'messages' => $messages
        ], $code);
    }

    /**
     * @param array $data
     * @param int   $code
     *
     * @return JsonResponse
     */
    protected function response($data=[], $code=200)
    {
        if ($data instanceof \JsonSerializable) {
            $response = new Response(json_encode($data));
            $response->headers->set('Content-Type', 'application/json');
            $response->setStatusCode($code);

            return $response;
        }

        return new JsonResponse($data, $code);
    }
}