<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class WelcomeFirstLoginMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function build()
    {
        // رابط لوحة التحكم الخاص بمنصتك
        $dashboardUrl = url('/dashboard');

        return $this->subject('مرحباً بك في رحاب القرآن | منصة آل السيحي الرقمية 🌟')
                    ->html("
                        <div style='direction: rtl; text-align: right; font-family: system-ui, -apple-system, sans-serif; line-height: 1.8; color: #333333; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 8px; background-color: #ffffff;'>
                            
                            <div style='text-align: center; margin-bottom: 25px;'>
                                <h1 style='color: #1a4731; margin-bottom: 5px; font-size: 24px;'>بِسْمِ اللَّهِ الرَّحْمَنِ الرَّحِيمِ</h1>
                                <p style='color: #4a5568; font-style: italic; font-size: 16px; margin-top: 0;'>«خَيْرُكُمْ مَنْ تَعَلَّمَ الْقُرْآنَ وَعَلَّمَهُ»</p>
                            </div>

                            <h2 style='color: #2c5282; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;'>أهلاً بك يا {$this->user->name} في رِحاب القرآن 👋</h2>
                            
                            <p style='font-size: 16px;'>نُبارك لأنفسنا ولكم هذا الانضمام المبارك إلى <strong>منصة آل السيحي القرآنية</strong>. نسأل الله العليّ القدير أن يجعل هذه الخطوة بداية لرحلة نورانية تفيض ببركة آيات الذكر الحكيم في حياتكم اليومية.</p>
                            
                            <div style='background-color: #f7fafc; padding: 15px; border-right: 4px solid #2c5282; margin: 20px 0; border-radius: 4px;'>
                                <h3 style='margin-top: 0; color: #2c5282; font-size: 18px;'>🗺️ كيف تسير رحلتك المعطاءة داخل المنصة؟</h3>
                                <ul style='padding-right: 20px; margin-bottom: 0;'>
                                    <li style='margin-bottom: 10px;'><strong>حلقات القرآن والمقارئ المباشرة:</strong> انضمام فوري لحلقات الإقراء مع المقرئين، أو إلحاق الأبناء لتبدأ رحلة الحفظ والترتيل والمراجعة الفردية والجماعية.</li>
                                    <li style='margin-bottom: 10px;'><strong>الختمات الجماعية والعائلية:</strong> مشاركة أفراد العائلة في حجز الأجزاء والسور ضمن الختمات النشطة لنكمل كتاب الله معاً في تلاحم مبارك.</li>
                                    <li style='margin-bottom: 10px;'><strong>الورد اليومي والمصحف المشترك:</strong> التزام بوردك اليومي وتسجيل إنجازك أولاً بأول للحفاظ على الـ (Streak) لتبقى همتك متقدة.</li>
                                    <li style='margin-bottom: 0;'><strong>لوحة الشرف والتنافس المحمود:</strong> كل صفحة تقرؤها تمنحك نقاطاً (XP) وترفع ترتيبك، مصداقاً لقوله تعالى: <em>«وَفِي ذَلِكَ فَلْيَتَنَافَسِ الْمُتَنَافِسُونَ»</em>.</li>
                                </ul>
                            </div>

                            <p style='font-size: 16px; margin-bottom: 25px;'>لوحة التحكم الخاصة بك بانتظارك، قم بالدخول واكتشف الحلقات المتاحة أو أنشئ ختمة جديدة وادعُ عائلتك إليها:</p>
                            
                            <div style='text-align: center; margin: 30px 0;'>
                                <a href='{$dashboardUrl}' style='background-color: #1a4731; color: #ffffff; padding: 12px 30px; text-decoration: none; font-weight: bold; border-radius: 5px; font-size: 16px; display: inline-block; box-shadow: 0 4px 6px rgba(0,0,0,0.1);'>الانتقال إلى لوحة التحكم وبدء التلاوة</a>
                            </div>

                            <hr style='border: 0; border-top: 1px solid #e2e8f0; margin: 30px 0;'>
                            
                            <div style='font-size: 14px; color: #4a5568;'>
                                <p style='margin-bottom: 5px;'>نشُدّ على يديك، ونقف خلفك داعمين وموجّهين في كل آية تحفظها وتدبّرها.</p>
                                <strong>مُحبّوكم في إدارة منصة آل السيحي القرآنية الرقمية</strong><br>
                                <span style='font-size: 12px; color: #718096; font-style: italic;'>جعلنا الله وإياكم من أهل القرآن الذين هم أهل الله وخاصته.</span>
                            </div>

                        </div>
                    ");
    }
}