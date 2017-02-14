<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use ONGR\ElasticsearchDSL\Aggregation\Bucketing\FilterAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Bucketing\TermsAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Metric\AvgAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Metric\SumAggregation;
use ONGR\ElasticsearchDSL\Search;
use Elasticsearch\ClientBuilder;
use ONGR\ElasticsearchDSL\Query\BoolQuery;
use ONGR\ElasticsearchDSL\Query\TermQuery;
use ONGR\ElasticsearchDSL\Query\MatchQuery;


class EsController extends Controller
{
    //
    private $esclient;
    private $search;
    private $finalParams = [
        'index' => 'rtmp-20161208',
        'type' => 'd_live_visitor',
        'body' => array(),
        '_source' => true,
    ];

    public function __construct()
    {
        $this->esclient = ClientBuilder::create()
            ->setHosts([['host' => "q.es.yfcloud.com","port" => "80"]])
            ->build();
        $this->search = new Search();
    }

    //term用法 must与must not 互斥(相当于sql and), should(相当于sql or)
    public function query1(){

        $streamTerm = new TermQuery('stream', 'm_b69887e699565cf71481126370376107');
        $hostTerm = new TermQuery('host', 'yf-push.v.momocdn.com');
        $termQuery1 = new TermQuery('id', 'b6e7cc38a4731044f8076e74b0b65724');
        $termQuery2 = new TermQuery('id', '7e6f7ac85073b0344626255779068d3c');

        $bool = new BoolQuery();
        $bool->add($streamTerm, BoolQuery::MUST);
        $bool->add($hostTerm, BoolQuery::MUST_NOT);
        $bool->add($termQuery1, BoolQuery::SHOULD);
        $bool->add($termQuery2, BoolQuery::SHOULD);

        $this->search->addQuery($bool);

//    dd($search->toArray());


        $this->finalParams['body'] = $this->search->toArray();

        $res = $this->esclient->search($this->finalParams);

        dd($res);
    }

    //match用法 多个match相当于sql and
    public function query2(){

        $termQuery1 = new MatchQuery('id', 'b6e7cc38a4731044f8076e74b0b65724');
        $termQuery2 = new MatchQuery('stream', 'm_b69887e699565cf71481126370376107');

        $bool = new BoolQuery();
        $bool->add($termQuery1, BoolQuery::MUST);
        $bool->add($termQuery2, BoolQuery::MUST);

        $this->search->addQuery($bool);

        dd($this->search->toArray());


        $this->finalParams['body'] = $this->search->toArray();

        $res = $this->esclient->search($this->finalParams);

        dd($res);
    }

