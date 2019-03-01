<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Agent;
use App\Entity\Image;
use App\Entity\Types;
use App\Entity\Property;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {

        $faker = Factory::create('fr-FR');
        for ($i=0 ; $i < 10 ; $i++) {
            
            $types = new Types();            
            $totalType = [];
            $totalAgent = [];
            for ($j=0; $j < 5 ; $j++) {
                $types->setName($faker->lastName);
                $manager->persist($types);
                $totalType[] = $types; 
            }

            for ($j=0; $j < 10 ; $j++) { 
                $agent = new Agent();
                $agent->setFullName($faker->name)
                      ->setDescription($faker->text($maxNbChars = 100))
                      ->setPhone($faker->phoneNumber)
                      ->setEmail($faker->email)
                      ->setSkype($faker->url)
                      ->setFacebook($faker->url)
                      ->setTwitter($faker->url)
                      ->setInstagram($faker->url)
                      ->setPinterest($faker->url)
                      ->setDribbble($faker->url)
                      ->setAvatar('agents-'.mt_rand(1,7).'.jpg')
                      ->setPassword($this->encoder->encodePassword($agent, 'apiyaapiya'))
                ;
                $manager->persist($agent);
                $totalAgent[] = $agent;
            }
            
            $property = new Property();

            $property->setTitle($faker->text($maxNbChars = 100))
                     ->setDescription($faker->text($maxNbChars = 100))
                     ->setCoverImage('post-'.mt_rand(1,7).'.jpg')
                     ->setAdresse($faker->address)
                     ->setTypes($totalType[mt_rand(1, count($totalType)-1)])
                     ->setStatus(mt_rand(1,2))
                     ->setArea(mt_rand(100, 200))
                     ->setBedrooms(mt_rand(2, 4))
                     ->setBathrooms(mt_rand(2, 5))
                     ->setGarage(mt_rand(1,3))
                     ->setCity($faker->city)
                     ->setPrice(mt_rand(200, 400))
                     ->setAgent($totalAgent[mt_rand(1, count($totalAgent)-1)])
            ;

            for ($j=0 ; $j < mt_rand(1, 4) ; $j++ ) { 
                $image = new Image();
                $image->setImageFile('silde-'.mt_rand(1, 3).'.jpg')
                      ->setProperty($property)
                ;
                $manager->persist($image);
            }

            $manager->persist($property);

        }

        $manager->flush();
    }

}
