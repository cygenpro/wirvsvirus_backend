<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Services\PointsService;
use Carbon\Carbon;

class PointsTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testPointsService() {
        $this->assertArrayHasKey('per_minute', config('points'));
        $this->assertArrayHasKey('bonus', config('points'));
        $this->assertTrue(class_exists(App\Services\PointsService::class));
        $this->assertTrue(method_exists(App\Services\PointsService::class, 'calculatePoints'));
        $this->assertArrayHasKey('12_hours', config('points.bonus'));
        $this->assertArrayHasKey('48_hours', config('points.bonus'));
    }

    public function testCalculatePoints() {
        $pointsService = new PointsService();
        $dtEntered = Carbon::now();
        $dtEntered->subHour();
        $dtLeft = Carbon::now();
        $this->assertTrue(is_int($pointsService->calculatePoints($dtEntered, $dtLeft)));
        $this->assertTrue($pointsService->calculatePoints($dtEntered, $dtLeft) == 60 * config('points.per_minute'));
        $this->assertTrue($pointsService->calculatePoints($dtLeft, $dtEntered) == 0);
        $this->assertTrue($pointsService->calculatePoints($dtLeft, $dtLeft) == 0);
        $dtEntered->subHours(11);
        $this->assertTrue($pointsService->calculatePoints($dtEntered, $dtLeft)
            == (720 * config('points.per_minute')) + config('points.bonus.12_hours'));
        $dtEntered->subHours(36);
        $this->assertTrue($pointsService->calculatePoints($dtEntered, $dtLeft)
            == (2880 * config('points.per_minute')) + 4 * config('points.bonus.12_hours') + config('points.bonus.48_hours'));
    }

}
