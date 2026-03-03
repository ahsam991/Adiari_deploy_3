<?php
/**
 * Home Controller
 * Handles homepage and general browsing
 */

require_once __DIR__ . '/../core/Controller.php';

class HomeController extends Controller {
    /**
     * Display homepage
     */
    public function index() {
        // Load Product Model
        require_once __DIR__ . '/../models/Product.php';
        $productModel = new Product();

        // Fetch Data
        $featuredProducts = $productModel->getFeaturedProducts(8);
        $newArrivals = $productModel->getActiveProducts(8, 0, 'newest');

        // Fetch Offers (Weekly Deals)
        require_once __DIR__ . '/../models/Offer.php';
        $offerModel = new Offer();
        $offerModel->updateExpiredOffers();
        $activeDeals = $offerModel->getActiveOffers();

        $data = [
            'title' => 'Welcome to ADI ARI Fresh',
            'message' => 'Your source for fresh vegetables and halal food',
            'businessName' => 'ADI ARI FRESH VEGETABLES AND HALAL FOOD',
            'businessAddress' => '114-0031 Higashi Tabata 2-3-1 Otsu building 101', // Correct Address
            'businessPhone' => '080-3408-8044',
            'featuredProducts' => $featuredProducts,
            'newArrivals' => $newArrivals,
            'weeklyDeals' => $activeDeals
        ];

        $this->view('home/index', $data);
    }

    /**
     * About page
     */
    public function about() {
        $data = [
            'title' => 'About Us - ADI ARI Fresh',
        ];

        $this->view('home/about', $data);
    }

    /**
     * Contact page
     */
    public function contact() {
        $data = [
            'title' => 'Contact Us - ADI ARI Fresh',
        ];

        $this->view('home/contact', $data);
    }

    /**
     * Weekly Deals page
     */
    public function deals() {
        require_once __DIR__ . '/../models/Offer.php';
        $offerModel = new Offer();
        
        // Update expired offers
        $offerModel->updateExpiredOffers();
        
        // Get active offers
        $offers = $offerModel->getActiveOffers();
        
        $data = [
            'title' => 'Weekly Deals - ADI ARI Fresh',
            'offers' => $offers
        ];

        $this->view('home/deals', $data);
    }

    /**
     * Set Application Language
     */
    public function setLanguage($lang) {
        if (array_key_exists($lang, Language::available())) {
            Session::set('lang', $lang);
        }
        
        $redirect = $_SERVER['HTTP_REFERER'] ?? '/';
        header('Location: ' . $redirect);
        exit;
    }
}
