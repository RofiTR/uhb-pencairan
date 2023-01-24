<?php

namespace App\Http\Controllers\Api\Users\Model;

use App\Models\UserModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

class Index extends Controller
{
    public function __invoke(Request $request): Collection
    {
        return UserModel::query()
            ->select('id', 'name', 'full_name', 'email')
            ->when(
                $request->search,
                fn (Builder $query) => $query
                    ->where('name', 'ilike', "%{$request->search}%")
            )
            ->when(
                $request->exists('selected'),
                fn (Builder $query) => $query->whereIn('id', $request->input('selected', []))
            )
            ->where('name', '<>', 'Admin Is Trator')
            ->orderBy('name', 'asc')->get()
            ->map(function ($item) {
                return ['label' => $item->full_name, 'value' => $item->id, 'description' => $item->email];
            });
    }
}
