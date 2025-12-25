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

    protected function _toHtml(): string
    {
        $movies = $this->getMovies();

        $html  = '<div class="mn-movie-wrap">';
        $html .= '<h1 class="mn-title">All Movies</h1>';

        if (!$movies) {
            $html .= '<div class="message info empty"><span>No movies found.</span></div>';
            $html .= '</div>';
            return $html;
        }

        foreach ($movies as $movie) {
            $name         = $this->escapeHtml($movie['name']);
            $desc         = $this->escapeHtml($movie['description'] ?? '');
            $directorName = $this->escapeHtml($movie['director_name'] ?? '');
            $rating       = $movie['rating'] !== null ? (int)$movie['rating'] : null;

            $html .= '<div class="mn-card">';
            $html .= '  <div class="mn-card__header">';
            $html .= '    <div class="mn-card__name">' . $name . '</div>';
            $html .= '    <div class="mn-badges">';

            if ($rating !== null) {
                $html .= '      <span class="mn-badge">Rating: ' . $rating . '</span>';
            }

            if ($directorName !== '') {
                $html .= '      <span class="mn-badge">Director: ' . $directorName . '</span>';
            } else {
                $html .= '      <span class="mn-badge mn-badge--muted">Director: (none)</span>';
            }

            $html .= '    </div>'; // mn-badges
            $html .= '  </div>';   // mn-card__header

            if ($desc !== '') {
                $html .= '<div class="mn-desc">' . $desc . '</div>';
            }

            $html .= '<div class="mn-actors">';
            $html .= '  <div class="mn-actors__label">Actors:</div>';

            $actors = $movie['actors'] ?? [];
            if (!empty($actors)) {
                $html .= '<ul class="mn-actors__list">';
                foreach ($actors as $actorName) {
                    $html .= '<li>' . $this->escapeHtml($actorName) . '</li>';
                }
                $html .= '</ul>';
            } else {
                $html .= '<div class="mn-actors__empty">(no actors)</div>';
            }

            $html .= '</div>'; // mn-actors
            $html .= '</div>'; // mn-card
        }

        $html .= '</div>'; // wrap
        return $html;
    }
}
