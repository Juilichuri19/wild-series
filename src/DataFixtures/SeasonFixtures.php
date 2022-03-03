<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class SeasonFixtures extends Fixture
{
    /*public const SEASONS = [
      'Season 1',
      'Season 2',
      'Season 3',
    ];*/

    public const SEASONS = 'Season 1';
    public const YEAR =[2007, 2008, 2009];
    public function load(ObjectManager $manager): void
    {
        $season = new Season();
        $season->setNumber(1);
        $season->setDescription('Season 1');
        $season->setYear(2007);
        $season->setProgram($this->getReference('program1'));
        $manager->persist($season);

        /*for ($i=0; $i< count(self::SEASONS); $i++) {
            $season = new Season();
            $season->setNumber($i+1);
            $season->setDescription($season[$i]);
            $season->setYear(self::YEAR[$i]);
            $season->setProgram(1);
            $manager->persist($season);
            $this->addReference('season_' . $i , $season);
        }*/
        $manager->flush();
        $this->addReference(self::SEASONS, $season);
    }

    public function getDependencies()
    {
        return [
            ProgramFixtures::class,
        ];
    }
}
