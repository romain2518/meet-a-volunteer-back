<?php

namespace App\DataFixtures;

use App\Entity\Message;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $roles = [
            'ROLE_USER',
            'ROLE_MODERATOR',
            'ROLE_ADMIN'
        ];

        $users = [];
        
        //! User
        for ($i=1; $i <= 30; $i++) { 
            $user = new User();

            $user->setPseudo('Member #' . $i);
            $user->setPseudoSlug('member_' . $i);
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



        // $product = new Product();
        // $manager->persist($product);

        // $manager->flush();
    }
}
