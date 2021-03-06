<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Post;
use App\Utils\Slugger;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PostFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        foreach ($this->getPostData() as [$title, $slug, $summary, $content, $publishedAt, $author]) {
            $post = new Post();
            $post->setTitle($title)
                ->setSlug($slug)
                ->setSummary($summary)
                ->setContent($content)
                ->setPublishedAt($publishedAt)
                ->setAuthor($author);

            for ($i = 0; $i < rand(1, 5); $i++) {
                $comment = new Comment();
                $comment->setContent($this->createRandomContent());
                $comment->setPublishedAt(new \DateTime(sprintf('now - %d days', rand(0, 30))));
                $comment->setAuthor($this->getReference(sprintf('user_%d', rand(1, 2))));

                $post->addComment($comment);
            }

            for ($j = 0; $j < rand(0, 4); $j++) {
                $post->addTag($this->getReference(sprintf('tag_%d', rand(1, 18))));
            }

            $manager->persist($post);
        }
        $manager->flush();
    }

    private function createRandomContent()
    {
        $phrases = $this->getPhrases();
        shuffle($phrases);

        return implode(' ', array_slice($phrases, 0, rand(1, count($phrases)))) . '.';
    }

    private function getPostData(): array
    {
        //[$title, $slug, $summary, $content, $publishedAt, $author]
        $posts = [];
        $phrases = $this->getPhrases();
        for ($i = 0; $i < 30; $i++) {

            $title = $phrases[rand(1, count($phrases) - 1)];

            $posts[] = [
                $title,
                Slugger::slugify($title),
                $this->getSummary(),
                $this->getPostContent(),
                new \DateTime(sprintf('now - %d days', rand(0, 30))),
                $this->getReference(sprintf('admin_%d', rand(1, 2))),
            ];
        }
        return $posts;
    }

    private function getSummary(int $count = 255): string
    {
        $phrases = $this->getPhrases();
        shuffle($phrases);
        $phrases = implode(' ', $phrases);
        return mb_substr($phrases, 0, $count, 'UTF8') . '.';
    }

    private function getPhrases(): array
    {
        return [
            'Lorem ipsum dolor sit amet consectetur adipiscing elit',
            'Pellentesque vitae velit ex',
            'Mauris dapibus risus quis suscipit vulputate',
            'Eros diam egestas libero eu vulputate risus',
            'In hac habitasse platea dictumst',
            'Morbi tempus commodo mattis',
            'Ut suscipit posuere justo at vulputate',
            'Ut eleifend mauris et risus ultrices egestas',
            'Aliquam sodales odio id eleifend tristique',
            'Urna nisl sollicitudin id varius orci quam id turpis',
            'Nulla porta lobortis ligula vel egestas',
            'Curabitur aliquam euismod dolor non ornare',
            'Sed varius a risus eget aliquam',
            'Nunc viverra elit ac laoreet suscipit',
            'Pellentesque et sapien pulvinar consectetur',
            'Ubi est barbatus nix',
            'Abnobas sunt hilotaes de placidus vita',
            'Ubi est audax amicitia',
            'Eposs sunt solems de superbus fortis',
            'Vae humani generis',
            'Diatrias tolerare tanquam noster caesium',
            'Teres talis saepe tractare de camerarius flavum sensorem',
            'Silva de secundus galatae demitto quadra',
            'Sunt accentores vitare salvus flavum parses',
            'Potus sensim ad ferox abnoba',
            'Sunt seculaes transferre talis camerarius fluctuies',
            'Era brevis ratione est',
            'Sunt torquises imitari velox mirabilis medicinaes',
            'Mineralis persuadere omnes finises desiderium',
            'Bassus fatalis classiss virtualiter transferre de flavum',
        ];
    }

    private function getPostContent(): string
    {
        return <<<'MARKDOWN'
Lorem ipsum dolor sit amet consectetur adipisicing elit, sed do eiusmod tempor
incididunt ut labore et **dolore magna aliqua**: Duis aute irure dolor in
reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia
deserunt mollit anim id est laborum.

  * Ut enim ad minim veniam
  * Quis nostrud exercitation *ullamco laboris*
  * Nisi ut aliquip ex ea commodo consequat

Praesent id fermentum lorem. Ut est lorem, fringilla at accumsan nec, euismod at
nunc. Aenean mattis sollicitudin mattis. Nullam pulvinar vestibulum bibendum.
Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos
himenaeos. Fusce nulla purus, gravida ac interdum ut, blandit eget ex. Duis a
luctus dolor.

Integer auctor massa maximus nulla scelerisque accumsan. *Aliquam ac malesuada*
ex. Pellentesque tortor magna, vulputate eu vulputate ut, venenatis ac lectus.
Praesent ut lacinia sem. Mauris a lectus eget felis mollis feugiat. Quisque
efficitur, mi ut semper pulvinar, urna urna blandit massa, eget tincidunt augue
nulla vitae est.

Ut posuere aliquet tincidunt. Aliquam erat volutpat. **Class aptent taciti**
sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Morbi
arcu orci, gravida eget aliquam eu, suscipit et ante. Morbi vulputate metus vel
ipsum finibus, ut dapibus massa feugiat. Vestibulum vel lobortis libero. Sed
tincidunt tellus et viverra scelerisque. Pellentesque tincidunt cursus felis.
Sed in egestas erat.

Aliquam pulvinar interdum massa, vel ullamcorper ante consectetur eu. Vestibulum
lacinia ac enim vel placerat. Integer pulvinar magna nec dui malesuada, nec
congue nisl dictum. Donec mollis nisl tortor, at congue erat consequat a. Nam
tempus elit porta, blandit elit vel, viverra lorem. Sed sit amet tellus
tincidunt, faucibus nisl in, aliquet libero.
MARKDOWN;
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            TagFixtures::class,
        ];
    }
}
