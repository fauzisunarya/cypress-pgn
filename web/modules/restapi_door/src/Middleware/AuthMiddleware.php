<?php

namespace Drupal\restapi_door\Middleware;

use Drupal;
use Drupal\Core\Database\Database;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Drupal\restapi_door\Helper\JWT;

/**
 * FirstMiddleware middleware.
 */
class AuthMiddleware implements HttpKernelInterface {

    use StringTranslationTrait;

    /**
     * The kernel.
     *
     * @var \Symfony\Component\HttpKernel\HttpKernelInterface
     */
    protected $httpKernel;

    protected $connection;

    /**
     * Constructs the FirstMiddleware object.
     *
     * @param \Symfony\Component\HttpKernel\HttpKernelInterface $http_kernel
     *   The decorated kernel.
     */
    public function __construct(HttpKernelInterface $http_kernel) {
        $this->httpKernel = $http_kernel;
        $this->connection = Database::getConnection();
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = TRUE) {
        // setting core
        $whitelist = array('media_render');

        $token = str_replace('Bearer ', '', $request->headers->get('authorization'));
        
        if (!is_null($token)) {
            $jwt = new JWT();
            $result = $jwt->authorize($request->headers->get('Authorization'));
            if ($result->code != $result::STATUS_SUCCESS) {
                return Drupal::service('restapi_door.app_helper')->response([
                    'info' => $result->info,
                    'code' => $result->code,
                    'status' => 'failed'
                ], $result->status);
            }
            
            $result->data->allowed_roles = ((array)((array)$result->data)['https://hasura.io/jwt/claims'])['x-hasura-allowed-roles'];
            
            $request->attributes->set('user', (array)$result->data);

            return $this->httpKernel->handle($request, $type, $catch);
        }

        return $this->error('Token is not available');
    }

    public function error(string $message)
    {
        return Drupal::service('restapi_door.app_helper')->response([
            'info' => $message,
            'code' => 500,
            'status' => 'failed'
        ], 500);
    }

}