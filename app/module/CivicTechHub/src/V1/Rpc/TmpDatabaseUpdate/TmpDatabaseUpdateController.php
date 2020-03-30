<?php
namespace CivicTechHub\V1\Rpc\TmpDatabaseUpdate;

use Laminas\Http\PhpEnvironment\Response;
use Laminas\Mvc\Controller\AbstractActionController;

class TmpDatabaseUpdateController extends AbstractActionController
{
    public function tmpDatabaseUpdateAction()
    {
        try {
            $this->checkToken();

            return [
                'migration' => $this->runMigrationIfNecessaryAndReturnResponse(),
                'import' => $this->runImportIfNecessaryAndReturnResponse()
            ];
        } catch (\Throwable $exception) {
            return (new Response())->setStatusCode(500)->setContent($exception->getMessage());
        }
    }

    private function checkToken()
    {
        $inputFilter = $this->getEvent()->getParam('Laminas\ApiTools\ContentValidation\InputFilter');
        $providedToken = $inputFilter->getValue('token');

        $requiredToken = env('TMP_DATABASE_UPDATE_TOKEN');
        if (empty($requiredToken)) {
            throw new \RuntimeException('No token configured.');
        }

        if ($providedToken != $requiredToken) {
            throw new \RuntimeException('Token invalid.');
        }
    }

    private function runMigrationIfNecessaryAndReturnResponse()
    {
        $inputFilter = $this->getEvent()->getParam('Laminas\ApiTools\ContentValidation\InputFilter');
        $migrate = $inputFilter->getValue('migrate');

        if ($migrate !== '1') {
            return 'not executed';
        }

        exec('/var/www/bin/migrate-database.sh', $output, $exitCode);
        return ['exitCode' => $exitCode, 'output' => $output];
    }

    private function runImportIfNecessaryAndReturnResponse()
    {
        $inputFilter = $this->getEvent()->getParam('Laminas\ApiTools\ContentValidation\InputFilter');
        $import = $inputFilter->getValue('import');

        if ($import !== '1') {
            return 'not executed';
        }

        exec('/var/www/bin/import-data-from-external-sources.sh', $output, $exitCode);
        return ['exitCode' => $exitCode, 'output' => $output];
    }
}
