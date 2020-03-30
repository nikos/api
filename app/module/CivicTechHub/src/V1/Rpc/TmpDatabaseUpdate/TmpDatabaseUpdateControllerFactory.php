<?php
namespace CivicTechHub\V1\Rpc\TmpDatabaseUpdate;

class TmpDatabaseUpdateControllerFactory
{
    public function __invoke($controllers)
    {
        return new TmpDatabaseUpdateController();
    }
}
