// test1-bounce-rate.js

(function () {
    const variant = ABTestCommon.assignVariant(); // Split users 50:50
    const testName = 'bounce-rate-test';

    // Modify strings for variant
    if (variant === 'variant') {
        document.querySelector('h1').textContent = 'New Variant Heading'; // Example string modification
        document.querySelector('p').textContent = 'This is the variant text.'; // Example string modification
    }

    // Detect bounce (if user doesn't scroll or interact for 30 seconds)
    let hasInteracted = false;
    document.addEventListener('scroll', () => hasInteracted = true);
    document.addEventListener('click', () => hasInteracted = true);

    setTimeout(() => {
        if (!hasInteracted) {
            ABTestCommon.trackEvent(testName, variant, 'bounce');
        }
    }, 30000); // 30 seconds inactivity threshold

    // Track page visit
    ABTestCommon.trackEvent(testName, variant, 'page_visit');
})();
