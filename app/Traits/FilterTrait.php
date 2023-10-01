<?php
namespace App\Traits;
trait FilterTrait 
{
    public function filter($perPage, $orderColumn, $orderDirection, $model ) {
        $query = $model::query();
        $user = auth()->user();
        // Logic for filtering by users here
        if( $user && in_array( $user->role, [ "artist" , "producer"] ) )
            $query = $user->songs();

        // Logic for filtering and ordering songs here
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
}