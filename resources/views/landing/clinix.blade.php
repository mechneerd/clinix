{{-- resources/views/landing/clinix.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clinix - Modern Clinic Management System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        
        /* Custom Animations */
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(2deg); }
        }
        
        @keyframes float-slow {
            0%, 100% { transform: translateY(0px) translateX(0px); }
            33% { transform: translateY(-15px) translateX(10px); }
            66% { transform: translateY(-10px) translateX(-5px); }
        }
        
        @keyframes float-reverse {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(20px) rotate(-2deg); }
        }
        
        @keyframes orbit {
            0% { transform: rotate(0deg) translateX(100px) rotate(0deg); }
            100% { transform: rotate(360deg) translateX(100px) rotate(-360deg); }
        }
        
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(14, 165, 233, 0.3); }
            50% { box-shadow: 0 0 40px rgba(14, 165, 233, 0.6); }
        }
        
        @keyframes slide-up {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes slide-in-left {
            from { opacity: 0; transform: translateX(-50px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        @keyframes slide-in-right {
            from { opacity: 0; transform: translateX(50px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        @keyframes scale-in {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
        
        @keyframes gradient-shift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        @keyframes blob {
            0%, 100% { border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%; }
            50% { border-radius: 30% 60% 70% 40% / 50% 60% 30% 60%; }
        }
        
        @keyframes bounce-subtle {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        @keyframes wiggle {
            0%, 100% { transform: rotate(-3deg); }
            50% { transform: rotate(3deg); }
        }
        
        .animate-float { animation: float 6s ease-in-out infinite; }
        .animate-float-slow { animation: float-slow 8s ease-in-out infinite; }
        .animate-float-reverse { animation: float-reverse 7s ease-in-out infinite; }
        .animate-orbit { animation: orbit 20s linear infinite; }
        .animate-pulse-glow { animation: pulse-glow 3s ease-in-out infinite; }
        .animate-slide-up { animation: slide-up 0.8s ease-out forwards; }
        .animate-slide-in-left { animation: slide-in-left 0.8s ease-out forwards; }
        .animate-slide-in-right { animation: slide-in-right 0.8s ease-out forwards; }
        .animate-scale-in { animation: scale-in 0.6s ease-out forwards; }
        .animate-gradient { background-size: 200% 200%; animation: gradient-shift 8s ease infinite; }
        .animate-blob { animation: blob 8s ease-in-out infinite; }
        .animate-bounce-subtle { animation: bounce-subtle 3s ease-in-out infinite; }
        .animate-wiggle { animation: wiggle 2s ease-in-out infinite; }
        
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
        .delay-400 { animation-delay: 0.4s; }
        .delay-500 { animation-delay: 0.5s; }
        .delay-1000 { animation-delay: 1s; }
        .delay-2000 { animation-delay: 2s; }
        .delay-3000 { animation-delay: 3s; }
        .delay-4000 { animation-delay: 4s; }
        
        /* Avatar specific animations */
        @keyframes avatar-float-1 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            25% { transform: translate(10px, -20px) scale(1.05); }
            50% { transform: translate(-5px, -10px) scale(1); }
            75% { transform: translate(-15px, -25px) scale(0.95); }
        }
        
        @keyframes avatar-float-2 {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(-20px, 15px) rotate(5deg); }
            66% { transform: translate(15px, -10px) rotate(-3deg); }
        }
        
        @keyframes avatar-float-3 {
            0%, 100% { transform: translate(0, 0); }
            20% { transform: translate(15px, -15px); }
            40% { transform: translate(-10px, -20px); }
            60% { transform: translate(-20px, -5px); }
            80% { transform: translate(5px, -10px); }
        }
        
        @keyframes avatar-pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.9; }
        }
        
        .animate-avatar-1 { animation: avatar-float-1 12s ease-in-out infinite; }
        .animate-avatar-2 { animation: avatar-float-2 10s ease-in-out infinite; }
        .animate-avatar-3 { animation: avatar-float-3 14s ease-in-out infinite; }
        .animate-avatar-pulse { animation: avatar-pulse 4s ease-in-out infinite; }
        
        /* Glassmorphism */
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .glass-dark {
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        /* Gradient Text */
        .gradient-text {
            background: linear-gradient(135deg, #0ea5e9 0%, #8b5cf6 50%, #ec4899 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Hover Effects */
        .hover-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .hover-lift:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #0ea5e9, #8b5cf6);
            border-radius: 4px;
        }
        
        /* Intersection Observer Animation Classes */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease-out;
        }
        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }
        
        /* Medical Cross Pattern */
        .medical-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%230ea5e9' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        
        /* Avatar ring animation */
        .avatar-ring {
            position: relative;
        }
        .avatar-ring::before {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0ea5e9, #8b5cf6, #ec4899);
            z-index: -1;
            animation: spin 4s linear infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 overflow-x-hidden">

    {{-- Navigation --}}
    <nav class="fixed top-0 left-0 right-0 z-50 glass">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                {{-- Logo --}}
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-sky-500 to-violet-600 flex items-center justify-center">
                        <i data-lucide="heart-pulse" class="w-6 h-6 text-white"></i>
                    </div>
                    <span class="text-2xl font-bold bg-gradient-to-r from-sky-600 to-violet-600 bg-clip-text text-transparent">
                        Clinix
                    </span>
                </div>
                
                {{-- Desktop Menu --}}
                <div class="hidden md:flex items-center gap-8">
                    <a href="#features" class="text-slate-600 hover:text-sky-600 font-medium transition-colors">Features</a>
                    <a href="#how-it-works" class="text-slate-600 hover:text-sky-600 font-medium transition-colors">How It Works</a>
                    <a href="#testimonials" class="text-slate-600 hover:text-sky-600 font-medium transition-colors">Testimonials</a>
                    <a href="#pricing" class="text-slate-600 hover:text-sky-600 font-medium transition-colors">Pricing</a>
                </div>
                
                {{-- CTA Buttons --}}
                <div class="hidden md:flex items-center gap-4">
                    <button class="text-slate-600 hover:text-sky-600 font-medium transition-colors">Sign In</button>
                    <button class="px-6 py-2.5 bg-gradient-to-r from-sky-500 to-violet-600 text-white font-semibold rounded-full hover:shadow-lg hover:shadow-sky-500/30 transition-all hover:-translate-y-0.5">
                        Get Started
                    </button>
                </div>
                
                {{-- Mobile Menu Button --}}
                <button class="md:hidden p-2" onclick="toggleMobileMenu()">
                    <i data-lucide="menu" class="w-6 h-6 text-slate-700"></i>
                </button>
            </div>
        </div>
        
        {{-- Mobile Menu --}}
        <div id="mobile-menu" class="hidden md:hidden glass border-t border-slate-200">
            <div class="px-4 py-4 space-y-3">
                <a href="#features" class="block py-2 text-slate-600 hover:text-sky-600">Features</a>
                <a href="#how-it-works" class="block py-2 text-slate-600 hover:text-sky-600">How It Works</a>
                <a href="#testimonials" class="block py-2 text-slate-600 hover:text-sky-600">Testimonials</a>
                <a href="#pricing" class="block py-2 text-slate-600 hover:text-sky-600">Pricing</a>
                <button class="w-full py-2.5 bg-gradient-to-r from-sky-500 to-violet-600 text-white font-semibold rounded-full mt-4">
                    Get Started
                </button>
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    <section class="relative min-h-screen pt-32 pb-20 overflow-hidden medical-pattern">
        {{-- Background Blobs --}}
        <div class="absolute top-20 left-10 w-72 h-72 bg-sky-300/30 rounded-full blur-3xl animate-blob"></div>
        <div class="absolute top-40 right-10 w-96 h-96 bg-violet-300/30 rounded-full blur-3xl animate-blob delay-200"></div>
        <div class="absolute bottom-20 left-1/3 w-80 h-80 bg-pink-300/30 rounded-full blur-3xl animate-blob delay-400"></div>
        
        {{-- Floating Avatars --}}
        <div class="absolute top-32 left-8 md:left-16 z-20 animate-avatar-1 hidden sm:block">
            <div class="relative">
                <div class="w-16 h-16 md:w-20 md:h-20 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 p-1 avatar-ring">
                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=DrSarah&backgroundColor=ecfdf5&clothing=labCoat" 
                         alt="Doctor" 
                         class="w-full h-full rounded-full bg-white object-cover">
                </div>
                <div class="absolute -bottom-2 -right-2 w-6 h-6 bg-emerald-500 rounded-full flex items-center justify-center border-2 border-white">
                    <i data-lucide="stethoscope" class="w-3 h-3 text-white"></i>
                </div>
                <div class="absolute top-full mt-2 left-1/2 -translate-x-1/2 glass px-3 py-1 rounded-full text-xs font-medium text-slate-700 whitespace-nowrap">
                    Dr. Sarah
                </div>
            </div>
        </div>
        
        <div class="absolute top-48 right-8 md:right-20 z-20 animate-avatar-2 hidden sm:block" style="animation-delay: -3s;">
            <div class="relative">
                <div class="w-14 h-14 md:w-18 md:h-18 rounded-full bg-gradient-to-br from-rose-400 to-rose-600 p-1 avatar-ring">
                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=NurseEmma&backgroundColor=fff1f2&clothing=nurse" 
                         alt="Nurse" 
                         class="w-full h-full rounded-full bg-white object-cover">
                </div>
                <div class="absolute -bottom-2 -right-2 w-6 h-6 bg-rose-500 rounded-full flex items-center justify-center border-2 border-white">
                    <i data-lucide="heart" class="w-3 h-3 text-white"></i>
                </div>
                <div class="absolute top-full mt-2 left-1/2 -translate-x-1/2 glass px-3 py-1 rounded-full text-xs font-medium text-slate-700 whitespace-nowrap">
                    Nurse Emma
                </div>
            </div>
        </div>
        
        <div class="absolute bottom-40 left-12 md:left-24 z-20 animate-avatar-3 hidden md:block" style="animation-delay: -6s;">
            <div class="relative">
                <div class="w-16 h-16 md:w-20 md:h-20 rounded-full bg-gradient-to-br from-amber-400 to-amber-600 p-1 avatar-ring">
                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=LabTechMike&backgroundColor=fffbeb&clothing=blazerAndShirt" 
                         alt="Lab Technician" 
                         class="w-full h-full rounded-full bg-white object-cover">
                </div>
                <div class="absolute -bottom-2 -right-2 w-6 h-6 bg-amber-500 rounded-full flex items-center justify-center border-2 border-white">
                    <i data-lucide="microscope" class="w-3 h-3 text-white"></i>
                </div>
                <div class="absolute top-full mt-2 left-1/2 -translate-x-1/2 glass px-3 py-1 rounded-full text-xs font-medium text-slate-700 whitespace-nowrap">
                    Lab Tech Mike
                </div>
            </div>
        </div>
        
        <div class="absolute top-64 left-1/4 z-20 animate-avatar-1 hidden lg:block" style="animation-delay: -4s;">
            <div class="relative">
                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-sky-400 to-sky-600 p-1 avatar-ring">
                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=DrJames&backgroundColor=e0f2fe&clothing=labCoat" 
                         alt="Doctor" 
                         class="w-full h-full rounded-full bg-white object-cover">
                </div>
                <div class="absolute -bottom-2 -right-2 w-6 h-6 bg-sky-500 rounded-full flex items-center justify-center border-2 border-white">
                    <i data-lucide="activity" class="w-3 h-3 text-white"></i>
                </div>
            </div>
        </div>
        
        <div class="absolute bottom-32 right-16 md:right-32 z-20 animate-avatar-2 hidden md:block" style="animation-delay: -7s;">
            <div class="relative">
                <div class="w-15 h-15 md:w-18 md:h-18 rounded-full bg-gradient-to-br from-violet-400 to-violet-600 p-1 avatar-ring">
                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=NurseLisa&backgroundColor=f3e8ff&clothing=nurse" 
                         alt="Nurse" 
                         class="w-full h-full rounded-full bg-white object-cover">
                </div>
                <div class="absolute -bottom-2 -right-2 w-6 h-6 bg-violet-500 rounded-full flex items-center justify-center border-2 border-white">
                    <i data-lucide="syringe" class="w-3 h-3 text-white"></i>
                </div>
            </div>
        </div>
        
        <div class="absolute top-40 right-1/3 z-20 animate-avatar-3 hidden xl:block" style="animation-delay: -2s;">
            <div class="relative">
                <div class="w-13 h-13 rounded-full bg-gradient-to-br from-teal-400 to-teal-600 p-1 avatar-ring">
                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=LabAna&backgroundColor=f0fdfa&clothing=blazerAndShirt" 
                         alt="Lab Employee" 
                         class="w-full h-full rounded-full bg-white object-cover">
                </div>
                <div class="absolute -bottom-2 -right-2 w-6 h-6 bg-teal-500 rounded-full flex items-center justify-center border-2 border-white">
                    <i data-lucide="flask-conical" class="w-3 h-3 text-white"></i>
                </div>
            </div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                {{-- Left Content --}}
                <div class="text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-sky-50 border border-sky-200 mb-6 animate-slide-up">
                        <span class="w-2 h-2 rounded-full bg-sky-500 animate-pulse"></span>
                        <span class="text-sm font-medium text-sky-700">Trusted by 500+ Healthcare Providers</span>
                    </div>
                    
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold leading-tight mb-6 animate-slide-up delay-100">
                        Manage Your Clinic <br>
                        <span class="gradient-text">Smarter & Faster</span>
                    </h1>
                    
                    <p class="text-lg text-slate-600 mb-8 max-w-xl mx-auto lg:mx-0 animate-slide-up delay-200">
                        Streamline appointments, patient records, billing, and prescriptions all in one powerful platform. Designed for modern healthcare professionals.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start mb-12 animate-slide-up delay-300">
                        <button class="px-8 py-4 bg-gradient-to-r from-sky-500 to-violet-600 text-white font-semibold rounded-full hover:shadow-xl hover:shadow-sky-500/30 transition-all hover:-translate-y-1 animate-pulse-glow">
                            Start Free Trial
                        </button>
                        <button class="px-8 py-4 bg-white text-slate-700 font-semibold rounded-full border-2 border-slate-200 hover:border-sky-300 hover:text-sky-600 transition-all flex items-center justify-center gap-2 group">
                            <i data-lucide="play-circle" class="w-5 h-5 group-hover:scale-110 transition-transform"></i>
                            Watch Demo
                        </button>
                    </div>
                    
                    {{-- Stats --}}
                    <div class="grid grid-cols-3 gap-6 animate-slide-up delay-400">
                        <div>
                            <div class="text-3xl font-bold text-slate-900">50K+</div>
                            <div class="text-sm text-slate-500">Patients Managed</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-slate-900">500+</div>
                            <div class="text-sm text-slate-500">Clinics Active</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-slate-900">99.9%</div>
                            <div class="text-sm text-slate-500">Uptime</div>
                        </div>
                    </div>
                </div>
                
                {{-- Right Content - Dashboard Mockup --}}
                <div class="relative animate-slide-in-right delay-200">
                    <div class="relative z-10 animate-float">
                        <div class="glass rounded-3xl p-2 shadow-2xl shadow-slate-200/50">
                            <div class="bg-white rounded-2xl overflow-hidden">
                                {{-- Mock Dashboard Header --}}
                                <div class="bg-gradient-to-r from-slate-900 to-slate-800 px-6 py-4 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-sky-500 flex items-center justify-center">
                                            <i data-lucide="heart-pulse" class="w-4 h-4 text-white"></i>
                                        </div>
                                        <span class="text-white font-semibold">Clinix Dashboard</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-700"></div>
                                    </div>
                                </div>
                                
                                {{-- Mock Dashboard Content --}}
                                <div class="p-6 space-y-4">
                                    <div class="grid grid-cols-3 gap-4">
                                        <div class="bg-sky-50 rounded-xl p-4">
                                            <div class="text-sky-600 text-sm font-medium mb-1">Today's Appointments</div>
                                            <div class="text-2xl font-bold text-slate-900">24</div>
                                        </div>
                                        <div class="bg-violet-50 rounded-xl p-4">
                                            <div class="text-violet-600 text-sm font-medium mb-1">New Patients</div>
                                            <div class="text-2xl font-bold text-slate-900">8</div>
                                        </div>
                                        <div class="bg-emerald-50 rounded-xl p-4">
                                            <div class="text-emerald-600 text-sm font-medium mb-1">Completed</div>
                                            <div class="text-2xl font-bold text-slate-900">16</div>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-slate-50 rounded-xl p-4">
                                        <div class="flex items-center justify-between mb-3">
                                            <span class="font-medium text-slate-700">Recent Patients</span>
                                            <span class="text-sm text-sky-600 cursor-pointer">View All</span>
                                        </div>
                                        <div class="space-y-3">
                                            <div class="flex items-center gap-3 bg-white p-3 rounded-lg">
                                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-sky-400 to-sky-600 flex items-center justify-center text-white font-semibold">JD</div>
                                                <div class="flex-1">
                                                    <div class="font-medium text-slate-900">John Doe</div>
                                                    <div class="text-sm text-slate-500">General Checkup • 10:30 AM</div>
                                                </div>
                                                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-medium rounded-full">Confirmed</span>
                                            </div>
                                            <div class="flex items-center gap-3 bg-white p-3 rounded-lg">
                                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-violet-400 to-violet-600 flex items-center justify-center text-white font-semibold">SM</div>
                                                <div class="flex-1">
                                                    <div class="font-medium text-slate-900">Sarah Miller</div>
                                                    <div class="text-sm text-slate-500">Follow-up • 11:00 AM</div>
                                                </div>
                                                <span class="px-3 py-1 bg-amber-100 text-amber-700 text-xs font-medium rounded-full">Pending</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Floating Elements --}}
                    <div class="absolute -top-6 -right-6 glass rounded-2xl p-4 shadow-xl animate-float-delayed z-20">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center">
                                <i data-lucide="check-circle" class="w-6 h-6 text-emerald-600"></i>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-slate-900">Appointment</div>
                                <div class="text-xs text-emerald-600 font-semibold">Confirmed</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="absolute -bottom-4 -left-6 glass rounded-2xl p-4 shadow-xl animate-float z-20">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-sky-100 flex items-center justify-center">
                                <i data-lucide="calendar" class="w-5 h-5 text-sky-600"></i>
                            </div>
                            <div>
                                <div class="text-xs text-slate-500">Next Available</div>
                                <div class="text-sm font-bold text-slate-900">Today, 2:00 PM</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Team Section with Animated Avatars --}}
    <section class="py-20 bg-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 reveal">
                <span class="inline-block px-4 py-2 rounded-full bg-sky-50 text-sky-700 text-sm font-semibold mb-4">
                    Your Healthcare Team
                </span>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-slate-900 mb-6">
                    Powered by <span class="gradient-text">Medical Professionals</span>
                </h2>
                <p class="text-lg text-slate-600">
                    Our platform is built with and for healthcare heroes - doctors, nurses, and lab technicians.
                </p>
            </div>
            
            {{-- Animated Team Avatars --}}
            <div class="relative h-96 md:h-[500px] flex items-center justify-center">
                {{-- Center Element --}}
                <div class="absolute z-10 glass rounded-3xl p-8 shadow-2xl animate-scale-in">
                    <div class="w-24 h-24 mx-auto rounded-2xl bg-gradient-to-br from-sky-500 to-violet-600 flex items-center justify-center mb-4">
                        <i data-lucide="heart-pulse" class="w-12 h-12 text-white"></i>
                    </div>
                    <div class="text-center">
                        <div class="text-xl font-bold text-slate-900">Clinix</div>
                        <div class="text-sm text-slate-500">Connects Everyone</div>
                    </div>
                </div>
                
                {{-- Orbiting Avatars --}}
                <div class="absolute w-full h-full animate-orbit" style="animation-duration: 30s;">
                    <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2">
                        <div class="glass rounded-2xl p-3 shadow-xl animate-float">
                            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 p-1">
                                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=DrChen&backgroundColor=ecfdf5&clothing=labCoat" 
                                     alt="Doctor" 
                                     class="w-full h-full rounded-full bg-white object-cover">
                            </div>
                            <div class="text-center mt-2">
                                <div class="text-xs font-semibold text-slate-700">Dr. Chen</div>
                                <div class="text-[10px] text-emerald-600">Cardiologist</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="absolute w-full h-full animate-orbit" style="animation-duration: 25s; animation-direction: reverse;">
                    <div class="absolute bottom-0 left-1/2 -translate-x-1/2 translate-y-1/2">
                        <div class="glass rounded-2xl p-3 shadow-xl animate-float-reverse">
                            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-rose-400 to-rose-600 p-1">
                                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=NurseJoy&backgroundColor=fff1f2&clothing=nurse" 
                                     alt="Nurse" 
                                     class="w-full h-full rounded-full bg-white object-cover">
                            </div>
                            <div class="text-center mt-2">
                                <div class="text-xs font-semibold text-slate-700">Nurse Joy</div>
                                <div class="text-[10px] text-rose-600">Head Nurse</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="absolute w-full h-full animate-orbit" style="animation-duration: 35s; animation-delay: -10s;">
                    <div class="absolute top-1/2 right-0 translate-x-1/2 -translate-y-1/2">
                        <div class="glass rounded-2xl p-3 shadow-xl animate-float-slow">
                            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-amber-400 to-amber-600 p-1">
                                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=LabTech&backgroundColor=fffbeb&clothing=blazerAndShirt" 
                                     alt="Lab Tech" 
                                     class="w-full h-full rounded-full bg-white object-cover">
                            </div>
                            <div class="text-center mt-2">
                                <div class="text-xs font-semibold text-slate-700">Alex Lab</div>
                                <div class="text-[10px] text-amber-600">Lab Technician</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="absolute w-full h-full animate-orbit" style="animation-duration: 28s; animation-delay: -15s; animation-direction: reverse;">
                    <div class="absolute top-1/2 left-0 -translate-x-1/2 -translate-y-1/2">
                        <div class="glass rounded-2xl p-3 shadow-xl animate-float" style="animation-delay: -2s;">
                            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-violet-400 to-violet-600 p-1">
                                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=DrPatel&backgroundColor=f3e8ff&clothing=labCoat" 
                                     alt="Doctor" 
                                     class="w-full h-full rounded-full bg-white object-cover">
                            </div>
                            <div class="text-center mt-2">
                                <div class="text-xs font-semibold text-slate-700">Dr. Patel</div>
                                <div class="text-[10px] text-violet-600">Surgeon</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Additional Floating Avatars --}}
                <div class="absolute top-10 left-10 animate-avatar-1">
                    <div class="glass rounded-xl p-2 shadow-lg">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-sky-400 to-sky-600 p-0.5">
                            <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=NurseA&backgroundColor=e0f2fe&clothing=nurse" 
                                 alt="Nurse" 
                                 class="w-full h-full rounded-full bg-white object-cover">
                        </div>
                    </div>
                </div>
                
                <div class="absolute bottom-10 right-10 animate-avatar-2" style="animation-delay: -5s;">
                    <div class="glass rounded-xl p-2 shadow-lg">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-teal-400 to-teal-600 p-0.5">
                            <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=LabB&backgroundColor=f0fdfa&clothing=blazerAndShirt" 
                                 alt="Lab Tech" 
                                 class="w-full h-full rounded-full bg-white object-cover">
                        </div>
                    </div>
                </div>
                
                <div class="absolute top-20 right-20 animate-avatar-3" style="animation-delay: -8s;">
                    <div class="glass rounded-xl p-2 shadow-lg">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-pink-400 to-pink-600 p-0.5">
                            <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=DrSmith&backgroundColor=fdf2f8&clothing=labCoat" 
                                 alt="Doctor" 
                                 class="w-full h-full rounded-full bg-white object-cover">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Features Section --}}
    <section id="features" class="py-24 bg-slate-50 relative overflow-hidden">
        <div class="absolute inset-0 medical-pattern opacity-50"></div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 reveal">
                <span class="inline-block px-4 py-2 rounded-full bg-violet-50 text-violet-700 text-sm font-semibold mb-4">
                    Powerful Features
                </span>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-slate-900 mb-6">
                    Everything You Need to <br><span class="gradient-text">Run Your Clinic</span>
                </h2>
                <p class="text-lg text-slate-600">
                    From appointment scheduling to prescription management, Clinix provides all the tools you need to deliver exceptional patient care.
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                {{-- Feature 1 --}}
                <div class="group p-8 rounded-3xl bg-white hover:shadow-2xl hover:shadow-sky-100/50 transition-all duration-500 hover-lift border border-transparent hover:border-sky-100 reveal">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-sky-500 to-sky-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-500 shadow-lg shadow-sky-500/30">
                        <i data-lucide="calendar-check" class="w-7 h-7 text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Smart Scheduling</h3>
                    <p class="text-slate-600 leading-relaxed">
                        AI-powered appointment booking with automatic reminders, waitlist management, and conflict detection.
                    </p>
                </div>
                
                {{-- Feature 2 --}}
                <div class="group p-8 rounded-3xl bg-white hover:shadow-2xl hover:shadow-violet-100/50 transition-all duration-500 hover-lift border border-transparent hover:border-violet-100 reveal delay-100">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-violet-500 to-violet-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-500 shadow-lg shadow-violet-500/30">
                        <i data-lucide="file-text" class="w-7 h-7 text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Digital Records</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Secure, cloud-based patient records with instant search, history tracking, and seamless data migration.
                    </p>
                </div>
                
                {{-- Feature 3 --}}
                <div class="group p-8 rounded-3xl bg-white hover:shadow-2xl hover:shadow-emerald-100/50 transition-all duration-500 hover-lift border border-transparent hover:border-emerald-100 reveal delay-200">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-500 shadow-lg shadow-emerald-500/30">
                        <i data-lucide="credit-card" class="w-7 h-7 text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Easy Billing</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Automated invoicing, insurance claims processing, and multiple payment gateway integrations.
                    </p>
                </div>
                
                {{-- Feature 4 --}}
                <div class="group p-8 rounded-3xl bg-white hover:shadow-2xl hover:shadow-rose-100/50 transition-all duration-500 hover-lift border border-transparent hover:border-rose-100 reveal">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-rose-500 to-rose-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-500 shadow-lg shadow-rose-500/30">
                        <i data-lucide="pill" class="w-7 h-7 text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">E-Prescriptions</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Digital prescription management with drug interaction checks and pharmacy integrations.
                    </p>
                </div>
                
                {{-- Feature 5 --}}
                <div class="group p-8 rounded-3xl bg-white hover:shadow-2xl hover:shadow-amber-100/50 transition-all duration-500 hover-lift border border-transparent hover:border-amber-100 reveal delay-100">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-500 shadow-lg shadow-amber-500/30">
                        <i data-lucide="video" class="w-7 h-7 text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Telemedicine</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Built-in video consultations with screen sharing, digital whiteboard, and session recording.
                    </p>
                </div>
                
                {{-- Feature 6 --}}
                <div class="group p-8 rounded-3xl bg-white hover:shadow-2xl hover:shadow-cyan-100/50 transition-all duration-500 hover-lift border border-transparent hover:border-cyan-100 reveal delay-200">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-cyan-500 to-cyan-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-500 shadow-lg shadow-cyan-500/30">
                        <i data-lucide="bar-chart-3" class="w-7 h-7 text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Analytics</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Comprehensive reports on patient flow, revenue, staff performance, and clinic efficiency metrics.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- How It Works --}}
    <section id="how-it-works" class="py-24 bg-gradient-to-br from-slate-900 via-slate-800 to-violet-900 relative overflow-hidden">
        {{-- Background Pattern --}}
        <div class="absolute inset-0 opacity-20">
            <div class="absolute top-0 left-0 w-full h-full" style="background-image: radial-gradient(circle at 2px 2px, rgba(255,255,255,0.15) 1px, transparent 0); background-size: 40px 40px;"></div>
        </div>
        
        {{-- Floating Medical Staff Avatars in Dark Section --}}
        <div class="absolute top-20 left-10 animate-avatar-2 hidden lg:block">
            <div class="glass-dark rounded-2xl p-3">
                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 p-1">
                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=DrNight&backgroundColor=064e3b&clothing=labCoat" 
                         alt="Doctor" 
                         class="w-full h-full rounded-full bg-white object-cover">
                </div>
                <div class="text-center mt-2">
                    <div class="text-xs text-white">On Duty</div>
                </div>
            </div>
        </div>
        
        <div class="absolute bottom-20 right-10 animate-avatar-3 hidden lg:block" style="animation-delay: -5s;">
            <div class="glass-dark rounded-2xl p-3">
                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-rose-400 to-rose-600 p-1">
                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=NurseNight&backgroundColor=881337&clothing=nurse" 
                         alt="Nurse" 
                         class="w-full h-full rounded-full bg-white object-cover">
                </div>
                <div class="text-center mt-2">
                    <div class="text-xs text-white">Night Shift</div>
                </div>
            </div>
        </div>
        
        <div class="absolute top-1/2 right-20 animate-avatar-1 hidden xl:block" style="animation-delay: -8s;">
            <div class="glass-dark rounded-2xl p-3">
                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-sky-400 to-sky-600 p-1">
                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=LabNight&backgroundColor=0c4a6e&clothing=blazerAndShirt" 
                         alt="Lab Tech" 
                         class="w-full h-full rounded-full bg-white object-cover">
                </div>
                <div class="text-center mt-2">
                    <div class="text-xs text-white">Lab Open</div>
                </div>
            </div>
        </div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 reveal">
                <span class="inline-block px-4 py-2 rounded-full bg-white/10 text-sky-300 text-sm font-semibold mb-4 backdrop-blur-sm">
                    Simple Process
                </span>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-6">
                    Get Started in <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-400 to-violet-400">3 Easy Steps</span>
                </h2>
                <p class="text-lg text-slate-300">
                    From signup to your first appointment in minutes, not days.
                </p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8 relative">
                {{-- Connecting Line --}}
                <div class="hidden md:block absolute top-24 left-1/6 right-1/6 h-0.5 bg-gradient-to-r from-sky-500 via-violet-500 to-pink-500"></div>
                
                {{-- Step 1 --}}
                <div class="relative text-center reveal">
                    <div class="w-20 h-20 mx-auto rounded-2xl bg-gradient-to-br from-sky-500 to-sky-600 flex items-center justify-center mb-6 relative z-10 shadow-2xl shadow-sky-500/50">
                        <span class="text-3xl font-bold text-white">1</span>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Create Account</h3>
                    <p class="text-slate-400">
                        Sign up in seconds. No credit card required to start your 14-day free trial.
                    </p>
                </div>
                
                {{-- Step 2 --}}
                <div class="relative text-center reveal delay-100">
                    <div class="w-20 h-20 mx-auto rounded-2xl bg-gradient-to-br from-violet-500 to-violet-600 flex items-center justify-center mb-6 relative z-10 shadow-2xl shadow-violet-500/50">
                        <span class="text-3xl font-bold text-white">2</span>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Setup Clinic</h3>
                    <p class="text-slate-400">
                        Add your services, staff, and customize your booking preferences.
                    </p>
                </div>
                
                {{-- Step 3 --}}
                <div class="relative text-center reveal delay-200">
                    <div class="w-20 h-20 mx-auto rounded-2xl bg-gradient-to-br from-pink-500 to-rose-600 flex items-center justify-center mb-6 relative z-10 shadow-2xl shadow-pink-500/50">
                        <span class="text-3xl font-bold text-white">3</span>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Start Booking</h3>
                    <p class="text-slate-400">
                        Share your booking link and start accepting appointments immediately.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Testimonials --}}
    <section id="testimonials" class="py-24 bg-slate-50 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 reveal">
                <span class="inline-block px-4 py-2 rounded-full bg-emerald-50 text-emerald-700 text-sm font-semibold mb-4">
                    Testimonials
                </span>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-slate-900 mb-6">
                    Loved by <span class="gradient-text">Healthcare Professionals</span>
                </h2>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                {{-- Testimonial 1 --}}
                <div class="glass rounded-3xl p-8 hover-lift reveal">
                    <div class="flex items-center gap-1 mb-4">
                        <i data-lucide="star" class="w-5 h-5 text-amber-400 fill-amber-400"></i>
                        <i data-lucide="star" class="w-5 h-5 text-amber-400 fill-amber-400"></i>
                        <i data-lucide="star" class="w-5 h-5 text-amber-400 fill-amber-400"></i>
                        <i data-lucide="star" class="w-5 h-5 text-amber-400 fill-amber-400"></i>
                        <i data-lucide="star" class="w-5 h-5 text-amber-400 fill-amber-400"></i>
                    </div>
                    <p class="text-slate-600 mb-6 leading-relaxed">
                        "Clinix transformed how we manage our practice. Appointment scheduling is seamless, and our patients love the reminder system."
                    </p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-sky-400 to-sky-600 flex items-center justify-center text-white font-semibold overflow-hidden">
                            <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=DrRachel&backgroundColor=e0f2fe&clothing=labCoat" 
                                 alt="Dr. Rachel" 
                                 class="w-full h-full object-cover">
                        </div>
                        <div>
                            <div class="font-semibold text-slate-900">Dr. Rachel Chen</div>
                            <div class="text-sm text-slate-500">Family Medicine, NY</div>
                        </div>
                    </div>
                </div>
                
                {{-- Testimonial 2 --}}
                <div class="glass rounded-3xl p-8 hover-lift reveal delay-100">
                    <div class="flex items-center gap-1 mb-4">
                        <i data-lucide="star" class="w-5 h-5 text-amber-400 fill-amber-400"></i>
                        <i data-lucide="star" class="w-5 h-5 text-amber-400 fill-amber-400"></i>
                        <i data-lucide="star" class="w-5 h-5 text-amber-400 fill-amber-400"></i>
                        <i data-lucide="star" class="w-5 h-5 text-amber-400 fill-amber-400"></i>
                        <i data-lucide="star" class="w-5 h-5 text-amber-400 fill-amber-400"></i>
                    </div>
                    <p class="text-slate-600 mb-6 leading-relaxed">
                        "The billing integration saved us countless hours. Insurance claims that used to take days now process automatically."
                    </p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-violet-400 to-violet-600 flex items-center justify-center text-white font-semibold overflow-hidden">
                            <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=DrMichael&backgroundColor=f3e8ff&clothing=labCoat" 
                                 alt="Dr. Michael" 
                                 class="w-full h-full object-cover">
                        </div>
                        <div>
                            <div class="font-semibold text-slate-900">Dr. Michael Johnson</div>
                            <div class="text-sm text-slate-500">Cardiology, CA</div>
                        </div>
                    </div>
                </div>
                
                {{-- Testimonial 3 --}}
                <div class="glass rounded-3xl p-8 hover-lift reveal delay-200">
                    <div class="flex items-center gap-1 mb-4">
                        <i data-lucide="star" class="w-5 h-5 text-amber-400 fill-amber-400"></i>
                        <i data-lucide="star" class="w-5 h-5 text-amber-400 fill-amber-400"></i>
                        <i data-lucide="star" class="w-5 h-5 text-amber-400 fill-amber-400"></i>
                        <i data-lucide="star" class="w-5 h-5 text-amber-400 fill-amber-400"></i>
                        <i data-lucide="star" class="w-5 h-5 text-amber-400 fill-amber-400"></i>
                    </div>
                    <p class="text-slate-600 mb-6 leading-relaxed">
                        "Best investment for our clinic. The telemedicine feature helped us serve patients during the pandemic and beyond."
                    </p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-white font-semibold overflow-hidden">
                            <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=DrSarahP&backgroundColor=ecfdf5&clothing=labCoat" 
                                 alt="Dr. Sarah" 
                                 class="w-full h-full object-cover">
                        </div>
                        <div>
                            <div class="font-semibold text-slate-900">Dr. Sarah Patel</div>
                            <div class="text-sm text-slate-500">Pediatrics, TX</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Pricing --}}
    <section id="pricing" class="py-24 bg-white relative overflow-hidden">
        <div class="absolute inset-0 medical-pattern opacity-30"></div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 reveal">
                <span class="inline-block px-4 py-2 rounded-full bg-sky-50 text-sky-700 text-sm font-semibold mb-4">
                    Pricing
                </span>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-slate-900 mb-6">
                    Simple, Transparent <span class="gradient-text">Pricing</span>
                </h2>
                <p class="text-lg text-slate-600">
                    Choose the plan that fits your practice. All plans include a 14-day free trial.
                </p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                {{-- Starter --}}
                <div class="rounded-3xl p-8 bg-slate-50 border border-slate-200 hover:border-sky-300 transition-all hover-lift reveal">
                    <div class="text-lg font-semibold text-slate-900 mb-2">Starter</div>
                    <div class="text-4xl font-bold text-slate-900 mb-6">$49<span class="text-lg font-normal text-slate-500">/month</span></div>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center gap-3 text-slate-600">
                            <i data-lucide="check" class="w-5 h-5 text-emerald-500"></i>
                            Up to 500 patients
                        </li>
                        <li class="flex items-center gap-3 text-slate-600">
                            <i data-lucide="check" class="w-5 h-5 text-emerald-500"></i>
                            Basic scheduling
                        </li>
                        <li class="flex items-center gap-3 text-slate-600">
                            <i data-lucide="check" class="w-5 h-5 text-emerald-500"></i>
                            Email reminders
                        </li>
                        <li class="flex items-center gap-3 text-slate-600">
                            <i data-lucide="check" class="w-5 h-5 text-emerald-500"></i>
                            2 staff members
                        </li>
                    </ul>
                    <button class="w-full py-3 rounded-full border-2 border-slate-300 text-slate-700 font-semibold hover:border-sky-500 hover:text-sky-600 transition-all">
                        Start Trial
                    </button>
                </div>
                
                {{-- Professional - Popular --}}
                <div class="rounded-3xl p-8 bg-gradient-to-br from-sky-500 to-violet-600 text-white relative transform scale-105 shadow-2xl shadow-sky-500/30 reveal delay-100">
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2 px-4 py-1 bg-amber-400 text-amber-900 text-sm font-bold rounded-full">
                        MOST POPULAR
                    </div>
                    <div class="text-lg font-semibold mb-2">Professional</div>
                    <div class="text-4xl font-bold mb-6">$99<span class="text-lg font-normal text-sky-100">/month</span></div>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center gap-3 text-sky-50">
                            <i data-lucide="check" class="w-5 h-5 text-amber-300"></i>
                            Unlimited patients
                        </li>
                        <li class="flex items-center gap-3 text-sky-50">
                            <i data-lucide="check" class="w-5 h-5 text-amber-300"></i>
                            Advanced scheduling
                        </li>
                        <li class="flex items-center gap-3 text-sky-50">
                            <i data-lucide="check" class="w-5 h-5 text-amber-300"></i>
                            SMS & Email reminders
                        </li>
                        <li class="flex items-center gap-3 text-sky-50">
                            <i data-lucide="check" class="w-5 h-5 text-amber-300"></i>
                            10 staff members
                        </li>
                        <li class="flex items-center gap-3 text-sky-50">
                            <i data-lucide="check" class="w-5 h-5 text-amber-300"></i>
                            Telemedicine
                        </li>
                        <li class="flex items-center gap-3 text-sky-50">
                            <i data-lucide="check" class="w-5 h-5 text-amber-300"></i>
                            Billing integration
                        </li>
                    </ul>
                    <button class="w-full py-3 rounded-full bg-white text-sky-600 font-semibold hover:bg-sky-50 transition-all">
                        Start Trial
                    </button>
                </div>
                
                {{-- Enterprise --}}
                <div class="rounded-3xl p-8 bg-slate-50 border border-slate-200 hover:border-violet-300 transition-all hover-lift reveal delay-200">
                    <div class="text-lg font-semibold text-slate-900 mb-2">Enterprise</div>
                    <div class="text-4xl font-bold text-slate-900 mb-6">$199<span class="text-lg font-normal text-slate-500">/month</span></div>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center gap-3 text-slate-600">
                            <i data-lucide="check" class="w-5 h-5 text-emerald-500"></i>
                            Everything in Pro
                        </li>
                        <li class="flex items-center gap-3 text-slate-600">
                            <i data-lucide="check" class="w-5 h-5 text-emerald-500"></i>
                            Unlimited staff
                        </li>
                        <li class="flex items-center gap-3 text-slate-600">
                            <i data-lucide="check" class="w-5 h-5 text-emerald-500"></i>
                            Multiple locations
                        </li>
                        <li class="flex items-center gap-3 text-slate-600">
                            <i data-lucide="check" class="w-5 h-5 text-emerald-500"></i>
                            Custom integrations
                        </li>
                        <li class="flex items-center gap-3 text-slate-600">
                            <i data-lucide="check" class="w-5 h-5 text-emerald-500"></i>
                            Dedicated support
                        </li>
                    </ul>
                    <button class="w-full py-3 rounded-full border-2 border-slate-300 text-slate-700 font-semibold hover:border-violet-500 hover:text-violet-600 transition-all">
                        Contact Sales
                    </button>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-24 bg-gradient-to-r from-sky-500 to-violet-600 relative overflow-hidden">
        {{-- Background Pattern --}}
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-0 w-full h-full" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 32px 32px;"></div>
        </div>
        
        {{-- Floating Blobs --}}
        <div class="absolute top-0 left-0 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-violet-400/20 rounded-full blur-3xl"></div>
        
        {{-- Floating Avatars in CTA --}}
        <div class="absolute top-10 left-10 md:left-20 animate-avatar-1 hidden md:block">
            <div class="glass rounded-2xl p-3 shadow-xl">
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 p-1">
                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=DrCTA&backgroundColor=ecfdf5&clothing=labCoat" 
                         alt="Doctor" 
                         class="w-full h-full rounded-full bg-white object-cover">
                </div>
            </div>
        </div>
        
        <div class="absolute bottom-10 right-10 md:right-20 animate-avatar-2 hidden md:block" style="animation-delay: -4s;">
            <div class="glass rounded-2xl p-3 shadow-xl">
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-rose-400 to-rose-600 p-1">
                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=NurseCTA&backgroundColor=fff1f2&clothing=nurse" 
                         alt="Nurse" 
                         class="w-full h-full rounded-full bg-white object-cover">
                </div>
            </div>
        </div>
        
        <div class="absolute top-1/2 left-5 animate-avatar-3 hidden lg:block" style="animation-delay: -7s;">
            <div class="glass rounded-2xl p-2 shadow-xl">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-amber-400 to-amber-600 p-0.5">
                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=LabCTA&backgroundColor=fffbeb&clothing=blazerAndShirt" 
                         alt="Lab Tech" 
                         class="w-full h-full rounded-full bg-white object-cover">
                </div>
            </div>
        </div>
        
        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center reveal">
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-6">
                Ready to Transform Your Clinic?
            </h2>
            <p class="text-xl text-sky-100 mb-10 max-w-2xl mx-auto">
                Join 500+ healthcare providers who trust Clinix to power their practice.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button class="px-8 py-4 bg-white text-sky-600 font-bold rounded-full hover:shadow-2xl hover:shadow-white/30 transition-all hover:-translate-y-1">
                    Start Free Trial
                </button>
                <button class="px-8 py-4 bg-transparent text-white font-bold rounded-full border-2 border-white/30 hover:border-white hover:bg-white/10 transition-all">
                    Schedule Demo
                </button>
            </div>
            <p class="mt-6 text-sky-200 text-sm">No credit card required • 14-day free trial • Cancel anytime</p>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-slate-900 text-slate-300 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 lg:grid-cols-5 gap-12 mb-12">
                {{-- Brand --}}
                <div class="lg:col-span-2">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-sky-500 to-violet-600 flex items-center justify-center">
                            <i data-lucide="heart-pulse" class="w-6 h-6 text-white"></i>
                        </div>
                        <span class="text-2xl font-bold text-white">Clinix</span>
                    </div>
                    <p class="text-slate-400 mb-6 max-w-sm">
                        Modern clinic management software designed to help healthcare providers deliver better patient care.
                    </p>
                    <div class="flex gap-4">
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-sky-600 transition-colors">
                            <i data-lucide="twitter" class="w-5 h-5"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-sky-600 transition-colors">
                            <i data-lucide="linkedin" class="w-5 h-5"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-sky-600 transition-colors">
                            <i data-lucide="facebook" class="w-5 h-5"></i>
                        </a>
                    </div>
                </div>
                
                {{-- Product --}}
                <div>
                    <h4 class="text-white font-semibold mb-4">Product</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="hover:text-sky-400 transition-colors">Features</a></li>
                        <li><a href="#" class="hover:text-sky-400 transition-colors">Pricing</a></li>
                        <li><a href="#" class="hover:text-sky-400 transition-colors">Integrations</a></li>
                        <li><a href="#" class="hover:text-sky-400 transition-colors">Updates</a></li>
                    </ul>
                </div>
                
                {{-- Company --}}
                <div>
                    <h4 class="text-white font-semibold mb-4">Company</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="hover:text-sky-400 transition-colors">About</a></li>
                        <li><a href="#" class="hover:text-sky-400 transition-colors">Blog</a></li>
                        <li><a href="#" class="hover:text-sky-400 transition-colors">Careers</a></li>
                        <li><a href="#" class="hover:text-sky-400 transition-colors">Contact</a></li>
                    </ul>
                </div>
                
                {{-- Support --}}
                <div>
                    <h4 class="text-white font-semibold mb-4">Support</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="hover:text-sky-400 transition-colors">Help Center</a></li>
                        <li><a href="#" class="hover:text-sky-400 transition-colors">Documentation</a></li>
                        <li><a href="#" class="hover:text-sky-400 transition-colors">API Reference</a></li>
                        <li><a href="#" class="hover:text-sky-400 transition-colors">Status</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-slate-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-slate-500 text-sm">© 2024 Clinix. All rights reserved.</p>
                <div class="flex gap-6 text-sm">
                    <a href="#" class="text-slate-500 hover:text-sky-400 transition-colors">Privacy Policy</a>
                    <a href="#" class="text-slate-500 hover:text-sky-400 transition-colors">Terms of Service</a>
                    <a href="#" class="text-slate-500 hover:text-sky-400 transition-colors">HIPAA Compliance</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Initialize Lucide Icons
        lucide.createIcons();
        
        // Mobile Menu Toggle
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }
        
        // Intersection Observer for Scroll Animations
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.1
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                }
            });
        }, observerOptions);
        
        document.querySelectorAll('.reveal').forEach((el) => observer.observe(el));
        
        // Smooth Scroll for Navigation Links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                    // Close mobile menu if open
                    document.getElementById('mobile-menu').classList.add('hidden');
                }
            });
        });
        
        // Navbar Glass Effect on Scroll
        window.addEventListener('scroll', () => {
            const nav = document.querySelector('nav');
            if (window.scrollY > 50) {
                nav.classList.add('shadow-lg');
            } else {
                nav.classList.remove('shadow-lg');
            }
        });
        
        // Parallax effect for floating avatars
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const parallaxElements = document.querySelectorAll('.animate-avatar-1, .animate-avatar-2, .animate-avatar-3');
            parallaxElements.forEach((el, index) => {
                const speed = 0.5 + (index * 0.1);
                el.style.transform = `translateY(${scrolled * speed}px)`;
            });
        });
    </script>
</body>
</html>