<?php
$origins = ['META_ADS','GOOGLE_ADS','INDICACAO','PAP','TERCEIRIZADO','OUTROS'];
$services = ['GMN','LANDING_PAGE','SISTEMA','TRAFEGO_META','TRAFEGO_GOOGLE'];
?>
<div class="space-y-6">
    <section class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-semibold">Novo lead</h2>
            <p class="text-sm text-slate-400">Cadastro completo de lead/cliente.</p>
        </div>
    </section>

    <form method="post" action="/leads" class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 space-y-4">
        <input type="hidden" name="csrf_token" value="<?php echo e(Csrf::token()); ?>">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-xs text-slate-400">Nome</label>
                <input type="text" name="name" required class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="text-xs text-slate-400">Telefone</label>
                <input type="text" name="phone" class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="text-xs text-slate-400">Email</label>
                <input type="email" name="email" class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="text-xs text-slate-400">Contato</label>
                <input type="text" name="contact_name" class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="text-xs text-slate-400">Nome do negocio (GMN)</label>
                <input type="text" name="business_gmn" class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="text-xs text-slate-400">Cidade</label>
                <input type="text" name="city" class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="text-xs text-slate-400">Local do servico</label>
                <input type="text" name="service_location" class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="text-xs text-slate-400">Origem</label>
                <select name="origin" class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
                    <?php foreach ($origins as $origin): ?>
                        <option value="<?php echo $origin; ?>"><?php echo $origin; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="text-xs text-slate-400">Quem converteu</label>
                <select name="converted_by" class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
                    <?php foreach ($users as $user): ?>
                        <option value="<?php echo $user['id']; ?>"><?php echo e($user['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div>
            <label class="text-xs text-slate-400">Servicos</label>
            <div class="flex flex-wrap gap-3 mt-2">
                <?php foreach ($services as $service): ?>
                    <label class="flex items-center gap-2 text-xs">
                        <input type="checkbox" name="services[]" value="<?php echo $service; ?>" class="rounded border-slate-600 bg-slate-950">
                        <?php echo $service; ?>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4" data-recurring-wrapper>
            <div>
                <label class="text-xs text-slate-400">Tipo de pagamento</label>
                <select name="payment_type" data-payment-type class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
                    <option value="ONE_TIME">Unico</option>
                    <option value="RECURRING">Recorrente</option>
                </select>
            </div>
            <div>
                <label class="text-xs text-slate-400">Valor de entrada</label>
                <input type="text" name="entry_value" required class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
            </div>
            <div data-recurring-field>
                <label class="text-xs text-slate-400">Valor mensal</label>
                <input type="text" name="monthly_value" class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
            </div>
            <div data-recurring-field>
                <label class="text-xs text-slate-400">Meses</label>
                <input type="number" name="months" class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
            </div>
            <div data-recurring-field>
                <label class="text-xs text-slate-400">Dia de pagamento</label>
                <input type="number" name="pay_day" value="5" class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4" data-recurring-field>
            <div>
                <label class="text-xs text-slate-400">Inicio do contrato</label>
                <input type="date" name="contract_start" value="<?php echo date('Y-m-d'); ?>" class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="text-xs text-slate-400">Termino do contrato</label>
                <input type="date" name="contract_end" class="w-full mt-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
            </div>
        </div>

        <div class="flex justify-end">
            <button class="bg-dz7-500 hover:bg-dz7-600 text-white px-6 py-2 rounded-lg">Salvar</button>
        </div>
    </form>
</div>
