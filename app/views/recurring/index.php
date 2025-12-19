<?php
$canManage = is_admin();
?>
<div class="space-y-6">
    <section>
        <h2 class="text-xl font-semibold">Clientes Recorrentes</h2>
        <p class="text-sm text-slate-400">Gestao de pagamentos mensais.</p>
    </section>

    <section class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-4">
            <div class="text-xs text-slate-400">Total contratos</div>
            <div class="text-xl font-semibold mt-2"><?php echo $totals['total']; ?></div>
        </div>
        <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-4">
            <div class="text-xs text-slate-400">Contratos ativos</div>
            <div class="text-xl font-semibold mt-2"><?php echo $totals['active']; ?></div>
        </div>
        <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-4">
            <div class="text-xs text-slate-400">Receita recorrente mensal</div>
            <div class="text-xl font-semibold mt-2"><?php echo format_money($totals['monthly_revenue']); ?></div>
        </div>
        <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-4">
            <div class="text-xs text-slate-400">Receita de entrada</div>
            <div class="text-xl font-semibold mt-2"><?php echo format_money($totals['entry_revenue']); ?></div>
        </div>
    </section>

    <section class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-xs text-slate-400">
                <tr>
                    <th class="text-left py-2">Cliente</th>
                    <th class="text-left py-2">GMN</th>
                    <th class="text-left py-2">Servicos</th>
                    <th class="text-left py-2">Entrada</th>
                    <th class="text-left py-2">Mensal</th>
                    <th class="text-left py-2">Inicio</th>
                    <th class="text-left py-2">Termino</th>
                    <th class="text-left py-2">Dia pgto</th>
                    <th class="text-left py-2">Status contrato</th>
                    <th class="text-left py-2">Status financeiro</th>
                    <th class="text-right py-2">Acoes</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contracts as $contract): ?>
                    <tr class="border-t border-slate-800">
                        <td class="py-2"><a href="/recurring/<?php echo $contract['id']; ?>" class="text-dz7-300 hover:text-dz7-100"><?php echo e($contract['name']); ?></a></td>
                        <td class="py-2"><?php echo e($contract['business_gmn']); ?></td>
                        <td class="py-2"><?php echo e($contract['services'] ?: '-'); ?></td>
                        <td class="py-2"><?php echo format_money($contract['entry_value']); ?></td>
                        <td class="py-2"><?php echo format_money($contract['monthly_value']); ?></td>
                        <td class="py-2"><?php echo e($contract['start_date']); ?></td>
                        <td class="py-2"><?php echo e($contract['end_date']); ?></td>
                        <td class="py-2"><?php echo e($contract['pay_day']); ?></td>
                        <td class="py-2">
                            <span class="px-2 py-1 rounded-full text-xs <?php echo $contract['status'] === 'ACTIVE' ? 'bg-emerald-500/20 text-emerald-300' : 'bg-slate-700 text-slate-300'; ?>">
                                <?php echo $contract['status']; ?>
                            </span>
                        </td>
                        <td class="py-2">
                            <span class="px-2 py-1 rounded-full text-xs <?php echo $contract['current_status'] === 'PAID' ? 'bg-emerald-500/20 text-emerald-300' : ($contract['current_status'] === 'INADIMPLENT' ? 'bg-rose-500/20 text-rose-300' : 'bg-amber-500/20 text-amber-300'); ?>">
                                <?php echo $contract['current_status']; ?>
                            </span>
                        </td>
                        <td class="py-2 text-right">
                            <?php if ($canManage): ?>
                                <form method="post" action="/recurring/<?php echo $contract['id']; ?>/paid" class="inline">
                                    <input type="hidden" name="csrf_token" value="<?php echo e(Csrf::token()); ?>">
                                    <input type="hidden" name="reference_month" value="<?php echo e($current_month); ?>">
                                    <button class="text-emerald-300 hover:text-emerald-100">Marcar pago</button>
                                </form>
                                <form method="post" action="/recurring/<?php echo $contract['id']; ?>/inad" class="inline ml-2">
                                    <input type="hidden" name="csrf_token" value="<?php echo e(Csrf::token()); ?>">
                                    <input type="hidden" name="reference_month" value="<?php echo e($current_month); ?>">
                                    <button class="text-rose-300 hover:text-rose-100">Inadimplente</button>
                                </form>
                                <form method="post" action="/recurring/<?php echo $contract['id']; ?>/status" class="inline ml-2">
                                    <input type="hidden" name="csrf_token" value="<?php echo e(Csrf::token()); ?>">
                                    <input type="hidden" name="status" value="<?php echo $contract['status'] === 'ACTIVE' ? 'INACTIVE' : 'ACTIVE'; ?>">
                                    <button class="text-slate-300 hover:text-white"><?php echo $contract['status'] === 'ACTIVE' ? 'Inativar' : 'Ativar'; ?></button>
                                </form>
                            <?php else: ?>
                                <span class="text-xs text-slate-500">Somente admin</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($contracts)): ?>
                    <tr><td colspan="11" class="py-4 text-center text-xs text-slate-500">Nenhum contrato recorrente.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
</div>
