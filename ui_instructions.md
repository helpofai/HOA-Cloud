# UI Instructions and Guidelines

This document outlines specific instructions and guidelines for the User Interface (UI) development of the File Sharing and Cloud Storage web application. These instructions build upon the general requirements detailed in `gemini.md`.

## General UI/UX Principles: The "Obsidian Glass" Theme

-   **Modern Aesthetic:** A premium, high-end SaaS aesthetic using a "Midnight" palette.
-   **Color Palette:** Deep charcoals (#121212) and Midnight Blues for dark mode; soft whites and Cloud Grays for light mode.
-   **Glassmorphism Core:** All containers (cards, sidebars, modals) must use `backdrop-filter: blur(12px)` with a semi-transparent border (`rgba(255, 255, 255, 0.1)`).
-   **Responsiveness:** The UI must be fully responsive and optimized for various screen sizes.
-   **Performance:** UI elements should load quickly; use pre-compiled Tailwind CSS for zero runtime overhead.

## Styling and Effects

-   **Animations:** 
    -   Subtle and functional transitions (e.g., 200ms fades).
    -   "Shimmer" effects for loading states and call-to-action buttons.
    -   Gradient mesh backgrounds for the hero section that shift subtly.
-   **Shadows:** 
    -   Strategic use of "Glow" shadows for active elements.
    -   Deep "Outer Shadows" for floating glass panels to create a 3D effect.
    -   "Inner Shadows" for recessed dashboard widgets.
-   **Borders:** 
    -   Clean 1px borders with low opacity (`0.1`).
    -   Gradient borders for highlighted or hovered cards.
-   **Hover Effects:** 
    -   Scale-up effects (`scale-105`) for media posters.
    -   Lifting transitions (`translate-y-[-5px]`) for feature cards.
-   **Popups & Modals:** 
    -   Utilize glassmorphism (blur + semi-transparency).
    -   Entry animation: `scale-95` to `scale-100` with `opacity-0` to `opacity-100`.
-   **Blur Effects:** 
    -   Apply heavy background blur (`blur(50px)`) behind media players using the content's poster art.
    -   Standard `8px` blur for modal backdrops.

## Layout and Navigation

### Home Page Redesign
-   **Hero:** Centered headline with glassy CTA; background animated gradient mesh.
-   **Feature Section:** Grid of lifting glass cards with gradient hover borders.
-   **Pricing:** High-contrast glass cards with "Glow" effects for the "Pro" tier.

### User Dashboard (Media Command Center)
-   **Sidebar:** Slim, fixed glass sidebar; icons only with tooltips or labels.
-   **Main Content Area:** Dynamic updating via Livewire (no full page reload).
-   **File Grid:** Netflix-style posters with rounded corners (12px); "Quick Play" overlay on hover.
-   **Upload Drawer:** Persistent blurred panel in bottom-right; progress bars with "Pulse" animation at 100%.

### Super-Admin Dashboard (The Engine Room)
-   **Analytics Overview:** Data widgets with recessed inner shadows and glowing line charts.
-   **Management UI:** Real-time status indicators with soft "Breathe" animations.
-   **Domain Control:** Centralized glass modal for domain rotation and "Ghost Hop" configuration.

## Interactive Element Guidelines

-   **Buttons:** 
    -   Default: Glassy, thin white border.
    -   Hover: Background opacity increases from `0.1` to `0.2`, border brightens.
-   **Scroll Position Management:** Intelligent resetting/maintaining during Livewire transitions.
-   **URL Updates:** History API (`pushState`) usage for all dashboard sections.

## Specific Component Guidelines

### Media-First User File Manager
- **Netflix-Style Visual Grid:** Use large posters for movies and waveform art for music.
- **Virtual Folder System:** Drag-and-drop support with glassy folder icons.
- **Context Menu Engine:** Right-click menus with glass blur and subtle dividers.

### Media Player Suite
- **Video Player (Netflix Style):** Custom Plyr/Video.js skin; dynamic Blob URL source.
- **Audio Player (Spotify Style):** Persistent mini-player; Wavesurfer.js integration.
- **Anti-Scraping:** Invisible overlays and right-click blocking.

## Accessibility
- WCAG compliance: keyboard navigation, ARIA attributes, sufficient contrast on glass backgrounds.

## Naming Conventions for UI-Related Files
- Pattern: `[Feature][Component]Component.blade.php`.
- Feature-wise CSS files (e.g., `file-manager.css`).

---
_Refer to `gemini.md` for backend architecture and security requirements._