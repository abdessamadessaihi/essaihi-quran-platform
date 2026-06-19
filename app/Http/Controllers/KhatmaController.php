<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Khatma;
use App\Models\JuzAllocation;
Use Illuminate\Support\Facades\DB;
use App\Services\NotificationService;
use App\Models\Family;
use App\Models\FamilyMember;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


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
        // جلب جميع العائلات التي ينتمي إليها المستخدم الحالي ونشط فيها
        $userFamilies = auth()->user()->families()
            ->wherePivot('status', 'active')
            ->get();

        return view('khatmas.create', compact('userFamilies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'           => 'required|string|max:200',
            'type'            => 'required|in:platform,family,individual,ramadan,weekly,monthly',
            'starts_at'       => 'nullable|date',
            'ends_at'         => 'nullable|date|after_or_equal:starts_at',
            'family_id'       => 'nullable|required_if:type,family|exists:families,id', // التحقق من اختيار العائلة إذا كان النوع عائلي
        ], [
            'title.required' => 'يرجى إدخال عنوان الختمة.',
            'title.max' => 'عنوان الختمة طويل جداً.',
            'type.in' => 'النوع المختار للختمة غير صالح.',
            'starts_at.date' => 'تاريخ البدء غير صالح.',
            'ends_at.date' => 'تاريخ الانتهاء غير صالح.',
            'ends_at.after_or_equal' => 'تاريخ الانتهاء يجب أن يكون بعد أو يساوي تاريخ البدء.',
            'family_id.required_if' => 'يرجى اختيار العائلة المستهدفة لهذه الختمة.',
            'family_id.exists' => 'العائلة المختارة غير صالحة.',
        ]);

        $user = $request->user();
        $familyId = null;
        $participantsIds = [];

        // التحقق من الختمة العائلية وجلب أعضاء العائلة المختارة تحديداً
        if ($validated['type'] === Khatma::TYPE_FAMILY) {
            $familyId = $validated['family_id'];
            
            // التأكد من أن المستخدم ينتمي فعلاً لهذه العائلة لحماية النظام
            $family = $user->families()->where('families.id', $familyId)->wherePivot('status', 'active')->first();
            
            if ($family) {
                $participantsIds = $family->activeMembers()->pluck('users.id')->toArray();
            } else {
                return back()->withErrors(['family_id' => 'لا تملك صلاحية إنشاء ختمة في هذه العائلة.'])->withInput();
            }
        }

        // إذا كانت الختمة فردية
        if ($validated['type'] === Khatma::TYPE_INDIVIDUAL) {
            $participantsIds = [$user->id];
        }

        // التوزيع التلقائي مسموح فقط في الختمة العائلية
        $isAutoDistribute = ($validated['type'] === Khatma::TYPE_FAMILY) ? $request->boolean('auto_distribute') : false;

        $khatma = Khatma::create([
            'title'               => $validated['title'],
            'type'                => $validated['type'],
            'status'              => Khatma::STATUS_ACTIVE,
            'created_by'          => $user->id,
            'family_id'           => $familyId,
            'starts_at'           => $validated['starts_at'] ?? now(),
            'ends_at'             => $validated['ends_at'],
            'auto_distribute'     => $isAutoDistribute,
            'completed_juz_count' => 0,
        ]);

        $juzAllocations = [];
        $participantCount = count($participantsIds);

        for ($i = 1; $i <= 30; $i++) {
            if ($isAutoDistribute && $participantCount > 0) {
                $assignedUserId = $participantsIds[($i - 1) % $participantCount];
                
                $juzAllocations[] = [
                    'khatma_id'   => $khatma->id,
                    'juz_number'  => $i,
                    'user_id'     => $assignedUserId,
                    'status'      => JuzAllocation::STATUS_RESERVED,
                    'claimed_at'  => now(),
                    'deadline_at' => now()->addDays(7),
                ];
            } else {
                $juzAllocations[] = [
                    'khatma_id'   => $khatma->id,
                    'juz_number'  => $i,
                    'user_id'     => null,
                    'status'      => JuzAllocation::STATUS_AVAILABLE,
                    'claimed_at'  => null,
                    'deadline_at' => null,
                ];
            }
        }
        try {
            \App\Services\NotificationService::onKhatmaCreated($khatma);
        } catch (\Exception $e) {
            \Log::error('Notification error: ' . $e->getMessage());
        }



        JuzAllocation::insert($juzAllocations);

        return redirect()->route('khatmas.index')->with('success', 'تم إنشاء الختمة بنجاح 🎉');
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