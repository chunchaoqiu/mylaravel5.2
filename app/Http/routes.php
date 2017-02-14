<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});



Route::get('/test/index', 'UserController@index');
Route::get('/test/chunk', 'UserController@chunk');
Route::get('/test', 'UserController@insert');
Route::get('/test/update', 'UserController@update');
Route::get('/test/delete', 'UserController@delete');

Route::get('/test/transaction', 'UserController@transaction');


Route::get('/test/save', 'UserController@save');

Route::get('/test/create', 'UserController@create');

Route::get('/test/trash', 'UserController@trash');//软删除

Route::get('/test/hasOne', 'UserController@hasOne');

Route::get('test/api', function(){
    echo 'app_path:' . app_path('new/catch') . "<br>";
    echo 'storage_path:' . storage_path() . "<br>";
    echo 'base_path:' . base_path() . "<br>";
});

Route::get('/test/provider', function(){
    app('mylog')->write('my first provider log');

//    $logService = app()->make('mylog');
    $logService = app()->make('mylog');

    var_dump($logService);

    $logService->write('destruct my logservice ');

});

Route::get('/test/app', function(){
    $app = app();
    dd($app);

});

//用户授权(使用方法1)
Route::get('/test/gate1', function(){

    if(Gate::allows('write')){
        app('mylog')->write('allow: allow to write gate log');
    }else{
        app('mylog')->write('deny: not allow to write gate log');
    }

});

//用户授权(使用方法2)
Route::get('/test/gate2', function(Request $request){

    if($request->user()->can('write')){
        app('mylog')->write('can: allow to write gate log');
    }else{
        app('mylog')->write('cannot: not allow to write gate log');
    }

});

Route::get('/test/authorize', function(Request $request){

    if($request->user()->can('write')){
        app('mylog')->write('can: allow to write gate log');
    }else{
        app('mylog')->write('cannot: not allow to write gate log');
    }

});


Route::get('/test/redis', function(Request $request){

//    Cache::put('name', 'qcc', 1);//增加缓存
//    Cache::add('age', 26, 1);//使用add时，只有key不存在时才会添加成功true
//    Cache::forever('sex', 'male');//持久化缓存
//    Cache::forget('sex');//删除缓存
//    Cache::flush();//清除所有缓存
//    var_dump(Cache::get('name'));
//    echo Cache::get('age') . "<br>";
//    echo Cache::get('sex') . "<br>";


//    Cache::tags(['star'])->put('name','chenglong',2);//缓存标签
//    echo Cache::tags(['star'])->get('name');//获取缓存标签


//    Cache::store('redis')->put('cache', 'redis',2);
//    echo Cache::store('redis')->get('cache');



//    $redis = app('redis');
//    $redis->set('band', 'bbc');
//    echo $redis->get('band');
//    dd($redis);


});

//集合
Route::get('/test/collection', function(){
    $collect = collect([1,2,3]);

    $collectArr = $collect->all();//返回数组

    $avg = $collect->avg();//返回数值平均值
    $aveObj = collect([['name' => 'a', 'age' => 22],['name' => 'b', 'age' => 24]])->avg('age');


    $chunks = collect([1,2,3,4,5,6,7,8,9])->chunk(2)->toArray();//一个集合分割成多个小尺寸的小集合

    $collapse = collect([[1, 2, 3], [4, 5, 6], [7, 8, 9]])->collapse()->all();

    $isContain = collect([['name' => 'qcc'],['name' => 'cnn']])->contains('name', 'qcc');//是否包含键值对
    $isContainValue = collect(['name' => 'qcc'])->contains('qcc');//判断是否包含某选项

    $isContainCall = collect([1,2,3])->contains(function($key, $value){
        return $value == 4;
    });//contains 回调

    $count = collect([1,2,3])->count();//元素个数


    $diff = collect([1,2,3,4])->diff([1,2])->all();//获取数组间交集(保留键名)

    $newItems = collect([1,2,3])->each(function($item, $key){

    })->all();


    $exceptItems = collect(['name' => 'qcc', 'age' => 26])->except(['age'])->all();//排除返回键
    $onlyItems = collect(['name' => 'qcc', 'age' => 26])->only(['age'])->all();//返回指定键

    $filterItems = collect([['name' => 'qcc', 'age' => 26],['name' => 'abc', 'age' => 22]])->filter(function($item){
        return $item['age'] > 22;
    })->all();//筛选

    $firstItem = collect([1, 2, 3, 4])->first(function ($key, $value) {
        return $value > 2;
    });//返回第一项

    $flatten = collect(['name' => 'taylor', 'languages' => ['php', 'javascript']])->flatten()->all();//将多维度的集合变成一维的


    dd($flatten);
});

