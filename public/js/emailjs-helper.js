/**
 * EmailJS Helper for Halimaw Siomai
 * Handles client-side email sending to bypass InfinityFree SMTP blocks.
 * Supports TWO templates: Online Order Confirmation & POS E-Receipt.
 */

// Configuration — Replace TEMPLATE IDs with your actual ones from EmailJS Dashboard
const EMAILJS_CONFIG = {
    PUBLIC_KEY: 'B9Ucc8-vx1IyTvaNV',
    SERVICE_ID: 'service_nqx46id',
    // Template for Online Order Confirmation (guest customers)
    TEMPLATE_ORDER_CONFIRMATION: 'template_order_confirm',
    // Template for POS E-Receipt (admin/staff transactions)
    TEMPLATE_POS_RECEIPT: 'template_pos_receipt'
};

// Initialize EmailJS
(function() {
    if (typeof emailjs !== 'undefined') {
        emailjs.init(EMAILJS_CONFIG.PUBLIC_KEY);
        console.log("EmailJS Initialized");
    }
})();

/**
 * Sends an Online Order Confirmation email to a guest customer.
 * Called from the customer ordering page after placing an order.
 * @param {Object} data - { customer_email, customer_name, order_id, order_items_html, order_total }
 */
async function sendOrderConfirmation(data) {
    if (typeof emailjs === 'undefined') {
        console.error("EmailJS SDK not loaded!");
        return;
    }

    try {
        console.log("Sending Order Confirmation email:", data);
        const response = await emailjs.send(
            EMAILJS_CONFIG.SERVICE_ID,
            EMAILJS_CONFIG.TEMPLATE_ORDER_CONFIRMATION,
            {
                to_email: data.customer_email,
                email: data.customer_email,
                customer_name: data.customer_name || 'Valued Customer',
                order_id: data.order_id,
                order_items: data.order_items_html,
                order_total: data.order_total,
                order_date: new Date().toLocaleString('en-PH', {
                    year: 'numeric', month: 'long', day: 'numeric',
                    hour: '2-digit', minute: '2-digit', hour12: true
                })
            }
        );
        console.log("Order Confirmation email sent!", response.status, response.text);
        return response;
    } catch (error) {
        console.error("Failed to send Order Confirmation email:", error);
        throw error;
    }
}

/**
 * Sends a POS E-Receipt email after a transaction is completed.
 * Called from the Admin/Staff POS after a sale is processed.
 * @param {Object} data - { customer_email, customer_name, transaction_id, order_items_html, order_total, payment_method, cashier_name }
 */
async function sendPosReceipt(data) {
    if (typeof emailjs === 'undefined') {
        console.error("EmailJS SDK not loaded!");
        return;
    }

    try {
        console.log("Sending POS E-Receipt email:", data);
        const response = await emailjs.send(
            EMAILJS_CONFIG.SERVICE_ID,
            EMAILJS_CONFIG.TEMPLATE_POS_RECEIPT,
            {
                to_email: data.customer_email,
                email: data.customer_email,
                customer_name: data.customer_name || 'Valued Customer',
                transaction_id: data.transaction_id,
                order_items: data.order_items_html,
                order_total: data.order_total,
                payment_method: data.payment_method || 'Cash',
                cashier_name: data.cashier_name || 'Staff',
                order_date: new Date().toLocaleString('en-PH', {
                    year: 'numeric', month: 'long', day: 'numeric',
                    hour: '2-digit', minute: '2-digit', hour12: true
                })
            }
        );
        console.log("POS E-Receipt email sent!", response.status, response.text);
        return response;
    } catch (error) {
        console.error("Failed to send POS E-Receipt email:", error);
        throw error;
    }
}

/**
 * Legacy wrapper — keeps old calls working.
 * Routes to the correct template based on data.type.
 */
async function sendHalimawEmail(data) {
    if (data.type === 'Receipt') {
        return sendPosReceipt({
            customer_email: data.customer_email,
            customer_name: data.customer_name,
            transaction_id: data.order_id,
            order_items_html: data.order_items_html,
            order_total: data.order_total,
            payment_method: data.payment_method,
            cashier_name: data.cashier_name
        });
    } else {
        return sendOrderConfirmation({
            customer_email: data.customer_email,
            customer_name: data.customer_name,
            order_id: data.order_id,
            order_items_html: data.order_items_html,
            order_total: data.order_total
        });
    }
}
