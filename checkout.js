document.addEventListener('DOMContentLoaded', () => {
    const subtotalElement = document.querySelector('.amount.subtotal');
    const discountElement = document.querySelector('.amount.discount');
    const totalElement = document.querySelector('.amount.total');
    const receiptSection = document.getElementById('receiptSection');
    const receiptDetails = document.getElementById('receiptDetails');
    const paymentForm = document.getElementById('paymentForm');
    const cartBadge = document.getElementById("cartBadge");

    let cartItems = [];
    let subtotal = 0;
    let discount = 0;
    let total = 0;

    async function fetchCartItems() {
        const response = await fetch('/backend/get_cart.php');
        const result = await response.json();
        if (result.status === "success") {
            return result.data;
        }
        return [];
    }

    async function updateCheckout() {
        cartItems = await fetchCartItems();

        // Calculate subtotal
        subtotal = cartItems.reduce((sum, item) => sum + item.product_price * item.quantity, 0);

        // Apply discount (20%)
        discount = subtotal * 0.2;

        // Calculate total
        total = subtotal - discount;

        // Update UI
        subtotalElement.textContent = `RM ${subtotal.toFixed(2)}`;
        discountElement.textContent = `RM ${discount.toFixed(2)}`;
        totalElement.textContent = `RM ${total.toFixed(2)}`;
    }

    async function processCheckout(paymentMethod) {
        const response = await fetch('/backend/process_checkout.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                total_price: total,
                cart_items: cartItems,
                payment_method: paymentMethod,
                discount: discount
            }),
        });

        const result = await response.json();
        return result;
    }

    paymentForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const paymentMethod = document.querySelector('input[name="payment"]:checked').id;

        const result = await processCheckout(paymentMethod);

        if (result.status === "success") {
            receiptDetails.innerHTML = `
                <p><strong>Total Paid:</strong> RM ${total.toFixed(2)}</p>
                <p><strong>Discount:</strong> RM ${discount.toFixed(2)}</p>
                <p><strong>Payment Method:</strong> ${paymentMethod}</p>
            `;
            receiptSection.style.display = 'block';
            document.querySelector('.checkout-section').style.display = 'none';
        } else {
            alert("Checkout failed: " + result.message);
        }
    });

    updateCheckout();
});
