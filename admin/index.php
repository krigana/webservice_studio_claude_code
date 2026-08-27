<?php
declare(strict_types=1);

require __DIR__ . '/includes/admin-bootstrap.php';
Auth::requireLogin();

$stats = [
    'leads_new' => Lead::newCount(),
    'posts' => BlogPost::count(),
    'cases' => PortfolioCase::count(),
    'services' => Service::count(),
];

admin_header('Дашборд', 'dashboard');
?>
<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(160px,1fr)); gap:16px;">
  <a href="/admin/leads/" class="card" style="color:inherit;"><div style="font-size:28px; font-weight:800;"><?= $stats['leads_new'] ?></div><div style="color:#7C99A1; font-size:13.5px;">Нові заявки</div></a>
  <a href="/admin/blog/" class="card" style="color:inherit;"><div style="font-size:28px; font-weight:800;"><?= $stats['posts'] ?></div><div style="color:#7C99A1; font-size:13.5px;">Статей у блозі</div></a>
  <a href="/admin/portfolio/" class="card" style="color:inherit;"><div style="font-size:28px; font-weight:800;"><?= $stats['cases'] ?></div><div style="color:#7C99A1; font-size:13.5px;">Кейсів у портфоліо</div></a>
  <a href="/admin/services/" class="card" style="color:inherit;"><div style="font-size:28px; font-weight:800;"><?= $stats['services'] ?></div><div style="color:#7C99A1; font-size:13.5px;">Послуг</div></a>
</div>
<?php admin_footer(); ?>
