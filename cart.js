document.addEventListener("DOMContentLoaded", () => {
  const cartContainer = document.getElementById("cartContainer");
  const totalContainer = document.querySelector(".total-amount");
  const resetCartBtn = document.getElementById("resetCartBtn");
  const checkoutButton = document.getElementById("checkoutButton");
  const continueShoppingButton = document.getElementById("continueShoppingButton");
  const cartBadge = document.getElementById("cartBadge");

  // Fetch and render cart items
  async function fetchCart() {
    try {
      const response = await fetch("/backend/get_cart.php");
      const result = await response.json();

      if (result.status === "success") {
        renderCartItems(result.data, result.total_price);
      } else {
        cartContainer.innerHTML = `<p>${result.message}</p>`;
        totalContainer.textContent = "RM 0.00";
        cartBadge.textContent = "0";
      }
    } catch (error) {
      console.error("Error fetching cart:", error);
    }
  }

  // Render cart items in the HTML
  function renderCartItems(cartItems, totalPrice) {
    cartContainer.innerHTML = ""; // Clear existing items

    if (!cartItems || cartItems.length === 0) {
      cartContainer.innerHTML = "<p>Your cart is empty.</p>";
      totalContainer.textContent = "RM 0.00";
      cartBadge.textContent = "0";
      return;
    }

    cartItems.forEach((item) => {
      const productName = item.product_name || "Unknown Item";
      const productPrice = item.product_price !== undefined ? item.product_price : 0;
      const quantity = item.quantity || 0;

      const itemElement = document.createElement("div");
      itemElement.classList.add("cart-item");
      itemElement.innerHTML = `
        <div class="cart-item-details">
          <h4>${productName}</h4>
          <p>Price: RM ${parseFloat(productPrice).toFixed(2)}</p>
          <p>Quantity: ${quantity}</p>
        </div>
        <div class="cart-item-actions">
          <button class="increase-quantity" data-product-id="${item.product_id}">+</button>
          <button class="decrease-quantity" data-product-id="${item.product_id}">-</button>
          <button class="remove-btn" data-product-id="${item.product_id}">Remove</button>
        </div>
      `;
      cartContainer.appendChild(itemElement);
    });

    // Update total price
    totalContainer.textContent = `RM ${parseFloat(totalPrice).toFixed(2)}`;
    updateCartBadge(cartItems);
  }

  // Update the cart badge
  function updateCartBadge(cartItems) {
    const totalQuantity = cartItems.reduce((sum, item) => sum + (item.quantity || 0), 0);
    cartBadge.textContent = totalQuantity > 0 ? totalQuantity : "0";
  }

  // Handle cart actions (increase, decrease, remove)
  async function updateCartAction(productId, action) {
    try {
      const response = await fetch("/backend/update_cart.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ product_id: productId, action }),
      });

      const result = await response.json();
      if (result.status === "success") {
        fetchCart(); // Refresh the cart after the action
      } else {
        alert(result.message);
      }
    } catch (error) {
      console.error("Error updating cart:", error);
    }
  }

  // Event listener for cart actions
  cartContainer.addEventListener("click", (e) => {
    const button = e.target;
    if (button.classList.contains("increase-quantity")) {
      const productId = button.dataset.productId;
      updateCartAction(productId, "increase");
    } else if (button.classList.contains("decrease-quantity")) {
      const productId = button.dataset.productId;
      updateCartAction(productId, "decrease");
    } else if (button.classList.contains("remove-btn")) {
      const productId = button.dataset.productId;
      updateCartAction(productId, "remove");
    }
  });

  // Reset the cart
  async function resetCart() {
    if (confirm("Are you sure you want to remove all items from your cart?")) {
      try {
        const response = await fetch("/backend/reset_cart.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
        });
        const result = await response.json();

        if (result.status === "success") {
          fetchCart(); // Clear the cart UI
        } else {
          alert(result.message);
        }
      } catch (error) {
        console.error("Error resetting cart:", error);
      }
    }
  }

  // Event listener for reset cart button
  if (resetCartBtn) {
    resetCartBtn.addEventListener("click", resetCart);
  }

  // Redirect to checkout
  if (checkoutButton) {
    checkoutButton.addEventListener("click", () => {
      window.location.href = "checkout.html";
    });
  }

  // Event listener for Continue Shopping button
  if (continueShoppingButton) {
    continueShoppingButton.addEventListener("click", () => {
      window.location.href = "index.html";
    });
  }

  // Initialize cart on page load
  fetchCart();
});
