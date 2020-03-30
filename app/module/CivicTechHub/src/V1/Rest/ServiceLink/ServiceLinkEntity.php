<?php
namespace CivicTechHub\V1\Rest\ServiceLink;

class ServiceLinkEntity
{
    const TypeSlack = 'SLACK';
    const TypeTelegram = 'TELEGRAM';
    const TypeDiscord = 'DISCORD';
    const TypeTwitter = 'TWITTER';
    const TypeFacebook = 'FACEBOOK';
    const TypeInstagram = 'INSTAGRAM';
    const TypeTrello = 'TRELLO';
    const TypeWebsite = 'WEBSITE';

    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $text;

    /**
     * @var string
     */
    public $url;

    /**
     * @var
     */
    public $groupId;

    /**
     * @var string
     */
    public $type;

    /**
     * @var bool
     */
    public $isMainLink;
}
