<div class="space-y-6">
    <section>
        <h2 class="text-xl font-semibold">Notificacoes</h2>
        <p class="text-sm text-slate-400">Avisos importantes do sistema.</p>
    </section>

    <section class="space-y-3">
        <?php foreach ($notifications as $note): ?>
            <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-4 flex items-start justify-between">
                <div>
                    <div class="text-sm font-semibold"><?php echo e($note['title']); ?></div>
                    <div class="text-xs text-slate-400 mt-1"><?php echo e($note['body']); ?></div>
                    <div class="text-xs text-slate-500 mt-2"><?php echo e($note['created_at']); ?></div>
                </div>
                <form method="post" action="/notifications/<?php echo $note['id']; ?>/read">
                    <input type="hidden" name="csrf_token" value="<?php echo e(Csrf::token()); ?>">
                    <button class="text-dz7-300 hover:text-dz7-100 text-xs">Marcar lida</button>
                </form>
            </div>
        <?php endforeach; ?>
        <?php if (empty($notifications)): ?>
            <div class="text-xs text-slate-500">Sem notificacoes.</div>
        <?php endif; ?>
    </section>
</div>
