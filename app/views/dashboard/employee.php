<div class="space-y-6">
    <section class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6">
        <h2 class="text-xl font-semibold">Dashboard</h2>
        <p class="text-sm text-slate-400">Visao rapida do time comercial.</p>
    </section>

    <section class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-5">
            <div class="text-xs text-slate-400">Recorrencias ativas</div>
            <div class="text-2xl font-semibold mt-2"><?php echo $active_recurring; ?></div>
        </div>
        <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-5">
            <div class="text-xs text-slate-400">Meta principal</div>
            <div class="text-2xl font-semibold mt-2"><?php echo e($settings['main_goal_name']); ?></div>
            <div class="text-xs text-slate-400 mt-2">Progresso: <?php echo $goal_progress; ?>%</div>
        </div>
        <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-5">
            <div class="text-xs text-slate-400">Reunioes realizadas</div>
            <div class="text-2xl font-semibold mt-2"><?php echo $meetings_done; ?></div>
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
