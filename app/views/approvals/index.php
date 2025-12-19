<div class="space-y-6">
    <section>
        <h2 class="text-xl font-semibold">Aprovacoes</h2>
        <p class="text-sm text-slate-400">Solicitacoes pendentes de alteracao.</p>
    </section>

    <section class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-xs text-slate-400">
                <tr>
                    <th class="text-left py-2">Tipo</th>
                    <th class="text-left py-2">Solicitante</th>
                    <th class="text-left py-2">Data</th>
                    <th class="text-left py-2">Resumo</th>
                    <th class="text-right py-2">Acoes</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($approvals as $approval): ?>
                    <tr class="border-t border-slate-800">
                        <td class="py-2"><?php echo e($approval['entity_type']); ?></td>
                        <td class="py-2"><?php echo e($approval['requested_by_name']); ?></td>
                        <td class="py-2"><?php echo e($approval['requested_at']); ?></td>
                        <td class="py-2"><?php echo e($approval['summary']); ?></td>
                        <td class="py-2 text-right">
                            <a href="/approvals/<?php echo $approval['id']; ?>" class="text-dz7-300 hover:text-dz7-100">Ver</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($approvals)): ?>
                    <tr><td colspan="5" class="py-4 text-center text-xs text-slate-500">Nenhuma solicitacao pendente.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
</div>
