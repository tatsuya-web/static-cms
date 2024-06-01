<?php

namespace Tests\Unit\Engine;

use PHPUnit\Framework\TestCase;
use App\Engine\Engine;

class EngineTest extends TestCase
{
    public function test_HTMLを入力してHTMLが返ってくることを確認する(): void
    {
        $engine = Engine::factory();

        // htmlを$html変数に代入する 改行なども含める
        $html = <<<HTML
                <!DOCTYPE html>
                <html lang="ja">
                <head>
                    <meta charset="UTF-8">
                    <title>Document</title>
                </head>
                <body>
                    <h1>テスト</h1>
                </body>
                </html>
                HTML;

        // $htmlを引数にしてEngineクラスのrenderメソッドを実行する
        $result = $engine->render($html);

        // $resultが$htmlと同じであることを確認する
        $this->assertSame($html, $result);
    }

    public function test_HTMLに変数タグを埋め込むと変数タグが展開された状態でHTMLが返ってくることを確認する(): void
    {
        $engine = Engine::factory();

        // htmlを$html変数に代入する 改行なども含める
        $html = <<<HTML
                <!DOCTYPE html>
                <html lang="ja">
                <head>
                    <meta charset="UTF-8">
                    <title>Document</title>
                </head>
                <body>
                    <h1>[:= title :]</h1>
                </body>
                </html>
                HTML;

        $want = <<<HTML
                <!DOCTYPE html>
                <html lang="ja">
                <head>
                    <meta charset="UTF-8">
                    <title>Document</title>
                </head>
                <body>
                    <h1>テスト</h1>
                </body>
                </html>
                HTML;

        $engine->set('title', 'テスト');

        // $htmlを引数にしてEngineクラスのrenderメソッドを実行する
        $result = $engine->render($html);

        // $resultが$htmlと同じであることを確認する
        $this->assertSame($want, $result);
    }
}