//日志记录
Route::get('/test/log', function(){
    $error = '日志测试';
    Log::info($error);
    Log::emergency($error);
    Log::alert($error);
    Log::critical($error);
    Log::error($error);
    Log::warning($error);
    Log::notice($error);
    Log::info($error);
    Log::debug($error);
    Log::info('User failed to login.', ['id' => 2]);
});

//事件触发
Route::get('/test/event', function(){
    Event::fire(new \App\Events\LogEvent("this is my first event"));
});

//文件系统
Route::get('/test/filesystem', function(){

});

//读写分离
Route::get('/test/migrate', function(){
    DB::table('jobs')->insert(['jobName' => 'job1']);

    $job = \App\Models\Job::find(1);

    echo $job->jobName . "<br>";

});

//任务队列
Route::get('/test/queue', function(){
    dispatch(new App\Jobs\LogWriteJob());

    return 'job done';
});

//多个数据库配置
Route::get('/test/database', function(){
    $domain = DB::connection('yf_new_console')->table('domains')->first();

    dd($domain);
});

Route::get('/hi', function(){
    echo "hello world";

})->name('hi');

Route::get('/test/echart', function(){
//    return view('first_test');
//    return Response()->json(['ok' => true]);

//    DB::table('gate')->chunk(1,function($items){
//        foreach ($items as $item){
//            echo $item->name . "<br>";
//        }
//    });

//    file_put_contents(storage_path('a.csv'),'abcd', FILE_APPEND);

//    return response()->download(storage_path('a.csv'));
//    return redirect()->route('hi');

    dd(app());
})->middleware('throttle');

Route::get('/test/app', function(){
    $logService = app()->make('log');

    dd($logService);
});

Route::get('/test/my', function(){
    call_user_func(function($name,$age){
        echo "hello this world:" . $name . "_" . $age;
    },"qcc",25);

    App::path();
});


//本地测试
Route::get('/test/search', function(){
    $esclient = Elasticsearch\ClientBuilder::create()
        ->setHosts(["localhost:9200"])
        ->build();
//    $params = [
//        'index' => 'twitter',
//        'type' => 'tweet',
//        'body' => [
//            'query' =>
//            [
//                'match_all' => new stdClass(),
//            ]
//        ]
//    ];

//    echo $esclient->get($params);

    $search = new \ONGR\ElasticsearchDSL\Search();
    $matchAll = new \ONGR\ElasticsearchDSL\Query\MatchAllQuery();
    $search->addQuery($matchAll);

//    dd($search->toArray());

    $params = [
        'index' => 'twitter',
        'type' => 'tweet',
        'body' => $search->toArray()
    ];

    $response = $esclient->search($params);
    dd($response);

});

//aas查询demo
Route::get('/test/search_demo', function(){
    $esclient = Elasticsearch\ClientBuilder::create()
        ->setHosts([['host' => "localhost:9200","port" => "80"]])
        ->build();
    $params = [
        'index' => 'rtmp-20161208',
        'type' => 'd_play_status',
        'body' => [
            'query' =>
                [
                    'term' => ['host' => 'yf-push.v.momocdn.com'],
                ]
        ]
    ];

    //使用match只可指定一个字段
    $params1 = [
        'index' => 'rtmp-20161208',
        'type' => 'd_play_status',
        'body' => [
            'query' =>
                [
                    'match' => [
                        'host' => 'yf-push.v.momocdn.com',
//                        'stream' => 'm_b69887e699565cf71481126370376107'
                    ]
                ]
        ]
    ];

    //使用multi_match 要指定field查询内容
    $params2 = [
        'index' => 'rtmp-20161208',
        'type' => 'd_play_status',
        'body' => [
            'query' =>
                [
                    'multi_match' => [
                        'query' => 'yf-push.v.momocdn.com',
                        'fields' => ['host']
                    ]
                ]
        ]
    ];

    $params3 = [
        'index' => 'rtmp-20161208',
        'type' => 'd_play_status',
        'body' => [
            'query' =>
                [
                    'bool' => [
                        'should' => [
                            ['term' => ['id' => 'b6e7cc38a4731044f8076e74b0b65724']],
                            ['term' => ['id' => '7e6f7ac85073b0344626255779068d3c']]
                        ]
                    ]
                ]
        ]
    ];

    //should多种term使用组合，相当于sql中or条件筛选
    $params4 = [
        'index' => 'rtmp-20161208',
        'type' => 'd_play_status',
        'body' => [
            'query' =>
                [
                    'bool' => [
                        'should' => [
                            ['term' => ['id' => 'b6e7cc38a4731044f8076e74b0b65724']],
                            ['term' => ['host' => 'yf-push.v.momocdn.com']]
                        ]
                    ]
                ]
        ]
    ];

    //must多种term使用组合，相当于sql中or条件筛选
    $params5 = [
        'index' => 'rtmp-20161208',
        'type' => 'd_play_status',
        'body' => [
            'query' =>
                [
                    'bool' => [
                        'must' => [
                            ['term' => ['id' => 'b6e7cc38a4731044f8076e74b0b65724']],
                            ['term' => ['host' => 'yf-push.v.momocdn.com']]
                        ]
                    ]
                ]
        ]
    ];

    new \ONGR\ElasticsearchDSL\Query\MatchAllQuery();

    $response = $esclient->search($params5);

    dd($response);

});


