<?php

namespace Tests\Feature;

use App\Enums\BanReason;
use App\Models\Report;
use App\Models\User;

class ReportsTest extends FeatureTestCase
{
    public function testStore()
    {
        $this->post(route('reports.store'), [
            'task_id' => $this->myTask->id,
        ]);

        $this->assertTrue($this->myTask->fresh()->reports()->count() === 1);
    }

    public function testTaskBannedAfterReports()
    {
        foreach (range(1, Report::LIMIT) as $i) {
            $user2 = factory(User::class)->create();
            $user2->reports()->create([
                'task_id' => $this->myTask->id
            ]);
            $this->assertTrue($this->myTask->reports()->count() === $i);
            if ($i === Report::LIMIT) {
                $this->assertTrue($this->myTask->fresh()->ban_reason === BanReason::Report);
            } else {
                $this->assertFalse($this->myTask->fresh()->is_banned);
            }
        }
    }
}
