<?php

namespace App\DataFixtures;

use App\Entity\Message;
use App\Entity\ReceptionStructure;
use App\Entity\Thematic;
use App\Entity\User;
use App\Entity\VolunteeringType;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    public function __construct(SluggerInterface $slugger) {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        //? Providers
        $roles = [
            'ROLE_USER',
            'ROLE_MODERATOR',
            'ROLE_ADMIN'
        ];

        $volunteeringTypeList = [
            'Public',
            'Private',
            'Pair to pair',
            'Other'
        ];

        $receptionStructureList = [
            'Association',
            'Local community',
            'Other'
        ];

        $thematicList = [
            'Sustainable development',
            'Animation',
            'Education',
            'Cultural',
            'Animal cause',
            'Animal care',
            'Social',
            'Health',
            'Prevent discriminations',
            'Women rights',
            'Migrant /refugee support',
            'Construction',
            'Agriculture, permaculture',
            'Sport',
            'Digital',
            'Seniors, intergenerational links',
            'Disability'
        ];

        //? Object lists
        $users = [];
        $volunteeringTypes = [];
        $receptionStructures = [];
        $thematics = [];
        
        //! User
        for ($i=1; $i <= 30; $i++) { 
            $user = new User();

            $user->setPseudo('Member #' . $i);
            $user->setPseudoSlug($this->slugger->slug($user->getPseudo()));
            $user->setRoles([$roles[array_rand($roles)]]);
            $user->setPassword('$2y$13$H1YPtVq4xMwKhd1H1D817OOnHRnYklL.3ZmM/ujTL28n9o/43w8MW'); // Password : 1234
            $user->setFirstname($faker->firstName());
            $user->setLastname($faker->lastName());
            $user->setAge($faker->dateTimeBetween('-100 years', '-13 years'));
            $user->setProfilePicture('0.jpg');
            $user->setEmail($faker->email());
            $user->setPhone($faker->phoneNumber());
            $user->setBiography($faker->realText(random_int(0, 250)));
            $user->setNativeCountry($faker->country());

            $users[] = $user;
            $manager->persist($user);
        }

        //! Message
        foreach ($users as $user) {
            for ($i=1; $i < random_int(0, 10); $i++) { 
                $message = new Message();

                $message->setMessage($faker->realText(150));
                $message->setIsRead(random_int(0,1));
                $message->setUserSender($user);

                do {
                    $userReceiver = $users[array_rand($users)];
                } while ($user === $userReceiver);

                $message->setUserReceiver($userReceiver);

                $manager->persist($message);
            }
        }


        //! Volunteering type
        foreach ($volunteeringTypeList as $volunteeringTypeName) {
            $volunteeringType = new VolunteeringType();

            $volunteeringType->setName($volunteeringTypeName);
            $volunteeringType->setSlugName($this->slugger->slug($volunteeringType->getName()));

            $volunteeringTypes[] = $volunteeringType;
            $manager->persist($volunteeringType);
        }

        //! Reception structure
        foreach ($receptionStructureList as $receptionStructureName) {
            $receptionStructure = new ReceptionStructure();

            $receptionStructure->setName($receptionStructureName);
            $receptionStructure->setSlugName($this->slugger->slug($receptionStructure->getName()));

            $receptionStructures[] = $receptionStructure;
            $manager->persist($receptionStructure);
        }

        //! Thematic
        foreach ($thematicList as $thematicName) {
            $thematic = new Thematic();

            $thematic->setName($thematicName);
            $thematic->setSlugName($this->slugger->slug($thematic->getName()));

            $thematics[] = $thematic;
            $manager->persist($thematic);
        }

        // $product = new Product();
        // $manager->persist($product);

        // $manager->flush();
    }
}