Route::group(['prefix' => 'dsl'], function(){
    Route::get('/term', 'EsController@query1');//term
    Route::get('/match', 'EsController@query2');
    Route::get('/filter', 'EsController@query3');
    Route::get('/aggs', 'EsController@query4');

    //实战aggs
    Route::get('/aggs_uv', 'EsController@query5');

    //实战dsl
    Route::get('/aggs_dsl_uv', 'EsController@query6');
    Route::get('/aggs_pro', 'EsController@query7');

});


Route::get('/test/collect', function(){

    $collection = collect([
        ['product_id' => 'prod-100', 'name' => 'Desk', '_source' => ['name' => 'qcc', 'age' => 25]],
        ['product_id' => 'prod-200', 'name' => 'Chair', '_source' => ['name' => 'bbc', 'age' => 22]],
    ]);

    $plucked = $collection->pluck('_source');

    dd($plucked->all());
});


Route::get('/test/aas_redis', function(){

//    $redis = Redis::connection('aas_redis');

//    dd(app()->make('redis'));
//    dd($redis);
//    app()->make('redis')->rpush('mine', ['a','b']);

//    echo app('redis')->hget('key2','name');

    app('redis')->del('fd_1');
});


//关联关系
Route::get('/test/relate', function(){
//    $shop = \App\Shop::find(1);
//    if(!is_null($shop)){
//        foreach ($shop->products as $product){
//            echo $product->productname. '<br>';
//        }
//    }

//    $newProduct = new \App\Product();
//    $newProduct->productname = 'p2';
//    $shop = \App\Shop::find(1);
//
//    $shop->products()->save($newProduct);

//    $shop = \App\Shop::find(1);
//    $shop->products()->create(
//        ['productname' => '书包']
//    );

//    $shop = \App\Shop::find(1);
//    $shop->products()->detach(5);//移除关联中间表
//    $shop = \App\Shop::find(2);
//    $shop->products()->attach([1 => ['expire' => 'n']]);//增加关联中间表



    DB::enableQueryLog();

//    $shop = \App\Shop::find(1);
//    $shop->products()->sync([2,4]);//只有数组中的id还存在中间表
//    $products = \App\Shop::with(['products' => function($query){
////        $query->where('expire', '=','n');
//    }]);//渴求式加载

//    $shops = \App\Shop::with('products')->get();//带条件的渴求式加载
    $shops = \App\Shop::where('id' ,1)->with(['products' => function($query){
        $query->where('expire', 'n');
    }])->get();//渴求式加载
    foreach ($shops as $shop){
        foreach ($shop->products as $product){
            echo $product->productname . "<br>";
        }
    }

    dd(DB::getQueryLog());
});

