<?php

/*
 * This file is part of asm89/stack-cors.
 *
 * (c) Alexander <iam.asm89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Drupal\media_upload\Middleware;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LandingMiddleware implements HttpKernelInterface
{
    /**
     * @var \Symfony\Component\HttpKernel\HttpKernelInterface
     */
    private $app;

    public function __construct(HttpKernelInterface $app)
    {
        $this->app  = $app;
    }

    public function handle(Request $request, $type = HttpKernelInterface::MASTER_REQUEST, $catch = true)
    {

      // if not url landingpage
      if ( ! str_starts_with($_SERVER['REQUEST_URI'], '/landingpage') && $_SERVER['REQUEST_METHOD']==='GET' ) {

        $arr = explode('://',$_ENV['APP_URL']); // ex : https://ami-dev.telkom.co.id
        if (count($arr)===2) {
          $domainAmi = $arr[1]; // ex : ami-dev.telkom.co.id

          // ex : ami-dev.telkom.co.id (regular)
          // ex : subdomain.ami-dev.telkom.co.id (using subdomain)
          // ex : otherdomain.com (using custom domain)
          $host = !empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];

          // if :
          if (str_ends_with($host, $domainAmi) && strlen($host) !== strlen($domainAmi)) {
            // using subdomain & not accessing landing url
            return \Drupal::service('media_upload.page_helper')->redirect($arr[0] . '://' . $host . '/landingpage');
          }
          else if ( ! str_ends_with($host, $domainAmi) ) {
            // using custom domain & not accessing landing url
            return \Drupal::service('media_upload.page_helper')->redirect($arr[0] . '://' . $host . '/landingpage');
          }
          
        }

      }

      // continue
      return $this->app->handle($request, $type, $catch);
    }
}
