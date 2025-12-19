<div class="space-y-6">
    <section>
        <h2 class="text-xl font-semibold">Detalhe da aprovacao</h2>
        <p class="text-sm text-slate-400">Comparativo antes e depois.</p>
    </section>

    <section class="grid grid-cols-1 xl:grid-cols-2 gap-4">
        <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6">
            <h3 class="text-sm font-semibold mb-4">Antes</h3>
            <pre class="text-xs text-slate-300 whitespace-pre-wrap"><?php echo e(json_encode($before, JSON_PRETTY_PRINT)); ?></pre>
        </div>
        <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6">
            <h3 class="text-sm font-semibold mb-4">Depois</h3>
            <pre class="text-xs text-slate-300 whitespace-pre-wrap"><?php echo e(json_encode($after, JSON_PRETTY_PRINT)); ?></pre>
        </div>
    </section>

    <section class="flex gap-3">
        <form method="post" action="/approvals/<?php echo $approval['id']; ?>/approve">
            <input type="hidden" name="csrf_token" value="<?php echo e(Csrf::token()); ?>">
            <button class="bg-emerald-500 hover:bg-emerald-600 text-white px-6 py-2 rounded-lg">Aprovar</button>
        </form>
        <form method="post" action="/approvals/<?php echo $approval['id']; ?>/reject">
            <input type="hidden" name="csrf_token" value="<?php echo e(Csrf::token()); ?>">
            <button class="bg-rose-500 hover:bg-rose-600 text-white px-6 py-2 rounded-lg">Rejeitar</button>
        </form>
    </section>
</div>
