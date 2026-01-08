<?php

namespace Tests\Models;

use App\Models\Link;
use App\Models\LinkList;
use App\Models\Tag;
use App\Models\User;
use App\Repositories\LinkRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LinkDeleteTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    /** @var User */
    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_correct_link_deletion(): void
    {
        $this->be($this->user);

        $link = Link::factory()->create();
        $tag = Tag::factory()->create();
        $list = LinkList::factory()->create();

        $link->tags()->attach([$tag->id]);
        $link->lists()->attach([$list->id]);

        $deletionResult = LinkRepository::delete($link);

        $this->assertTrue($deletionResult);
        $this->assertDatabaseCount('link_lists', 0);
        $this->assertDatabaseCount('link_tags', 0);
    }
}
