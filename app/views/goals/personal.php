<?php
$goalTypes = ['DAILY' => 'Diaria', 'MONTHLY' => 'Mensal', 'YEARLY' => 'Anual', 'CUSTOM' => 'Custom'];
?>
<div class="space-y-6">
    <section class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold">Minhas metas</h2>
            <p class="text-sm text-slate-400">Controle individual de metas.</p>
        </div>
        <?php if (is_admin()): ?>
            <form method="get" action="/goals/personal" class="flex items-center gap-2">
                <select name="user_id" class="bg-slate-950 border border-slate-700 rounded-lg px-3 py-2 text-sm">
                    <?php foreach ($users as $user): ?>
                        <option value="<?php echo $user['id']; ?>" <?php echo (int)$selected_user_id === (int)$user['id'] ? 'selected' : ''; ?>><?php echo e($user['name']); ?></option>
                    <?php endforeach; ?>
                </select>
                <button class="bg-dz7-500 hover:bg-dz7-600 text-white px-4 py-2 rounded-lg text-sm">Ver</button>
            </form>
        <?php endif; ?>
    </section>

    <section class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6">
        <h3 class="text-sm font-semibold mb-4">Nova meta pessoal</h3>
        <form method="post" action="/goals/personal" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <input type="hidden" name="csrf_token" value="<?php echo e(Csrf::token()); ?>">
            <div>
                <label class="text-xs text-slate-400">Nome</label>
                <input type="text" name="name" class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="text-xs text-slate-400">Tipo</label>
                <select name="type" class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
                    <?php foreach ($goalTypes as $value => $label): ?>
                        <option value="<?php echo $value; ?>"><?php echo $label; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="text-xs text-slate-400">Meta</label>
                <input type="text" name="target_value" class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="text-xs text-slate-400">Atual</label>
                <input type="text" name="current_value" class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="text-xs text-slate-400">Inicio</label>
                <input type="date" name="start_date" value="<?php echo date('Y-m-d'); ?>" class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="text-xs text-slate-400">Fim</label>
                <input type="date" name="end_date" class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
            </div>
            <div class="md:col-span-3 flex justify-end">
                <button class="bg-dz7-500 hover:bg-dz7-600 text-white px-6 py-2 rounded-lg">Criar</button>
            </div>
        </form>
    </section>

    <section class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-xs text-slate-400">
                <tr>
                    <th class="text-left py-2">Nome</th>
                    <th class="text-left py-2">Tipo</th>
                    <th class="text-left py-2">Meta</th>
                    <th class="text-left py-2">Atual</th>
                    <th class="text-left py-2">Inicio</th>
                    <th class="text-left py-2">Fim</th>
                    <th class="text-right py-2">Acoes</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($goals as $goal): ?>
                    <tr class="border-t border-slate-800">
                        <td class="py-2"><?php echo e($goal['name']); ?></td>
                        <td class="py-2"><?php echo e($goal['type']); ?></td>
                        <td class="py-2"><?php echo format_money($goal['target_value']); ?></td>
                        <td class="py-2"><?php echo format_money($goal['current_value']); ?></td>
                        <td class="py-2"><?php echo e($goal['start_date']); ?></td>
                        <td class="py-2"><?php echo e($goal['end_date']); ?></td>
                        <td class="py-2 text-right">
                            <a href="/goals/personal/<?php echo $goal['id']; ?>/edit" class="text-dz7-300 hover:text-dz7-100">Editar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($goals)): ?>
                    <tr><td colspan="7" class="py-4 text-center text-xs text-slate-500">Nenhuma meta pessoal cadastrada.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
</div>