//hprose
Route::group(['prefix' => '/test/hp'], function(){

//    $client = \Hprose\Http\Client::create('http://hprose.com/example/', false);
//
//    var_dump($client->hello("world"));
//    var_dump($client->sum(1, 2, 3));


    Route::get('/start', function(){
        $RpcClient = app('RpcClient');
        $client = $RpcClient->use('http://192.168.3.197:8072/api',false);
//        $client->write('hprose note');

        echo $client->sayHi(new \Hprose\InvokeSettings(array('mode' => \Hprose\ResultMode::Serialized))) . "<br>";
        echo $client->hello('world', new \Hprose\InvokeSettings(array('mode' => \Hprose\ResultMode::Normal)));
    });

    Route::get('/middleware', function(){

        $logHandler = function($name, array &$args, \stdClass $context, \Closure $next) {
            file_put_contents(storage_path('logs/myinput.log'), "before invoke:" . "\r\n", FILE_APPEND);
            file_put_contents(storage_path('logs/myinput.log'), $name . "\r\n", FILE_APPEND);
            file_put_contents(storage_path('logs/myinput.log'), json_encode($args) . "\r\n", FILE_APPEND);

            $result = $next($name, $args, $context);
            file_put_contents(storage_path('logs/myinput.log'), "after invoke:" . "\r\n", FILE_APPEND);
            if (Hprose\Future\isFuture($result)) {
                $result->then(function($result) {
//                    error_log(var_export($result, true));

                    file_put_contents(storage_path('logs/myinput.log'), 'future_' .json_encode($result) . "\r\n", FILE_APPEND);
                });
            }
            else {
//                error_log(var_export($result, true));

                file_put_contents(storage_path('logs/myinput.log'), json_encode($result) . "\r\n", FILE_APPEND);
            }
            return $result;
        };

        $cacheSettings = new \Hprose\InvokeSettings(array("userdata" => array("cache" => true)));
        $RpcClient = app('RpcClient');
        $client = $RpcClient->use('http://192.168.3.197:8072/api',false);
        $client->addInvokeHandler(array(new App\Handles\CacheHandle(), 'handle'));
        $client->addInvokeHandler($logHandler);
//        echo $client->sayHi(new \Hprose\InvokeSettings(array('mode' => \Hprose\ResultMode::Serialized))) . "<br>";
        echo $client->hello('world', $cacheSettings);
        echo $client->hello('world', $cacheSettings);
        echo $client->hello("no cache world");
        echo $client->hello("no cache world");
    });


});

//hprose发布服务
Route::any('/api', function() {
    $logHandler = function($name, array &$args, \stdClass $context, \Closure $next) {
        file_put_contents(storage_path('logs/myoutput.log'), "before invoke:" . "\r\n", FILE_APPEND);
        file_put_contents(storage_path('logs/myoutput.log'), $name . "\r\n", FILE_APPEND);
        file_put_contents(storage_path('logs/myoutput.log'), json_encode($args) . "\r\n", FILE_APPEND);

        $result = $next($name, $args, $context);
        file_put_contents(storage_path('logs/myoutput.log'), "after invoke:" . "\r\n", FILE_APPEND);
        if (Hprose\Future\isFuture($result)) {
            $result->then(function($result) {
//                error_log(var_export($result, true));
                file_put_contents(storage_path('logs/myoutput.log'), 'future_' .json_encode($result) . "\r\n", FILE_APPEND);
            });
        }
        else {
//            error_log(var_export($result, true));

            file_put_contents(storage_path('logs/myoutput.log'), json_encode($result) . "\r\n", FILE_APPEND);
        }
        return $result;
    };


    $server = app('RpcServer');

    // Hprose support XmlRPC and JsonRPC
    // if want support XmlRpc
    $server->addFilter(new Hprose\Filter\XMLRPC\ServiceFilter());
    // if want support JsonRpc
    $server->addFilter(new Hprose\Filter\JSONRPC\ServiceFilter());

//    $server->addFilter(new App\Filters\LogFilter());//过滤器

    $server->addInvokeHandler($logHandler);

    $server->addInstanceMethods(new \App\Services\LogService());
    $server->start();
});


Route::group(['prefix' => 'mongo'], function(){

    Route::get('/test', function(){
        $mongo = DB::connection('mongodb');
        $data = $mongo->table('rtmp_20170112')->where('addition.operator', '电信')->get();//支持嵌套查询
//        dd($data);

        return $data;
    });

    Route::get('/test1', function(){
        $mongo = DB::connection('mongodb');
        $data = $mongo->table('rtmp_20170119')->take(10)->get();
//        $search = '46455_xR2MZ';
//        $data = $mongo->table('rtmp_20170113')->where(function($query) use($search) {
//            $query->where('task_list.url', 'like', "%{$search}%")
//                ->orWhere('task_list.PlayUrl', 'like', "%{$search}%");
//        })->take(20)->get();


        dd($data);
    });

    Route::get('/test2', function(){
        $mongo = DB::connection('mongodb');
        $data = $mongo->table('task')->max('created_at');
        dd($data);
    });

    Route::get('/test3', function(){
        $mongo = DB::connection('mongodb');
        $data = $mongo->table('rtmp_20170112')->take(10)->get();

        $re = [];
        foreach ($data as $item){
            if(!isset($item['type'])){
                continue;
            }

            $isContain = false;
            $taskList = $item['task_list'];
            foreach ($taskList as $subItem){
                $url = $subItem['url'];
                if(str_contains($url, 'yfrtmpup.other.cdn.zhanqi.tv')){
                    $isContain = true;
                    break;
                }
            }

            if($isContain){
                $re[] = $item;
            }
        }
        dd($re);
    });

    Route::get('/test4', function(){
        $mongo = DB::connection('mongodb');
        $data = $mongo->table('rtmp_20170112')->distinct('type')->get();//获取type节点类型

        dd($data);
    });

    Route::get('/condition', 'RtmpController@getConditionData');
    Route::get('/summary', 'RtmpController@getSummaryData');

});

