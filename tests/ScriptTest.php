<?php

namespace Test;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Idamkiller\FluentFM\Connection\FluentFMRepository;

class ScriptTest extends TestBase
{
    public function testScriptPost(): void
    {
        $fm = new FluentFMRepository(static::$config, $this->client([
            static::token_request(),
            new Response(200, [], file_get_contents(__DIR__.'/responses/records.json')),
        ]));

        $fm->find('tabla_a')
           ->where('id', '1')
           ->script('scriptname', 'scriptparam')
           ->presort('presort_scriptname', 'presort_scriptparam')
           ->prerequest('prerequest_scriptname', 'prerequest_scriptparam')
           ->get();

        /** @var Request $request */
        $request = $this->history[1]['request'];
        $this->assertEquals(
            '{"query":[{"id":"=1"}],"script":"scriptname","script.param":"scriptparam","script.presort":"presort_scriptname","script.presort.param":"presort_scriptparam","script.prerequest":"prerequest_scriptname","script.prerequest.param":"prerequest_scriptparam"}',
            $request->getBody()->getContents()
        );
    }

    public function testScriptQuery(): void
    {
        $fm = new FluentFMRepository(static::$config, $this->client([
            static::token_request(),
            new Response(200, [], file_get_contents(__DIR__.'/responses/records.json')),
        ]));

        $fm->records('table_a')
           ->script('scriptname', 'scriptparam')
           ->presort('presort_scriptname', 'presort_scriptparam')
           ->prerequest('prerequest_scriptname', 'prerequest_scriptparam')
           ->get();

        /** @var Request $request */
        $request = $this->history[1]['request'];
        $this->assertEquals(
            '_script=scriptname&_script.param=scriptparam&_script.presort=presort_scriptname&_script.presort.param=presort_scriptparam&_script.prerequest=prerequest_scriptname&_script.prerequest.param=prerequest_scriptparam',
            $request->getUri()->getQuery()
        );
    }
}
