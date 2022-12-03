<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use RedisException;

class LinkController extends Controller
{

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function shortLink(Request $request): JsonResponse
    {
        $data = $request->validate([
            'link' => ['required', 'url']
        ]);
        $alias = Str::random(5);
        $this->addToRedis($alias);
        $link = Link::query()->create([
            'link' => $data['link'],
            'alias' => $alias
        ]);
        return successResponse([
            'link' => $link->getAttribute('link'),
            'alias' => $link->getAttribute('alias')
        ]);
    }

    /**
     * @param $alias
     */
    protected function addToRedis($alias)
    {
        Redis::command('ZADD', ['links', 0, $alias]);
    }

    /**
     * @param $alias
     * @return JsonResponse
     * @throws RedisException
     */
    public function readAlias($alias): JsonResponse
    {
        $link = Link::query()->where('alias', $alias)->firstOrFail();
        $this->incrementScore($alias);
        return successResponse([
            'link' => $link->getAttribute('link')
        ]);
    }

    /**
     * @param $alias
     * @return void
     * @throws RedisException
     */
    protected function incrementScore($alias): void
    {
        Redis::command('ZINCRBY', ['links', 1, $alias]);
    }

    public function allLinks(Request $request)
    {
        $links = $this->linksInRedis();
        arsort($links);
        if ($request->has('top') &&  $request->top != '') {
            $collection = collect($links);
            $links = $collection->take($request->top);
        }
        return successResponse([
            'links' => $links
        ]);
    }

    protected function linksInRedis()
    {
        return Redis::command('ZRANGE', ['links', 0, -1, 'WITHSCORES']);
    }
}
