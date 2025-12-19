<div class="min-h-screen flex items-center justify-center px-6 py-12">
    <div class="w-full max-w-md bg-slate-900/80 border border-slate-700 rounded-2xl p-8 shadow-xl">
        <h1 class="text-2xl font-semibold mb-2">Esqueci minha senha</h1>
        <p class="text-sm text-slate-400 mb-6">Informe seu email para notificar um admin.</p>
        <form method="post" action="/forgot" class="space-y-4">
            <input type="hidden" name="csrf_token" value="<?php echo e(Csrf::token()); ?>">
            <div>
                <label class="text-xs text-slate-400">Email</label>
                <input type="email" name="email" required class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
            </div>
            <button class="w-full bg-dz7-500 hover:bg-dz7-600 text-white py-2 rounded-lg">Solicitar reset</button>
        </form>
        <div class="mt-4 text-sm text-slate-400">
            <a href="/login" class="hover:text-white">Voltar ao login</a>
        </div>
    </div>
</div>
