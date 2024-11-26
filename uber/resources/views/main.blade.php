<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uber</title>
    <link rel="stylesheet" href="{{ asset('style/app.css') }}">
</head>
<body>
    <header id="header" class="bg-black text-white py-4 fixed top-0 left-0 w-full z-50">
        <div class="container mx-auto flex items-center justify-between">
            <a href="#" class="text-2xl font-bold">Uber</a>
            <nav>
                <ul class ="flex space-x-4">
                    <li><a href="#" class="hover:text-gray-400">Ride</a></li>
                    <li><a href="#" class="hover:text-gray-400">Drive</a></li>
                    <li><a href="#" class="hover:text-gray-400">Eat</a></li>
                    <li><a href="#" class="hover:text-gray-400">Help</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <section class="hero bg-cover bg-center h-screen flex items-center justify-center text-center text-white">
            <div>
                <h1 class="text-4xl font-bold mb-4">Ride with Uber</h1>
                <p class="text-lg mb-8">Get a ride in minutes with the Uber app.</p>
                <a href="#" class="bg-indigo-500 hover:bg-indigo-600 text-white py-3 px-6 rounded-full">Order Now</a>
            </div>
        </section>

        <section class="bg-gray-100 py-12">
            <div class="container mx-auto">
                <h2 class="text-3xl font-bold mb-8">Why Uber?</h2>
                <div class="grid grid-cols-3 gap-8">
                    <div>
                        <i class="fas fa-clock text-indigo-500 text-3xl mb-4"></i>
                        <h3 class="text-xl font-bold mb-2">Fast and Convenient</h3>
                        <p>Get a ride in minutes with the Uber app.</p>
                    </div>
                    <div>
                        <i class="fas fa-car text-indigo-500 text-3xl mb-4"></i>
                        <h3 class="text-xl font-bold mb-2">Reliable Transportation</h3>
                        <p>Ride with confidence in a safe and reliable vehicle.</p>
                    </div>
                    <div>
                        <i class="fas fa-wallet text-indigo-500 text-3xl mb-4"></i>
                        <h3 class="text-xl font-bold mb-2">Affordable Prices</h3>
                        <p>Enjoy competitive pricing with no hidden fees.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-gray-800 text-white py-8">
        <div class="container mx-auto flex justify-between items-center">
            <p>&copy; 2023 Uber. All rights reserved.</p>
            <nav>
                <ul class="flex space-x-4">
                    <li><a href="#" class="hover:text-gray-400">Privacy</a></li>
                    <li><a href="#" class="hover:text-gray-400">Terms</a></li>
                    <li><a href="#" class="hover:text-gray-400">Careers</a></li>
                    <li><a href="#" class="hover:text-gray-400">Blog</a></li>
                </ul>
            </nav>
        </div>
    </footer>
</body>
</html>