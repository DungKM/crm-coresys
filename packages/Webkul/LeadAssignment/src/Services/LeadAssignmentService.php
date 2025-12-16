<?php

namespace Webkul\LeadAssignment\Services;

use Illuminate\Support\Facades\DB;

class LeadAssignmentService
{
    /**
     * Return user id chosen for the next lead, or null if disabled / no users.
     */
    public function assignUserId(): ?int
    {
        $config = $this->getConfig();

        if (!$config['enabled'] || empty($config['active_users'])) {
            return null;
        }

        return $config['method'] === 'weighted'
            ? $this->assignWeighted($config['active_users'], $config['weights'])
            : $this->assignRoundRobin($config['active_users']);
    }

    /**
     * Load and normalize lead assignment config from core_config.
     */
    protected function getConfig(): array
    {
        $rows = DB::table('core_config')
            ->whereIn('code', [
                'lead_assignment.enabled',
                'lead_assignment.method',
                'lead_assignment.active_users',
                'lead_assignment.weights',
                'lead_assignment.last_assigned_index',
            ])
            ->pluck('value', 'code')
            ->toArray();

        $activeUsers = json_decode($rows['lead_assignment.active_users'] ?? '[]', true) ?: [];

        return [
            'enabled' => (int) ($rows['lead_assignment.enabled'] ?? 0),
            'method' => $rows['lead_assignment.method'] ?? 'round_robin',
            'active_users' => array_map('intval', $activeUsers),
            'weights' => json_decode($rows['lead_assignment.weights'] ?? '[]', true) ?: [],
            'last_assigned_index' => (int) ($rows['lead_assignment.last_assigned_index'] ?? 0),
        ];
    }

    /**
     * Round-robin assignment with index persisted in core_config.
     * 
     * Logic:
     * 1. Load current last_assigned_index with database lock
     * 2. Calculate next index: (last + 1) % count(users)
     * 3. Get user at next index
     * 4. Update last_assigned_index to next index
     * 5. Return user_id
     * 
     * This ensures fair distribution where each user gets ⌊N/M⌋ or ⌈N/M⌉ leads
     * (where N = total leads, M = number of active users)
     * 
     * Uses database transaction with lockForUpdate() to prevent race conditions
     * when multiple leads are created simultaneously.
     */
    protected function assignRoundRobin(array $activeUsers): ?int
    {
        if (!count($activeUsers)) {
            return null;
        }

        return DB::transaction(function () use ($activeUsers) {
            $row = DB::table('core_config')
                ->where('code', 'lead_assignment.last_assigned_index')
                ->lockForUpdate()
                ->first();

            $lastIndex = $row ? (int) $row->value : 0;
            $nextIndex = ($lastIndex + 1) % count($activeUsers);
            $userId = $activeUsers[$nextIndex];

            // Update the index for next assignment
            DB::table('core_config')
                ->where('code', 'lead_assignment.last_assigned_index')
                ->update(['value' => $nextIndex, 'updated_at' => now()]);

            return $userId;
        });
    }

    /**
     * Weighted random assignment using percentage weights.
     * 
     * Logic:
     * 1. Build a pool array with user_ids repeated by their weight
     * 2. Randomly pick one user_id from the pool
     * 3. Probability is proportional to weight
     * 
     * Note: This method does NOT use last_assigned_index.
     * Each assignment is independent and random based on weights.
     * 
     * Example: 
     * - User A with 5 stars (100%): appears 100 times in pool
     * - User B with 3 stars (60%):  appears 60 times in pool
     * - Random pick: A has 100/160 = 62.5% chance, B has 60/160 = 37.5%
     */
    protected function assignWeighted(array $activeUsers, array $weights): ?int
    {
        if (!count($activeUsers)) {
            return null;
        }

        $pool = [];

        foreach ($activeUsers as $userId) {
            $weight = (int) ($weights[$userId] ?? 0);
            $weight = max(1, min(100, $weight)); // clamp to [1, 100]
            $pool = array_merge($pool, array_fill(0, $weight, $userId));
        }

        if (!count($pool)) {
            return null;
        }

        return $pool[random_int(0, count($pool) - 1)];
    }
}
