<?php

use common\models\CrmPush;
use common\models\Student;
use common\models\Direction;
use common\models\Exam;
use common\models\StudentPerevot;
use common\models\StudentDtm;
use common\models\Course;
use Da\QrCode\QrCode;
use frontend\models\Contract;
use common\models\User;
use common\models\Consulting;
use common\models\Branch;
use common\models\StudentMaster;

/** @var Student $student */
/** @var Direction $direction */
/** @var User $user */
/** @var Branch $filial */

$user = $student->user;
$cons = Consulting::findOne($user->cons_id);
$eduDirection = $student->eduDirection;
$direction = $eduDirection->direction;
$full_name = $student->last_name . ' ' . $student->first_name . ' ' . $student->middle_name;
$code = '';
$joy = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
$date = '';
$link = '';
$con2 = '';
if ($student->edu_type_id == 1) {
    $contract = Exam::findOne([
        'edu_direction_id' => $eduDirection->id,
        'student_id' => $student->id,
        'status' => 3,
        'is_deleted' => 0
    ]);
    $code = 'Q2/' . $cons->code . '/' . $contract->id;
    $date = date("Y-m-d H:i", $contract->confirm_date);
    $link = '1&id=' . $contract->id;
    $con2 = '2' . $contract->invois;
    $contract->down_time = time();
    $contract->save(false);
} elseif ($student->edu_type_id == 2) {
    $contract = StudentPerevot::findOne([
        'edu_direction_id' => $eduDirection->id,
        'student_id' => $student->id,
        'file_status' => 2,
        'is_deleted' => 0
    ]);
    $code = 'P2/' . $cons->code . '/' . $contract->id;
    $date = date("Y-m-d H:i", $contract->confirm_date);
    $link = '2&id=' . $contract->id;
    $con2 = '2' . $contract->invois;
    $contract->down_time = time();
    $contract->save(false);
} elseif ($student->edu_type_id == 3) {
    $contract = StudentDtm::findOne([
        'edu_direction_id' => $eduDirection->id,
        'student_id' => $student->id,
        'file_status' => 2,
        'is_deleted' => 0
    ]);
    $code = 'D2/' . $cons->code . '/' . $contract->id;
    $date = date("Y-m-d H:i:s", $contract->confirm_date);
    $link = '3&id=' . $contract->id;
    $con2 = '2' . $contract->invois;
    $contract->down_time = time();
    $contract->save(false);
} elseif ($student->edu_type_id == 4) {
    $contract = StudentMaster::findOne([
        'edu_direction_id' => $eduDirection->id,
        'student_id' => $student->id,
        'file_status' => 2,
        'is_deleted' => 0
    ]);
    $code = 'M2/' . $cons->code . '/' . $contract->id;
    $date = date("Y-m-d H:i:s", $contract->confirm_date);
    $link = '4&id=' . $contract->id;
    $con2 = '2' . $contract->invois;
    $contract->down_time = time();
    $contract->save(false);
}

$contract->contract_price = preg_replace('/\D/', '', $contract->contract_price);

$student->is_down = 1;
$student->update(false);

$filial = Branch::findOne($student->branch_id);

$qr = (new QrCode('https://qabul.tpu.uz/site/contract?key=' . $link . '&type=2'))->setSize(120, 120)
    ->setMargin(10);
$img = $qr->writeDataUri();

$lqr = (new QrCode('https://license.gov.uz/registry/23a03e28-2919-4472-ae48-772580999311'))->setSize(100, 100)
    ->setMargin(10);
$limg = $lqr->writeDataUri();


?>


