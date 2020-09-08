<?php

declare(strict_types=1);

namespace Core;

class BaseModel
{
    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(BaseEntity $entity): bool
    {
        if (!empty($entity->id)) {
            return $this->update($entity);
        }
        // INSERT
        $sql = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $entity->getTable(),
            $entity->getFieldsName(),
            $entity->getPlaceholders()
        );

        return $this->bindValues($this->pdo->prepare($sql), $entity->getPlaceholdersWithData())->execute();
    }

    public function delete(BaseEntity $entity): bool
    {
        $this->verifyId($entity);
        $sql = sprintf('DELETE FROM %s WHERE %s = %d', $entity->getTable(), $entity->getPrimaryKeyName(),$entity->getPrimaryKeyName());

        return $this->pdo->prepare($sql)->execute();
    }

    public function update(BaseEntity $entity): bool
    {
        $this->verifyId($entity);
        $sql = sprintf('UPDATE %s SET %s WHERE %s = %d', $entity->getTable(),$entity->getFieldsWithPlaceHolders(), $entity->getPrimaryKeyName(), $entity->getPrimaryKeyName());

        return $this->bindValues($this->pdo->prepare($sql), $entity->getPlaceholdersWithData())->execute();
    }

    public function get(BaseEntity $entity, int $id): ?BaseEntity
    {
        $where = (new BaseCollectionWhereExpression())->add($entity->getPrimaryKeyName(), $id);

        return (new BaseCollection($this->pdo))->setWhere($where)->setLimit(1,1)->getEntities($entity)->current();
    }

    protected function verifyId(BaseEntity $entity): void
    {
        if (empty($entity->id)) {
            $message = sprintf(
                'Сущность "%s" не инициализирована с таблицы "%s"',
                self::class,
                $entity->getTable()
            );
            throw new \RuntimeException($message);
        }
    }

    protected function bindValues(\PDOStatement $statement, array $placeholdersWithData): \PDOStatement
    {
        foreach ($placeholdersWithData as $placeholder => $value) {
            if (!$statement->bindValue($placeholder, $value)) {
                $message = sprintf('Несмог привязать к паратмеру "%s" значение"%s"', $placeholder, $value);
                throw new \RuntimeException($message);
            }
        }

        return $statement;
    }
}
