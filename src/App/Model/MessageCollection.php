<?php

declare(strict_types=1);

namespace App\Model;

use Core\BaseCollection;
use App\Entity\Message;

final class MessageCollection
{
    private const PAGE_SIZE = 5;
    private const PAGE = 1;
    private const DATE_FORMAT = 'd.m.Y H:i.s';
    private const MAX_MESSAGE_LENGTH = 200;
    private $collection;

    public function __construct(\PDO $pdo)
    {
        $this->collection = new BaseCollection($pdo);
    }

    public function getTotalPages(int $pageSize = self::PAGE_SIZE): int
    {
        return (int)ceil($this->collection->rowTotalCount(new Message()) / $pageSize);
    }

    public function getOnPage(int $page = self::PAGE, int $pageSize = self::PAGE_SIZE): array
    {
        $this->collection->setOrderBy('id')->setLimit($page, $pageSize);
        $date = new \DateTimeImmutable();
        $messages = [];
        /** @var Message $message */
        foreach ($this->collection->getEntities(new Message()) as $message) {
            $message->createdAt = $date->setTimestamp((int)$message->createdAt)->format(self::DATE_FORMAT);
            $message->message = substr($message->message, 0, self::MAX_MESSAGE_LENGTH) .
                (mb_strlen($message->message) > self::MAX_MESSAGE_LENGTH ? '...' : '');
            $messages[] = $message;
        }

        return $messages;
    }
}
