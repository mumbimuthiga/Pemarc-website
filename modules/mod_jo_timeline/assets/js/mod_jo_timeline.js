document.addEventListener("DOMContentLoaded", function () {
    // Zigzag2 Animation for Timeline Items
    const zigzag2Items = document.querySelectorAll(".timeline-item.zigzag2");

    if (zigzag2Items.length > 0) {
        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add("show");
                        observer.unobserve(entry.target); // Stop observing once the item is visible
                    }
                });
            },
            { threshold: 0.2 } // Trigger when 20% of the item is visible
        );

        zigzag2Items.forEach((item) => {
            observer.observe(item);
        });
    }

    // Dynamic Progress Bar for Progress Timeline
    const timeline = document.querySelector(".jo-timeline.progress-bar");
    if (!timeline) return;

    const progressFill = document.createElement("div");
    progressFill.classList.add("progress-fill");
    timeline.appendChild(progressFill);

    const timelineItems = Array.from(document.querySelectorAll(".timeline-item.progress-bar"));
    if (!timelineItems.length) return;

    // Calculate the top positions of all timeline items
    const itemPositions = timelineItems.map((item) => {
        const rect = item.getBoundingClientRect();
        return window.scrollY + rect.top; // Absolute top position of each item
    });

    // Calculate the total height of the timeline container
    const timelineTop = timeline.offsetTop; // Top of the timeline container
    const timelineHeight = timeline.offsetHeight; // Total height of the timeline container

    let isScrolling;
    window.addEventListener("scroll", () => {
        clearTimeout(isScrolling);
        isScrolling = setTimeout(() => {
            const scrollPosition = window.scrollY + window.innerHeight / 2; // Center of the viewport
            let progress = 0;

            // Find the current item that is being scrolled into view
            for (let i = 0; i < itemPositions.length; i++) {
                const currentItemTop = itemPositions[i];
                const nextItemTop =
                    itemPositions[i + 1] ||
                    timelineTop + timelineHeight; // Handle the last item (extend to the end of the timeline)

                if (scrollPosition >= currentItemTop && scrollPosition < nextItemTop) {
                    // Calculate progress within the current item
                    const itemHeight = timelineItems[i].offsetHeight;
                    const scrolledWithinItem = Math.min(scrollPosition - currentItemTop, itemHeight);
                    progress = ((i + scrolledWithinItem / itemHeight) / timelineItems.length) * 100;
                    break;
                }
            }

            // If scrolled past the last item, calculate progress based on the remaining timeline height
            if (scrollPosition >= timelineTop + timelineHeight) {
                progress = 100; // Fully scrolled to the bottom
            } else if (scrollPosition > itemPositions[itemPositions.length - 1]) {
                // Smoothly transition to the end of the timeline after the last item
                const lastItemTop = itemPositions[itemPositions.length - 1];
                const lastItemHeight = timelineItems[timelineItems.length - 1].offsetHeight;
                const remainingHeight = timelineHeight - (lastItemTop - timelineTop + lastItemHeight);
                const scrolledAfterLastItem = Math.max(0, scrollPosition - (lastItemTop + lastItemHeight));
                progress =
                    ((itemPositions.length + scrolledAfterLastItem / remainingHeight) / timelineItems.length) * 100;
            }

            // Ensure progress doesn't exceed 100%
            const progressHeight = Math.min(progress, 100);
            progressFill.style.height = `${progressHeight}%`; // Update the progress fill height
        }, 100); // Throttle to 100ms
    });
});