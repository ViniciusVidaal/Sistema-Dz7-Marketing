<div class="space-y-6">
    <section>
        <h2 class="text-xl font-semibold">Editar usuario</h2>
    </section>
    <form method="post" action="/users/<?php echo $user['id']; ?>/update" class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 space-y-4">
        <input type="hidden" name="csrf_token" value="<?php echo e(Csrf::token()); ?>">
        <div>
            <label class="text-xs text-slate-400">Nome</label>
            <input type="text" name="name" value="<?php echo e($user['name']); ?>" class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
        </div>
        <div>
            <label class="text-xs text-slate-400">Email</label>
            <input type="email" name="email" value="<?php echo e($user['email']); ?>" class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
        </div>
        <div>
            <label class="text-xs text-slate-400">Cargo</label>
            <input type="text" name="position" value="<?php echo e($user['position']); ?>" class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-xs text-slate-400">Perfil</label>
                <select name="role" class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
                    <option value="ADMIN" <?php echo $user['role'] === 'ADMIN' ? 'selected' : ''; ?>>Admin</option>
                    <option value="EMPLOYEE" <?php echo $user['role'] === 'EMPLOYEE' ? 'selected' : ''; ?>>Funcionario</option>
                </select>
            </div>
            <div>
                <label class="text-xs text-slate-400">Status</label>
                <select name="active" class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
                    <option value="1" <?php echo (int)$user['active'] === 1 ? 'selected' : ''; ?>>Ativo</option>
                    <option value="0" <?php echo (int)$user['active'] === 0 ? 'selected' : ''; ?>>Inativo</option>
                </select>
            </div>
        </div>
        <div class="flex justify-end">
            <button class="bg-dz7-500 hover:bg-dz7-600 text-white px-6 py-2 rounded-lg">Salvar</button>
        </div>
    </form>
</div>
