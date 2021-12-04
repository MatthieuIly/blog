<?php

namespace App\DataTransfer;

use App\DataTransfertObject\Comment;
use App\Entity\Comment as BaseComment;
use Symfony\Component\Security\Core\Security;

class CommentTransfer implements DataTransferInterface
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @param BaseComment $originalData
     */
    public function transfer($originalData): mixed
    {
        return new Comment(
            $originalData->getAuthor(),
            $originalData->getContent()
        );
    }

    /**
     * @param Comment     $data
     * @param BaseComment $originalData
     */
    public function reverseTransfer($data, $originalData): mixed
    {
        if ($this->security->isGranted('ROLE_USER')) {
            $originalData->setUser($this->security->getUser());
        }

        $originalData->setAuthor($data->getAuthor());
        $originalData->setContent($data->getContent());
    }
}
