<div class="space-y-6">
    <section>
        <h2 class="text-xl font-semibold">Novo usuario</h2>
    </section>
    <form method="post" action="/users" class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 space-y-4">
        <input type="hidden" name="csrf_token" value="<?php echo e(Csrf::token()); ?>">
        <div>
            <label class="text-xs text-slate-400">Nome</label>
            <input type="text" name="name" required class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
        </div>
        <div>
            <label class="text-xs text-slate-400">Email</label>
            <input type="email" name="email" required class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
        </div>
        <div>
            <label class="text-xs text-slate-400">Cargo</label>
            <input type="text" name="position" class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-xs text-slate-400">Perfil</label>
                <select name="role" class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
                    <option value="ADMIN">Admin</option>
                    <option value="EMPLOYEE">Funcionario</option>
                </select>
            </div>
            <div>
                <label class="text-xs text-slate-400">Senha padrao</label>
                <input type="text" name="default_password" value="<?php echo e(env('DEFAULT_PASSWORD', 'ChangeMe123!')); ?>" class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
            </div>
        </div>
        <div class="flex justify-end">
            <button class="bg-dz7-500 hover:bg-dz7-600 text-white px-6 py-2 rounded-lg">Salvar</button>
        </div>
    </form>
</div>
