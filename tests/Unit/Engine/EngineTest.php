<?php

namespace Tests\Unit\Engine;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use eftec\bladeone\BladeOne;
use App\Engine\Engine;
use Illuminate\Support\Facades\Storage;

class EngineTest extends TestCase
{
    private string $views;
    private string $cache;
    private string $wants;

    public function setUp(): void
    {
        parent::setUp();

        $this->views = __DIR__ . '/views';
        $this->cache = __DIR__ . '/cache';

        $this->wants = __DIR__ . '/wants';
    }

    private function getWants(string $template): string
    {
        return file_get_contents($this->wants . '/' . $template . '.html');
    }

    public function test_viewsとcacheをセットしてbladeファイルをレンダリングする() :void
    {
        $engine = Engine::factory(
            $this->views,
            $this->cache,
        );

        $template = 'test_01';

        $wants = $this->getWants($template);

        $result = $engine->render($template, ['title' => 'test', 'message' => 'Hello World !']);

        $this->assertEquals($wants, $result);
    }

    public function test_foearchを使用してループ処理() :void
    {
        $engine = Engine::factory(
            $this->views,
            $this->cache,
        );

        $messages = [
            'Hello World 1',
            'Hello World 2',
            'Hello World 3',
            'Hello World 4',
            'Hello World 5',
        ];

        $template = 'test_02';

        $wants = $this->getWants($template);

        $result = $engine->render($template, ['title' => 'test', 'messages' => $messages]);

        $this->assertEquals($wants, $result);
    }

    public function test_issetを使用して変数の存在確認() :void
    {
        $engine = Engine::factory(
            $this->views,
            $this->cache,
        );

        $template = 'test_03';

        $wants = $this->getWants($template);

        $result = $engine->render($template, ['title' => 'test', 'message' => 'Hello World !']);

        $this->assertEquals($wants, $result);
    }

    public function test_partsを利用して共通パーツを展開する() :void
    {
        $engine = Engine::factory(
            $this->views,
            $this->cache,
        );

        $template = 'test_04';

        $wants = $this->getWants($template);

        $result = $engine->render($template, ['title' => 'test', 'message' => 'Hello World !', 'header_title' => 'Header Title']);

        $this->assertEquals($wants, $result);
    }

    public function test_partsを利用して共通パーツを複数展開しifやforeachを使用する() :void
    {
        $engine = Engine::factory(
            $this->views,
            $this->cache,
        );

        $template = 'test_05';

        $wants = $this->getWants($template);

        $nav = [
            ['title' => 'Home', 'url' => '/'],
            ['title' => 'About', 'url' => '/about'],
            ['title' => 'Contact', 'url' => '/contact'],
        ];

        $result = $engine->render($template, ['title' => 'test', 'message' => 'Hello World !', 'header_title' => 'Header Title', 'footer_title' => 'Footer Title', 'nav' => $nav]);

        // dd($result);

        $this->assertEquals($wants, $result);
    }
}