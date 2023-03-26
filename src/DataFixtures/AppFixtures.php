<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use App\Entity\Recette;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class AppFixtures extends Fixture
{
    /**
     * @var Generator
     */
    private Generator $faker ;
    public function __construct()
    {
        $this->faker = Factory::create('en_EN');
    }


    public function load(ObjectManager $manager): void
    {
        //! ingredient
        $ingredients=[];
        for ($i=0; $i < 25; $i++) {
            $ingredient = new Ingredient();
            $ingredient->setName($this->faker->word())
            ->setPrice(mt_rand(0, 200)) ;
            $ingredients[]= $ingredient;
            $manager->persist($ingredient);
        }

        //! recette
        // memory_limit=512M
        for ($i=0; $i <12; $i++) {
            $recette = new Recette();
            $recette->setName($this->faker->word())
            ->setPrice(mt_rand(0, 200))
            ->setTime(mt_rand(0, 1) ? mt_rand(1, 1440) : null)
            // ->setTime(mt_rand(1, 1440))
            ->setNumPersons(mt_rand(0, 1) ? mt_rand(1, 50) : null)
            // ->setNumPersons(mt_rand(1, 50))
            ->setDifficulty(mt_rand(0, 1) ? mt_rand(1, 5) : null)
            // ->setDifficulty(mt_rand(1, 5))
            ->setDescription($this->faker->text(300))
            ->setPrice((mt_rand(0, 1) ? mt_rand(1, 1000) : null))
            // ->setPrice(mt_rand(1, 1000))
            ->setIsFavorite(mt_rand(0, 1));
            for ($i=0; $i < mt_rand(5, 15); $i++) {
                $recette->addIngredient($ingredients[mt_rand(0, count($ingredients)-1)]);
            }
            $manager->persist($recette);
        }
        $manager->flush();
    }
}
