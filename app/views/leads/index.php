<?php
$origins = ['META_ADS','GOOGLE_ADS','INDICACAO','PAP','TERCEIRIZADO','OUTROS'];
$services = ['GMN','LANDING_PAGE','SISTEMA','TRAFEGO_META','TRAFEGO_GOOGLE'];
?>
<div class="space-y-6">
    <section class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold">Leads e Clientes</h2>
            <p class="text-sm text-slate-400">Cadastro e acompanhamento comercial.</p>
        </div>
        <a href="/leads/create" class="bg-dz7-500 hover:bg-dz7-600 text-white px-4 py-2 rounded-lg text-sm">Novo lead</a>
    </section>

    <section class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6">
        <form method="get" class="flex flex-wrap gap-2 items-end">
            <input type="text" name="search" value="<?php echo e($filters['search']); ?>" placeholder="Buscar por nome ou GMN" class="bg-slate-950 border border-slate-700 rounded-lg px-3 py-2 text-sm">
            <select name="origin" class="bg-slate-950 border border-slate-700 rounded-lg px-3 py-2 text-sm">
                <option value="">Origem</option>
                <?php foreach ($origins as $origin): ?>
                    <option value="<?php echo $origin; ?>" <?php echo $filters['origin'] === $origin ? 'selected' : ''; ?>><?php echo $origin; ?></option>
                <?php endforeach; ?>
            </select>
            <select name="payment_type" class="bg-slate-950 border border-slate-700 rounded-lg px-3 py-2 text-sm">
                <option value="">Pagamento</option>
                <option value="ONE_TIME" <?php echo $filters['payment_type'] === 'ONE_TIME' ? 'selected' : ''; ?>>Unico</option>
                <option value="RECURRING" <?php echo $filters['payment_type'] === 'RECURRING' ? 'selected' : ''; ?>>Recorrente</option>
            </select>
            <select name="service" class="bg-slate-950 border border-slate-700 rounded-lg px-3 py-2 text-sm">
                <option value="">Servico</option>
                <?php foreach ($services as $service): ?>
                    <option value="<?php echo $service; ?>" <?php echo $filters['service'] === $service ? 'selected' : ''; ?>><?php echo $service; ?></option>
                <?php endforeach; ?>
            </select>
            <button class="bg-dz7-500 hover:bg-dz7-600 text-white px-4 py-2 rounded-lg text-sm">Filtrar</button>
        </form>
        <div class="mt-4 overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-xs text-slate-400">
                    <tr>
                        <th class="text-left py-2">Cliente</th>
                        <th class="text-left py-2">GMN</th>
                        <th class="text-left py-2">Origem</th>
                        <th class="text-left py-2">Pagamento</th>
                        <th class="text-right py-2">Entrada</th>
                        <th class="text-right py-2">Acoes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($leads as $lead): ?>
                        <tr class="border-t border-slate-800">
                            <td class="py-2"><?php echo e($lead['name']); ?></td>
                            <td class="py-2"><?php echo e($lead['business_gmn']); ?></td>
                            <td class="py-2"><?php echo e($lead['origin']); ?></td>
                            <td class="py-2"><?php echo e($lead['payment_type']); ?></td>
                            <td class="py-2 text-right"><?php echo format_money($lead['entry_value']); ?></td>
                            <td class="py-2 text-right">
                                <a href="/leads/<?php echo $lead['id']; ?>/edit" class="text-dz7-300 hover:text-dz7-100">Editar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($leads)): ?>
                        <tr><td colspan="6" class="py-4 text-center text-xs text-slate-500">Nenhum lead encontrado.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php
            $query = $_GET;
            unset($query['page']);
            $baseQuery = http_build_query($query);
            $baseQuery = $baseQuery ? $baseQuery . '&' : '';
        ?>
        <div class="mt-4 flex items-center justify-between text-sm">
            <div class="text-xs text-slate-500">Pagina <?php echo $page; ?> de <?php echo $total_pages; ?></div>
            <div class="flex gap-2">
                <?php if ($page > 1): ?>
                    <a href="/leads?<?php echo $baseQuery; ?>page=<?php echo $page - 1; ?>" class="px-3 py-1 rounded-lg border border-slate-700 hover:bg-slate-800">Anterior</a>
                <?php endif; ?>
                <?php if ($page < $total_pages): ?>
                    <a href="/leads?<?php echo $baseQuery; ?>page=<?php echo $page + 1; ?>" class="px-3 py-1 rounded-lg border border-slate-700 hover:bg-slate-800">Proxima</a>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>
