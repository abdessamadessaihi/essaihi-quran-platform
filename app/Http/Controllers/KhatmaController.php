<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KhatmaController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $familyIds = $user->families()->pluck('families.id');

        $query = \App\Models\Khatma::with('creator')
            ->where(function ($q) use ($user, $familyIds) {
                // Public types
                $q->whereIn('type', [
                    \App\Models\Khatma::TYPE_PLATFORM,
                    \App\Models\Khatma::TYPE_RAMADAN,
                    \App\Models\Khatma::TYPE_WEEKLY,
                    \App\Models\Khatma::TYPE_MONTHLY
                ])
                // Individual types
                ->orWhere(function ($q2) use ($user) {
                    $q2->where('type', \App\Models\Khatma::TYPE_INDIVIDUAL)
                       ->where('created_by', $user->id);
                })
                // Family types
                ->orWhere(function ($q3) use ($familyIds) {
                    $q3->where('type', \App\Models\Khatma::TYPE_FAMILY)
                       ->whereIn('family_id', $familyIds);
                });
            });

        $activeKhatmas = (clone $query)->where('status', 'active')->latest()->get();
        $completedKhatmas = (clone $query)->where('status', 'completed')->latest()->get();
        $allKhatmas = (clone $query)->latest()->get();

        return view('khatmas.index', compact('activeKhatmas', 'completedKhatmas', 'allKhatmas'));
    }

    public function create()
    {
        return view('khatmas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'           => 'required|string|max:200',
            'type'            => 'required|in:platform,family,individual,ramadan,weekly,monthly',
            'starts_at'       => 'nullable|date',
            'ends_at'         => 'nullable|date|after_or_equal:starts_at',
        ], [
            'title.required' => 'يرجى إدخال عنوان الختمة.',
            'title.max' => 'عنوان الختمة طويل جداً.',
            'type.in' => 'النوع المختار للختمة غير صالح.',
            'starts_at.date' => 'تاريخ البدء غير صالح.',
            'ends_at.date' => 'تاريخ الانتهاء غير صالح.',
            'ends_at.after_or_equal' => 'تاريخ الانتهاء يجب أن يكون بعد أو يساوي تاريخ البدء.',
        ]);

        $user = $request->user();
        $familyId = null;

        if ($validated['type'] === \App\Models\Khatma::TYPE_FAMILY) {
            $family = $user->families()->first();
            if ($family) {
                $familyId = $family->id;
            }
        }

        $khatma = \App\Models\Khatma::create([
            'title'               => $validated['title'],
            'type'                => $validated['type'],
            'status'              => \App\Models\Khatma::STATUS_ACTIVE,
            'created_by'          => $user->id,
            'family_id'           => $familyId,
            'starts_at'           => $validated['starts_at'] ?? now(),
            'ends_at'             => $validated['ends_at'],
            'auto_distribute'     => $request->boolean('auto_distribute'),
            'completed_juz_count' => 0,
        ]);

        $juzAllocations = [];
        $now = now();
        for ($i = 1; $i <= 30; $i++) {
            $juzAllocations[] = [
                'khatma_id'   => $khatma->id,
                'juz_number'  => $i,
                'status'      => \App\Models\JuzAllocation::STATUS_AVAILABLE,
            ];
        }
        
        \App\Models\JuzAllocation::insert($juzAllocations);

        return redirect()->route('khatmas.index')
                         ->with('success', 'تم إنشاء الختمة بنجاح 🎉');
    }

    public function show(\App\Models\Khatma $khatma)
    {
        $user = auth()->user();

        if ($khatma->type === \App\Models\Khatma::TYPE_INDIVIDUAL && $khatma->created_by !== $user->id) {
            abort(403, 'عذراً، هذه الختمة فردية خاصة بمؤسسها فقط.');
        }

        if ($khatma->type === \App\Models\Khatma::TYPE_FAMILY) {
            $isFamilyMember = $user->families()->where('families.id', $khatma->family_id)->exists();
            if (!$isFamilyMember && $khatma->created_by !== $user->id) {
                abort(403, 'عذراً، هذه الختمة خاصة بعائلة أخرى ولا يمكنك الوصول إليها.');
            }
        }

        $khatma->load(['creator', 'juzAllocations.user']);
        return view('khatmas.show', compact('khatma'));
    }

    public function destroy(\App\Models\Khatma $khatma)
    {
        if ($khatma->created_by !== auth()->id()) {
            abort(403, 'لا تملك صلاحية حذف هذه الختمة.');
        }

        $khatma->delete();

        return redirect()->route('khatmas.index')->with('success', 'تم حذف الختمة بنجاح.');
    }
}