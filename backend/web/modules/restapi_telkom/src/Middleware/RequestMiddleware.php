<?php

namespace Drupal\restapi_telkom\Middleware;

use Drupal;
use Exception;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * FirstMiddleware middleware.
 */
class RequestMiddleware implements HttpKernelInterface {

    use StringTranslationTrait;

    /**
     * The kernel.
     *
     * @var \Symfony\Component\HttpKernel\HttpKernelInterface
     */
    protected $httpKernel;

    /**
     * Constructs the FirstMiddleware object.
     *
     * @param \Symfony\Component\HttpKernel\HttpKernelInterface $http_kernel
     *   The decorated kernel.
     */
    public function __construct(HttpKernelInterface $http_kernel) {
        $this->httpKernel = $http_kernel;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = TRUE) {
        // this URL not contins restapi keyword
        if (strpos($request->getRequestUri(), 'restapi') === false) {
            return $this->httpKernel->handle($request, $type, $catch);
        };

        // check is current URL is available?
        if (!$this->checkUrl($request)) {
            return $this->error('URL Is Not Completed / not found, try check uuid or see documentation again');
        };

        // get current method
        $method       = $request->getMethod();
        // get raw data body
        $json = preg_replace("#(/\*([^*]|[\r\n]|(\*+([^*/]|[\r\n])))*\*+/)|([\s\t]//.*)|(^//.*)#", '', $request->getContent());
        $list_request = json_decode($json, true);        

        if (!empty($list_request)) :
            // loop based on available request data
            foreach ($list_request as $index => $value) {
                // insert into parameter bag based on request method
                if ($method === 'POST') :
                    $request->request->set($index, $value);   
                else:
                    $request->query->set($index, $value);
                endif;
            };

        endif;
        
        return $this->httpKernel->handle($request, $type, $catch);
    }

    public function checkUrl($request)
    {
        $match = 0;
        $file_contents = file_get_contents(DRUPAL_ROOT . '/modules/restapi_telkom/restapi_telkom.routing.yml');
        $pathData = Yaml::parse($file_contents);

        foreach (array_column($pathData, 'path') as $index => $routes) {
            // clear bracket variable
            $routes = preg_replace('/{.*/is', '', $routes);

            if (stristr($request->getPathInfo(), $routes)) {
                // trim the path based on current route
                $currentPath = str_replace([$routes, '/'], '', $request->getPathInfo());
                // current url not found
                if (empty($currentPath)) :
                    // this is full url
                    if (strlen($request->getPathInfo()) === strlen($routes)) {
                        $match++;
                        // stop check after first match
                        break;
                    }
                    else{
                        return false;
                    };
                else:
                
                    // ignored
                    $ignored = ['/restapi/v1/form/', '/restapi/v1/ebis/'];
                    foreach ($ignored as $val) {
                      if (str_starts_with($_SERVER['REQUEST_URI'], $val)) {
                          $match++;
                          // stop check after first match
                          break;
                      }
                    }

                    // this is uuid or using id or md5 (for request_token in API request)
                    if(substr($routes, -1) === '/' && $this->isValidUuid($currentPath) || preg_match("/^\d+$/", $currentPath) || preg_match("/^[0-9a-f]{32}$/", $currentPath)) {
                        $match++;
                        // stop check after first match
                        break;
                    }
                    // this URL is not correct one search another
                    else{
                        continue;
                    };
                endif;

                // stop check after first match
                break;
            };
        };

        return $match > 0 ? true : false;
    }

    public function isValidUuid( $uuid )
    {
        if (!is_string($uuid) || (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/', $uuid) !== 1)) {
            return false;
        }
        return true;
    }

    public function error(string $message)
    {
        return Drupal::service('restapi_telkom.app_helper')->response([
            'code' => 404,
            'status' => 'failed',
            'message' => $message,
        ], 500);
    }
}