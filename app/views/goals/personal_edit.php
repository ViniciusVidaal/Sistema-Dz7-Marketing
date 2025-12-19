<?php
$goalTypes = ['DAILY' => 'Diaria', 'MONTHLY' => 'Mensal', 'YEARLY' => 'Anual', 'CUSTOM' => 'Custom'];
?>
<div class="space-y-6">
    <section>
        <h2 class="text-xl font-semibold">Editar meta pessoal</h2>
    </section>
    <form method="post" action="/goals/personal/<?php echo $goal['id']; ?>/update" class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 space-y-4">
        <input type="hidden" name="csrf_token" value="<?php echo e(Csrf::token()); ?>">
        <div>
            <label class="text-xs text-slate-400">Nome</label>
            <input type="text" name="name" value="<?php echo e($goal['name']); ?>" class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-xs text-slate-400">Tipo</label>
                <select name="type" class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
                    <?php foreach ($goalTypes as $value => $label): ?>
                        <option value="<?php echo $value; ?>" <?php echo $goal['type'] === $value ? 'selected' : ''; ?>><?php echo $label; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="text-xs text-slate-400">Meta</label>
                <input type="text" name="target_value" value="<?php echo e($goal['target_value']); ?>" class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="text-xs text-slate-400">Atual</label>
                <input type="text" name="current_value" value="<?php echo e($goal['current_value']); ?>" class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="text-xs text-slate-400">Inicio</label>
                <input type="date" name="start_date" value="<?php echo e($goal['start_date']); ?>" class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="text-xs text-slate-400">Fim</label>
                <input type="date" name="end_date" value="<?php echo e($goal['end_date']); ?>" class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
            </div>
        </div>
        <div class="flex justify-between">
            <form method="post" action="/goals/personal/<?php echo $goal['id']; ?>/delete">
                <input type="hidden" name="csrf_token" value="<?php echo e(Csrf::token()); ?>">
                <button class="text-rose-300 hover:text-rose-100">Excluir</button>
            </form>
            <button class="bg-dz7-500 hover:bg-dz7-600 text-white px-6 py-2 rounded-lg">Salvar</button>
        </div>
    </form>
</div>
