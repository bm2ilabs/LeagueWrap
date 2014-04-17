<?php

use LeagueWrap\Api;
use Mockery as m;

class ApiChampionTest extends PHPUnit_Framework_TestCase {

	protected $client;

	public function setUp()
	{
		$client       = m::mock('LeagueWrap\Client');
		$this->client = $client;
	}

	public function tearDown()
	{
		m::close();
	}

	public function testAll()
	{
		$this->client->shouldReceive('baseUrl')
		             ->once();
		$this->client->shouldReceive('request')
		             ->with('na/v1.2/champion', [
						'freeToPlay' => 'false',
						'api_key'    => 'key',
		             ])->once()
		             ->andReturn(file_get_contents('tests/Json/champion.json'));

		$api = new Api('key', $this->client);
		$champion = $api->champion();
		$champion->all();
		$this->assertTrue($champion->get(53) instanceof LeagueWrap\Dto\Champion);
	}

	public function testFree()
	{
		$this->client->shouldReceive('baseUrl')
		             ->once();
		$this->client->shouldReceive('request')
		             ->with('na/v1.2/champion', [
						'freeToPlay' => 'true',
						'api_key'    => 'key',
		             ])->once()
		             ->andReturn(file_get_contents('tests/Json/champion.free.json'));

		$api  = new Api('key', $this->client);
		$free = $api->champion()->free();
		$this->assertEquals(10, count($free));
	}

	public function testChampionById()
	{
		$this->client->shouldReceive('baseUrl')
		             ->once();
		$this->client->shouldReceive('request')
		             ->with('na/v1.2/champion/10', [
						'api_key' => 'key',
		             ])->once()
		             ->andReturn(file_get_contents('tests/Json/champion.10.json'));

		$api   = new Api('key', $this->client);
		$kayle = $api->champion()->championById(10);
		$this->assertEquals(true, $kayle->rankedPlayEnabled);
	}

	public function testAllRegionKR()
	{
		$this->client->shouldReceive('baseUrl')
		             ->once()
		             ->with('https://asia.api.pvp.net/api/lol/');
		$this->client->shouldReceive('request')
		             ->with('kr/v1.2/champion', [
						'freeToPlay' => 'false',
						'api_key'    => 'key',
		             ])->once()
		             ->andReturn(file_get_contents('tests/Json/champion.kr.json'));

		$api = new Api('key', $this->client);
		$api->setRegion('kr');
		$champion = $api->champion();
		$champion->all();
		$this->assertTrue($champion->get(53) instanceof LeagueWrap\Dto\Champion);
	}

	public function testAllRegionRU()
	{
		$this->client->shouldReceive('baseUrl')
		             ->once()
		             ->with('https://eu.api.pvp.net/api/lol/');
		$this->client->shouldReceive('request')
		             ->with('ru/v1.2/champion', [
						'freeToPlay' => 'false',
						'api_key'    => 'key',
		             ])->once()
		             ->andReturn(file_get_contents('tests/Json/champion.ru.json'));

		$api = new Api('key', $this->client);
		$api->setRegion('RU');
		$champion = $api->champion();
		$champion->all();
		$this->assertTrue($champion->get(53) instanceof LeagueWrap\Dto\Champion);
	}
}

