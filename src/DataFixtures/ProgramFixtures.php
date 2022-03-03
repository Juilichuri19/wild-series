<?php

namespace App\DataFixtures;

use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Program;

class ProgramFixtures extends Fixture
{
    private Slugify $slugify;
    public function __construct(\App\Service\Slugify $slugify)
    {
        $this->slugify = $slugify;
    }
    public function load(ObjectManager $manager)
    {
        $program = new Program();
        //$program->setTitle('Walking dead');
        $program->setTitle($this->slugify->generate('Walking dead'));
        $program->setSummary('Des zombies envahissent la terre');
        $program->setCategory($this->getReference('category_0'));
        //ici les acteurs sont insérés via une boucle pour être DRY mais ce n'est pas obligatoire
        for ($i=0; $i < count(ActorFixtures::ACTORS); $i++) {
            $program->addActor($this->getReference('actor_' . $i));
        }
        $this->addReference('program1', $program);
        $manager->persist($program);
        $manager->flush();
    }
    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
            ActorFixtures::class,
            CategoryFixtures::class,
        ];
    }
}
