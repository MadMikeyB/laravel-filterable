<?php

namespace Kyslik\LaravelFilterable\Test;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Kyslik\LaravelFilterable\FilterableServiceProvider;
use Kyslik\LaravelFilterable\Generic\Templater;
use Kyslik\LaravelFilterable\Test\Stubs\UserFilter;
use Kyslik\LaravelFilterable\Test\Stubs\RoleFilter;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{

    /** @var Builder $filter */
    protected $builder;


    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            FilterableServiceProvider::class,
        ];
    }


    public function setUp()
    {
        parent::setUp();

        /** @var Builder $builder */
        $this->builder = resolve(Builder::class);
    }


    protected function resetBuilder()
    {
        $this->builder = resolve(Builder::class);
    }


    protected function buildFilter($requestQuery)
    {

        /** @var Request $request */
        $request = resolve(Request::class)->create('http://test.dev?'.$requestQuery);

        return new UserFilter($request, resolve(Templater::class));
    }


    protected function buildCustomFilter($requestQuery)
    {
        /** @var Request $request */
        $request = resolve(Request::class)->create('http://test.dev?'.$requestQuery);
        return new RoleFilter($request);
    }


    protected function dumpQuery(Builder $builder)
    {
        return vsprintf(str_replace(['?'], ['\'%s\''], $builder->toSql()), $builder->getBindings());
    }
}
