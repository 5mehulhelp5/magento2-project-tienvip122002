<?php
declare(strict_types=1);

namespace Magenest\Movie\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;

class UpgradeData implements UpgradeDataInterface
{
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $conn = $setup->getConnection();

        $currentVersion = $context->getVersion() ?: '0.0.0';

        // Chỉ seed thêm data khi upgrade lên 1.0.2
        if (version_compare($currentVersion, '1.0.2', '<')) {

            $directorTable = $setup->getTable('magenest_director');
            $movieTable    = $setup->getTable('magenest_movie');
            $actorTable    = $setup->getTable('magenest_actor');
            $pivotTable    = $setup->getTable('magenest_movie_actor');

            // ===== 1) Directors (insert nếu chưa có) =====
            $directors = [
                'Christopher Nolan',
                'Hayao Miyazaki',
                'Quentin Tarantino',
                'James Cameron',
            ];

            $conn->insertOnDuplicate(
                $directorTable,
                array_map(fn ($n) => ['name' => $n], $directors),
                ['name']
            );

            $directorIds = $this->getIdMap($conn, $directorTable, 'director_id', 'name', $directors);

            // ===== 2) Actors (insert nếu chưa có) =====
            $actors = [
                'Leonardo DiCaprio',
                'Joseph Gordon-Levitt',
                'Tom Hardy',
                'Ken Watanabe',
                'Christian Bale',
                'Heath Ledger',
                'Anne Hathaway',
                'Samuel L. Jackson',
                'Uma Thurman',
                'John Travolta',
                'Arnold Schwarzenegger',
            ];

            $conn->insertOnDuplicate(
                $actorTable,
                array_map(fn ($n) => ['name' => $n], $actors),
                ['name']
            );

            $actorIds = $this->getIdMap($conn, $actorTable, 'actor_id', 'name', $actors);

            // ===== 3) Movies (insert nếu chưa có) =====
            // rating theo đề là INT
            $movies = [
                [
                    'name' => 'Interstellar',
                    'description' => 'A team travels through a wormhole in space in an attempt to ensure humanity’s survival.',
                    'rating' => 9,
                    'director_name' => 'Christopher Nolan',
                ],
                [
                    'name' => 'The Dark Knight',
                    'description' => 'Batman faces the Joker in Gotham City.',
                    'rating' => 9,
                    'director_name' => 'Christopher Nolan',
                ],
                [
                    'name' => 'Tenet',
                    'description' => 'Time inversion espionage to prevent a global catastrophe.',
                    'rating' => 8,
                    'director_name' => 'Christopher Nolan',
                ],
                [
                    'name' => 'Pulp Fiction',
                    'description' => 'Crime stories woven together in Los Angeles.',
                    'rating' => 9,
                    'director_name' => 'Quentin Tarantino',
                ],
                [
                    'name' => 'Kill Bill: Vol. 1',
                    'description' => 'A former assassin seeks revenge on her old team.',
                    'rating' => 8,
                    'director_name' => 'Quentin Tarantino',
                ],
                [
                    'name' => 'Terminator 2: Judgment Day',
                    'description' => 'A cyborg protects John Connor from a more advanced Terminator.',
                    'rating' => 9,
                    'director_name' => 'James Cameron',
                ],
                // Nếu ông đã có Inception / Spirited Away từ InstallData thì cái này sẽ update “nhẹ” chứ không tạo trùng
                [
                    'name' => 'Inception',
                    'description' => 'A thief who steals secrets via dream-sharing technology.',
                    'rating' => 9,
                    'director_name' => 'Christopher Nolan',
                ],
                [
                    'name' => 'Spirited Away',
                    'description' => 'A girl enters a world of spirits.',
                    'rating' => 9,
                    'director_name' => 'Hayao Miyazaki',
                ],
            ];

            // map movie rows để insert/update theo name
            $movieRows = [];
            foreach ($movies as $m) {
                $movieRows[] = [
                    'name' => $m['name'],
                    'description' => $m['description'],
                    'rating' => (int)$m['rating'],
                    'director_id' => $directorIds[$m['director_name']] ?? null,
                ];
            }

            // update nếu trùng name (nếu ông muốn strict unique name thì nên add unique index, còn không thì vẫn OK cho seed)
            $conn->insertOnDuplicate(
                $movieTable,
                $movieRows,
                ['description', 'rating', 'director_id']
            );

            $movieNames = array_map(fn ($m) => $m['name'], $movies);
            $movieIds = $this->getIdMap($conn, $movieTable, 'movie_id', 'name', $movieNames);

            // ===== 4) Pivot (movie_actor) =====
            $relations = [
                // Inception
                ['movie' => 'Inception', 'actors' => ['Leonardo DiCaprio', 'Joseph Gordon-Levitt', 'Tom Hardy', 'Ken Watanabe']],
                // Interstellar (seed đơn giản, không cần đúng cast 100%)
                ['movie' => 'Interstellar', 'actors' => ['Anne Hathaway']],
                // Dark Knight
                ['movie' => 'The Dark Knight', 'actors' => ['Christian Bale', 'Heath Ledger']],
                // Pulp Fiction
                ['movie' => 'Pulp Fiction', 'actors' => ['Samuel L. Jackson', 'John Travolta']],
                // Kill Bill
                ['movie' => 'Kill Bill: Vol. 1', 'actors' => ['Uma Thurman', 'Samuel L. Jackson']],
                // T2
                ['movie' => 'Terminator 2: Judgment Day', 'actors' => ['Arnold Schwarzenegger']],
            ];

            $pivotRows = [];
            foreach ($relations as $rel) {
                $mid = $movieIds[$rel['movie']] ?? null;
                if (!$mid) {
                    continue;
                }

                foreach ($rel['actors'] as $actorName) {
                    $aid = $actorIds[$actorName] ?? null;
                    if (!$aid) {
                        continue;
                    }

                    $pivotRows[] = ['movie_id' => (int)$mid, 'actor_id' => (int)$aid];
                }
            }

            if (!empty($pivotRows)) {
                // pivot có PK (movie_id, actor_id) => insert trùng sẽ update chính nó, không chết
                $conn->insertOnDuplicate($pivotTable, $pivotRows, ['movie_id', 'actor_id']);
            }
        }

        $setup->endSetup();
    }

    private function getIdMap($conn, string $table, string $idField, string $nameField, array $names): array
    {
        if (empty($names)) {
            return [];
        }

        $select = $conn->select()
            ->from($table, [$nameField, $idField])
            ->where($nameField . ' IN (?)', $names);

        // fetchPairs: [name => id]
        return $conn->fetchPairs($select);
    }
}
