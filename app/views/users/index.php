<div class="space-y-6">
    <section class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-semibold">Usuarios</h2>
            <p class="text-sm text-slate-400">Cadastro e controle de acessos.</p>
        </div>
        <a href="/users/create" class="bg-dz7-500 hover:bg-dz7-600 text-white px-4 py-2 rounded-lg text-sm">Novo usuario</a>
    </section>

    <section class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6">
        <h3 class="text-sm font-semibold mb-4">Pedidos de reset de senha</h3>
        <div class="space-y-3">
            <?php foreach ($reset_requests as $req): ?>
                <div class="flex items-center justify-between bg-slate-950 border border-slate-800 rounded-lg p-3">
                    <div>
                        <div class="text-sm font-semibold"><?php echo e($req['name']); ?></div>
                        <div class="text-xs text-slate-400"><?php echo e($req['email']); ?></div>
                    </div>
                    <form method="post" action="/users/<?php echo $req['user_id']; ?>/reset">
                        <input type="hidden" name="csrf_token" value="<?php echo e(Csrf::token()); ?>">
                        <button class="text-amber-300 hover:text-amber-100">Resetar senha</button>
                    </form>
                </div>
            <?php endforeach; ?>
            <?php if (empty($reset_requests)): ?>
                <div class="text-xs text-slate-500">Nenhuma solicitacao pendente.</div>
            <?php endif; ?>
        </div>
    </section>

    <section class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-xs text-slate-400">
                <tr>
                    <th class="text-left py-2">Nome</th>
                    <th class="text-left py-2">Email</th>
                    <th class="text-left py-2">Perfil</th>
                    <th class="text-left py-2">Cargo</th>
                    <th class="text-left py-2">Status</th>
                    <th class="text-right py-2">Acoes</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $item): ?>
                    <tr class="border-t border-slate-800">
                        <td class="py-2"><?php echo e($item['name']); ?></td>
                        <td class="py-2"><?php echo e($item['email']); ?></td>
                        <td class="py-2"><?php echo e($item['role']); ?></td>
                        <td class="py-2"><?php echo e($item['position']); ?></td>
                        <td class="py-2"><?php echo $item['active'] ? 'Ativo' : 'Inativo'; ?></td>
                        <td class="py-2 text-right">
                            <a href="/users/<?php echo $item['id']; ?>/edit" class="text-dz7-300 hover:text-dz7-100">Editar</a>
                            <form method="post" action="/users/<?php echo $item['id']; ?>/reset" class="inline ml-2">
                                <input type="hidden" name="csrf_token" value="<?php echo e(Csrf::token()); ?>">
                                <button class="text-amber-300 hover:text-amber-100">Reset senha</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($users)): ?>
                    <tr><td colspan="6" class="py-4 text-center text-xs text-slate-500">Nenhum usuario cadastrado.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
</div>
