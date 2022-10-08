<?php

namespace Spork\Greenhouse\Actions;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spork\Core\Contracts\ActionInterface;
use Spork\Greenhouse\Models\Plant;
use Spork\Greenhouse\Models\Seed;

class WaterSeedling implements ActionInterface
{
    use ValidatesRequests;

    public function name(): string
    {
        return 'Water seedling';
    }

    public function route(): string
    {
        return '/greenhouse/seeds/water';
    }

    public function tags(): array 
    {
        return ['seeds'];
    }

    public function validation(array $rules): void
    {

    }

    public function __invoke(Request $request)
    {
        $this->validate($request, [
            'plants' => 'required|array',
        ]);

        $plants = Seed::whereIn('id', $request->get('plants'))->get();

        foreach ($plants as $plant) {
            $plant->water();

            activity()->performedOn($plant)->causedBy($request->user())->log('water');
        }

        return response()->json([
            'message' => sprintf('You watered %s seedling%s.', $plants->count(), $plants->count() !== 1 ? 's' : ''),
        ]);
    }
}