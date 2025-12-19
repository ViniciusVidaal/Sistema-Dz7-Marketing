<?php
$canManage = is_admin();
?>
<div class="space-y-6">
    <section>
        <h2 class="text-xl font-semibold">Metas da Empresa</h2>
        <p class="text-sm text-slate-400">Metas corporativas e meta principal do dashboard.</p>
    </section>

    <?php if ($canManage): ?>
        <section class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6">
            <h3 class="text-sm font-semibold mb-4">Meta principal e reunioes</h3>
            <form method="post" action="/goals/settings" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <input type="hidden" name="csrf_token" value="<?php echo e(Csrf::token()); ?>">
                <div>
                    <label class="text-xs text-slate-400">Nome da meta principal</label>
                    <input type="text" name="main_goal_name" value="<?php echo e($settings['main_goal_name']); ?>" class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="text-xs text-slate-400">Valor da meta (R$)</label>
                    <input type="text" name="main_goal_value" value="<?php echo e($settings['main_goal_value']); ?>" class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="text-xs text-slate-400">Meta reunioes</label>
                    <input type="number" name="meetings_goal" value="<?php echo e($settings['meetings_goal']); ?>" class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="text-xs text-slate-400">Reunioes realizadas</label>
                    <input type="number" name="meetings_done" value="<?php echo e($settings['meetings_done']); ?>" class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="text-xs text-slate-400">Meta contratos</label>
                    <input type="number" name="deals_goal" value="<?php echo e($settings['deals_goal']); ?>" class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="text-xs text-slate-400">Contratos fechados</label>
                    <input type="number" name="deals_done" value="<?php echo e($settings['deals_done']); ?>" class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
                </div>
                <div class="md:col-span-3 flex justify-end">
                    <button class="bg-dz7-500 hover:bg-dz7-600 text-white px-6 py-2 rounded-lg">Salvar</button>
                </div>
            </form>
        </section>

        <section class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6">
            <h3 class="text-sm font-semibold mb-4">Nova meta da empresa</h3>
            <form method="post" action="/goals/company" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <input type="hidden" name="csrf_token" value="<?php echo e(Csrf::token()); ?>">
                <div>
                    <label class="text-xs text-slate-400">Nome</label>
                    <input type="text" name="name" class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="text-xs text-slate-400">Valor alvo</label>
                    <input type="text" name="target_value" class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="text-xs text-slate-400">Valor atual</label>
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
    <?php endif; ?>

    <section class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 overflow-x-auto">
        <h3 class="text-sm font-semibold mb-4">Metas cadastradas</h3>
        <table class="w-full text-sm">
            <thead class="text-xs text-slate-400">
                <tr>
                    <th class="text-left py-2">Nome</th>
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
                        <td class="py-2"><?php echo format_money($goal['target_value']); ?></td>
                        <td class="py-2"><?php echo format_money($goal['current_value']); ?></td>
                        <td class="py-2"><?php echo e($goal['start_date']); ?></td>
                        <td class="py-2"><?php echo e($goal['end_date']); ?></td>
                        <td class="py-2 text-right">
                            <a href="/goals/company/<?php echo $goal['id']; ?>/edit" class="text-dz7-300 hover:text-dz7-100">Solicitar/Editar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($goals)): ?>
                    <tr><td colspan="6" class="py-4 text-center text-xs text-slate-500">Nenhuma meta cadastrada.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
</div>
