<div class="space-y-6">
    <section>
        <h2 class="text-xl font-semibold">Novo investimento</h2>
    </section>
    <form method="post" action="/investments" class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 space-y-4">
        <input type="hidden" name="csrf_token" value="<?php echo e(Csrf::token()); ?>">
        <div>
            <label class="text-xs text-slate-400">Nome</label>
            <input type="text" name="name" required class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
        </div>
        <div>
            <label class="text-xs text-slate-400">Descricao</label>
            <textarea name="description" class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2"></textarea>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-xs text-slate-400">Valor</label>
                <input type="text" name="value" class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="text-xs text-slate-400">Prioridade</label>
                <select name="priority" class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
                    <option value="ALTA">Alta</option>
                    <option value="MEDIA" selected>Media</option>
                    <option value="BAIXA">Baixa</option>
                </select>
            </div>
            <div>
                <label class="text-xs text-slate-400">Data limite</label>
                <input type="date" name="due_date" value="<?php echo date('Y-m-d'); ?>" class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="text-xs text-slate-400">Status</label>
                <select name="status" class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
                    <option value="PENDENTE">Pendente</option>
                    <option value="EM_ANDAMENTO">Em andamento</option>
                    <option value="CONCLUIDO">Concluido</option>
                </select>
            </div>
        </div>
        <div class="flex justify-end">
            <button class="bg-dz7-500 hover:bg-dz7-600 text-white px-6 py-2 rounded-lg">Salvar</button>
        </div>
    </form>
</div>
