/*
* محمود مصطفي أبوعرقوب
 * 4/2/2024
 * كود إنهاء الجلسة:
 * يُستخدم هذا الكود لإنهاء جلسة المستخدم، وذلك بإيقاف جلسة العمل (session)، ثم يقوم بإعادة توجيه المستخدم إلى صفحة تسجيل الدخول.
 *
 * الدوال المستخدمة:
 * - require_once: تُستخدم لتضمين ملف التحكم في جلسات المستخدم.
 * - SMSSessionHandler::start(): تقوم ببدء أو استئناف جلسة المستخدم.
 * - SMSSessionHandler::destroy(): تُستخدم لإنهاء جلسة المستخدم بشكل كامل.
 * - header('Location: ../login.php'): تُعيد توجيه المتصفح إلى صفحة تسجيل الدخول بعد إنهاء الجلسة.
 */
<?php
require_once './session_handler.php';
SMSSessionHandler::start();

SMSSessionHandler::destroy();
header('Location: ../login.php');