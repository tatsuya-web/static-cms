<?php

namespace App\Engine;

use eftec\bladeone\BladeOne;
use Illuminate\Support\Facades\Storage;

class Engine
{
    /*
    * @var string $views
    */
    private string $views;

    /*
    * @var string $cache
    */
    private string $cache;

    /*
    * @var string $mode
    */
    private int $mode;

    /*
    * @var BladeOne $blade
    */
    private BladeOne $blade;

    public function __construct()
    {
        // storage/app/views
        $this->views = Storage::path('views');

        // storage/app/cache
        $this->cache = Storage::path('cache');

        $this->mode = BladeOne::MODE_DEBUG;

        $this->blade = new BladeOne($this->views, $this->cache, $this->mode);

        $this->setMethod();
    }

    /*
    * @param string|null $views
    * @param string|null $cache
    * @param int|null $mode
    * @return Engine
    */
    public static function factory(?string $views = null, ?string $cache = null, ?int $mode = null): Engine
    {
        $engine = new Engine();

        if (!empty($views)) {
            $engine->setViews($views);
        }

        if (!empty($cache)) {
            $engine->setCache($cache);
        }

        if (!empty($mode)) {
            $engine->setMode($mode);
        }

        if (!empty($views) || !empty($cache) || !empty($mode)) {
            $engine->refresh();
        }

        return $engine;
    }

    /*
    * メゾットを追加
    */
    private function setMethod(): void
    {
        $this->blade->directive('parts', function ($expression) {
            return "<?php echo app('App\\Engine\\Engine')
                        ->setViews('" . $this->views . "')
                        ->setCache('" . $this->cache . "')
                        ->setMode('" . $this->mode . "')
                        ->refresh()
                        ->deployParts($expression); ?>";
        });
    }

    public function setViews(string $views): self
    {
        $this->views = $views;

        return $this;
    }

    public function setCache(string $cache): self
    {
        $this->cache = $cache;

        return $this;
    }

    public function setMode(int $mode): self
    {
        $this->mode = $mode;

        return $this;
    }

    public function renderParts(string $name, array $data = []): string
    {
        return $this->deployParts($name, $data);
    }

    /*
    * @param $expression
    * @return string
    */
    public function deployParts($arg_name, $arg_data): string
    {
        return $this->blade->run('cmn_' . $arg_name, $arg_data);
    }

    /*
    * BladeOneのインスタンス再度生成
    */
    public function refresh(): self
    {
        $this->blade = new BladeOne($this->views, $this->cache, $this->mode);

        $this->setMethod();

        return $this;
    }

    /*
    * @param string $template
    * @param array $data
    * @return string
    */
    public function render(string $template, array $data): string
    {
        return $this->blade->run('page_' . $template, $data);
    }
}