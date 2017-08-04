<?php

namespace KunicMarko\SimpleCmsBundle\EventListener;

use Sonata\AdminBundle\Event\PersistenceEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpKernel\KernelInterface;
use KunicMarko\SimpleCmsBundle\Entity\Page;

class RoutingCacheHandler
{
    /** @var string */
    private $environment;
    /** @var string */
    private $cacheDirectory;
    /** @var Request */
    private $request;

    public function __construct(KernelInterface $kernel, RequestStack $requestStack)
    {
        $this->environment = $kernel->getEnvironment();
        $this->cacheDirectory = $kernel->getCacheDir();
        $this->request = $requestStack->getCurrentRequest();
    }

    /**
     * Ensures handler is responsible for this $object type
     *
     * @param $object
     * @return bool
     */
    private function isPage($object)
    {
        return $object instanceof Page;
    }

    /**
     * @param PersistenceEvent $event
     */
    public function onPersistUpdateRemove(PersistenceEvent $event)
    {
        $object = $event->getObject();
        if (!$this->isPage($object)) {
            return;
        }

        $this->clearRouteCache();
    }
    
    /**
     * Removes the routing cache each time there is a change in node tree
     * so that the new routes get cached
     */
    private function clearRouteCache()
    {
        $cacheFiles = $this->getCachedRoutingFiles();
        $isSuccess = $this->removeRoutingCache($cacheFiles);
        $this->addRemoveNotification($isSuccess);
    }

    /**
     * Returns array of routing cached files that need to be deleted based on environment
     */
    private function getCachedRoutingFiles()
    {
        if($this->environment == 'dev') {
            return [
                'appDevDebugProjectContainerUrlGenerator.php',
                'appDevDebugProjectContainerUrlGenerator.php.meta',
                'appDevDebugProjectContainerUrlMatcher.php',
                'appDevDebugProjectContainerUrlMatcher.php.meta'
            ];
        } elseif ($this->environment == 'prod') {
            return [
                'appProdUrlGenerator.php',
                'appProdUrlMatcher.php',
            ];
        }
    }

    /**
     *
     * @param array $cacheFiles
     * @return bool
     */
    private function removeRoutingCache($cacheFiles)
    {
        $success = true;

        foreach ($cacheFiles as $file) {
            $filePath = $this->cacheDirectory .'/'. $file;
            if (file_exists($filePath)) {
                unlink($filePath) ?: $success = false;
            } else {
                $success = false;
            }
        }

        return $success;
    }

    /**
     * Adds flash message to request bag
     * @param bool $isSuccess
     */
    private function addRemoveNotification($isSuccess)
    {
        /** @var FlashBag $flashBag */
        $flashBag = $this->request->getSession()->getFlashBag();

        if($isSuccess) {
            $flashBag->add("success", "Routing cache was removed successfully for " . $this->environment . " environment.");
        } else {
            $flashBag->add("error", "Error occurred while removing " . $this->environment . " environment routing cache.");
        }
    }
}
