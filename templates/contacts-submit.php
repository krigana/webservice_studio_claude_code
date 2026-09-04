<?php
/**
 * Обработка формы связи: валидация, honeypot, CSRF, сохранение заявки
 * в БД и мгновенное уведомление в Telegram (ТЗ п.4.4).
 */

declare(strict_types=1);

// honeypot — если заполнено, это бот: тихо "успешно" завершаем, никуда не сохраняя
if (!empty($_POST['website'])) {
    redirect('/kontakty?sent=1');
}

if (!csrf_verify()) {
    flash_set('contact_error', 'Форма застаріла, оновіть сторінку і спробуйте ще раз.');
    redirect('/kontakty');
}

// reCAPTCHA v2 (якщо ключі задані в .env — див. Recaptcha.php)
if (Recaptcha::isEnabled($GLOBALS['config'])) {
    $recaptchaToken = (string) ($_POST['g-recaptcha-response'] ?? '');
    if (!Recaptcha::verify($GLOBALS['config'], $recaptchaToken, $_SERVER['REMOTE_ADDR'] ?? null)) {
        flash_set('contact_error', 'Підтвердіть, будь ласка, що ви не робот, і спробуйте ще раз.');
        redirect('/kontakty');
    }
}

// простая защита от повторной отправки чаще, чем раз в 20 секунд
if (!empty($_SESSION['last_lead_at']) && (time() - $_SESSION['last_lead_at']) < 20) {
    flash_set('contact_error', 'Заявку вже надіслано, зачекайте трохи перед повторною спробою.');
    redirect('/kontakty');
}

$name = trim((string) ($_POST['name'] ?? ''));
$contact = trim((string) ($_POST['contact'] ?? ''));
$message = trim((string) ($_POST['message'] ?? ''));
$serviceId = !empty($_POST['service_id']) ? (int) $_POST['service_id'] : null;

if ($name === '' || $contact === '') {
    flash_set('contact_error', "Вкажіть ім'я та контакт для зв'язку.");
    redirect('/kontakty');
}
if (mb_strlen($name) > 150 || mb_strlen($contact) > 190 || mb_strlen($message) > 2000) {
    flash_set('contact_error', 'Занадто довге значення в одному з полів.');
    redirect('/kontakty');
}

$serviceTitle = null;
if ($serviceId !== null) {
    $service = Service::find($serviceId);
    if ($service !== null) {
        $serviceTitle = $service['title'];
    } else {
        $serviceId = null;
    }
}

$leadId = Lead::create([
    'name' => $name,
    'contact' => $contact,
    'service_id' => $serviceId,
    'message' => $message !== '' ? $message : null,
    'source_url' => $_SERVER['HTTP_REFERER'] ?? null,
    'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
]);

$text = "<b>Нова заявка з сайту</b>\n"
    . "Ім'я: " . htmlspecialchars($name, ENT_QUOTES) . "\n"
    . "Контакт: " . htmlspecialchars($contact, ENT_QUOTES) . "\n"
    . ($serviceTitle ? "Послуга: " . htmlspecialchars($serviceTitle, ENT_QUOTES) . "\n" : '')
    . ($message !== '' ? "Повідомлення: " . htmlspecialchars($message, ENT_QUOTES) . "\n" : '');

$notified = Telegram::send($GLOBALS['config'], $text);
if ($notified) {
    Lead::update($leadId, ['telegram_notified' => 1]);
}

$_SESSION['last_lead_at'] = time();

redirect('/kontakty?sent=1');