    //filter
    public function query3(){
//        $termQuery1 = new MatchQuery('id', 'b6e7cc38a4731044f8076e74b0b65724');
//        $termQuery2 = new MatchQuery('stream', 'm_b69887e699565cf71481126370376107');
//
//        $bool = new BoolQuery();
//        $bool->add($termQuery1, BoolQuery::MUST);
//        $bool->add($termQuery2, BoolQuery::MUST);
//
//        $this->search->addQuery($bool);
//
//        dd($this->search->toArray());


        //单个过滤 select * from d_play_status where id = 'b6e7cc38a4731044f8076e74b0b65724'
        $this->finalParams['body'] = [
            'query' => [
                'filtered' => [
                    'filter' => [
                        'term' => ['id' => 'b6e7cc38a4731044f8076e74b0b65724']
                    ]
                ]
            ]
        ];

        /**
         * 多个过滤 SELECT * FROM  d_play_status
         *  WHERE  (id = 'b6e7cc38a4731044f8076e74b0b65724' OR id = '7e6f7ac85073b0344626255779068d3c') AND  (host != 'yf-push.v.momocdn.com')
        */
         $this->finalParams['body'] = [
            'query' => [
                'filtered' => [
                    'filter' => [
                        'bool' => [
                            'should' => [
                                ['term' => ['id' => 'b6e7cc38a4731044f8076e74b0b65724']],
                                ['term' => ['id' => '7e6f7ac85073b0344626255779068d3c']]
                            ],
                            'must_not' => [
                                'term' => ['host' => 'yf-push.v.momocdn.com']
                            ]
                        ]
                    ]
                ]
            ]
        ];

        //嵌套 term与terms 是包含操作，例如搜索term => [name => bruce],name=bruce lee 与 name=bruce chen都会返回,
        //所以要保证返回唯一，必须增加term => [tag_count => 1]
        $this->finalParams['body'] = [
            'size' => '20',
            'query' => [
                'filtered' => [
                    'filter' => [
                        'bool' => [
                            'should' => [
                                ['term' => ['id' => 'b6e7cc38a4731044f8076e74b0b65724']],
                                [
                                    'bool' => [
//                                        'should' => [
//                                            'term' => ['id' => '7e6f7ac85073b0344626255779068d3c']
//                                        ],
                                        'must' => [
                                            'term' => ['stream' => '9dqIRZ']
                                        ],
                                        'must_not' => [
                                            'term' => ['host' => 'yf-push.v.momocdn.com']
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $this->finalParams['body'] = [
            'size' => 10,
            'query' => [
                'bool' => [
                    'must' => [
                        'term' => ['stream' => '9dqIRZ']
                    ],
                    'filter' => [
                        'range' => [
                            'create_time' => [
                                'gte' => 1481129160,
                                'lte' => 1481129160
                            ]
                        ]
                    ]
                ]
            ]
        ];


        $res = $this->esclient->search($this->finalParams);

        dd($res);
    }

    public function query4(){

//        $this->finalParams['body'] = [
//            'query' => [
//                'filtered' => [
//                    'filter' => [
//                        'term' => ['stream' => 'm_b69887e699565cf71481126370376107']
//                    ]
//                ]
//            ]
//        ];

        //avg
//        $avgAggregation = new AvgAggregation('avg_frame');
//        $sumAggregation = new SumAggregation('sum_frame');
//        $avgAggregation->setField('av_frame');
//        $sumAggregation->setField('av_frame');
//
//        $this->search->addAggregation($avgAggregation);
//        $this->search->addAggregation($sumAggregation);
//
//        $this->finalParams['body'] = $this->search->toArray();
//        $res = $this->esclient->search($this->finalParams);
//        dd($res);

        //filter
        $streamTerm = new TermQuery('stream', 'm_b69887e699565cf71481126370376107');
        $avgAggregation = new AvgAggregation('avg_frame', 'av_frame');

        $filterAvgAggregation = new FilterAggregation('filter_frame', $streamTerm);
        $filterAvgAggregation->addAggregation($avgAggregation);

        $this->search->addAggregation($filterAvgAggregation);
        $this->finalParams['body'] = $this->search->toArray();

        $res = $this->esclient->search($this->finalParams);
        dd($res);







    }

    public function query5(){
//        $this->finalParams['_source'] = false;
//        $this->finalParams['search_type'] = 'count';//0,1,2,3,4,5
        $this->finalParams['index'] = 'rtmp-20161212';
        $this->finalParams['type'] = 'd_live_visitor';

        /**
         * SELECT isp,sum(uv) FROM rtmp-20161212/d_live_visitor group by isp
         */
        $this->finalParams['body'] = [
            'aggregations' => [
                "isp_count" => [
                    "terms" => [
                        "field" => "isp"
                    ],
                    "aggregations" => [
                        "sum_uv" => [
                            "sum" => [
                                "field" => "uv"
                            ]
                        ]
                    ]
                ]
            ]
        ];

        /**
         * SELECT isp,province,sum(uv) FROM rtmp-20161212/d_live_visitor group by isp,province
         */
        $this->finalParams['body'] = [
            'aggregations' => [
                "isp_count" => [
                    "terms" => [
                        "field" => "isp"
                    ],
                    "aggregations" => [
                        "isp_province_count" => [
                            "terms" => [
                                "field" => "province",
                                "size" => 34
                            ],
                            "aggregations" => [
                                "isp_province_sum" => [
                                    "sum" => [
                                        "field" => "uv"
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        /**
         * SELECT isp,province,sum(uv),sum(node_uv) FROM rtmp-20161212/d_live_visitor group by isp,province
         */
        $this->finalParams['body'] = [
            'aggregations' => [
                "isp_count" => [
                    "terms" => [
                        "field" => "isp"
                    ],
                    "aggregations" => [
                        "isp_province_count" => [
                            "terms" => [
                                "field" => "province",
                                "size" => 34
                            ],
                            "aggregations" => [
                                "isp_province_sum" => [
                                    "sum" => [
                                        "field" => "uv"
                                    ]
                                ],
                                "isp_province_nodeUv_sum" => [
                                    "sum" => [
                                        "field" => 'node_uv'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        /**
         * SELECT isp,timestamp,sum(uv) FROM rtmp-20161212/d_live_visitor where isp in ('移动','联通') group by isp,timestamp
         */
        $this->finalParams['body'] = [
            'query' => [
                'filtered' => [
                    'filter' => [
                        'query' => [
                            'terms' => ['isp' => ['移动','联通']]
                        ]
//                        'range' => [
//
//                        ]
                    ]
                ]
            ],
            'aggregations' => [
                "isp_count" => [
                    "terms" => [
                        "field" => "isp"
                    ],
                    "aggregations" => [
                        "isp_province_count" => [
                            "date_histogram" => [
                                "field" => "@timestamp",
                                "interval" => "5m",
                                "time_zone" => "Asia/Shanghai",
//                                "min_doc_count" => 1,
                            ],
                            "aggs" => [
                                "sum_uv" => [
                                    "sum" => [
                                        "field" => "uv"
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        /**
         * SELECT isp,timestamp,sum(uv) FROM rtmp-20161212/d_live_visitor where isp = '移动' and province = '广东' group by isp,timestamp
         */
        $this->finalParams['body'] = [
            'query' => [
                'bool' => [
                    'must' => [
                        ['term' => ['isp' => '移动']],
                        ['term' => ['province' => '广东']]
                    ]
                ]
            ],
            'aggregations' => [
                "isp_count" => [
                    "terms" => [
                        "field" => "isp"
                    ],
                    "aggregations" => [
                        "isp_province_count" => [
                            "date_histogram" => [
                                "field" => "@timestamp",
                                "interval" => "5m",
                                "time_zone" => "Asia/Shanghai",
//                                "min_doc_count" => 1,
                            ],
                            "aggs" => [
                                "sum_uv" => [
                                    "sum" => [
                                        "field" => "uv"
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        /**
         * SELECT isp,timestamp,sum(uv) FROM rtmp-20161212/d_live_visitor where isp = '移动' and province = '广东' group by isp,timestamp
         */
        $this->finalParams['body'] = [
            'query' => [
                'bool' => [
                    'must' => [
                        'match_all' => []
                    ],
                    'filter' => [
                        ['term' => ['isp' => '移动']]
//                        ['term' => ['province' => '广东']]
                    ]
                ]
            ]
        ];

        $res = $this->esclient->search($this->finalParams);
//        var_dump($res);
        echo json_encode($res);

    }


    /**
     * SELECT isp,province,sum(uv) FROM rtmp-20161212/d_live_visitor group by isp,province
     */
    public function query6(){

        $this->finalParams['search_type'] = 'count';
        $this->finalParams['index'] = 'rtmp-20161212';
        $this->finalParams['type'] = 'd_live_visitor';


        $termsQuery1 = new TermsAggregation("isp_count","isp");
        $termsQuery2 = new TermsAggregation("isp_province_count","province");
//        $termsQuery1->addParameter('size', 30);
        $termsQuery2->addParameter('size', 37);
        $sumAggs = new SumAggregation("sum_uv", "uv");

        $termsQuery1->addAggregation($termsQuery2);
        $termsQuery2->addAggregation($sumAggs);

        $this->search->addAggregation($termsQuery1);

        $this->finalParams['body'] = $this->search->toArray();
        $res = $this->esclient->search($this->finalParams);

        echo json_encode($res);
    }

    /**
     * SELECT isp,province,sum(uv) FROM rtmp-20161212/d_live_visitor group by isp,province
     */
    public function query7(){

        $this->finalParams['index'] = 'rtmp-20161212';
        $this->finalParams['type'] = 'd_live_visitor';


        $termsQuery1 = new TermsAggregation("isp_count","isp");
        $termsQuery2 = new TermsAggregation("isp_province_count","province");
        $sumAggs = new SumAggregation("sum_uv", "uv");

//        $termsQuery1->addParameter('size', 5);
        $termsQuery1->setParameters(['size' => 5]);
        $termsQuery1->addAggregation($termsQuery2);
        $termsQuery2->addAggregation($sumAggs);

        $this->search->addAggregation($termsQuery1);


        dd($this->search->toArray());

        $this->finalParams['body'] = $this->search->toArray();
        $res = $this->esclient->search($this->finalParams);

        echo json_encode($res);
    }

    public function setQueryStat(){

    }

    public function setAggStat(){

    }







}




