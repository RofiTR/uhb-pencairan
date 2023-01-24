<?php

namespace App\Http\Controllers\Api\Users\Dapo;

use App\Models\User;
use App\Models\UserModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

class Index extends Controller
{
    public function __invoke(Request $request): Collection
    {
        $currents = UserModel::get()->pluck('id');
        return User::query()
            ->select('id', 'name', 'title', 'email')
            ->when(
                $request->search,
                fn (Builder $query) => $query
                    ->where('name', 'ilike', "%{$request->search}%")
            )
            ->when(
                $request->exists('selected'),
                fn (Builder $query) => $query->whereIn('id', $request->input('selected', []))
            )
            ->whereNotIn('id', $currents)->whereIn('position', [2, 4, 5])->orderBy('name', 'asc')->get()
            ->map(function ($item) {
                $name = $item->name;
                if ($item->title) {
                    $name = $item->title['prefix'] ? $item->title['prefix'] . ' ' . $item->name : $item->name;
                    $name .= $item->title['sufix'] ? ', ' . $item->title['sufix'] : '';
                }
                return ['label' => $name, 'value' => $item->id, 'description' => $item->email];
            });
    }
}
