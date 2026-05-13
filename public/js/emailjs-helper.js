/**
 * EmailJS Helper for Halimaw Siomai
 * Handles client-side email sending to bypass InfinityFree SMTP blocks.
 */

// Placeholder Configuration - PLEASE REPLACE WITH YOUR ACTUAL KEYS
const EMAILJS_CONFIG = {
    PUBLIC_KEY: 'B9Ucc8-vx1IyTvaNV',     // Account > API Keys
    SERVICE_ID: 'service_nqx46id',     // Email Services
    TEMPLATE_ID: 'template_b1fhlhv'    // Email Templates
};

// Initialize EmailJS
(function() {
    if (typeof emailjs !== 'undefined') {
        emailjs.init(EMAILJS_CONFIG.PUBLIC_KEY);
        console.log("EmailJS Initialized");
    }
})();

/**
 * Sends an Order Confirmation or Electronic Receipt email.
 * @param {Object} data - The data to send to the template.
 * @returns {Promise} - Resolves on success, rejects on failure.
 */
async function sendHalimawEmail(data) {
    if (typeof emailjs === 'undefined') {
        console.error("EmailJS SDK not loaded!");
        return;
    }

    if (!EMAILJS_CONFIG.PUBLIC_KEY || EMAILJS_CONFIG.PUBLIC_KEY.includes('YOUR_')) {
        console.warn("EmailJS Keys not configured. Email will not be sent.");
        return;
    }

    try {
        console.log("Sending email with data:", data);
        const response = await emailjs.send(
            EMAILJS_CONFIG.SERVICE_ID,
            EMAILJS_CONFIG.TEMPLATE_ID,
            {
                to_email: data.customer_email, // For templates using {{to_email}}
                email: data.customer_email,    // For templates using {{email}}
                customer_name: data.customer_name,
                order_id: data.order_id,
                order_items: data.order_items_html, // Pass pre-rendered HTML or list
                order_total: data.order_total,
                order_date: new Date().toLocaleString(),
                payment_method: data.payment_method || 'Online Order',
                type: data.type || 'Confirmation' // 'Confirmation' or 'Receipt'
            }
        );
        console.log("Email sent successfully!", response.status, response.text);
        alert("Confirmation email sent to " + data.customer_email);
        return response;
    } catch (error) {
        console.error("Failed to send email via EmailJS:", error);
        alert("Notice: Order placed, but confirmation email failed to send. Error: " + (error.text || error.message || error));
        throw error;
    }
}
