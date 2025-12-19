# Dz7 Manager

Sistema web em PHP puro para Dz7 Marketing.

## Requisitos
- PHP 7.4+
- MySQL 5.7+

## Instalacao (HostGator)
1) Envie os arquivos para `public_html` (ou aponte o docroot para `/public`).
2) Crie o banco no cPanel.
3) Importe `database/schema.sql`.
4) Importe `database/seed.sql`.
5) Copie `.env.example` para `.env` e ajuste:
   - `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`
   - `APP_URL`
   - `DEFAULT_PASSWORD`
   - `CRON_TOKEN`
6) Acesse `/login` no navegador.

Login inicial:
- Email: `admin@dz7marketing.com`
- Senha: `ChangeMe123!`

No primeiro acesso o usuario sera obrigado a trocar a senha.
Se quiser outro padrao, ajuste o `database/seed.sql` antes de importar.

## Cron (notificacoes)
Configure no cPanel uma tarefa cron (diaria ou horaria) chamando:

```
https://seu-dominio.com/cron/run.php?token=SEU_TOKEN
```

O cron gera notificacoes de vencimento de ferramentas, recorrencias e contratos em atraso.

## Observacoes
- O seed usa SHA2 para a senha inicial. No primeiro login o sistema converte automaticamente para bcrypt.
- Eventos financeiros sao a fonte da verdade para saldo e faturamento.
