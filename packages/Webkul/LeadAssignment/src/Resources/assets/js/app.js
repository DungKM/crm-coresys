/**
 * This will track all the images and fonts for publishing.
 */
import.meta.glob(["../images/**", "../fonts/**"]);

/**
 * Import component from .js file
 */
import LeadAssignmentSettings from "./components/LeadAssignmentSettings.js";

/**
 * Register LeadAssignmentSettings component to the global Vue app (window.app)
 * This follows Krayin's pattern of using a single Vue app instance
 */
if (window.app) {
    console.log("[LeadAssignment] Registering component to window.app");
    window.app.component("lead-assignment-settings", LeadAssignmentSettings);
} else {
    console.warn(
        "[LeadAssignment] window.app not found, component registration delayed"
    );
}
