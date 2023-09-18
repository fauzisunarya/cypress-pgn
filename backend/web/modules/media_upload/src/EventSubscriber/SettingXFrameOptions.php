<?php

namespace Drupal\media_upload\EventSubscriber;

use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpFoundation\Request;

/**
 * Subscribing an event.
 */
class SettingXFrameOptions implements EventSubscriberInterface {
  /**
   * Executes actions on the respose event.
   *
   * @param \Symfony\Component\HttpKernel\Event\FilterResponseEvent $event
   *   Filter Response Event object.
   */
  public function onKernelResponse(ResponseEvent $event) {
    // $request = $event->getRequest();
    // $response = $event->getResponse();
    
    // remove header to allow embed landing page in iframe
    // if ( preg_match("/^\/landingpage\/.+/", $request->getRequestUri()) ) {
    //   $response->headers->remove('X-Frame-Options');
    // }
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    // Adds the event in the list of KernelEvents::RESPONSE with priority -10.
    $events[KernelEvents::RESPONSE][] = ['onKernelResponse', -10];
    return $events;
  }

}
