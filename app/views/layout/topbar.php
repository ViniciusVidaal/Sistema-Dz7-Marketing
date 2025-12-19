<?php
$unreadCount = 0;
if ($user) {
    $roleForView = ($user['role'] === 'ADMIN' && view_as_employee()) ? 'EMPLOYEE' : $user['role'];
    $unreadCount = Notification::unreadCount($user['id'], $roleForView);
}
$showEmployeeView = $user && $user['role'] === 'ADMIN' && view_as_employee();
?>
<header class="fixed top-0 left-0 right-0 lg:left-64 bg-slate-950/80 border-b border-slate-800 backdrop-blur z-40">
    <div class="px-6 py-4 flex flex-wrap items-center gap-4">
        <button type="button" id="sidebarToggle" class="lg:hidden text-sm px-3 py-2 rounded-lg border border-slate-700 hover:bg-slate-800">Menu</button>
        <form action="/leads" method="get" class="flex-1 min-w-[220px]">
            <input type="text" name="search" placeholder="Buscar por cliente ou GMN" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-dz7-500">
        </form>
        <?php if ($user && $user['role'] === 'ADMIN'): ?>
            <a href="/profile/toggle-view" class="text-xs px-3 py-2 rounded-lg border border-dz7-500 text-dz7-100 hover:bg-dz7-600">
                <?php echo $showEmployeeView ? 'Voltar modo admin' : 'Modo funcionario'; ?>
            </a>
        <?php endif; ?>
        <a href="/notifications" class="relative text-sm px-3 py-2 rounded-lg border border-slate-700 hover:bg-slate-800">
            Notificacoes
            <?php if ($unreadCount > 0): ?>
                <span class="absolute -top-2 -right-2 bg-rose-500 text-xs rounded-full px-2 py-0.5"><?php echo $unreadCount; ?></span>
            <?php endif; ?>
        </a>
        <a href="/logout" class="text-sm px-3 py-2 rounded-lg border border-slate-700 hover:bg-slate-800">Sair</a>
    </div>
</header>
