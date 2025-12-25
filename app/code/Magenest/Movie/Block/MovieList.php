<?php
declare(strict_types=1);

namespace Magenest\Movie\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\App\ResourceConnection;

class MovieList extends Template
{
    public function __construct(
        Template\Context $context,
        private ResourceConnection $resource,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    public function getMovies(): array
    {
        $conn = $this->resource->getConnection();

        $movieTable    = $this->resource->getTableName('magenest_movie');
        $directorTable = $this->resource->getTableName('magenest_director');
        $actorTable    = $this->resource->getTableName('magenest_actor');
        $pivotTable    = $this->resource->getTableName('magenest_movie_actor');

        $select = $conn->select()
            ->from(['m' => $movieTable], ['movie_id', 'name', 'description', 'rating', 'director_id'])
            ->joinLeft(['d' => $directorTable], 'd.director_id = m.director_id', ['director_name' => 'name'])
            ->joinLeft(['ma' => $pivotTable], 'ma.movie_id = m.movie_id', [])
            ->joinLeft(['a' => $actorTable], 'a.actor_id = ma.actor_id', ['actor_name' => 'name'])
            ->order('m.movie_id ASC');

        $rows = $conn->fetchAll($select);

        $movies = [];
        foreach ($rows as $row) {
            $movieId = (int)$row['movie_id'];

            if (!isset($movies[$movieId])) {
                $movies[$movieId] = [
                    'movie_id' => $movieId,
                    'name' => (string)$row['name'],
                    'description' => (string)($row['description'] ?? ''),
                    'rating' => $row['rating'] !== null ? (int)$row['rating'] : null,
                    'director_name' => (string)($row['director_name'] ?? ''),
                    'actors' => []
                ];
            }

            if (!empty($row['actor_name'])) {
                $movies[$movieId]['actors'][] = (string)$row['actor_name'];
            }
        }

        return array_values($movies);
    }
}
