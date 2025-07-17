<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Slipstream - Customers</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-100 flex h-screen overflow-hidden">
    <aside class="w-64 bg-white border-r border-gray-200 h-full overflow-y-auto">
        <div class="p-4">
            <h1 class="text-blue-600 font-bold text-xl">slipstream</h1>
            <p class="text-gray-600 text-sm">TMS Tenant</p>
        </div>
        <nav class="mt-4">
            <ul>
                <li class="p-4 font-semibold text-gray-600">Demo Menu</li>
                <li>
                    <a href="/customers" class="block p-4 text-blue-600 bg-blue-50 font-semibold">Customers</a>
                </li>
            </ul>
        </nav>
    </aside>

    <main class="flex-1 overflow-y-auto p-6">
        <div id="app">
            <customer-table :initial-customers="{{ json_encode($customers) }}" :categories="{{ json_encode($categories) }}"></customer-table>
        </div>
    </main>
</body>
</html>