document.addEventListener('DOMContentLoaded', function() {
    var faqPages = document.querySelectorAll('.faq-page');

    faqPages.forEach(function(faqPage) {
        // Add 'active' class to toggle visibility on click
        faqPage.classList.add('active');

        // Hide faq-body initially
        var faqBody = faqPage.nextElementSibling;
        faqBody.classList.add('hidden');

        faqPage.addEventListener('click', function() {
            // Toggle 'active' class on click
            this.classList.toggle('active');

            // Toggle 'hidden' class on faq-body
            var body = this.nextElementSibling;
            body.classList.toggle('hidden');
        });
    });
});