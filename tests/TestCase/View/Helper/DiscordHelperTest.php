<?php
declare(strict_types=1);

namespace App\Test\TestCase\View\Helper;

use App\View\Helper\DiscordHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;

/**
 * App\View\Helper\DiscordHelper Test Case
 */
class DiscordHelperTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\View\Helper\DiscordHelper
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
        $view = new View();
        $this->Discord = new DiscordHelper($view);
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
