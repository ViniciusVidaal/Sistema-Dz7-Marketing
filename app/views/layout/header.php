<?php $user = current_user(); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dz7 Manager</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        display: ['Space Grotesk', 'sans-serif']
                    },
                    colors: {
                        dz7: {
                            50: '#f3f7ff',
                            100: '#e0ecff',
                            200: '#b9d1ff',
                            300: '#8ab0ff',
                            400: '#5d8eff',
                            500: '#2d6bff',
                            600: '#1f52d6',
                            700: '#1b43ad',
                            800: '#173685',
                            900: '#10265b'
                        }
                    }
                }
            }
        };
    </script>
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body class="font-display bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 text-slate-100 min-h-screen">
<div class="min-h-screen">
    <?php if (!empty($_SESSION['flash_error'])): ?>
        <div class="bg-red-500 text-white px-4 py-3 text-sm">
            <?php echo e($_SESSION['flash_error']); unset($_SESSION['flash_error']); ?>
        </div>
    <?php endif; ?>
    <?php if (!empty($_SESSION['flash_success'])): ?>
        <div class="bg-emerald-500 text-white px-4 py-3 text-sm">
            <?php echo e($_SESSION['flash_success']); unset($_SESSION['flash_success']); ?>
        </div>
    <?php endif; ?>
