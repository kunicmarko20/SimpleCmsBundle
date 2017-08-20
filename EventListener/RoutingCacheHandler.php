<?php

namespace KunicMarko\SimpleCmsBundle\EventListener;

use Sonata\AdminBundle\Event\PersistenceEvent;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpKernel\KernelInterface;
use KunicMarko\SimpleCmsBundle\Entity\Page;
use Psr\Log\LoggerInterface;

class RoutingCacheHandler
{
    /** @var string */
    private $cacheDirectory;
    /** @var Request */
    private $request;
    /** @var Filesystem  */
    private $fileSystem;
    /** @var LoggerInterface  */
    private $logger;

    public function __construct(
        KernelInterface $kernel,
        RequestStack $requestStack,
        Filesystem $filesystem,
        LoggerInterface $logger
    ) {
        $this->cacheDirectory = $kernel->getCacheDir();
        $this->request = $requestStack->getCurrentRequest();
        $this->fileSystem = $filesystem;
        $this->logger = $logger;
    }

    /**
     * Ensures handler is responsible for this $object type
     *
     * @param $object
     * @return bool
     */
    protected function isExpectedObject($object)
    {
        return $object instanceof Page;
    }

    /**
     * @param PersistenceEvent $event
     */
    public function onPersistUpdateRemove(PersistenceEvent $event)
    {
        $object = $event->getObject();

        if (!$this->isExpectedObject($object)) {
            return;
        }

        $this->clearRouteCache();
    }
    
    /**
     * Removes the routing cache each time there is a change in page
     * so that the new routes get cached
     */
    public function clearRouteCache()
    {
        $isSuccess = $this->removeRoutingCache();
        $this->addRemoveNotification($isSuccess);
    }

    /**
     *
     * @return bool
     */
    protected function removeRoutingCache()
    {
        try {
            $finder = new Finder();

            /** @var File $file */
            foreach ($finder->files()->depth('== 0')->in($this->cacheDirectory) as $file) {
                if (preg_match('/UrlGenerator|UrlMatcher/', $file->getFilename()) == 1) {
                    $this->fileSystem->remove($file->getRealpath());
                }
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            return false;
        }

        return true;
    }

    /**
     * Adds flash message to request bag
     * @param bool $isSuccess
     */
    protected function addRemoveNotification($isSuccess)
    {
        /** @var FlashBag $flashBag */
        $flashBag = $this->request->getSession()->getFlashBag();

        if ($isSuccess) {
            return $flashBag->add(
                "success",
                "Routing cache was removed successfully."
            );
        }

        return $flashBag->add(
            "error",
            "Error occurred while removing routing cache."
        );
    }
}
