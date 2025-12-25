<?php

namespace Magenest\Movie\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\DB\Adapter\AdapterInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $conn = $setup->getConnection();

        $directorTableName = $setup->getTable('magenest_director');
        $movieTableName    = $setup->getTable('magenest_movie');
        $actorTableName    = $setup->getTable('magenest_actor');
        $pivotTableName    = $setup->getTable('magenest_movie_actor');

        /**
         * magenest_director
         * director_id (PK, AI), name (text)
         */
        if (!$conn->isTableExists($directorTableName)) {
            $table = $conn->newTable($directorTableName)
                ->addColumn(
                    'director_id',
                    Table::TYPE_INTEGER,
                    null,
                    ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                    'Director ID'
                )
                ->addColumn(
                    'name',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Director Name'
                )
                ->setComment('Magenest Directors');
            $conn->createTable($table);
        }

        /**
         * magenest_movie
         * movie_id (PK, AI), name (text), description (text), rating (int), director_id (int FK -> director)
         */
        if (!$conn->isTableExists($movieTableName)) {
            $table = $conn->newTable($movieTableName)
                ->addColumn(
                    'movie_id',
                    Table::TYPE_INTEGER,
                    null,
                    ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                    'Movie ID'
                )
                ->addColumn(
                    'name',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Movie Name'
                )
                ->addColumn(
                    'description',
                    Table::TYPE_TEXT,
                    '64k',
                    ['nullable' => true],
                    'Description'
                )
                ->addColumn(
                    'rating',
                    Table::TYPE_INTEGER,
                    null,
                    ['nullable' => true],
                    'Rating'
                )
                ->addColumn(
                    'director_id',
                    Table::TYPE_INTEGER,
                    null,
                    ['unsigned' => true, 'nullable' => true, 'default' => null],
                    'Director ID'
                )
                ->addIndex(
                    $setup->getIdxName('magenest_movie', ['director_id']),
                    ['director_id']
                )
                ->addForeignKey(
                    $setup->getFkName('magenest_movie', 'director_id', 'magenest_director', 'director_id'),
                    'director_id',
                    $directorTableName,
                    'director_id',
                    Table::ACTION_SET_NULL
                )
                ->setComment('Magenest Movies');
            $conn->createTable($table);
        }

        /**
         * magenest_actor
         * actor_id (PK, AI), name (text)
         */
        if (!$conn->isTableExists($actorTableName)) {
            $table = $conn->newTable($actorTableName)
                ->addColumn(
                    'actor_id',
                    Table::TYPE_INTEGER,
                    null,
                    ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                    'Actor ID'
                )
                ->addColumn(
                    'name',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Actor Name'
                )
                ->setComment('Magenest Actors');
            $conn->createTable($table);
        }

        /**
         * magenest_movie_actor (pivot many-to-many)
         * movie_id (FK), actor_id (FK)
         * Tôi set composite PK để tránh trùng quan hệ (cực nên làm).
         */
        if (!$conn->isTableExists($pivotTableName)) {
            $table = $conn->newTable($pivotTableName)
                ->addColumn(
                    'movie_id',
                    Table::TYPE_INTEGER,
                    null,
                    ['unsigned' => true, 'nullable' => false],
                    'Movie ID'
                )
                ->addColumn(
                    'actor_id',
                    Table::TYPE_INTEGER,
                    null,
                    ['unsigned' => true, 'nullable' => false],
                    'Actor ID'
                )
                ->addIndex(
                    $setup->getIdxName('magenest_movie_actor', ['movie_id']),
                    ['movie_id']
                )
                ->addIndex(
                    $setup->getIdxName('magenest_movie_actor', ['actor_id']),
                    ['actor_id']
                )
                // composite primary key
                ->addIndex(
                    $setup->getIdxName(
                        'magenest_movie_actor',
                        ['movie_id', 'actor_id'],
                        AdapterInterface::INDEX_TYPE_PRIMARY
                    ),
                    ['movie_id', 'actor_id'],
                    ['type' => AdapterInterface::INDEX_TYPE_PRIMARY]
                )
                ->addForeignKey(
                    $setup->getFkName('magenest_movie_actor', 'movie_id', 'magenest_movie', 'movie_id'),
                    'movie_id',
                    $movieTableName,
                    'movie_id',
                    Table::ACTION_CASCADE
                )
                ->addForeignKey(
                    $setup->getFkName('magenest_movie_actor', 'actor_id', 'magenest_actor', 'actor_id'),
                    'actor_id',
                    $actorTableName,
                    'actor_id',
                    Table::ACTION_CASCADE
                )
                ->setComment('Magenest Movie - Actor Relation');
            $conn->createTable($table);
        }

        $setup->endSetup();
    }
}
