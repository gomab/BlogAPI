<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
         $blogPost = new BlogPost();
         $blogPost->setTitle(' A first post!')
                  ->setPublished(new \DateTime())
                  ->setContent('First post content')
                  ->setAuthor('Gomab')
                  ->setSlug('a-first-post');

         $manager->persist($blogPost);

        $blogPost = new BlogPost();
        $blogPost->setTitle(' A second post!')
            ->setPublished(new \DateTime())
            ->setContent('Second post content')
            ->setAuthor('Gomab')
            ->setSlug('a-second-post');

        $manager->persist($blogPost);

        $manager->flush();
    }
}
