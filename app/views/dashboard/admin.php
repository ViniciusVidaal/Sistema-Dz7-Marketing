<?php
$hideSensitive = view_as_employee();
?>
<div class="space-y-6">
    <section class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6">
        <div class="flex flex-wrap gap-4 items-end justify-between">
            <div>
                <h2 class="text-xl font-semibold">Dashboard</h2>
                <p class="text-sm text-slate-400">Filtros financeiros com base em eventos.</p>
            </div>
            <form method="get" class="flex flex-wrap gap-2 items-end">
                <select name="preset" class="bg-slate-950 border border-slate-700 rounded-lg px-3 py-2 text-sm">
                    <option value="day" <?php echo $filter['preset'] === 'day' ? 'selected' : ''; ?>>Dia</option>
                    <option value="month" <?php echo $filter['preset'] === 'month' ? 'selected' : ''; ?>>Mes</option>
                    <option value="year" <?php echo $filter['preset'] === 'year' ? 'selected' : ''; ?>>Ano</option>
                    <option value="custom" <?php echo $filter['preset'] === 'custom' ? 'selected' : ''; ?>>Intervalo</option>
                </select>
                <input type="date" name="start" value="<?php echo e($filter['start']); ?>" class="bg-slate-950 border border-slate-700 rounded-lg px-3 py-2 text-sm">
                <input type="date" name="end" value="<?php echo e($filter['end']); ?>" class="bg-slate-950 border border-slate-700 rounded-lg px-3 py-2 text-sm">
                <button class="bg-dz7-500 hover:bg-dz7-600 text-white px-4 py-2 rounded-lg text-sm">Aplicar</button>
            </form>
        </div>
    </section>

    <section class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-5">
            <div class="text-xs text-slate-400">Faturamento no periodo</div>
            <div class="text-2xl font-semibold mt-2"><?php echo $hideSensitive ? '---' : format_money($revenue_total); ?></div>
        </div>
        <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-5">
            <div class="text-xs text-slate-400">Receita de entrada</div>
            <div class="text-2xl font-semibold mt-2"><?php echo $hideSensitive ? '---' : format_money($entry_revenue); ?></div>
        </div>
        <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-5">
            <div class="text-xs text-slate-400">Recorrencias ativas</div>
            <div class="text-2xl font-semibold mt-2"><?php echo $active_recurring; ?></div>
        </div>
        <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-5">
            <div class="text-xs text-slate-400">Saldo em caixa</div>
            <div class="text-2xl font-semibold mt-2"><?php echo $hideSensitive ? '---' : format_money($balance); ?></div>
        </div>
        <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-5">
            <div class="text-xs text-slate-400">Gastos trafego</div>
            <div class="text-2xl font-semibold mt-2"><?php echo $hideSensitive ? '---' : format_money($traffic_spend); ?></div>
        </div>
        <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-5">
            <div class="text-xs text-slate-400">Gastos diversos</div>
            <div class="text-2xl font-semibold mt-2"><?php echo $hideSensitive ? '---' : format_money($misc_spend); ?></div>
        </div>
        <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-5">
            <div class="text-xs text-slate-400">Meta principal</div>
            <div class="text-2xl font-semibold mt-2"><?php echo $settings['main_goal_name']; ?></div>
            <div class="text-xs text-slate-400 mt-2">Progresso: <?php echo $goal_progress; ?>%</div>
        </div>
        <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-5">
            <div class="text-xs text-slate-400">Receita recorrente mensal</div>
            <div class="text-2xl font-semibold mt-2"><?php echo $hideSensitive ? '---' : format_money($recurring_revenue); ?></div>
        </div>
    </section>

    <section class="grid grid-cols-1 xl:grid-cols-3 gap-4">
        <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 xl:col-span-2">
            <div class="text-sm text-slate-400 mb-4">Faturamento por mes</div>
            <canvas id="revenueChart" data-series='<?php echo json_encode($monthly_series); ?>'></canvas>
        </div>
        <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6">
            <div class="text-sm text-slate-400 mb-4">Gastos por plataforma</div>
            <canvas id="spendChart" data-meta="<?php echo e($meta_spend); ?>" data-google="<?php echo e($google_spend); ?>" data-misc="<?php echo e($misc_spend); ?>"></canvas>
        </div>
    </section>

    <section class="grid grid-cols-1 xl:grid-cols-2 gap-4">
        <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6">
            <div class="text-sm text-slate-400 mb-4">Reunioes mensais</div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <div class="text-xs text-slate-400">Meta reunioes</div>
                    <div class="text-lg font-semibold"><?php echo $meetings_goal; ?></div>
                </div>
                <div>
                    <div class="text-xs text-slate-400">Reunioes realizadas</div>
                    <div class="text-lg font-semibold"><?php echo $meetings_done; ?></div>
                </div>
                <div>
                    <div class="text-xs text-slate-400">Meta contratos</div>
                    <div class="text-lg font-semibold"><?php echo $deals_goal; ?></div>
                </div>
                <div>
                    <div class="text-xs text-slate-400">Contratos fechados</div>
                    <div class="text-lg font-semibold"><?php echo $deals_done; ?></div>
                </div>
            </div>
            <div class="mt-4 text-xs text-slate-400">Taxa de conversao: <?php echo $conversion_rate; ?>%</div>
        </div>
        <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6">
            <div class="text-sm text-slate-400 mb-4">Notificacoes recentes</div>
            <div class="space-y-3">
                <?php foreach (array_slice($notifications, 0, 5) as $note): ?>
                    <div class="bg-slate-950 border border-slate-800 rounded-lg p-3">
                        <div class="text-sm font-semibold"><?php echo e($note['title']); ?></div>
                        <div class="text-xs text-slate-400"><?php echo e($note['body']); ?></div>
                    </div>
                <?php endforeach; ?>
                <?php if (empty($notifications)): ?>
                    <div class="text-xs text-slate-500">Sem notificacoes.</div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>
