<div class="space-y-6">
    <section>
        <h2 class="text-xl font-semibold">Nova ferramenta</h2>
    </section>
    <form method="post" action="/tools" class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 space-y-4">
        <input type="hidden" name="csrf_token" value="<?php echo e(Csrf::token()); ?>">
        <div>
            <label class="text-xs text-slate-400">Nome</label>
            <input type="text" name="name" required class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-xs text-slate-400">Valor</label>
                <input type="text" name="value" class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="text-xs text-slate-400">Ultimos 4 digitos</label>
                <input type="text" name="card_last4" class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="text-xs text-slate-400">Data de contratacao</label>
                <input type="date" name="start_date" value="<?php echo date('Y-m-d'); ?>" class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="text-xs text-slate-400">Proximo vencimento</label>
                <input type="date" name="next_due_date" value="<?php echo date('Y-m-d'); ?>" class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
            </div>
        </div>
        <div class="flex justify-end">
            <button class="bg-dz7-500 hover:bg-dz7-600 text-white px-6 py-2 rounded-lg">Salvar</button>
        </div>
    </form>
</div>
