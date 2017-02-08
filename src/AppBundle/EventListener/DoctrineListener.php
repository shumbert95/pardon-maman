<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\Contest;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DoctrineListener {

    /**
     * @var string
     */
    private $privateDir;
    private $container;

    /**
     * @param string $privateDir
     */
    public function __construct($privateDir, ContainerInterface $container) {
        $this->privateDir = $privateDir;
        $this->container = $container;
    }

    public function preUpdate(LifecycleEventArgs $args) {
//        $entity = $args->getEntity();
//        if ($entity instanceof Contest) {
//            $entity->setDateUpdate(new \DateTime(date('Y-m-d H:i:s')));
//            if ($entity->getStatus() == 1) {
//                $onlineContests = $this->container->get('doctrine')->getRepository('AppBundle:Contest')->findByStatus(1);
//                foreach ($onlineContests as $onlineContest) {
//                    $onlineContest->setStatus(false);
//                }
//            }
//        }
    }

    public function prePersist(LifecycleEventArgs $args) {
        $entity = $args->getEntity();
        if ($entity instanceof Contest) {
            $entity->setDateAdd( new \DateTime(date('Y-m-d H:i:s')));
            if ($entity->getStatus() == 1) {
                $onlineContest = $this->container->get('doctrine')->getRepository('AppBundle:Contest')->findOneByStatus(1);
                $onlineContest->setStatus(false);
            }
        }
    }

    public function postPersist(LifecycleEventArgs $args) {
    }

    public function postUpdate(LifecycleEventArgs $args) {
    }

    public function postLoad(LifecycleEventArgs $args) {
    }

    public function postRemove(LifecycleEventArgs $args) {
    }



}
