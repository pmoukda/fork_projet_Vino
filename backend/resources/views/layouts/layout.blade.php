<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Vins</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.3/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../.././css/app.css">
</head>
<body class="bg-gray-100">
    <header class="p-4 bg-white shadow">
        <h1 class="text-xl font-bold">Mon cher cellier 🍇</h1>
    </header>

    <main class="p-4">
        @yield('content')
    </main>
</body>
</html>