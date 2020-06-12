<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Component;

use App\Controller\Component\DiscordComponent;
use Cake\Controller\ComponentRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\Component\DiscordComponent Test Case
 */
class DiscordComponentTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Controller\Component\DiscordComponent
     */
    protected $Discord;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $registry = new ComponentRegistry();
        $this->Discord = new DiscordComponent($registry);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Discord);

        parent::tearDown();
    }
}
