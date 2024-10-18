// common.js

(function () {
    // Helper to get URL parameters
    function getUrlParameter(name) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name);
    }

    // Function to generate a CSRF token if needed
    function generateToken(length = 32) {
        const array = new Uint8Array(length);
        window.crypto.getRandomValues(array);
        return Array.from(array, dec => dec.toString(16).padStart(2, '0')).join('');
    }

    // Function to get or generate CSRF token
    function getCsrfToken() {
        let csrfToken = localStorage.getItem('csrfToken');
        if (!csrfToken) {
            csrfToken = generateToken();
            localStorage.setItem('csrfToken', csrfToken);
        }
        return csrfToken;
    }

    // Function to assign 50% of users to a variant
    function assignVariant() {
        let variant = localStorage.getItem('abTestVariant');
        if (!variant) {
            variant = Math.random() < 0.5 ? 'original' : 'variant';
            localStorage.setItem('abTestVariant', variant);
        }
        return variant;
    }

    // Function to track events with API
    function trackEvent(testName, variant, event) {
        fetch('/?wp-staging-ab-tests=track', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Api-Key': '123456', // Replace with actual API key
                'X-CSRF-Token': getCsrfToken() // This token is currently not used.
            },
            body: JSON.stringify({ test: testName, variant: variant, event: event })
        })
            .then(response => response.json())
            .then(data => console.log(data))
            .catch(error => console.error('Error:', error));
    }

    window.ABTestCommon = {
        getUrlParameter,
        assignVariant,
        trackEvent
    };
})();