<table width="100%" style="font-family: 'Times New Roman'; font-size: 14px; border-collapse: collapse;">

    <tr>
        <td colspan="4" style="text-align: center">
            <b>
                2025-2026 o‘quv yilida to‘lov-kontakt (ikki tonomlama) asosida<br>
                ta’lim xizmatlarini ko‘rsatish bo‘yicha<br>
                № <?= $code ?> - sonli <br> SHARTNOMA
            </b>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="2"><?= $date ?></td>
        <td colspan="2" style="text-align: right"><span><?= $filial->name_uz ?></span></td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            “PERFECT-UNIVERSITY” oliy ta’lim tashkiloti (keyingi o‘rinlarda “Universitet”) nomidan Ustav asosida ish yurituvchi rektor <b><?= $filial->rector_uz ?></b> birinchi tomondan, <b><?= $full_name ?></b> (keyingi o‘rinlarda “Talaba”) ikkinchi tomondan, keyingi o‘rinlarda birgalikda “Tomonlar” deb ataluvchi shaxslar o‘rtasida mazkur shartnoma quyidagilar haqida tuzildi:
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: center">
            <b>I. SHARTNOMA PREDMETI</b>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            1.1. Mazkur shartnomaga muvofiq Universitet Talabani quyida ko‘rsatilgan ta’lim yo‘nalishi va ta’lim shakli bo‘yicha oliy ta’limning davlat ta’lim standartlari asosida tasdiqlangan o‘quv reja va o‘quv dasturlari asosida o‘qitish majburiyatini oladi.
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="border: 1px solid #000000; padding: 5px;">
            <table width="100%">
                <tr>
                    <td colspan="2">
                        Ta’lim yo‘nalishi:
                    </td>
                    <td colspan="2">
                        <b><?= $direction->code . ' ' . $direction->name_uz ?></b>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Ta’lim shakli:</td>
                    <td colspan="2"><b><?= $eduDirection->eduForm->name_uz ?></b></td>
                </tr>
                <tr>
                    <td colspan="2">Ta’lim yo‘nalishi bo‘yicha o‘qish muddati:</td>
                    <td colspan="2"><b><?= $eduDirection->duration . ' yil' ?></b></td>
                </tr>
                <tr>
                    <td colspan="2">O‘quv kursi:</td>
                    <?php if ($student->edu_type_id == 2) : ?>
                        <td colspan="2"><b><?= Course::findOne(['id' => ($student->course_id + 1)])->name_uz ?></b></td>
                    <?php else: ?>
                        <td colspan="2"><b>1 kurs</b></td>
                    <?php endif; ?>
                </tr>
                <tr>
                    <td colspan="2">Shartnomaning umumiy narxi (bir o‘quv yili uchun):</td>
                    <td colspan="2"><b><?= number_format((int)$contract->contract_price, 0, '', ' ') ?></b></td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            Talaba esa Universitet tomonidan belgilangan tartib qoidalarga rioya qilgan holda ta’lim olish va ta’lim olganlik uchun haq to‘lash majburiyatini oladi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            1.2. Universitetda shartnoma asosida o‘qitishning to‘lovi (keyingi o‘rinlarda – shartnoma to‘lovi) miqdori ta’lim yo‘nalishi, ta’lim shaklining kunduzgi, kechki va sirtqi, o‘qishni ko‘chirish bilan bog‘liq fanlarning farqini o‘qitish hamda to‘plagan ballidan kelib chiqib, har bir o‘quv yili uchun alohida belgilanadi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            1.3. “Universitet”ga o‘qishga qabul qilingan “Talaba”lar O‘zbekiston Respublikasining “Ta’lim to‘g‘risida”gi Qonuni va davlat ta’lim standartlarga muvofiq ishlab chiqilgan o‘quv rejalar va fan dasturlari asosida ta’lim oladilar.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            1.4. Talaba to‘lov shartnomasini Universitetning qabul.tpu.uz rasmiy saytidan elektron tarzda oladi, to‘lov shartnomasini olgan talaba ushbu shartnomaning barcha shartlariga rozi bo‘lgan hisoblanadi.
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: center">
            <b>II. TOMONLARNING HUQUQ VA MAJBURIYATLARI</b>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            <b>2.1. Universitetning huquqlari:</b>
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.1.1. Talaba tomonidan o‘z majburiyatlarini bajarishini doimiy nazorat qilish.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.1.2. Talabadan shartnoma majburiyatlarining bajarilishini, ichki tartib qoidalariga rioya etilishini, shartnoma bo‘yicha to‘lovlarni o‘z vaqtida to‘lashni talab qilish.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.1.3. Shartnoma to‘lovini amalga oshirish tartibini, ichki tartib va ta’lim dasturi qoidalarini buzganligi, semestr davomida uzrli sababsiz Universitetda belgilangan akademik soat miqdoridan ortiq dars qoldirgani uchun talabani talabalar safidan ogohlantirmasdan chiqarish (chetlashtirish) yoki Talaba o‘quv yili semestrlarida yakuniy nazoratlarni topshirish, qayta topshirish natijalariga ko‘ra akademik qarzdor bo‘lib qolsa uni kursdan-kursga qoldirish.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.1.4. Talabadan o‘rnatilgan tartibda tegishli hujjatlarni talab qilish va ular taqdim etilmagan taqdirda shartnoma to‘lovi amalga oshirilganidan qat’i nazar, Talabani o‘qishga qabul qilish yoki keyingi kursga o‘tkazish to‘g‘risidagi Universitet rektorining buyrug‘iga kiritmaslik.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.1.5. Universitetning ichki hujjatlarida belgilangan hollarda Talabani imtihonga kiritmaslik.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.1.6. Universitet quyidagi haollarda Talabani o‘qishdan chetlashtirishi mumkin:
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            a) o‘z xohishiga binoan;
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            b) o‘qishning boshqa ta’lim muassasasiga ko‘chirilishi munosabati bilan;
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            g) o‘quv intizomini va oliy ta’lim muassasasining ichki tartib-qoidalari hamda odob-axloq qoidalarini buzganligi uchun;
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            d) bir semestr davomida darslarni uzrli sabablarsiz 74 soatdan ortiq qoldirganligi sababli;
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            e) o‘qish uchun belgilangan to‘lov o‘z vaqtida amalga oshirilmaganligi sababli (to‘lov-kontrakt bo‘yicha tahsil olayotganlar uchun);
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            j) talaba sud tomonidan ozodlikdan mahrum etilganligi munosabati bilan;
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            z) sud qaroriga ko‘ra kirish imtihonlarida belgilangan tartibni buzganligi aniqlanganda (ushbu holatda talabalar safidan chetlashtirilganlar talabalar safiga qayta tiklanmaydi);
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            i) vafot etganligi sababli.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.1.7. Ko‘rsatilayotgan ta’lim xizmatlarining miqdori va sifatini pasaytirmagan holda tasdiqlangan dars jadvaliga o‘zgartirishlar kiritish, O‘zbekiston Respublikasining amaldagi qonunchiligiga muvofiq va fors-major holatlariga qarab, ushbu shartnoma shartlarida belgilangan ta’lim xizmatlari xarajatlarini kamaytirmasdan o‘qitish rejimini masofaviy shaklga o‘tkazish to‘g‘risida qaror qabul qilish.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.1.8. Mehnatga haq to‘lashning eng kam miqdori yoki bazaviy hisoblash miqdori o‘zgarganda, shartnoma to‘lovi miqdorini o‘zgartirish.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.1.9. Shartnoma to‘lovi muddatlarini uzaytirish.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.1.10. O‘zbekiston Respublikasining amaldagi qonunchiligiga muvofiq va fors-major holatlariga qarab, ushbu shartnoma shartlarida belgilangan ta’lim xizmatlari xarajatlarini kamaytirmasdan o‘qitish rejimini masofaviy shaklga o‘tkazish to‘g‘risida qaror qabul qilish.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            <b>2.2. Universitetning majburiyatlari:</b>
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.2.1. Talabani davlat ta’lim standartlari, ta’lim sohasidagi qonunchilik talablari, o‘quv dasturlari hamda ushbu shartnoma shartlariga muvofiq o‘qitish.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.2.2. Talabaning qonunchilikda belgilangan huquqlarini ta’minlash.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.2.3. O‘quv jarayonini yuqori malakali professor-o‘qituvchilarni jalb qilgan holda tashkil etish.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.2.4. O‘quv yili davomida elektron hisob fakturalar yuborish.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.2.5. Universitet quyidagilarni o‘z zimmasiga olmaydi:
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.2.5.1. Talabaning stipendiya va moddiy jihatdan ta’minoti;
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.2.5.2. Talabaning hayoti, sog‘ligi va uning shaxsiy mulki uchun javobgarlik;
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.2.6.  O‘quv jarayonini boshlanishi haqida Universitet rasmiy kanallarida e’lon qiladi, talabalarni har birini telefon yoki xat orqali bu jarayonni boshlanishi haqida ogohlantirishni o‘z majburiyatiga olmaydi, sirtqi ta’lim shaklida tahsil oluvchi tabalarga Hemis elektron platformasida chaqiruv qog‘ozi rasmiylashtiriladi, kunduzgi va kechki talabalarga o‘quv jarayoni boshlanishi bo‘yicha  bo‘yicha rasmiy kanallarda e’lon qiladi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            <b>2.5. Talabaning huquqlari:</b>
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.5.1. Universitetdan shartnoma bo‘yicha o‘z majburiyatlarini bajarishni talab qilish.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.5.2. Universitet tomonidan tasdiqlangan o‘quv reja va dasturlarga muvofiq davlat standarti talablari darajasida ta’lim olish.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.5.3. Universitetning moddiy-texnik bazasidan, jumladan laboratoriya jihozlari, asbob-uskunalar, axborot-resurs markazi va Wi-Fi hududidan foydalanish.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.5.4. Universitetning o‘quv jarayonlarini takomillashtirish bo‘yicha takliflar kiritish.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.5.5. Shartnoma to‘lovini shartnoma shartlariga muvofiq to‘lash.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.5.6. Bilim va ko‘nikmalarini rivojlantirish, takomillashtirish, Universitet taqdim etayotgan barcha imkoniyatlaridan foydalanish, shuningdek, dars va o‘qishdan bo‘sh vaqtlarida jamiyat hayotida faol ishtirok etish.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.5.7. Quyidagi hollarda Universitet ruxsati bilan asoslovchi hujjatlar mavjud bo‘lgan taqdirda Talabalarga akademik ta’til quyidagi hollarda berilishi mumkin:
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            harbiy xizmatni o‘tash uchun;
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            salomatligini tiklash uchun;
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            homiladorlik va tug‘ish uchun;
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            bolalarni parvarish qilish uchun;
        </td>
    </tr>
    <tr>
        <td colspan="4" style="text-align: justify">
            oilasining betob a’zosini (otasi, onasi yoki ularning o‘rnini bosuvchi shaxslar, turmush o‘rtog‘i, farzandi) parvarish qilish uchun.
        </td>
    </tr>
    <tr>
        <td colspan="4" style="text-align: justify">
            Akademik ta’tilning muddatlari Vazirlar Mahkamasining 2021-yil 3-iyundagi 344-son qarori bilan tasdiqlangan O‘zbekiston Respublikasi oliy ta’lim muassasalari talabalariga akademik ta’til berish to‘g‘risida nizomga ko‘ra belgilanadi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.5.8. O‘qishning barcha bosqichlarini muvaffaqiyatli tamomlagandan so‘ng O‘zbekiston Respublikasida oliy ma’lumot to‘g‘risidagi hujjat bo‘lgan Universitetning oliy ma’lumot to‘g‘risidagi diplomini olish.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            <b>2.6. Talabaning majburiyatlari:</b>
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.6.1. Shartnoma to‘lovi bo‘yicha barcha majburiyatlarni o‘z zimmasiga olish.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.6.2. O‘zbekiston Respublikasi qonunchiligida, shuningdek Universitetning o‘quv jarayoni va faoliyatini tartibga soluvchi normativ-huquqiy hujjatlarida belgilangan oliy ta’lim muassasalari talabalariga qo‘yiladigan talablarga muvofiq ta’lim olish.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.6.3. Univesitet ichki hujjatlariga muvofiq talab etiladigan barcha hujjatni taqdim etish.
        </td>
    </tr>
    <tr>
        <td colspan="4" style="text-align: justify">
            2.6.4. Universitet ichki tartib qoidalari, Universitetga kirish-chiqish, shaxsiy va yong‘in xavfsizligi qoidalari talablariga qat’iy rioya qilish.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.6.5. O‘zbekiston Respublikasi qonunchiligiga zid harakatlar va qilmishlarni sodir etmaslik.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.6.6. Universitetning texnik va boshqa o‘quv qurollari, shuningdek asbob-uskuna/jihozlari va boshqa mol-mulkidan oqilona foydalanish.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.6.7. Pasport ma’lumotlari, yashash manzili va telefon raqami o‘zgarganligi to‘g‘risida ular o‘zgartirilgan kundan e’tiboran besh kun ichida Universitetni xabardor qilish.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.6.8. O‘zbekiston Respublikasi hududini Universitetning yozma ruxsati asosida tark etish.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.6.9. O‘zlashtirish darajasi, fanlar/darslar bo‘yicha davomat foizi, Universitet oldidagi moliyaviy majburiyatlarning bajarilishi ustidan doimiy nazorat olib borish.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.6.10. Talaba ushbu shartnomada ko‘zda tutilmagan qo‘shimcha xizmatlarni olganida xizmat haqini to‘lash. Universitetning ichki hujjatlari talablarini buzganda jarima nazarda tutilgan hollarda mazkur jarima(lar)ni o‘z vaqtida to‘lash.
        </td>
    </tr>


    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: center">
            <b>III. TA’LIM XIZMATINI KO‘RSATISH NARXI, TO‘LASH MUDDATI VA TARTIBI</b>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            3.1. 2025-2026 o‘quv yili uchun shartnoma to‘lovi <?= number_format((int)$contract->contract_price, 0, '', ' ') . ' (' . Contract::numUzStr($contract->contract_price) . ')' ?> so‘mni tashkil etadi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            3.2. Universitet ta’lim xizmatlarining narxini o‘zgartirish huquqini o‘zida saqlab qoladi. Bunday holatda Talaba bilan qo‘shimcha kelishuv tuziladi va Tomonlar yangi to‘lov miqdorini hisobga olgan holda o‘zaro hisob-kitoblarni amalga oshirish majburiyatini oladi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            3.3. O‘qish uchun to‘lov quyidagi tartibda amalga oshiriladi:
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            3.3.1. 2025-yil 15-sentabrgaga qadar – shartnoma to‘lovining 25% dan kam bo‘lmagan miqdorda.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            3.3.2. 2025-yil 15-dekabrga qadar – shartnoma to‘lovining 50% dan kam bo‘lmagan miqdorda.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            3.3.3. 2026-yil 15-fevralga qadar – shartnoma to‘lovining 75% dan kam bo‘lmagan miqdorda.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            3.3.4. 2026-yil 15-aprelga qadar ta’lim to‘lovining 100% ini amalga oshirishi zarur.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            3.4. Talaba tomonidan shartnoma to‘lovini to‘lashda shartnomaning tartib raqami va sanasi,  familiyasi, ismi va sharifi hamda o‘quv kursi to‘lov topshiriqnomasida to‘liq ko‘rsatiladi. Universitetning bank hisob raqamiga mablag‘ kelib tushgan kun to‘lov amalga oshirilgan kun hisoblanadi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            3.5. Talaba tegishli fanlar bo‘yicha akademik qarzdorlikni qayta topshirish sharti bilan keyingi kurs (semestr)ga o‘tkazilgan taqdirda, keyingi semestr uchun shartnoma to‘lovi Talaba tomonidan akademik qarzdorlik belgilangan muddatda topshirilishiga qadar amalga oshiriladi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            3.6. Talaba ushbu Shartnomaning amal qilish muddati davomida o‘quv darslarini o‘zlashtira olmagani, ichki tartib qoidalarini, odob-axloq qoidalarini yoki o‘quv jarayoni bilan bog‘liq tartiblarni buzgani sababli unga nisbatan kursdan-kursga qoldirish yoki o‘qishdan chetlatish chorasi ko‘rilganligi, uni o‘qish uchun haq to‘lash bo‘yicha moliyaviy majburiyatlardan ozod etmaydi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            3.7. Shartnoma Universitet tashabbusi bilan Talaba uning xatti-harakatlari (harakatsizligi) sababli talabalar safidan chetlashtirilsa, shartnoma bo‘yicha to‘langan mablag‘lar qaytarilmaydi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            3.8. Talaba 3.3.1.-3.3.4. band bo‘yicha belgilangan muddatlarda shartnoma pulini to‘lash majburiyatini o‘z zimmasiga oladi, belgilangan o‘quv yili uchun shartnoma pulini to‘lash muddati Universitet tomonidan uzaytirilishi mumkin. Belgilangan to‘lovni o‘z vaqtida to‘lamagan talabaga nisbatan Universitet tomonidan ushbu to‘lovni amalga oshirmagunga qadar yakuniy nazoratga ruxsat bermaslik chorasi ko‘riladi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            3.9. Talaba qonunchilikda belgilangan tartibda talabalar safidan chetlatilganda (har qanday sabablardan qat’i nazar (talabaning mashg‘ulotlarga qatnashmaganligi sababli yoki h.k.), talaba chetlatilgunga qadar ko‘rsatilgan xizmatlar uchun talabadan to‘lovni amalga oshirishni talab qilishga haqli.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            3.10. Agar Universitet rektorining Talabani talabalar safiga qabul qilish bo‘yicha buyrug‘i chiqquniga qadar Talaba/to‘lovchi o‘zining yozma xabarnomasiga binoan pulni yoki hujjatlarini qaytarishni so‘ragan taqdirda mehnatga haq to‘lash eng kam miqdorining 50 foizi miqdorida to‘lovni amalga oshirishi lozim.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            3.11. Agarda talaba o‘z xohishiga ko‘ra akademik ta’til olish yoki talabalar safidan chetlashtirish bo‘yicha ariza bilan murojaat qilsa, talaba tomonidan ariza bilan murojaat qilgan sanagacha bo‘lgan muddat uchun ta’lim to‘lovini amalga oshirishi lozim.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            3.12. Talaba o‘quv jarayonlarida fanlarni o‘zlashtira olmagan taqdirda Universitet tomonidan belgilangan tariflar bo‘yicha qo‘shimcha shartnoma asosida fanlarni qayta o‘zlashtirish bo‘yicha qo‘shimcha to‘lovlarni amalga oshirishi lozim.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            3.13. O‘zbekiston Respublikasi Prezidentining tegishli farmoniga muvofiq mehnatga haq to‘lashning eng kam miqdori yoki bazaviy hisoblash miqdori o‘zgarganda, shartnoma to‘lovi miqdori navbatdagi semestr boshidan oshiriladi.
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: center;">
            <b>IV. SHARTNOMAGA O‘ZGARTIRISH KIRITISH VA UNI BEKOR QILISH TARTIBI</b>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify">
            4.1. Ushbu Shartnoma shartlari Tomonlar kelishuvi bilan yoki O‘zbekiston Respublikasi qonunchiligiga muvofiq o‘zgartirilishi mumkin. Shartnomaga kiritilgan barcha o‘zgartirish va qo‘shimchalar, agar ular yozma ravishda tuzilgan va Tomonlar yoki ularning vakolatli vakillari tomonidan imzolangan bo‘lsa, haqiqiy hisoblanadi.
        </td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify">
            4.2. Ushbu Shartnoma quyidagi hollarda bekor qilinishi mumkin:
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            4.2.1. Tomonlarning kelishuviga binoan;
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            4.2.2. Universitetning tashabbusiga ko‘ra bir tomonlama (2.1.6-bandda nazarda tutilgan hollarda);
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            4.2.3. Sudning qonuniy kuchga kirgan qarori asosida;
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            4.2.4. Shartnoma tuzilgan o‘quv yili muddati tugashi munosabati bilan;
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            4.2.5. Talaba o‘qishni muvaffaqiyatli tamomlaganligi munosabati bilan;
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            4.2.6. Universitet faoliyati tugatilgan taqdirda.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            4.3. Shartnomani Universitetning tashabbusiga ko‘ra bir tomonlama tartibda bekor qilinganida Talabaning yuridik yoki elektron pochta manziliga tegishli xabar yuboriladi va shu bilan Talaba xabardor qilingan hisoblanadi.
        </td>
    </tr>


    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: center;">
            <b>V. FORS-MAJOR HOLATLAR</b>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            5.1. Tomonlardan biri tarafidan shartnomani to‘liq yoki qisman bajarishni imkonsiz qiladigan holatlar, xususan, yong‘in, tabiiy ofat, urush, har qanday harbiy harakatlar, mavjud huquqiy hujjatlarni almashtirish va boshqa mumkin bo‘lgan tomonlarga bog‘liq bo‘lmagan fors-major holatlari shartnoma bo‘yicha majburiyatlarni bajarish muddatlari ushbu holatlarning amal qilish muddatiga mos ravishda uzaytiriladi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            5.2. Ushbu shartnoma bo‘yicha o‘z majburiyatlarini bajarishga qodir bo‘lmagan tomon ikkinchi tomonni ushbu holatlarni bajarishiga to‘sqinlik qiladigan holatlar yuzaga kelganligi yoki bekor qilinganligi to‘g‘risida darhol xabardor qilishi shart. <br>
            Xabarnoma shartnomada ko‘rsatilgan yuridik manzilga yuboriladi va jo‘natuvchi pochta bo‘limi tomonidan tasdiqlanadi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: center;">
            5.3. Agar shartnoma tomonlariga bog‘liq bo‘lmagan tarzda sodir bo‘lgan har qanday hodisa, tabiiy ofatlar, urush yoki mamlakatdagi favqulodda holat, davlat hokimiyati organi tomonidan qabul qilingan qaror, uning ijrosi, uning yuzasidan amalga oshirilgan harakatlar (shular bilan cheklanmagan hodisalar) tufayli yuzaga kelgan bo‘lsa, bir tomon ikkinchi tomon oldida ushbu shartnomani bajarmaslik yoki bajarishni kechiktirish oqibatlari uchun javobgar bo‘lmaydi. Ijrosi shu tarzda to‘xtatilgan tomon bunday majburiyatlarni bajarish muddatini tegishli ravishda uzaytirish huquqiga ega bo‘ladi.
        </td>
    </tr>


    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: center;">
            <b>VI. TOMONLARNING JAVOBGARLIGI</b>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify">
            6.1. Talaba mol-mulk, jihozlar, o‘quv qurollari va hokazoga moddiy zarar yetkazgan taqdirda Universitet oldida to‘liq moddiy javobgarlikni o‘z zimmasiga oladi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            6.2. Ushbu shartnomaning 3.3-bandiga muvofiq o‘qish uchun to‘lov kechiktirilgan taqdirda:
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            6.2.1. Talabaning Universitetga kirishi cheklanadi;
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            6.2.2. Quyidagilar to‘xtatiladi:<br>
            Universitet tomonidan akademik xizmatlar ko‘rsatilishi; <br>
            Talabani imtihonlarga kiritilishi; <br>
            har qanday akademik ma’lumotnomalar/sertifikatlar berish.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            6.3. Universitet shartnoma to‘lovi manbalari uchun javobgarlikni o‘z zimmasiga olmaydi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            6.4. Universitet shartnoma to‘lovini amalga oshirishda yo‘l qo‘yilgan xatolar uchun javobgar bo‘lmaydi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            6.5. Talabaning o‘qishdan chetlashtirilishi yoki talabalar safidan chiqarilishi Talabani ushbu shartnoma bo‘yicha Talabaga ko‘rsatilgan ta’lim xizmatlari uchun haq to‘lash hamda boshqa majburiyatlardan ozod etmaydi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            6.6. Tomonlarning ushbu Shartnomada nazarda tutilmagan javobgarlik choralari O‘zbekiston Respublikasining amaldagi qonunchiligi bilan belgilanadi.
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: center;">
            <b>VII. QO‘SHIMCHA SHARTLAR</b>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify">
            7.1. Universitetning Talabani o‘qishga qabul qilish buyrug‘i Talaba tomonidan barcha kerakli hujjatlarni taqdim etish va shartnomaning 3.3.1-bandiga muvofiq to‘lovni amalga oshirish sharti bilan chiqariladi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            7.2. Talabaga Universitet tomonidan stipendiya to‘lanmaydi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            7.3. Mazkur Shartnomaning 1-bandida nazarda tutilgan majburiyatlar O‘zbekiston Respublikasining amaldagi qonunchiligi talablariga muvofiq, bevosita yoki onlayn tarzda taqdim etilishi mumkin. Akademik ta’lim xizmatlari onlayn tarzda taqdim etilgan taqdirda, Talaba texnik va telekommunikatsiya aloqalari holatining sifati uchun shaxsan javobgardir.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            7.4. Ushbu Shartnoma Tomonlar bir o‘quv yili uchun uning predmetida ko‘rsatilgan maqsadlar uchun o‘z majburiyatlarini to‘liq bajarguniga qadar, lekin 2026-yil 1-iyuldan kechikmagan muddatga qadar tuziladi. Shartnomaning amal qilish muddati tugaganligi qarzdor Tomonlarni o‘z zimmasidagi majburiyatlarini bajarishdan ozod qilmaydi.
        </td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify">
            7.5. O‘qish davrida Talaba yoki boshqa shaxsga rasmiy hujjatlarning asl nusxalari, shu jumladan o‘rta yoki o‘rta maxsus ta’lim muassasasining bitiruv hujjatlari (attestat/diplom/sertifikat) berilmaydi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            7.6. Universitet Talabani ishga joylashtirish majburiyatini o‘z zimmasiga olmaydi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            7.7. Shartnoma to‘lovlari va ularni qaytarish bilan bog‘liq barcha bank xizmatlari Talaba tomonidan to‘lanadi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            7.8. Universitet tomonidan ushbu shartnoma bo‘yicha mablag‘lar qaytarilishi lozim bo‘lgan hollarda mazkur mablag‘lar tegishli hujjat o‘z kuchiga kirgan paytdan boshlab 30 (o‘ttiz) kun ichida qaytariladi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            7.9. Ushbu Shartnomaga kiritilgan har qanday o‘zgartirish va/yoki qo‘shimchalar, agar ular tomonlar tomonidan yozma shaklda rasmiylashtirilgan, imzolangan/muhrlangan bo‘lsagina amal qiladi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            7.10. Tomonlar shartnomada Universitet faksimilesini tegishli imzo sifatida tan olishga kelishib oldilar.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            7.11. Ushbu shartnomadan kelib chiqadigan har qanday nizo yoki kelishmovchiliklarni tomonlar muzokaralar yo‘li bilan hal qilishga intiladi. Kelishuvga erishilmagan taqdirda, nizolar O‘zbekiston Respublikasi qonun hujjatlarida belgilangan tartibda Universitet joylashgan yerdagi sud tomonidan ko‘rib chiqiladi.
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: center;">
            <b>VIII. YAKUNIY QOIDALAR</b>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify">
            8.1. Ushbu shartnoma Tomonlar tomonidan imzolangan paytdan boshlab kuchga kiradi.
        </td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify">
            8.2. Talaba shartnoma shartlaridan norozi bo‘lgan taqdirda 05.09.2025-yildan kechiktirmay murojaat qilishi lozim, bunda mazkur sanaga qadar Universitet bilan shartnoma tuzmagan Talaba o‘qishga qabul qilinmaydi.
        </td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify">
            8.3. Mazkur shartnomani imzolanishi, o‘zgartirilishi, ijro etilishi, bekor qilinishi yuzasidan Tomonlar o‘rtasida yozishmalar shartnomada ko‘rsatilgan Tomonlarning rasmiy elektron pochta manzillari orqali amalga oshirilishi mumkin va Tomonlar bu tartibda yuborilgan xabarlarning yuridik kuchga ega ekanligini tan oladilar. Elektron pochta manzili o‘zgarganligi to‘g‘risida boshqa tomonni yozma ravishda xabardor qilmagan tomon bu bilan bog‘liq barcha xavflarni o‘z zimmasiga oladi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            8.4. Ushbu Shartnoma o‘zbek tilida, uch asl nusxada, teng yuridik kuchga ega, har bir tomon uchun bir nusxadan tuzildi.
        </td>
    </tr>



    <tr>
        <td colspan="4">
            <div>
                <table width="100%">

                    <tr>
                        <td>&nbsp;</td>
                    </tr>

                    <tr>
                        <td colspan="4" style="text-align: center;">
                            <b>IX. TOMONLARNING YURIDIK MANZILLARI VA BANK REKVIZITLARI</b>
                        </td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                    </tr>

                    <tr>
                        <td colspan="2">
                            <b>UNIVERSITET</b>
                        </td>
                        <td colspan="2">
                            <b>Talaba</b>
                        </td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                    </tr>

                    <tr>
                        <td colspan="2" style="vertical-align: top">
                            <b>“PERFECT-UNIVERSITY” oliy ta’lim tashkiloti</b> <br>
                            <b>Manzili:</b> <?= $filial->address_uz ?> <br>
                            <b>H/R:</b> <?= $cons->hr ?> <br>
                            <b>Bank:</b> <?= $cons->bank_name_uz ?> <br>
                            <b>Bank kodi (MFO):</b> <?= $cons->mfo ?> <br>
                            <b>STIR (INN):</b> <?= $cons->inn ?> <br>
                            <b>Tel:</b> <?= $cons->tel1 ?> <br>
                            <b>Rektor:</b> <?= $filial->rector_uz ?> <br>
                        </td>
                        <td colspan="2" style="vertical-align: top">
                            <b>Talabaning F.I.O.:</b> <?= $full_name ?> <br>
                            <b>Pasport ma’lumotlari:</b> <?= $student->passport_serial . ' ' . $student->passport_number ?> <br>
                            <b>JShShIR raqami:</b> <?= $student->passport_pin ?> <br>
                            <b>Tеlefon raqami: </b> <?= $student->user->username ?> <br>
                            <b>Talaba imzosi: </b> ______________ <br>
                        </td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                    </tr>

                    <tr>
                        <td colspan="2" style="vertical-align: top;">
                            <img src="<?= $img ?>" width="120px">
                        </td>
                        <td colspan="2" style="vertical-align: top">
                            <img src="<?= $limg ?>" width="120px"> <br>
                            <b>Litsenziya berilgan sana va raqami</b> <br>
                            19.10.2022 <b>№ 424703</b>
                        </td>
                    </tr>

                </table>
            </div>
        </td>
    </tr>

</table>