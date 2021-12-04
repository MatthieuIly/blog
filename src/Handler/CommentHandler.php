<?php

namespace App\Handler;

use App\DataTransfertObject\Comment;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CommentHandler extends AbstractHandler
{
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    protected function getFormType(): string
    {
        return CommentType::class;
    }

    protected function process(mixed $data): void
    {
        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }

    protected function getDataTransfertObject(): object
    {
        return new Comment();
    }

}
