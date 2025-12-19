<?php
$role = $user ? $user['role'] : 'EMPLOYEE';
if ($role === 'ADMIN' && view_as_employee()) {
    $role = 'EMPLOYEE';
}
?>
<div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-30 hidden lg:hidden"></div>
<aside id="sidebar" class="fixed inset-y-0 left-0 w-64 flex flex-col bg-slate-950/90 border-r border-slate-800 z-40 transform -translate-x-full transition-transform duration-200 lg:translate-x-0 lg:static lg:inset-auto">
    <div class="px-6 py-6">
        <div class="text-xl font-semibold">Dz7 Manager</div>
        <div class="text-xs text-slate-400">Dz7 Marketing</div>
    </div>
    <nav class="flex-1 px-4 space-y-2 text-sm">
        <a href="/dashboard" class="block px-3 py-2 rounded-lg hover:bg-slate-800">Dashboard</a>
        <?php if ($role === 'ADMIN'): ?>
            <a href="/finance" class="block px-3 py-2 rounded-lg hover:bg-slate-800">Financeiro</a>
        <?php endif; ?>
        <a href="/leads" class="block px-3 py-2 rounded-lg hover:bg-slate-800">Leads/Clientes</a>
        <a href="/recurring" class="block px-3 py-2 rounded-lg hover:bg-slate-800">Recorrencias</a>
        <a href="/tools" class="block px-3 py-2 rounded-lg hover:bg-slate-800">Ferramentas</a>
        <a href="/investments" class="block px-3 py-2 rounded-lg hover:bg-slate-800">Investimentos</a>
        <a href="/goals/company" class="block px-3 py-2 rounded-lg hover:bg-slate-800">Metas Empresa</a>
        <a href="/goals/personal" class="block px-3 py-2 rounded-lg hover:bg-slate-800">Minhas Metas</a>
        <?php if ($role === 'ADMIN'): ?>
            <a href="/users" class="block px-3 py-2 rounded-lg hover:bg-slate-800">Usuarios</a>
            <a href="/approvals" class="block px-3 py-2 rounded-lg hover:bg-slate-800">Aprovacoes</a>
        <?php endif; ?>
        <a href="/notifications" class="block px-3 py-2 rounded-lg hover:bg-slate-800">Notificacoes</a>
    </nav>
    <div class="px-6 py-6 text-xs text-slate-400">
        <?php if ($user): ?>
            <div><?php echo e($user['name']); ?></div>
            <div><?php echo e($user['position']); ?></div>
        <?php endif; ?>
    </div>
</aside>
