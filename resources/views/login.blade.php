<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Laravel') }} - Login</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('logo.jpg') }}" type="image/x-icon">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    },
                    colors: {
                        primary: {
    50:  '#ecfdf5',
    100: '#d1fae5',
    200: '#a7f3d0',
    300: '#6ee7b7',
    400: '#34d399',
    500: '#10b981',
    600: '#059669',
    700: '#047857',
    800: '#065f46',
    900: '#064e3b',
    950: '#022c22',
},

                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-out',
                        'slide-up': 'slideUp 0.5s ease-out',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(20px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        },
                    },
                    boxShadow: {
                        'amber': '0 4px 14px 0 rgba(16, 185, 129, 0.39)', // pakai green-500 dari Tailwind (10b981)
                    }
                },
            },
        }
    </script>
    
    <style type="text/css">
        /* Custom styles that go beyond Tailwind */
        .bg-image {
            background-image: url('{{ asset('bg.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
            width: 100%;
        }
        
        /* Fallback background gradient if image fails to load */
        .bg-gradient-fallback {
            background: linear-gradient(135deg, #92400e 0%, #fbbf24 100%);
        }
        
        /* Custom checkbox styling */
        .custom-checkbox:checked {
            background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3cpath d='M12.207 4.793a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-2-2a1 1 0 011.414-1.414L6.5 9.086l4.293-4.293a1 1 0 011.414 0z'/%3e%3c/svg%3e");
        }
        
        /* Remove autofill background color */
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus,
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 30px white inset !important;
        }
        
        /* Glass effect */
        .glass-effect {
            backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.7);
        }
        
        /* Animated gradient border */
        .gradient-border {
            position: relative;
            border-radius: 1rem;
        }
        
        .gradient-border::before {
            content: "";
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, #f59e0b, #fbbf24, #f59e0b, #d97706);
            background-size: 400% 400%;
            z-index: -1;
            border-radius: 1.1rem;
            animation: gradient 15s ease infinite;
        }
        
        @keyframes gradient {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }
        
        /* Floating animation */
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        
        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
    </style>
</head>
<body class="antialiased font-sans">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-fallback" 
         style="background-image: url('{{ asset('bg.jpg') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        
        <div class="absolute inset-0 bg-black opacity-60"></div>
        
        <!-- Decorative elements -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0">
            <div class="absolute top-1/4 left-1/4 w-32 h-32 bg-primary-500 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse-slow"></div>
            <div class="absolute bottom-1/3 right-1/3 w-40 h-40 bg-primary-400 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse-slow" style="animation-delay: 1s;"></div>
            <div class="absolute top-2/3 right-1/4 w-28 h-28 bg-primary-600 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse-slow" style="animation-delay: 2s;"></div>
        </div>
        
        <div class="w-full sm:max-w-md mt-6 px-8 py-10 bg-white shadow-2xl overflow-hidden sm:rounded-2xl z-10 animate-fade-in ">
            <div class="text-center mb-8">
                <!-- Logo -->
                <div class="flex justify-center mb-6">
                    <div class=" bg-primary-50 flex items-center justify-center p-1 shadow-amber floating">
                        <img src="/logo.jpg" class="w-20 h-20 object-fit " alt="Logo" />
                    </div>
                </div>
                
                <h2 class="text-3xl font-bold text-gray-900 animate-slide-up">
                    Pelayanan Desa Sapa
                </h2>
                <div class="mt-2 flex items-center justify-center">
                    <span class="h-1 w-10 bg-primary-500 rounded-full"></span>
                    <span class="h-1 w-1 mx-1 bg-primary-500 rounded-full"></span>
                    <span class="h-1 w-1 bg-primary-500 rounded-full"></span>
                </div>
                <p class="mt-3 text-sm text-gray-600 animate-slide-up">
                    Sign in to your account to continue
                </p>
            </div>
            
            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif
            
            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 rounded-md">
                    <div class="font-medium text-red-700">
                        {{ __('Whoops! Something went wrong.') }}
                    </div>
                    
                    <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form method="POST" action="{{ route('login.post') }}" class="space-y-6">
                @csrf
                
                <div class="group">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1 group-focus-within:text-dark transition-colors">
                        Email Address
                    </label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 group-focus-within:text-primary-500">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                            </svg>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}" 
                            class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-lg py-3.5 transition-all duration-200 bg-gray-50 focus:bg-white"
                            placeholder="you@example.com">
                    </div>
                </div>
                
                <div class="group">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1 group-focus-within:text-dark transition-colors">
                        Password
                    </label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 group-focus-within:text-primary-500">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                            class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-lg py-3.5 transition-all duration-200 bg-gray-50 focus:bg-white"
                            placeholder="••••••••">
                    </div>
                </div>
                
                <div>
                    <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-lg shadow-amber text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition duration-150 ease-in-out transform hover:-translate-y-0.5">
                        <span class="mr-2">Sign in</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
        
        <div class="mt-8 text-center text-xs text-white opacity-70 z-10">
            <p>© {{ date('Y') }} Pelayanan Desa Sapa. All rights reserved.</p>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Focus the email field on page load
            document.getElementById('email').focus();
            
            // Add focus effect to input groups
            const inputGroups = document.querySelectorAll('.group');
            inputGroups.forEach(group => {
                const input = group.querySelector('input');
                input.addEventListener('focus', () => {
                    group.classList.add('is-focused');
                });
                input.addEventListener('blur', () => {
                    group.classList.remove('is-focused');
                });
            });
        });
    </script>
</body>
</html>
