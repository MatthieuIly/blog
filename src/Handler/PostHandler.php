<?php

namespace App\Handler;

use App\DataTransfertObject\Post;
use App\Form\PostType;
use App\Uploader\UploaderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\UnitOfWork;

class PostHandler extends AbstractHandler
{
    private EntityManagerInterface $entityManager;

    private UploaderInterface $uploader;

    /**
     * @param EntityManagerInterface $entityManager
     * @param UploaderInterface $uploader
     */
    public function __construct(EntityManagerInterface $entityManager, UploaderInterface $uploader)
    {
        $this->entityManager = $entityManager;
        $this->uploader = $uploader;
    }


    protected function getFormType(): string
    {
        return PostType::class;
    }

    protected function process(mixed $data): void
    {
        if (UnitOfWork::STATE_NEW === $this->entityManager->getUnitOfWork()->getEntityState($data)) {
            $this->entityManager->persist($data);
        }
        $this->entityManager->flush();
    }

    protected function getDataTransfertObject(): object
    {
        return new Post();
    }

}
