<?php
require 'bootstrap/app.php';

$leads = \Illuminate\Support\Facades\DB::table('leads')
    ->whereIn('id', [231, 232, 233, 234, 235, 236, 237, 238, 239, 240])
    ->orderBy('id')
    ->pluck('user_id');

echo "Lead assignments in order:\n";
echo implode(', ', $leads->toArray()) . "\n";

// Now let's trace the round robin logic
$activeUsers = [2, 3, 4, 5, 8, 9, 10, 13];
$startIndex = 6; // This was before the test

echo "\n\nExpected round robin sequence starting from index $startIndex:\n";
for ($i = 0; $i < 10; $i++) {
    $nextIndex = ($startIndex + $i + 1) % count($activeUsers);
    $userId = $activeUsers[$nextIndex];
    echo "Lead " . (231 + $i) . ": User $userId (index $nextIndex)\n";
}
