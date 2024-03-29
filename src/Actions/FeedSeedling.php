<?php

namespace Spork\Greenhouse\Actions;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Spork\Core\Contracts\ActionInterface;
use Spork\Greenhouse\Models\Seed;

class FeedSeedling implements ActionInterface
{
    use ValidatesRequests;

    public function name(): string
    {
        return 'Feed seedling';
    }

    public function route(): string
    {
        return '/greenhouse/seeds/feed';
    }

    public function tags(): array
    {
        return ['seeds'];
    }

    public function validation(array $rules): void
    {
        request()->validate([
            'plants' => 'required|array',
        ]);
    }

    public function __invoke(Request $request)
    {
        $plants = Seed::whereIn('id', $request->get('plants'))->get();

        foreach ($plants as $plant) {
            $plant->feed();

            activity()->performedOn($plant)->causedBy($request->user())->log('feed');
        }

        return response()->json([
            'message' => sprintf('You fed %s seedling%s.', $plants->count(), $plants->count() !== 1 ? 's' : ''),
        ]);
    }
}
