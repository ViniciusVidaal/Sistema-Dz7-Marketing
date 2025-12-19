<?php
$canManage = is_admin();
?>
<div class="space-y-6">
    <section>
        <h2 class="text-xl font-semibold">Detalhes do contrato</h2>
        <p class="text-sm text-slate-400"><?php echo e($contract['name']); ?> - <?php echo e($contract['business_gmn']); ?></p>
    </section>

    <section class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-4">
            <div class="text-xs text-slate-400">Entrada</div>
            <div class="text-xl font-semibold mt-2"><?php echo format_money($contract['entry_value']); ?></div>
        </div>
        <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-4">
            <div class="text-xs text-slate-400">Mensalidade</div>
            <div class="text-xl font-semibold mt-2"><?php echo format_money($contract['monthly_value']); ?></div>
        </div>
        <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-4">
            <div class="text-xs text-slate-400">Status atual</div>
            <div class="text-xl font-semibold mt-2"><?php echo e($contract['current_status']); ?></div>
        </div>
    </section>

    <section class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6">
        <h3 class="text-sm font-semibold mb-4">Pagamento do mes</h3>
        <?php if ($canManage): ?>
            <div class="flex gap-3">
                <form method="post" action="/recurring/<?php echo $contract['id']; ?>/paid">
                    <input type="hidden" name="csrf_token" value="<?php echo e(Csrf::token()); ?>">
                    <input type="hidden" name="reference_month" value="<?php echo e($current_month); ?>">
                    <button class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-lg">Marcar pago</button>
                </form>
                <form method="post" action="/recurring/<?php echo $contract['id']; ?>/inad">
                    <input type="hidden" name="csrf_token" value="<?php echo e(Csrf::token()); ?>">
                    <input type="hidden" name="reference_month" value="<?php echo e($current_month); ?>">
                    <button class="bg-rose-500 hover:bg-rose-600 text-white px-4 py-2 rounded-lg">Inadimplente</button>
                </form>
            </div>
        <?php else: ?>
            <div class="text-xs text-slate-500">Somente admin pode alterar status financeiro.</div>
        <?php endif; ?>
    </section>

    <section class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 overflow-x-auto">
        <h3 class="text-sm font-semibold mb-4">Historico de pagamentos</h3>
        <table class="w-full text-sm">
            <thead class="text-xs text-slate-400">
                <tr>
                    <th class="text-left py-2">Mes</th>
                    <th class="text-left py-2">Status</th>
                    <th class="text-left py-2">Pago em</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contract['payments'] as $payment): ?>
                    <tr class="border-t border-slate-800">
                        <td class="py-2"><?php echo e($payment['reference_month']); ?></td>
                        <td class="py-2"><?php echo e($payment['status']); ?></td>
                        <td class="py-2"><?php echo e($payment['paid_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($contract['payments'])): ?>
                    <tr><td colspan="3" class="py-4 text-center text-xs text-slate-500">Sem pagamentos registrados.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
</div>
