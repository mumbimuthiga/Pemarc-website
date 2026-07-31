document.addEventListener("DOMContentLoaded", function () {
    // Initialize Intersection Observer
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Start animations when the module becomes visible
                startAnimations(entry.target);
                observer.unobserve(entry.target); // Stop observing after triggering
            }
        });
    }, {
        threshold: 0.9 // Trigger when at least 10% of the element is visible
    });

    // Get all animated module containers
    document.querySelectorAll('.jo-animated-progress-module').forEach(container => {
        // Observe each module container
        observer.observe(container);
    });

    /**
     * Function to start animations for a specific module
     */
    function startAnimations(container) {
        const moduleId = container.id.replace('jo-animated-', '');

        // Get module settings from unique ID
        const scriptTagId = 'mod_jo_animated_number_' + moduleId;
        const moduleSettings = Joomla.getOptions(scriptTagId);

        if (!moduleSettings) {
            console.warn(`Module settings not found: ${scriptTagId}`);
            return;
        }

        const targetNumber = moduleSettings.targetNumber;
        const animationType = moduleSettings.animationType;
        const duration = moduleSettings.animationDuration;
        const animationColor = moduleSettings.animationColor;

        // Scoped elements
        const numberElement = container.querySelector('.number-text');
        if (!numberElement) return;

        // Animate Counter
        let startCounter = null;
        function animateCounter(timestamp) {
            if (!startCounter) startCounter = timestamp;
            const elapsed = timestamp - startCounter;
            const percent = Math.min(elapsed / duration, 1);
            const current = Math.floor(percent * targetNumber);

            numberElement.textContent = current;

            if (elapsed < duration) {
                requestAnimationFrame(animateCounter);
            }
        }

        // Pie Chart Logic
        if (animationType === 'circular') {
            const progressCircle = container.querySelector('.progress');
            if (!progressCircle) return;

            const radius = 80;
            const circumference = 2 * Math.PI * radius;
            const cappedPercent = Math.min(targetNumber, 100); // Cap at 100%

            let startPie = null;
            function animatePie(timestamp) {
                if (!startPie) startPie = timestamp;
                const elapsed = timestamp - startPie;
                const percent = Math.min(elapsed / duration, 1);
                const current = percent * cappedPercent;

                const offset = circumference - (current / 100) * circumference;
                progressCircle.style.strokeDashoffset = offset + 'px';

                if (elapsed < duration) {
                    requestAnimationFrame(animatePie);
                }
            }

            requestAnimationFrame(animatePie);
        }

        // Signal Bars Logic
        if (animationType === 'signal') {
            const bars = container.querySelectorAll('.bar');
            const maxLevel = Math.min(Math.ceil(targetNumber / 5), 20); // Scale to 0–20 bars
            let startBars = null;

            function animateBars(timestamp) {
                if (!startBars) startBars = timestamp;
                const elapsed = timestamp - startBars;
                const percent = Math.min(elapsed / duration, 1);
                const animatedBarsCount = Math.floor(percent * maxLevel);

                bars.forEach((bar, index) => {
                    if (index < animatedBarsCount) {
                        bar.style.backgroundColor = animationColor;
                        bar.style.height = `${(index + 1) * 5}%`;
                    }
                });

                if (elapsed < duration) {
                    requestAnimationFrame(animateBars);
                }
            }

            requestAnimationFrame(animateBars);
        }

        // Start counter animation with small delay
        setTimeout(() => {
            requestAnimationFrame(animateCounter);
        }, 50);
    }
});