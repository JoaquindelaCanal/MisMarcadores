<?php

require_once 'C:/xampp/htdocs/JoaquinCakeApp/bin/vendor/fzaninotto/faker/src/autoload.php';
use Phinx\Migration\AbstractMigration;
use Cake\Auth\DefaultPasswordHasher;


class CreateAdminSeedMigration extends AbstractMigration
{
    
    public function up()
    {
        $faker = \Faker\Factory::create();
        $populator = new Faker\ORM\CakePHP\Populator($faker);

        $populator->addEntity('Users', 1, [
            'first_name' => 'Joaquin',
            'last_name' => 'de la Canal',
            'email' => 'jdelacanal@hotmail.com',
            'password' => function (){
                return 'secret';
            },
            'role' => 'admin',
            'active' => 1,
            'created' => function () use ($faker) {
                return $faker->dateTimeBetween($startDate = 'now', $endDate = 'now');
            },

            'modified' => function () use ($faker) {
                return $faker->dateTimeBetween($startDate = 'now', $endDate = 'now');
            }

            ]);
        $populator->execute();
    }
}
