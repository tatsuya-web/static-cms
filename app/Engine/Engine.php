<?php

namespace App\Engine;

use eftec\bladeone\BladeOne;
use Illuminate\Support\Facades\Storage;
use App\Models\Content;
use App\Models\Template;
use Carbon\Carbon;

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

    /*
    * コンテンツ変数の予約語
    * @var array $reserved
    */
    protected $reserved = [
        'first'            => [
                                // XXX_first 
                                'pattern' => '/\_first/',
                                'replace' => '',
                            ],
        'last'             => [
                                // XXX_last
                                'pattern' => '/\_last/',
                                'replace' => '',
                            ],
        'reverse'          => [
                                // XXX_reverse
                                'pattern' => '/\_reverse/',
                                'replace' => '',
                            ],
        'limit'            => [
                                // XXX_limit[10]
                                //'/\$([a-zA-Z0-9_]+)_limit\[([0-9]+)\]/'
                                'pattern' => '/\_limit\[([0-9]+)\]/',
                                'replace' => 'value',
                            ],
        'where'            => [
                                // XXX_where['category_id','=',1]
                                // XXX_where['category_id','!=',1]
                                // XXX_where['category_id','>=',1]
                                // XXX_where['category_id','<=',1]
                                // XXX_where['category_id','>',1]
                                // XXX_where['category_id','<',1]
                                'pattern' => '/\_where\[([a-zA-Z0-9_\.]+),([=|!=|>=|<=|>|<]+),(.+)\]/',
                                'replace' => 'key,operator,value',
                            ],
        'whereBetween'     => [
                                // XXX_whereBetween[price,1,10]
                                'pattern' => '/\_whereBetween\[([a-zA-Z0-9_\.]+),([0-9]+),([0-9]+)\]/',
                                'replace' => 'key,min,max',
                            ],
        'whereDate'        => [
                                // XXX_whereDate[created_at,YYYY-MM-DD]
                                'pattern' => '/\_whereDate\[([a-zA-Z0-9_]+),(.+)\]/',
                                'replace' => 'key,date',
                            ],
        'whereCreateDate'   => [
                                // XXX_whereDate[created_at,YYYY-MM-DD]
                                'pattern' => '/\_whereCreateDate\[(.+)\]/',
                                'replace' => 'date',
                            ],
        'whereBetweenCreateDate' => [
                                // XXX_whereBetweenCreateDate[YYYY-MM-DD,YYYY-MM-DD]
                                'pattern' => '/\_whereBetweenCreateDate\[(.+),(.+)\]/',
                                'replace' => 'min,max',
                            ],
        'whereUpdateDate'   => [
                                // XXX_whereDate[created_at,YYYY-MM-DD]
                                'pattern' => '/\_whereUpdateDate\[(.+)\]/',
                                'replace' => 'date',
                            ],
        'whereBetweenUpdateDate' => [
                                // XXX_whereBetweenUpdateDate[YYYY-MM-DD,YYYY-MM-DD]
                                'pattern' => '/\_whereBetweenUpdateDate\[(.+),(.+)\]/',
                                'replace' => 'min,max',
                            ],
        'whereNot'         => [
                                // XXX_whereNot[category_id,1]
                                'pattern' => '/\_whereNot\[([a-zA-Z0-9_\.]+),(.+)\]/',
                                'replace' => 'key,value',
                            ],
        'whereIn'           => [
                                // XXX_whereIn[category_id,1:2:3]
                                'pattern' => '/\_whereIn\[([a-zA-Z0-9_\.]+),(.+)\]/',
                                'replace' => 'key,value',
                            ],
        'whereNotIn'       => [
                                // XXX_whereNotIn[category_id,1:2:3]
                                'pattern' => '/\_whereNotIn\[([a-zA-Z0-9_\.]+),(.+)\]/',
                                'replace' => 'key,value',
                            ],
        'whereLike'        => [
                                // XXX_whereLike[title,%test%]
                                'pattern' => '/\_whereLike\[([a-zA-Z0-9_\.]+),(.+)\]/',
                                'replace' => 'key,value',
                            ],
        'orderBy'          => [
                                // XXX_orderBy[created_at,desc]
                                'pattern' => '/\_orderBy\[([a-zA-Z0-9_\.]+),(asc|desc)\]/',
                                'replace' => 'key,order',
                            ],
    ];

    public function __construct()
    {
        // storage/app/views
        $this->views = Storage::path('views');

        // storage/app/cache
        $this->cache = Storage::path('cache');

        $this->mode = BladeOne::MODE_DEBUG;

        $this->blade = new CustomeBladeOne($this->views, $this->cache, $this->mode);

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
        $this->blade = new CustomeBladeOne($this->views, $this->cache, $this->mode);

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

    /*
    * @param string $template
    * @param array $data
    * @return string
    */
    public function renderString(string $template, array $data): string
    {
        // ページ変数
        $page_vars = [];

        // 予約語のquery文字列
        $reserv_query = [];

        // 予約語クエリーの解析結果
        $reserv_vars = [];

        // 予約語クエリーの変数
        $reserve_query_vars = [];

        $pages = Template::getPages();

        foreach($pages as $page) {
            $page_vars[$page->multi_value_name] = $page->contents()->get();
        }

        $matches = [];
        // Templateのtypeがpageのもののmulti_value_nameをすべて取得
        $multi_names = Template::where('type', 'page')->get()->pluck('multi_value_name')->toArray();

        foreach($multi_names as $multi_name) {
            if(preg_match_all('/' . $multi_name . '_(.+)_query/', $template, $matches)) {
                $reserv_query[$multi_name] = $matches[0];
            }
        }

        $query_count = 0;

        // $this->reserverdをループして、$reserve_queryの
        foreach($reserv_query as $multi_name => $queries) {
            foreach($queries as $query) {
                $reserv_vars[$query_count]['model'] = $multi_name;
                $reserv_vars[$query_count]['var_name'] = $multi_name . '_' . $query_count . '_query';

                // $templateの中にある$queryにマッチしたものをvar_nameに置換
                $template = str_replace($query, $reserv_vars[$query_count]['var_name'], $template);

                foreach($this->reserved as $key => $value) {
                    $replaced = [];

                    // ,で分割
                    $replace_vars = explode(',', $value['replace']);

                    // $value['pattern']にマッチしたものを$replacedに格納
                    if(preg_match($value['pattern'], $query, $replaced)) {
                        $replacese = [];

                        if(count($replace_vars) == 1 && $replace_vars[0] == '') {
                            $replacese = [];
                        } else {
                            foreach($replace_vars as $k => $replace_var) {
                                // $replaced[$key + 1]の値で]から後ろは削除
                                $replacese[$replace_var] =  preg_replace('/\].+/', '', $replaced[$k + 1]);
                            }
                        }

                        $reserv_vars[$query_count]['query'][$key][] = [
                            'key' => $key,
                            'values' => $replacese,
                        ];
                    }
                }

                $query_count++;
            }
        }

        foreach($reserv_vars as $reserv_var) {
            $contents = Template::where('multi_value_name', $reserv_var['model'])->first()->contents();
            foreach($reserv_var['query'] as $key => $query) {
                $query_value = $query[0]['values'];
                match($key) {
                    // id が最も小さいものを一件だけ取得
                    'first'                  => $contents->orderBy('id', 'asc')->take(1),
                    // id が最も大きいものを一件だけ取得
                    'last'                   => $contents->orderBy('id', 'desc')->take(1),
                    'reverse'                => $contents->orderBy('id', 'desc'),
                    'limit'                  => $contents->take($query_value['value']),
                    'where'                  => $contents->whereHas('values', function($q) use ($query_value) {
                        $keys = explode('.', $query_value['key']);
                        if(count($keys) > 1) {
                            $q->where('name', $keys[0]);
                            foreach($keys as $key) {
                                if($key == $keys[0]) {
                                    continue;
                                }
                                $q->whereHas('children', function($q) use ($keys, $key, $query_value) {
                                    $q->where('name', $key);
                                    if($key == end($keys)) {
                                        $q->where('value', $query_value['operator'], $query_value['value']);
                                    }
                                });
                            }
                        } else {
                            $q->where('name', $query_value['key'])->where('value', $query_value['operator'], $query_value['value']);
                        }
                    }),
                    'whereBetween'           => $contents->whereHas('values', function($q) use ($query_value) {
                        $keys = explode('.', $query_value['key']);
                        if(count($keys) > 1) {
                            $q->where('name', $keys[0]);
                            foreach($keys as $key) {
                                if($key == $keys[0]) {
                                    continue;
                                }
                                $q->whereHas('children', function($q) use ($keys, $key, $query_value) {
                                    $q->where('name', $key);
                                    if($key == end($keys)) {
                                        $q->where('value', '>=', $query_value['min'])->where('value', '<=', $query_value['max']);
                                    }
                                });
                            }
                        } else {
                            $q->where('name', $query_value['key'])->whereBetween('value', [$query_value['min'], $query_value['max']]);
                        }
                    }),
                    'whereDate'              => $contents->whereHas('values', function($q) use ($query_value) {
                        // $query_value['date']の値に'が含まれている場合取り除く
                        $date = str_replace("'", '', $query_value['date']);
                        $q->where('name', $query_value['key'])->whereDate('value', $date);
                    }),
                    'whereCreateDate'        => $contents->where(function($q) use ($query_value) {
                        // $query_value['date']の値に'が含まれている場合取り除く
                        $q->whereDate('created_at', $query_value['date']);
                    }),
                    'whereBetweenCreateDate' => $contents->where(function($q) use ($query_value) {
                        // $query_value['date']の値に'が含まれている場合取り除く
                        $q->whereDate('created_at', '>=', $query_value['min'])->whereDate('created_at', '<=', $query_value['max']);
                    }),
                    'whereUpdateDate'        => $contents->where(function($q) use ($query_value) {
                        // $query_value['date']の値に'が含まれている場合取り除く
                        $q->whereDate('updated_at', $query_value['date']);
                    }),
                    'whereBetweenUpdateDate' => $contents->where(function($q) use ($query_value) {
                        // $query_value['date']の値に'が含まれている場合取り除く
                        $q->whereDate('updated_at', '>=', $query_value['min'])->whereDate('updated_at', '<=', $query_value['max']);
                    }),
                    'whereNot'               => $contents->whereHas('values', function($q) use ($query_value) {
                        $keys = explode('.', $query_value['key']);
                        if(count($keys) > 1) {
                            $q->where('name', $keys[0]);
                            foreach($keys as $key) {
                                if($key == $keys[0]) {
                                    continue;
                                }
                                $q->whereHas('children', function($q) use ($keys, $key, $query_value) {
                                    $q->where('name', $key);
                                    if($key == end($keys)) {
                                        $q->where('value', '!=', $query_value['value']);
                                    }
                                });
                            }
                        } else {
                            $q->where('name', $query_value['key'])->where('value', '!=', $query_value['value']);
                        }
                    }),
                    'whereIn'                => $contents->whereHas('values', function($q) use ($query_value) {
                        $keys = explode('.', $query_value['key']);
                        if(count($keys) > 1) {
                            $q->where('name', $keys[0]);
                            foreach($keys as $key) {
                                if($key == $keys[0]) {
                                    continue;
                                }
                                $q->whereHas('children', function($q) use ($keys, $key, $query_value) {
                                    $q->where('name', $key);
                                    if($key == end($keys)) {
                                        $q->whereIn('value', explode(':', $query_value['value']));
                                    }
                                });
                            }
                        } else {
                            $q->where('name', $query_value['key'])->whereIn('value', explode(':', $query_value['value']));
                        }
                    }),
                    'whereNotIn'             => $contents->whereHas('values', function($q) use ($query_value) {
                        $keys = explode('.', $query_value['key']);
                        if(count($keys) > 1) {
                            $q->where('name', $keys[0]);
                            foreach($keys as $key) {
                                if($key == $keys[0]) {
                                    continue;
                                }
                                $q->whereHas('children', function($q) use ($keys, $key, $query_value) {
                                    $q->where('name', $key);
                                    if($key == end($keys)) {
                                        $q->whereNotIn('value', explode(':', $query_value['value']));
                                    }
                                });
                            }
                        } else {
                            $q->where('name', $query_value['key'])->whereNotIn('value', explode(':', $query_value['value']));
                        }
                    }),
                    'whereLike'              => $contents->whereHas('values', function($q) use ($query_value) {
                        $value = '%' . addcslashes($query_value['value'], '%_\\') . '%';
                        $keys = explode('.', $query_value['key']);
                        if(count($keys) > 1) {
                            $q->where('name', $keys[0]);
                            foreach($keys as $key) {
                                if($key == $keys[0]) {
                                    continue;
                                }
                                $q->whereHas('children', function($q) use ($keys, $key, $value) {
                                    $q->where('name', $key);
                                    if($key == end($keys)) {
                                        $q->where('value', 'like', $value);
                                    }
                                });
                            }
                        } else {
                            $q->where('name', $query_value['key'])->where('value', 'like', $value);
                        }
                    }),
                    'orderBy'                => $contents->orderBy($query_value['key'], $query_value['order']),
                };
            }

            $reserve_query_vars[$reserv_var['var_name']] = $contents->get();
        }

        $vars = array_merge($reserve_query_vars, $page_vars);

        return $this->blade->runString($template, $vars);
    }
}