<?php
$priorityColors = [
    'ALTA' => 'bg-rose-500/20 text-rose-300',
    'MEDIA' => 'bg-amber-500/20 text-amber-300',
    'BAIXA' => 'bg-emerald-500/20 text-emerald-300',
];
?>
<div class="space-y-6">
    <section class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-semibold">Investimentos e Melhorias</h2>
            <p class="text-sm text-slate-400">Registro de melhorias planejadas.</p>
        </div>
        <a href="/investments/create" class="bg-dz7-500 hover:bg-dz7-600 text-white px-4 py-2 rounded-lg text-sm">Novo investimento</a>
    </section>

    <section class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-xs text-slate-400">
                <tr>
                    <th class="text-left py-2">Nome</th>
                    <th class="text-left py-2">Valor</th>
                    <th class="text-left py-2">Prioridade</th>
                    <th class="text-left py-2">Status</th>
                    <th class="text-left py-2">Limite</th>
                    <th class="text-right py-2">Acoes</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($investments as $item): ?>
                    <tr class="border-t border-slate-800">
                        <td class="py-2"><?php echo e($item['name']); ?></td>
                        <td class="py-2"><?php echo format_money($item['value']); ?></td>
                        <td class="py-2">
                            <span class="px-2 py-1 rounded-full text-xs <?php echo $priorityColors[$item['priority']] ?? 'bg-slate-700 text-slate-300'; ?>">
                                <?php echo e($item['priority']); ?>
                            </span>
                        </td>
                        <td class="py-2"><?php echo e($item['status']); ?></td>
                        <td class="py-2"><?php echo e($item['due_date']); ?></td>
                        <td class="py-2 text-right">
                            <a href="/investments/<?php echo $item['id']; ?>/edit" class="text-dz7-300 hover:text-dz7-100">Editar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($investments)): ?>
                    <tr><td colspan="6" class="py-4 text-center text-xs text-slate-500">Nenhum investimento cadastrado.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
</div>
