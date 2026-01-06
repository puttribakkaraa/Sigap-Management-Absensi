<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Sigap</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md">
        <div class="text-center mb-8">
            <img src="{{ asset('images/logomtmfix.png') }}" alt="Logo" class="h-16 mx-auto mb-4">
            <h2 class="text-2xl font-bold text-gray-800">Admin Login</h2>
            <p class="text-gray-500 text-sm">Silakan masuk ke sistem absensi</p>
        </div>

        @if($errors->any())
            <div class="bg-red-100 text-red-600 p-3 rounded-lg mb-4 text-sm font-medium">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="/login" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Email Address</label>
                <input type="email" name="email" class="w-full border border-gray-300 px-4 py-3 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none" required>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <input type="password" name="password" class="w-full border border-gray-300 px-4 py-3 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none" required>
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl shadow-lg transition duration-300">
                MASUK SEKARANG
            </button>
        </form>
    </div>
</body>
</html>