<?php
use Phinx\Seed\AbstractSeed;

class GroupSeeder extends AbstractSeed
{
    public function run()
    {
        $this->execute(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'groups.sql'));
    }
}
