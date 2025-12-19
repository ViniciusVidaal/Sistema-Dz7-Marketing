<div class="space-y-6">
    <section>
        <h2 class="text-xl font-semibold">Alterar senha</h2>
        <p class="text-sm text-slate-400">Senha obrigatoria no primeiro acesso.</p>
    </section>
    <form method="post" action="/profile/password" class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 space-y-4 max-w-lg">
        <input type="hidden" name="csrf_token" value="<?php echo e(Csrf::token()); ?>">
        <div>
            <label class="text-xs text-slate-400">Senha atual</label>
            <input type="password" name="current_password" required class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
        </div>
        <div>
            <label class="text-xs text-slate-400">Nova senha</label>
            <input type="password" name="new_password" required class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
        </div>
        <div>
            <label class="text-xs text-slate-400">Confirmar nova senha</label>
            <input type="password" name="confirm_password" required class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
        </div>
        <div class="flex justify-end">
            <button class="bg-dz7-500 hover:bg-dz7-600 text-white px-6 py-2 rounded-lg">Atualizar senha</button>
        </div>
    </form>
</div>
