<?php

// app/Services/SongService.php

namespace App\Services;

use App\Models\Song;

class SongService {
    public function getAllSongs() {
        return Song::all();
    }

    public function getPaginatedAndOrderedSongs($perPage, $orderColumn, $orderDirection) {
        // Logic for filtering and ordering songs here
        $query = Song::query();

        if (in_array($orderColumn, ['latest', 'oldest'])) {
            $query->orderBy($orderColumn === 'latest' ? 'created_at' : 'created_at', $orderDirection);
        } else {
            // Handle ordering by specific column here
            if (!in_array($orderDirection, ['asc', 'desc'])) {
                throw new \InvalidArgumentException('Invalid order direction. Use "asc" or "desc".');
            }

            $query->orderBy($orderColumn, $orderDirection);
        }

        return $query->paginate($perPage);
    }

    // Other methods related to songs can be added here
}
