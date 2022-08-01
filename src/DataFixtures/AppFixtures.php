<?php

namespace App\DataFixtures;

use App\Entity\Experience;
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

        $choices = [
            'Yes',
            'No',
            'Partially'
        ];

        $durationChoices = [
            'Less than a month',
            'Between 1 and 6 months',
            'Between 6 months and one year',
            'More than a year'
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
        //? Member
        $user = new User();

        $user->setPseudo('User');
        $user->setPseudoSlug($this->slugger->slug($user->getPseudo())->lower());
        $user->setRoles(['ROLE_USER']);
        $user->setPassword('$2y$13$H1YPtVq4xMwKhd1H1D817OOnHRnYklL.3ZmM/ujTL28n9o/43w8MW'); // Password : 1234
        $user->setFirstname($faker->firstName());
        $user->setLastname($faker->lastName());
        $user->setAge($faker->dateTimeBetween('-100 years', '-13 years'));
        $user->setProfilePicture('0.jpg');
        $user->setEmail($faker->email());
        $user->setPhone($faker->phoneNumber());
        $user->setBiography($faker->realText(random_int(10, 250)));
        $user->setNativeCountry($faker->country());

        $users[] = $user;
        $manager->persist($user);

        //? Moderator
        $moderator = new User();

        $moderator->setPseudo('Moderator');
        $moderator->setPseudoSlug($this->slugger->slug($moderator->getPseudo())->lower());
        $moderator->setRoles(['ROLE_MODERATOR']);
        $moderator->setPassword('$2y$13$H1YPtVq4xMwKhd1H1D817OOnHRnYklL.3ZmM/ujTL28n9o/43w8MW'); // Password : 1234
        $moderator->setFirstname($faker->firstName());
        $moderator->setLastname($faker->lastName());
        $moderator->setAge($faker->dateTimeBetween('-100 years', '-13 years'));
        $moderator->setProfilePicture('0.jpg');
        $moderator->setEmail($faker->email());
        $moderator->setPhone($faker->phoneNumber());
        $moderator->setBiography($faker->realText(random_int(10, 250)));
        $moderator->setNativeCountry($faker->country());

        $users[] = $moderator;
        $manager->persist($moderator);

        //? Member
        $admin = new User();

        $admin->setPseudo('Admin');
        $admin->setPseudoSlug($this->slugger->slug($admin->getPseudo())->lower());
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword('$2y$13$H1YPtVq4xMwKhd1H1D817OOnHRnYklL.3ZmM/ujTL28n9o/43w8MW'); // Password : 1234
        $admin->setFirstname($faker->firstName());
        $admin->setLastname($faker->lastName());
        $admin->setAge($faker->dateTimeBetween('-100 years', '-13 years'));
        $admin->setProfilePicture('0.jpg');
        $admin->setEmail($faker->email());
        $admin->setPhone($faker->phoneNumber());
        $admin->setBiography($faker->realText(random_int(10, 250)));
        $admin->setNativeCountry($faker->country());

        $users[] = $admin;
        $manager->persist($admin);

        for ($i=1; $i <= 30; $i++) { 
            $user = new User();

            $user->setPseudo('Member #' . $i);
            $user->setPseudoSlug($this->slugger->slug($user->getPseudo())->lower());
            $user->setRoles([$roles[array_rand($roles)]]);
            $user->setPassword('$2y$13$H1YPtVq4xMwKhd1H1D817OOnHRnYklL.3ZmM/ujTL28n9o/43w8MW'); // Password : 1234
            $user->setFirstname($faker->firstName());
            $user->setLastname($faker->lastName());
            $user->setAge($faker->dateTimeBetween('-100 years', '-13 years'));
            $user->setProfilePicture('0.jpg');
            $user->setEmail($faker->email());
            $user->setPhone($faker->phoneNumber());
            $user->setBiography($faker->realText(random_int(10, 250)));
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
            $volunteeringType->setSlugName($this->slugger->slug($volunteeringType->getName())->lower());

            $volunteeringTypes[] = $volunteeringType;
            $manager->persist($volunteeringType);
        }

        //! Reception structure
        foreach ($receptionStructureList as $receptionStructureName) {
            $receptionStructure = new ReceptionStructure();

            $receptionStructure->setName($receptionStructureName);
            $receptionStructure->setSlugName($this->slugger->slug($receptionStructure->getName())->lower());

            $receptionStructures[] = $receptionStructure;
            $manager->persist($receptionStructure);
        }

        //! Thematic
        foreach ($thematicList as $thematicName) {
            $thematic = new Thematic();

            $thematic->setName($thematicName);
            $thematic->setSlugName($this->slugger->slug($thematic->getName())->lower());

            $thematics[] = $thematic;
            $manager->persist($thematic);
        }

        //! Experience
        foreach ($users as $user) {
            for ($i=1; $i < random_int(0, 10); $i++) { 
                $experience = new Experience();

                $experience->setTitle($faker->realTextBetween(5, 100));
                $experience->setSlugTitle($this->slugger->slug($experience->getTitle())->lower());
                $experience->setCountry($faker->country());
                $experience->setYear(random_int(1900, 2022));
                $experience->setDuration($durationChoices[array_rand($durationChoices)]);
                $experience->setFeedback($faker->realTextBetween(5, 1500));
                $experience->setViews(random_int(0, 5000));
                $experience->setPicture('0.png');
                $experience->setParticipationFee(random_int(0, 4000000));
                $experience->setIsHosted($choices[array_rand($choices)]);
                $experience->setIsFed($choices[array_rand($choices)]);

                if (random_int(1, 2) === 1) {
                    $experience->setLanguage(['French']);
                } else {
                    $experience->setLanguage(['French', 'English']);
                }
                

                $experience->setUser($user);
                $experience->setVolunteeringType($volunteeringTypes[array_rand($volunteeringTypes)]);
                $experience->setReceptionStructure($receptionStructures[array_rand($receptionStructures)]);

                $numberOfThematics = random_int(1, 3);

                if ($numberOfThematics !== 1) { // If we want more than one item, we need an array of key
                    $thematicIndexes = array_rand($thematics, $numberOfThematics);

                    foreach ($thematicIndexes as $thematicIndex) {
                        $experience->addThematic($thematics[$thematicIndex]);
                    }
                } else {
                    $experience->addThematic($thematics[array_rand($thematics, 1)]);
                }

                $manager->persist($experience);
            }
        }

        $manager->flush();
    }
}
