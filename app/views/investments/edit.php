<div class="space-y-6">
    <section>
        <h2 class="text-xl font-semibold">Editar investimento</h2>
    </section>
    <form method="post" action="/investments/<?php echo $investment['id']; ?>/update" class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 space-y-4">
        <input type="hidden" name="csrf_token" value="<?php echo e(Csrf::token()); ?>">
        <div>
            <label class="text-xs text-slate-400">Nome</label>
            <input type="text" name="name" value="<?php echo e($investment['name']); ?>" required class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
        </div>
        <div>
            <label class="text-xs text-slate-400">Descricao</label>
            <textarea name="description" class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2"><?php echo e($investment['description']); ?></textarea>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-xs text-slate-400">Valor</label>
                <input type="text" name="value" value="<?php echo e($investment['value']); ?>" class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="text-xs text-slate-400">Prioridade</label>
                <select name="priority" class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
                    <option value="ALTA" <?php echo $investment['priority'] === 'ALTA' ? 'selected' : ''; ?>>Alta</option>
                    <option value="MEDIA" <?php echo $investment['priority'] === 'MEDIA' ? 'selected' : ''; ?>>Media</option>
                    <option value="BAIXA" <?php echo $investment['priority'] === 'BAIXA' ? 'selected' : ''; ?>>Baixa</option>
                </select>
            </div>
            <div>
                <label class="text-xs text-slate-400">Data limite</label>
                <input type="date" name="due_date" value="<?php echo e($investment['due_date']); ?>" class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="text-xs text-slate-400">Status</label>
                <select name="status" class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
                    <option value="PENDENTE" <?php echo $investment['status'] === 'PENDENTE' ? 'selected' : ''; ?>>Pendente</option>
                    <option value="EM_ANDAMENTO" <?php echo $investment['status'] === 'EM_ANDAMENTO' ? 'selected' : ''; ?>>Em andamento</option>
                    <option value="CONCLUIDO" <?php echo $investment['status'] === 'CONCLUIDO' ? 'selected' : ''; ?>>Concluido</option>
                </select>
            </div>
        </div>
        <div class="flex justify-end">
            <button class="bg-dz7-500 hover:bg-dz7-600 text-white px-6 py-2 rounded-lg">Salvar</button>
        </div>
    </form>
</div>
