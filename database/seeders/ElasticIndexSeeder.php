<?php

namespace Database\Seeders;

use Elastic\Elasticsearch\ClientBuilder;
use Illuminate\Database\Seeder;

class ElasticIndexSeeder extends Seeder
{
    public function run()
    {
        $client = ClientBuilder::create()->build();

        $params = [
            'index' => 'properties',
            'body' => [
                'mappings' => [
                    'properties' => [
                        'name' => ['type' => 'text'],
                        'description' => ['type' => 'text'],
                        'location' => ['type' => 'text'],
                        'price' => ['type' => 'double'],
                        'status' => ['type' => 'keyword'],
                    ],
                ],
            ],
        ];

        $client->indices()->create($params);
        echo 'ElasticSearch index "properties" has been created.';
    }
}
