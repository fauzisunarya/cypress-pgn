<?php

namespace Drupal\restapi_telkom\Middleware;

use Drupal;
use Drupal\Core\Database\Database;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

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

        // this URL not contins restapi keyword
        if (strpos($request->getRequestUri(), 'restapi') === false || strpos($request->getRequestUri(), 'login')) {
            return $this->httpKernel->handle($request, $type, $catch);
        };
        // whitelist API
        if (strlen(str_replace($whitelist, '', $request->getRequestUri())) !== strlen($request->getRequestUri())) {
            return $this->httpKernel->handle($request, $type, $catch);
        };

        $token = str_replace('Bearer ', '', $request->headers->get('authorization'));

        if (!is_null($token)) {
            $data = $this
                ->connection
                ->select('auth_tokens', 'at')
                ->fields('at', [
                    'token',
                    'expired_at'
                ])
                ->condition('token', $token)
                ->execute()
                ->fetchObject();

            if ($data) {
                if ($data->expired_at >= date('Y-m-d H:i:s')) {
                    // do auth check
                    return $this->httpKernel->handle($request, $type, $catch);
                }
    
                return $this->error('Token is expired');
            }
        }

        return $this->error('Token is not available');
    }

    public function error(string $message)
    {
        return Drupal::service('restapi_telkom.app_helper')->response([
            'message' => $message,
            'code' => 500,
            'status' => 'failed'
        ], 500);
    }

}