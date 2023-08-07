<?php

/**
 * @file
 * Contains \Drupal\my_module\EventSubscriber\MyModuleRedirectSubscriber
 */
 
namespace Drupal\media_upload\EventSubscriber;
 
use Drupal\Core\Url;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
 
class MyModuleRedirectLanding implements EventSubscriberInterface {
 
  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    // This announces which events you want to subscribe to.
    // We only need the request event for this example.  Pass
    // this an array of method names
    return([
      KernelEvents::REQUEST => [
        ['redirectMyContentTypeNode'],
      ]
    ]);
  }
 
  /**
   * Redirect requests for landing node detail pages to node/123.
   *
   * @param GetResponseEvent $event
   * @return void
   */
  public function redirectMyContentTypeNode(RequestEvent $event) {
    $request = $event->getRequest();
 
    // This is necessary because this also gets called on
    // node sub-tabs such as "edit", "revisions", etc.  This
    // prevents those pages from redirected.
    if ($request->attributes->get('_route') !== 'entity.node.canonical') {
      return;
    }
 
    // Only redirect a certain content type.
    if ($request->attributes->get('node')->getType() !== 'landing') {
      return;
    }

    $base_url = $request->getBaseUrl();
    $request_uri = $request->getRequestUri(); // landing content type have structure web_url/landing/slug, convert to weburl/landingpage/slug
    $landing_slug = explode('landing/',$request_uri)[1];

    $url_redirect = $base_url . "/landingpage/" . $landing_slug;

    // This is where you set the destination.
    // $redirect_url = Url::fromUri('entity:node/39');
    $response = new RedirectResponse($url_redirect, 301);
    $event->setResponse($response);
  }
 
}