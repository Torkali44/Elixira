<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAvatarOptionRequest;
use App\Http\Requests\Admin\UpdateAvatarOptionRequest;
use App\Models\AvatarOption;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AvatarOptionController extends Controller
{
    public function index(): View
    {
        $avatarOptions = AvatarOption::withCount('users')
            ->ordered()
            ->paginate(16);

        return view('admin.avatar-options.index', [
            'avatarOptions' => $avatarOptions,
            'stats' => [
                'total' => AvatarOption::count(),
                'active' => AvatarOption::where('is_active', true)->count(),
                'inactive' => AvatarOption::where('is_active', false)->count(),
                'assigned' => AvatarOption::has('users')->count(),
            ],
        ]);
    }

    public function create(): View
    {
        return view('admin.avatar-options.create');
    }

    public function store(StoreAvatarOptionRequest $request)
    {
        DB::transaction(function () use ($request) {
            $sortOrder = $request->filled('sort_order')
                ? max(1, $request->integer('sort_order'))
                : ((int) AvatarOption::max('sort_order') + 1);

            AvatarOption::where('sort_order', '>=', $sortOrder)->increment('sort_order');

            AvatarOption::create([
                ...$request->validated(),
                'sort_order' => $sortOrder,
            ]);
        });

        return redirect()->route('admin.avatar-options.index')->with('success', 'Avatar added successfully.');
    }

    public function edit(AvatarOption $avatarOption): View
    {
        return view('admin.avatar-options.edit', compact('avatarOption'));
    }

    public function update(UpdateAvatarOptionRequest $request, AvatarOption $avatarOption)
    {
        DB::transaction(function () use ($request, $avatarOption) {
            $newSortOrder = max(1, $request->integer('sort_order', 1));
            $oldSortOrder = (int) $avatarOption->sort_order;

            if ($newSortOrder !== $oldSortOrder) {
                if ($newSortOrder < $oldSortOrder) {
                    AvatarOption::where('id', '!=', $avatarOption->id)
                        ->whereBetween('sort_order', [$newSortOrder, $oldSortOrder - 1])
                        ->increment('sort_order');
                } else {
                    AvatarOption::where('id', '!=', $avatarOption->id)
                        ->whereBetween('sort_order', [$oldSortOrder + 1, $newSortOrder])
                        ->decrement('sort_order');
                }
            }

            $avatarOption->update([
                ...$request->validated(),
                'sort_order' => $newSortOrder,
            ]);
        });

        return redirect()->route('admin.avatar-options.index')->with('success', 'Avatar updated successfully.');
    }

    public function destroy(AvatarOption $avatarOption)
    {
        DB::transaction(function () use ($avatarOption) {
            $deletedSortOrder = (int) $avatarOption->sort_order;
            $avatarOption->delete();
            AvatarOption::where('sort_order', '>', $deletedSortOrder)->decrement('sort_order');
        });

        return redirect()->route('admin.avatar-options.index')->with('success', 'Avatar deleted successfully.');
    }

    public function toggle(Request $request, AvatarOption $avatarOption)
    {
        $avatarOption->update([
            'is_active' => !$avatarOption->is_active,
        ]);

        return redirect()->back()->with('success', 'Avatar visibility updated.');
    }
}
