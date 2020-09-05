<?php

declare(strict_types=1);

namespace Core;

class BaseCollection
{
    private $pdo;
    private $pageSize;
    private $pageOffset;
    /** @var BaseCollectionWhereExpression */
    private $where;
    private $orderBy;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->pageSize = 10;
        $this->pageOffset = 0;
    }

    public function setLimit(int $page, int $pageSize): self
    {
        $this->pageSize = ($pageSize > 1) ? $pageSize : 1;
        $page = $page > 1 ? $page : 1;
        $this->pageOffset = ($page - 1) * $pageSize;

        return $this;
    }

    public function setWhere(?BaseCollectionWhereExpression $whereExpression = null): self
    {
        $this->where = $whereExpression;

        return $this;
    }

    public function setOrderBy(string $fieldName, string $ascType = 'desc'): self
    {
        $this->orderBy[] = sprintf('%s %s', $fieldName, $ascType);

        return $this;
    }

    public function rowTotalCount(BaseEntity $entity): int
    {
        $sql = sprintf('SELECT COUNT (%s) as rowCount FROM %s %s', $entity->getPrimaryKeyName(), $entity->getTable(), $this->getConditions());
        $pdoStatement = $this->pdo->prepare($sql);
        $this->executePdoStatement($pdoStatement);

        return (int)$pdoStatement->fetch()['rowCount'];
    }

    /**
     * При выполнении в шаблонах итерации лучше использовать этот метод, будет меньше потреблять ресурсов.
     * @return \Iterator
     */
    public function getEntities(BaseEntity $entity): \Iterator
    {
        $pdoStatement = $this->prepareCollectionQuery($entity);
        $this->executePdoStatement($pdoStatement);
        $pdoStatement->setFetchMode(\PDO::FETCH_LAZY | \PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, get_class($entity));
        while ($record = $pdoStatement->fetch()) {
            yield $record;
        }
    }

    /**
     * @return BaseEntity[]
     */
    public function getAll(BaseEntity $entity): array
    {
        $pdoStatement = $this->prepareCollectionQuery($entity);
        $this->executePdoStatement($pdoStatement);
        return $pdoStatement->fetchAll(\PDO::FETCH_CLASS, get_class($entity)) ?: [];
    }

    protected function getConditions(): string
    {
        $conditions = [];
        if ($this->where) {
            $conditions[] = $this->where;
        }
        if ($this->orderBy) {
            $conditions[] = 'ORDER BY ' . implode(', ', $this->orderBy);
        }
        if ($this->pageOffset || $this->pageSize) {
            $conditions[] = 'LIMIT ' . ($this->pageOffset ?: 0) . ($this->pageSize ? ', ' . $this->pageSize : '');
        }

        return implode(' ', $conditions);
    }

    protected function prepareCollectionQuery(BaseEntity $entity): \PDOStatement
    {
        $filedId = $entity->getPrimaryKeyName();
        $fields = $entity->getFieldsName();
        $sql = sprintf('SELECT %s, %s from %s %s',$filedId, $fields, $entity->getTable(), $this->getConditions());

        return $this->pdo->prepare($sql);
    }

    protected function executePdoStatement(\PDOStatement &$pdoStatement): void
    {
        $dataPlaceholders = [];
        if ($this->where) {
            $dataPlaceholders += $this->where->getPlaceholdersWithData();
        }
        $pdoStatement->execute($dataPlaceholders);
    }
}
