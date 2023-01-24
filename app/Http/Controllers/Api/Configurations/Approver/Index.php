<?php

namespace App\Http\Controllers\Api\Configurations\Approver;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;

class Index extends Controller
{
    public function __invoke(Request $request): Collection
    {
        return UserModel::query()
            ->select('id', 'name', 'limit')
            ->orderBy('limit')
            ->when(
                $request->search,
                fn (Builder $query) => $query
                    ->where('name', 'ilike', "%{$request->search}%")
            )
            ->when(
                $request->exists('selected'),
                fn (Builder $query) => $query->whereIn('id', $request->input('selected', []))
            )
            ->where('limit', '<>', 0)
            ->orWhere('limit', NULL)
            ->get()
            ->map(function ($item) {
                return ['label' => $item->name, 'value' => $item->id, 'description' => 'Limit Rp. ' . number_format($item->limit, 2, ',', '.')];
            });
    }
}
