<?php
$canManage = is_admin();
?>
<div class="space-y-6">
    <section class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-semibold">Ferramentas</h2>
            <p class="text-sm text-slate-400">Controle de assinaturas e vencimentos.</p>
        </div>
        <?php if ($canManage): ?>
            <a href="/tools/create" class="bg-dz7-500 hover:bg-dz7-600 text-white px-4 py-2 rounded-lg text-sm">Nova ferramenta</a>
        <?php endif; ?>
    </section>

    <section class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-xs text-slate-400">
                <tr>
                    <th class="text-left py-2">Nome</th>
                    <th class="text-left py-2">Valor</th>
                    <th class="text-left py-2">Contratacao</th>
                    <th class="text-left py-2">Vencimento</th>
                    <th class="text-left py-2">Cartao</th>
                    <?php if ($canManage): ?>
                        <th class="text-right py-2">Acoes</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tools as $tool): ?>
                    <tr class="border-t border-slate-800">
                        <td class="py-2"><?php echo e($tool['name']); ?></td>
                        <td class="py-2"><?php echo format_money($tool['value']); ?></td>
                        <td class="py-2"><?php echo e($tool['start_date']); ?></td>
                        <td class="py-2"><?php echo e($tool['next_due_date']); ?></td>
                        <td class="py-2"><?php echo e($tool['card_last4']); ?></td>
                        <?php if ($canManage): ?>
                            <td class="py-2 text-right">
                                <a href="/tools/<?php echo $tool['id']; ?>/edit" class="text-dz7-300 hover:text-dz7-100">Editar</a>
                                <form method="post" action="/tools/<?php echo $tool['id']; ?>/delete" class="inline ml-2">
                                    <input type="hidden" name="csrf_token" value="<?php echo e(Csrf::token()); ?>">
                                    <button class="text-rose-300 hover:text-rose-100">Excluir</button>
                                </form>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($tools)): ?>
                    <tr><td colspan="6" class="py-4 text-center text-xs text-slate-500">Nenhuma ferramenta cadastrada.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
</div>
