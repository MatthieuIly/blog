<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Class PostFixtures
 * @package App\DataFixtures
 */
class PostFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 100; $i++) {
            $post = new Post();
            $post->setTitle('Article N°' . $i);
            $post->setContent('Contenu N°' . $i);
            $post->setUser($this->getReference(sprintf('user-%d', ($i % 10) + 1)));
            $post->setImage('https://picsum.photos/400/300');
            $manager->persist($post);

            for($j = 1; $j <= rand(5, 15); $j++) {
                $comment = new Comment();
                $comment->setAuthor('Auteur ' . $i);
                $comment->setContent('Commentaire N° ' . $j);
                $comment->setPost($post);
                $manager->persist($comment);
            }
        }
        $manager->flush();
    }

    /**
     * @return string[]
     */
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
