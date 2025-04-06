<?php

namespace App\DataFixtures;
use App\Entity\User;
//use Symfony\Component\Security\Core\User\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;
class UserFixtures extends Fixture
{
    // je cree le constructeur pour crypter le mot d passe

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;

    }
   
        public function load(ObjectManager $manager)
        {
            // $product = new Product();
            // $manager->persist($product);

            $user = new User();
            $user->setUsername('demo');
            $user->setPassword($this->encoder->encodePassword($user,'demo'));
            $manager->persist($user);
            $manager->flush();
        }
        
}
