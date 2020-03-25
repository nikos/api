<?php
namespace CivicTechHub\V1\Rest\ServiceLink;

class ServiceLinkEntity
{
    const TYPE_SLACK = 'SLACK';
    const TYPE_TELEGRAM = 'TELEGRAM';
    const TYPE_DISCORD = 'DISCORD';
    const TYPE_TWITTER = 'TWITTER';
    const TYPE_FACEBOOK = 'FACEBOOK';
    const TYPE_INSTAGRAM = 'INSTAGRAM';
    const TYPE_TRELLO = 'TRELLO';
    const TYPE_WEBSITE = 'WEBSITE';

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
}
