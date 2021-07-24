<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TagFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $tagData=$this->getTagData();
        for ($i=0; $i< count($tagData); $i++) {

            $tag = new Tag();
            $tag->setName($tagData[$i]);

            $this->setReference(sprintf("tag_%d", $i), $tag);

            $manager->persist($tag);
        }

        $manager->flush();
    }

    private function getTagData()
    {
        //
        return [
            'Lorem',
            'ipsum',
            'dolor',
            'sit',
            'amet,',
            'consectetur',
            'adipiscing',
            'elit,',
            'sed',
            'do',
            'eiusmod',
            'tempor',
            'incididunt',
            'ut',
            'labore',
            'et',
            'dolore',
            'magna',
            'aliqua.',
        ];
    }
}

