<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use App\Entity\Season;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EpisodeFixtures extends Fixture

{
    public const EPISODE =[
        'Unaired Pilot',
        'Pilot',
        'The Big Bran Hypothesis'
    ];
    public const SYNOPSIS = [
        'The first Pilot of what will become The Big Bang Theory. Leonard and Sheldon are two awkward scientists who share an apartment. They meet a drunk girl called Katie and invite her to stay at their place, because she has nowhere to stay. The two guys have a female friend, also a scientist, called Gilda.',
        'A pair of socially awkward theoretical physicists meet their new neighbor Penny, who is their polar opposite.',
        'Penny is furious with Leonard and Sheldon when they sneak into her apartment and clean it while she is sleeping.',
    ];

    private Slugify $slugify;
    public function __construct(\App\Service\Slugify $slugify)
    {
        $this->slugify = $slugify;
    }

    public function load(ObjectManager $manager): void
    {
        for($i=0; $i<count(self::EPISODE); $i++)
        {
            $episode = new Episode();
            $episode->setTitle(self::EPISODE[$i]);
            $episode->setSynopsis(self::SYNOPSIS[$i]);
            $episode->setNumber($i);
            //$episode->setSeason($this->)
            //$episode->addSeason($this->getReference(SeasonFixtures::SEASONS));
        }
        $manager->persist($episode);

        $manager->flush();
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
            SeasonFixtures::class,
        ];
    }
}
