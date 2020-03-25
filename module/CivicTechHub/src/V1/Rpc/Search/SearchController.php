<?php
namespace CivicTechHub\V1\Rpc\Search;

use CivicTechHub\V1\Rest\Country\Model\RepositoryInterface as CountryRepositoryInterface;
use CivicTechHub\V1\Rest\Group\Model\RepositoryInterface as GroupRepositoryInterface;
use CivicTechHub\V1\Rest\Topic\Model\RepositoryInterface as TopicRepositoryInterface;
use Laminas\Mvc\Controller\AbstractActionController;

class SearchController extends AbstractActionController
{
    const EntityCountry = 'country';
    const EntityGroup = 'group';
    const EntityTopic = 'topic';
    const AllEntities = [
        self::EntityCountry,
        self::EntityGroup,
        self::EntityTopic
    ];
    /**
     * @var CountryRepositoryInterface
     */
    private $countryRepository;
    /**
     * @var GroupRepositoryInterface
     */
    private $groupRepository;
    /**
     * @var TopicRepositoryInterface
     */
    private $topicRepository;

    public function __construct(
        CountryRepositoryInterface $countryRepository,
        GroupRepositoryInterface $groupRepository,
        TopicRepositoryInterface $topicRepository
    ) {
        $this->countryRepository = $countryRepository;
        $this->groupRepository = $groupRepository;
        $this->topicRepository = $topicRepository;
    }

    public function searchAction()
    {
        $phrase = $this->getPhrase();
        $entities = $this->getFilteredUniqueEntities();

        return $this->buildResponse($phrase, $entities);
    }

    private function buildResponse(string $phrase, array $entities)
    {
        $response = [
            'phrase' => $phrase,
            'results' => []
        ];
        if (in_array(self::EntityCountry, $entities)) {
            $response['results'][self::EntityCountry] = $this->countryRepository->fetchListWithIdAndNameForSearchphrase($phrase);
        }
        if (in_array(self::EntityGroup, $entities)) {
            $response['results'][self::EntityGroup] = $this->groupRepository->fetchListWithIdAndNameForSearchphrase($phrase);
        }
        if (in_array(self::EntityTopic, $entities)) {
           $response['results'][self::EntityTopic] = $this->topicRepository->fetchListWithIdAndNameForSearchphrase($phrase);
        }

        return $response;
    }

    private function getPhrase()
    {
        $inputFilter = $this->getEvent()->getParam('Laminas\ApiTools\ContentValidation\InputFilter');
        return $inputFilter->getValue('phrase');
    }

    private function getFilteredUniqueEntities()
    {
        $inputFilter = $this->getEvent()->getParam('Laminas\ApiTools\ContentValidation\InputFilter');
        $filteredUniqueEntities = $inputFilter->getValue('entities');

        if (empty($filteredUniqueEntities)) {
            return self::AllEntities;
        }

        return $filteredUniqueEntities;
    }
}
