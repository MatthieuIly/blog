<?php

namespace App\DataTransfertObject;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class Post
{
    /**
     * @Assert\NotBlank
     */
    private ?string $title = null;

    /**
     * @var UploadedFile|null
     * @Assert\NotNull(groups={"create"})
     * @Assert\Image
     */
    private ?UploadedFile $image = null;

    /**
     * @Assert\NotBlank
     * @Assert\Length(min=10)
     */
    private ?string $content = null;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getImage(): ?UploadedFile
    {
        return $this->image;
    }

    public function setImage(?UploadedFile $image): void
    {
        $this->image = $image;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): void
    {
        $this->content = $content;
    }
}
