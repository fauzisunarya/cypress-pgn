<?php

/*
 * This file is part of asm89/stack-cors.
 *
 * (c) Alexander <iam.asm89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Drupal\restapi_telkom\Middleware;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Asm89\Stack\CorsService;

class CorsMiddleware implements HttpKernelInterface
{
    /**
     * @var \Symfony\Component\HttpKernel\HttpKernelInterface
     */
    private $app;

    /**
     * @var \Asm89\Stack\CorsService
     */
    private $cors;

    private $defaultOptions = array(
        'allowedHeaders'         => array(),
        'allowedMethods'         => array(),
        'allowedOrigins'         => array(),
        'allowedOriginsPatterns' => array(),
        'exposedHeaders'         => false,
        'maxAge'                 => false,
        'supportsCredentials'    => false,
    );

    public function __construct(HttpKernelInterface $app, array $options = array())
    {
        $this->app  = $app;
        $this->cors = new CorsService(array_merge($this->defaultOptions, $options));
    }

    public function handle(Request $request, $type = HttpKernelInterface::MASTER_REQUEST, $catch = true)
    {

        // submit form embedded from other website
        if ($_SERVER['REQUEST_URI']==='/api/project/form_post/ajax' && $_SERVER['REQUEST_METHOD']==='POST') {
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
                        $_POST = array_merge($_POST, [$index => $value]);
                    else:
                        $request->query->set($index, $value);
                        $_GET = array_merge($_GET, [$index => $value]);
                    endif;
                };

            endif;

            // continue, will be checked in controller
            $response = $this->app->handle($request, $type, $catch);
            return $this->cors->addActualRequestHeaders($response, $request);
        }
        else if (!$this->cors->isCorsRequest($request)) {
            return $this->app->handle($request, $type, $catch);
        }

        if ($this->cors->isPreflightRequest($request)) {
            return $this->cors->handlePreflightRequest($request);
        }

        $origin = $request->headers->get('Origin');
        $url = explode('://', $_ENV['APP_URL']);
        if (count($url)===2) {
            $domain = $url[1];
        }

        if (!empty($domain)) {
            // allow access from subdomain
            if (strpos($origin, $domain) !== false) {
                // allow
            }
            else{
                $entity = \Drupal::entityTypeManager()->getStorage('node');
                $query = $entity->getQuery();
                $ids = $query->condition('status', 1)
                    ->condition('type', 'cors')#type = bundle id (machine name)
                    ->execute();
          
                if (!empty($ids)) {
                    $allowedOrigin = array_map(function($each){
                        return strtolower($each->label());
                    }, $entity->loadMultiple($ids));
                    
                    $is_allow = false;
                    foreach ($allowedOrigin as $value) {
                        if($value === $origin){
                            $is_allow = true;
                            break;
                        }
                    }
    
                    // if not in allowed list
                    if ( ! $is_allow ) {
                        return new Response('Not Allowed (CORS)', 403);
                    }
                }
            }
        }

        $response = $this->app->handle($request, $type, $catch);

        return $this->cors->addActualRequestHeaders($response, $request);
    }
}
