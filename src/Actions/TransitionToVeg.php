<?php

namespace Spork\Greenhouse\Actions;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spork\Core\Contracts\ActionInterface;
use Spork\Greenhouse\Models\Plant;
use Spork\Greenhouse\Models\Seed;

class TransitionToVeg implements ActionInterface
{
    use ValidatesRequests;

    public function name(): string
    {
        return 'Transition to veg';
    }

    public function route(): string
    {
        return '/greenhouse/seeds/veg';
    }

    public function tags(): array {
        return ['seeds'];
    }

    public function validation(array $rules): void
    {
        request()->validate($rules);
    }

    public function __invoke(Request $request)
    {
        $this->validation([
            'plants' => 'required|array',
        ]);

        $plants = Seed::whereIn('id', $request->get('plants'))->get();

        foreach ($plants as $plant) {
            activity()->performedOn($plant)->causedBy($request->user())->log('feed');
        }

        return response()->json([
            'message' => sprintf('You fed %s seedling%s.', $plants->count(), $plants->count() !== 1 ? 's' : ''),
        ]);
    }
}