//guzzlehttp
Route::group(['prefix' => '/test/guzzle'], function(){

    Route::get('/client', function(){

        $client = new \GuzzleHttp\Client(['base_uri' => 'http://192.168.3.197:8072/mongo/']);

        $response = $client->request('GET', 'test');

        return $response->getBody()->getContents();
    });


    Route::get('/asyn', function(){

        $output = "";

        $client = new \GuzzleHttp\Client(['base_uri' => 'http://192.168.3.197:8072/mongo/']);
        $promise = $client->requestAsync('GET', 'test');

        $promise->then(
            function (Psr\Http\Message\ResponseInterface $res) use(&$output){

                file_put_contents('/tmp/tmp.log', "abc \r\n", FILE_APPEND);


                var_dump($res->getStatusCode());
                $output = $res->getStatusCode();
                return $res->getStatusCode() . "\n";
            },
            function (GuzzleHttp\Exception\RequestException $e) {
                echo $e->getMessage() . "\n";
                echo $e->getRequest()->getMethod();
            }
        );

        echo "hello";
    });


    //同步处理返回结果
    Route::get('/pool', function(){
        $requests = function ($total) {
            $uri = 'http://192.168.3.197:8072/mongo/test';
            for ($i = 0; $i < $total; $i++) {
                yield new GuzzleHttp\Psr7\Request('GET', $uri);
            }
        };

        $client = new \GuzzleHttp\Client();
        $pool = new GuzzleHttp\Pool($client, $requests(100), [
            'concurrency' => 5,
            'fulfilled' => function ($response, $index) {
                // this is delivered each successful response
                echo $index . '_' . $response->getStatusCode() . "\n";
            },
            'rejected' => function ($reason, $index) {
                // this is delivered each failed request
                echo $reason->getMessage() . "\n";
            },
        ]);

        // Initiate the transfers and create a promise
        $promise = $pool->promise();

        // Force the pool of requests to complete.
        $promise->wait();

    });

    //异步处理返回结果
    Route::get('/asyc_pool', function(){

        $client = new \GuzzleHttp\Client();

        $requests = function ($total) use ($client) {
            $uri = 'http://192.168.3.197:8072/mongo/test';
            for ($i = 0; $i < $total; $i++) {
                yield function() use ($client, $uri) {
                    return $client->getAsync($uri);
                };
            }
        };

        $pool = new GuzzleHttp\Pool($client, $requests(100), [
            'fulfilled' => function ($response, $index) {
                // this is delivered each successful response
                echo $index . '_' . $response->getStatusCode() . "\n";
            },
            'rejected' => function ($reason, $index) {
                // this is delivered each failed request
                echo $reason->getMessage() . "\n";
            },
        ]);

        // Initiate the transfers and create a promise
        $promise = $pool->promise();

        // Force the pool of requests to complete.
        $promise->wait();
    });


});




Route::get('/test/tmp', function(){
//    $collection = collect(['a' => 2, 'b' => 4, 'c' => 3]);
//
//    $collection->splice(0, 1);
//
//    dd($collection->all());
//
//    $data = $collection->sort(function($a,$b){
//        echo $a . '_' . $b . "<br>";
//        if ($a == $b) {
//            return 0;
//        }
//        return ($a > $b) ? -1 : 1;
//    })->all();
//
//    dd($data);

//    dd(app());

//    $dsn = "mysql:dbname=test;host=127.0.0.1;port=3306";
//    $pdo = new PDO($dsn, 'root', '123456');
//
//    $sth = $pdo->prepare('select * from products where id = ?');
//    $sth->execute([1]);
//
//    $data = $sth->fetchAll();
//
//    dd($data);

//    \App\Mongodb::test();
//    $mongo = DB::connection('mongodb');
//
//    $data = $mongo->table('20170112')->take(10)->get();
//
//
//    dd($data);
});














//// 认证路由...
//Route::get('auth/login', 'Auth\AuthController@getLogin');
//Route::post('auth/login', 'Auth\AuthController@postLogin');
//Route::get('auth/logout', 'Auth\AuthController@getLogout');
//// 注册路由...
//Route::get('auth/register', 'Auth\AuthController@getRegister');
//Route::post('auth/register', 'Auth\AuthController@postRegister');



Route::auth();

Route::get('/home', 'HomeController@index');



