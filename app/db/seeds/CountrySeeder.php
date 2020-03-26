<?php

use Phinx\Seed\AbstractSeed;

class CountrySeeder extends AbstractSeed
{
    public function run()
    {
        $this->execute(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'countries.sql'));
    }
}
