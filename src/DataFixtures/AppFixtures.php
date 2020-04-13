<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var \Faker\Factory
     */
    private $faker;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->faker = \Faker\Factory::create();
    }

    public function load(ObjectManager $manager)
    {
        $this->loadUser($manager);
        $this->loadBlogPost($manager);
    }

    public function loadBlogPost(ObjectManager $manager){

        $user = $this->getReference('user_admin');

        $blogPost = new BlogPost();
        $blogPost->setTitle(' A first post!')
            ->setPublished(new \DateTime())
            ->setContent('First post content')
            ->setAuthor($user)
            ->setSlug('a-first-post');

        $manager->persist($blogPost);

        $blogPost = new BlogPost();
        $blogPost->setTitle(' A second post!')
            ->setPublished(new \DateTime())
            ->setContent('Second post content')
            ->setAuthor($user)
            ->setSlug('a-second-post');

        $manager->persist($blogPost);

        $manager->flush();
    }

    public function loadComments(ObjectManager $manager){

    }

    public function loadUser(ObjectManager $manager){
        $user = new User();
        $user->setUsername('admin')
             ->setEmail('admin@blog.com')
             ->setName('Gomab Fumu')

             ->setPassword($this->passwordEncoder->encodePassword(
                 $user,
                 'secret123#'
             ));

        $this->addReference('user_admin', $user);

        $manager->persist($user);
        $manager->flush();
    }
}
