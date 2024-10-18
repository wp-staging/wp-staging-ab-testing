// test2-user-flow.js

(function () {
    const variant = ABTestCommon.assignVariant(); // Split users 50:50
    const testName = 'user-flow-test';

    // Modify strings for variant
    if (variant === 'variant') {
        document.querySelector('h1').textContent = 'Variant A/B Test Heading';
        document.querySelector('p').textContent = 'This is the text for the variant flow.';
        console.log('Variant delivered');
    }

    // Track page visit (site A or site B)
    const currentPage = window.location.pathname;
    console.log('Current page:', currentPage);
    const pageA = '/'; // Change to actual page A URL
    const pageB = '/checkout'; // Change to actual page B URL

    if (currentPage === pageA) {
        ABTestCommon.trackEvent(testName, variant, 'visited_page_a');
        localStorage.setItem('visitedPageA', true); // Mark that user visited page A
        console.log('Visited home page');
    }

    if (currentPage === pageB && localStorage.getItem('visitedPageA')) {
        ABTestCommon.trackEvent(testName, variant, 'visited_page_b_after_a');
        console.log('Visited checkout page after visiting home page');
    }
})();
