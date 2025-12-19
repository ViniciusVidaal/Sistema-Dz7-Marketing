<div class="space-y-6">
    <section class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6">
        <h2 class="text-xl font-semibold">Financeiro</h2>
        <p class="text-sm text-slate-400">Lancamentos baseados em eventos financeiros.</p>
    </section>

    <section class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-5">
            <h3 class="text-sm font-semibold mb-4">Ajuste de caixa <span title="Use para corrigir saldo sem editar manualmente." class="text-xs text-slate-400">?</span></h3>
            <form method="post" action="/finance/adjustment" class="space-y-3">
                <input type="hidden" name="csrf_token" value="<?php echo e(Csrf::token()); ?>">
                <input type="date" name="event_date" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2" value="<?php echo date('Y-m-d'); ?>">
                <input type="text" name="amount" placeholder="Valor" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
                <select name="direction" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
                    <option value="IN">Entrada</option>
                    <option value="OUT">Saida</option>
                </select>
                <input type="text" name="reason" placeholder="Motivo" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
                <button class="w-full bg-dz7-500 hover:bg-dz7-600 text-white py-2 rounded-lg text-sm">Salvar ajuste</button>
            </form>
        </div>
        <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-5">
            <h3 class="text-sm font-semibold mb-4">Gasto trafego pago <span title="Lancamento diario por plataforma." class="text-xs text-slate-400">?</span></h3>
            <form method="post" action="/finance/traffic" class="space-y-3">
                <input type="hidden" name="csrf_token" value="<?php echo e(Csrf::token()); ?>">
                <input type="date" name="event_date" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2" value="<?php echo date('Y-m-d'); ?>">
                <input type="text" name="amount" placeholder="Valor" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
                <select name="platform" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
                    <option value="META_ADS">Meta Ads</option>
                    <option value="GOOGLE_ADS">Google Ads</option>
                </select>
                <input type="text" name="card_last4" placeholder="Ultimos 4 digitos" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
                <button class="w-full bg-dz7-500 hover:bg-dz7-600 text-white py-2 rounded-lg text-sm">Salvar gasto</button>
            </form>
        </div>
        <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-5">
            <h3 class="text-sm font-semibold mb-4">Gasto diverso <span title="Historico com filtros por data." class="text-xs text-slate-400">?</span></h3>
            <form method="post" action="/finance/misc" class="space-y-3">
                <input type="hidden" name="csrf_token" value="<?php echo e(Csrf::token()); ?>">
                <input type="date" name="event_date" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2" value="<?php echo date('Y-m-d'); ?>">
                <input type="text" name="amount" placeholder="Valor" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
                <input type="text" name="category" placeholder="Categoria" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
                <input type="text" name="description" placeholder="Descricao" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
                <button class="w-full bg-dz7-500 hover:bg-dz7-600 text-white py-2 rounded-lg text-sm">Salvar gasto</button>
            </form>
        </div>
    </section>

    <section class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6">
        <div class="flex flex-wrap gap-4 items-end justify-between">
            <div>
                <h3 class="text-sm font-semibold">Eventos financeiros</h3>
                <p class="text-xs text-slate-400">Fonte da verdade para saldo e faturamento.</p>
            </div>
            <form method="get" class="flex flex-wrap gap-2">
                <input type="date" name="start" value="<?php echo e($filters['start']); ?>" class="bg-slate-950 border border-slate-700 rounded-lg px-3 py-2 text-sm">
                <input type="date" name="end" value="<?php echo e($filters['end']); ?>" class="bg-slate-950 border border-slate-700 rounded-lg px-3 py-2 text-sm">
                <select name="type" class="bg-slate-950 border border-slate-700 rounded-lg px-3 py-2 text-sm">
                    <option value="">Tipo</option>
                    <option value="CONTRACT_ENTRY" <?php echo $filters['type'] === 'CONTRACT_ENTRY' ? 'selected' : ''; ?>>Entrada contrato</option>
                    <option value="RECURRING_PAYMENT" <?php echo $filters['type'] === 'RECURRING_PAYMENT' ? 'selected' : ''; ?>>Recorrencia</option>
                    <option value="TRAFFIC_SPEND" <?php echo $filters['type'] === 'TRAFFIC_SPEND' ? 'selected' : ''; ?>>Trafego</option>
                    <option value="MISC_SPEND" <?php echo $filters['type'] === 'MISC_SPEND' ? 'selected' : ''; ?>>Diversos</option>
                    <option value="CASH_ADJUSTMENT" <?php echo $filters['type'] === 'CASH_ADJUSTMENT' ? 'selected' : ''; ?>>Ajuste caixa</option>
                </select>
                <select name="direction" class="bg-slate-950 border border-slate-700 rounded-lg px-3 py-2 text-sm">
                    <option value="">Direcao</option>
                    <option value="IN" <?php echo $filters['direction'] === 'IN' ? 'selected' : ''; ?>>Entrada</option>
                    <option value="OUT" <?php echo $filters['direction'] === 'OUT' ? 'selected' : ''; ?>>Saida</option>
                </select>
                <button class="bg-dz7-500 hover:bg-dz7-600 text-white px-4 py-2 rounded-lg text-sm">Filtrar</button>
            </form>
        </div>
        <div class="mt-4 text-sm text-slate-400">Totais no periodo: entradas <?php echo format_money($totals['in']); ?> | saidas <?php echo format_money($totals['out']); ?></div>
        <div class="mt-4 overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-xs text-slate-400">
                    <tr>
                        <th class="text-left py-2">Data</th>
                        <th class="text-left py-2">Tipo</th>
                        <th class="text-left py-2">Descricao</th>
                        <th class="text-right py-2">Valor</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $event): ?>
                        <tr class="border-t border-slate-800">
                            <td class="py-2"><?php echo e($event['event_date']); ?></td>
                            <td class="py-2"><?php echo e($event['type']); ?></td>
                            <td class="py-2"><?php echo e($event['description']); ?></td>
                            <td class="py-2 text-right <?php echo $event['direction'] === 'IN' ? 'text-emerald-400' : 'text-rose-400'; ?>">
                                <?php echo format_money($event['amount']); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($events)): ?>
                        <tr><td colspan="4" class="py-4 text-center text-xs text-slate-500">Nenhum evento encontrado.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php
            $query = $_GET;
            unset($query['page']);
            $baseQuery = http_build_query($query);
            $baseQuery = $baseQuery ? $baseQuery . '&' : '';
        ?>
        <div class="mt-4 flex items-center justify-between text-sm">
            <div class="text-xs text-slate-500">Pagina <?php echo $page; ?> de <?php echo $total_pages; ?></div>
            <div class="flex gap-2">
                <?php if ($page > 1): ?>
                    <a href="/finance?<?php echo $baseQuery; ?>page=<?php echo $page - 1; ?>" class="px-3 py-1 rounded-lg border border-slate-700 hover:bg-slate-800">Anterior</a>
                <?php endif; ?>
                <?php if ($page < $total_pages): ?>
                    <a href="/finance?<?php echo $baseQuery; ?>page=<?php echo $page + 1; ?>" class="px-3 py-1 rounded-lg border border-slate-700 hover:bg-slate-800">Proxima</a>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>
