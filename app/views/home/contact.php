<?php
/**
 * Contact Page - Mobile Optimized Professional Design
 */
?>

<!-- Hero Section -->
<section class="bg-gradient-to-br from-green-600 to-emerald-600 text-white py-12 sm:py-20">
    <div class="container mx-auto px-3 sm:px-6 text-center">
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-3 sm:mb-4">Get In Touch</h1>
        <p class="text-sm sm:text-lg text-green-50 max-w-2xl mx-auto">We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
    </div>
</section>

<!-- Contact Content -->
<section class="py-8 sm:py-16 bg-gray-50">
    <div class="container mx-auto px-3 sm:px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8">
            
            <!-- Contact Form -->
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-8">
                <h2 class="text-xl sm:text-2xl font-bold mb-4 sm:mb-6">Send Us a Message</h2>
                <form action="<?= $this->url('/contact/submit') ?>" method="POST" class="space-y-3 sm:space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 sm:mb-2">Full Name *</label>
                        <input type="text" name="name" required class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm sm:text-base" placeholder="John Doe">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 sm:mb-2">Email Address *</label>
                        <input type="email" name="email" required class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm sm:text-base" placeholder="john@example.com">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 sm:mb-2">Phone Number</label>
                        <input type="tel" name="phone" class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm sm:text-base" placeholder="+81 90-1234-5678">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 sm:mb-2">Subject *</label>
                        <input type="text" name="subject" required class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm sm:text-base" placeholder="How can we help?">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 sm:mb-2">Message *</label>
                        <textarea name="message" required rows="5" class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent resize-none text-sm sm:text-base" placeholder="Tell us more about your inquiry..."></textarea>
                    </div>
                    
                    <button type="submit" class="w-full bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700 transition-colors flex items-center justify-center gap-2 text-sm sm:text-base">
                        <span>Send Message</span>
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </form>
            </div>
            
            <!-- Contact Information -->
            <div class="space-y-4 sm:space-y-6">
                <!-- Business Hours -->
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-6">
                    <div class="flex items-start gap-3 sm:gap-4">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base sm:text-lg font-bold mb-2">Business Hours</h3>
                            <div class="space-y-1 text-xs sm:text-sm text-gray-600">
                                <p><span class="font-medium">Mon - Fri:</span> 8:00 AM - 8:00 PM</p>
                                <p><span class="font-medium">Saturday:</span> 9:00 AM - 6:00 PM</p>
                                <p><span class="font-medium">Sunday:</span> 10:00 AM - 4:00 PM</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Phone -->
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-6">
                    <div class="flex items-start gap-3 sm:gap-4">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base sm:text-lg font-bold mb-2">Phone</h3>
                            <p class="text-xs sm:text-sm text-gray-600 mb-1">Customer Service</p>
                            <a href="tel:+81-3-1234-5678" class="text-sm sm:text-base text-green-600 hover:text-green-700 font-medium">+81-3-1234-5678</a>
                        </div>
                    </div>
                </div>
                
                <!-- Email -->
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-6">
                    <div class="flex items-start gap-3 sm:gap-4">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base sm:text-lg font-bold mb-2">Email</h3>
                            <p class="text-xs sm:text-sm text-gray-600 mb-1">General Inquiries</p>
                            <a href="mailto:info@adiarifresh.com" class="text-sm sm:text-base text-green-600 hover:text-green-700 font-medium break-all">info@adiarifresh.com</a>
                        </div>
                    </div>
                </div>
                
                <!-- Address -->
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-6">
                    <div class="flex items-start gap-3 sm:gap-4">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base sm:text-lg font-bold mb-2">Location</h3>
                            <p class="text-xs sm:text-sm text-gray-600">2 Chome-3-1 Higashitabata<br>Osu Building 101, Kita City<br>Tokyo 114-0013, Japan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="py-8 sm:py-16 bg-white">
    <div class="container mx-auto px-3 sm:px-6">
        <div class="text-center mb-6 sm:mb-8">
            <h2 class="text-xl sm:text-3xl font-bold mb-2 sm:mb-3">Visit Our Store</h2>
            <p class="text-xs sm:text-base text-gray-600">Come visit us at our physical location in Tokyo</p>
        </div>
        
        <!-- Map Placeholder -->
        <div class="bg-gray-200 rounded-xl sm:rounded-2xl overflow-hidden shadow-lg" style="height: 300px; sm:height: 400px;">
            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-green-100 to-emerald-100">
                <div class="text-center p-4">
                    <svg class="w-12 h-12 sm:w-16 sm:h-16 text-green-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <p class="text-sm sm:text-base text-gray-700 font-medium">2 Chome-3-1 Higashitabata, Osu Building 101, Kita City, Tokyo</p>
                    <a href="https://maps.app.goo.gl/a82sVHcnPMW1FdSKA" target="_blank" class="inline-block mt-3 text-xs sm:text-sm text-green-600 hover:text-green-700 font-semibold">
                        Open in Google Maps →
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-8 sm:py-16 bg-gray-50">
    <div class="container mx-auto px-3 sm:px-6">
        <div class="text-center mb-6 sm:mb-12">
            <h2 class="text-xl sm:text-3xl font-bold mb-2 sm:mb-3">Frequently Asked Questions</h2>
            <p class="text-xs sm:text-base text-gray-600">Quick answers to common questions</p>
        </div>
        
        <div class="max-w-3xl mx-auto space-y-3 sm:space-y-4">
            <div class="bg-white rounded-lg sm:rounded-xl shadow-md p-4 sm:p-6">
                <h3 class="text-sm sm:text-lg font-bold mb-2">What are your delivery areas?</h3>
                <p class="text-xs sm:text-sm text-gray-600">We deliver across Tokyo and surrounding areas. Same-day delivery is available for orders placed before 2 PM.</p>
            </div>
            
            <div class="bg-white rounded-lg sm:rounded-xl shadow-md p-4 sm:p-6">
                <h3 class="text-sm sm:text-lg font-bold mb-2">Are all your products halal certified?</h3>
                <p class="text-xs sm:text-sm text-gray-600">Yes, all our meat products are 100% halal certified by recognized Islamic authorities in Japan.</p>
            </div>
            
            <div class="bg-white rounded-lg sm:rounded-xl shadow-md p-4 sm:p-6">
                <h3 class="text-sm sm:text-lg font-bold mb-2">What payment methods do you accept?</h3>
                <p class="text-xs sm:text-sm text-gray-600">We accept cash on delivery, credit/debit cards, and major digital payment methods including PayPay and LINE Pay.</p>
            </div>
            
            <div class="bg-white rounded-lg sm:rounded-xl shadow-md p-4 sm:p-6">
                <h3 class="text-sm sm:text-lg font-bold mb-2">Can I return products?</h3>
                <p class="text-xs sm:text-sm text-gray-600">We offer a 100% satisfaction guarantee. If you're not happy with your order, contact us within 24 hours for a refund or replacement.</p>
            </div>
        </div>
    </div>
</section>
