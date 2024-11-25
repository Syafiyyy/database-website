window.addEventListener("DOMContentLoaded", () => {
  const cartBadge = document.getElementById("cartBadge"); // Badge to display the cart item count
  console.log("Cart Badge Element:", cartBadge);
  const productsContainer = document.getElementById("productsContainer"); // Container for dynamically rendering products

  async function fetchProducts() {
    try {
      const response = await fetch("../backend/get_product.php");
      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }
      const result = await response.json();

      if (result.status === "success") {
        console.log("Fetched product:", result.data);
        renderProducts(result.data);
      } else {
        console.error("Error fetching product:", result.message);
      }
    } catch (error) {
      console.error("Error fetching product:", error);
    }
  }

  function renderProducts(products) {
    const productsContainer = document.getElementById("productsContainer");
    productsContainer.innerHTML = ""; // Clear existing products

    products.forEach((product) => {
      const productElement = document.createElement("div");
      productElement.classList.add("product-item");

      productElement.innerHTML = `
            <figure>
                <img src="data:image/jpeg;base64,${product.image}" alt="${product.name}" />
            </figure>
            <div class="product-info">
                <h3>${product.name}</h3>
                <p>Price: RM ${product.price}</p>
                <p>Category: ${product.category}</p>
                <p>Rating: ${product.rating} ⭐</p>
                <p>Release Date: ${product.release_date}</p>
                <button 
                    class="add-to-cart-btn" 
                    data-product-id="${product.id}" 
                    data-product-name="${product.name}" 
                    data-product-price="${product.price}">
                    Buy Now
                </button>
            </div>
        `;

      productsContainer.appendChild(productElement);
    });
  }
  
  // Add event listener for "Buy Now" buttons
  document.addEventListener("click", (event) => {
    if (event.target.classList.contains("add-to-cart-btn")) {
      const button = event.target;
      const productId = button.getAttribute("data-product-id");
      const productName = button.getAttribute("data-product-name");
      const productPrice = button.getAttribute("data-product-price");

      console.log("Product Data:", { productId, productName, productPrice });

      addToCart(productId, productName, productPrice);
    }
  });

  // Function to update the cart badge with the total item count
  async function updateCartBadge() {
    try {
      // Fetch cart data from the backend
      const response = await fetch("../backend/get_cart.php");
  
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
  
      const result = await response.json();
      console.log("Cart API Result:", result); // Debugging response
  
      if (result.status === "success") {
        const totalQuantity = result.total_quantity || 0; // Use total_quantity from response
        console.log("Total Quantity in Cart:", totalQuantity);
  
        // Update the cart badge
        cartBadge.textContent = totalQuantity > 0 ? totalQuantity : ""; // Hide badge if 0
      } else {
        console.error("Error: Failed to fetch cart data", result.message);
      }
    } catch (error) {
      console.error("Error updating cart badge:", error);
    }
  }

 // Update cart badge on page load
document.addEventListener("DOMContentLoaded", () => {
  console.log("DOM fully loaded, updating cart badge...");
  updateCartBadge();
});
  
    // Add click event listener for "Add to Cart" buttons
    document.addEventListener("click", async (event) => {
      if (event.target.classList.contains("add-to-cart-btn")) {
        const button = event.target;
  
        // Extract product details
        const productId = button.getAttribute("data-product-id");
        const productElement = button.closest(".col");
        const productName = productElement
          .querySelector(".heading-three")
          .textContent.trim();
        const productPrice = parseFloat(
          productElement.querySelector(".show-price").textContent.replace("RM ", "")
        );
  
        console.log(
          "Product ID:",
          productId,
          "Product Name:",
          productName,
          "Product Price:",
          productPrice
        );
  
        // Send product details to the backend
        try {
          const response = await fetch("../backend/add_to_cart.php", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify({
              product_id: productId,
              product_name: productName,
              product_price: productPrice,
              quantity: 1, // Default to 1
            }),
          });
  
          const data = await response.json();
          if (data.status === "success") {
            alert("Item added to cart successfully!");
            updateCartBadge(); // Refresh the cart badge after adding to cart
          } else {
            alert("Error: " + data.message);
          }
        } catch (error) {
          console.error("Error adding item to cart:", error);
        }
      }
    });

  async function initializeProducts() {
    const products = await fetchProducts(); // Fetch products from the backend
    console.log("Products fetched:", products); // Log the fetched products

    if (products && products.status === "success") {
      renderProducts(products.data); // Render fetched products
    } else {
      console.error("Failed to load products."); // Log error if products fail to load
    }
    updateCartBadge(); // Update the cart badge
  }

  document.addEventListener("DOMContentLoaded", () => {
    console.log("DOM Loaded, updating cart badge...");
    updateCartBadge();
});


  // Add click event listener for each game image to go to respective checkout page
  document
    .querySelector('img[src="/static/photo/fc24.jpg"]')
    .addEventListener("click", () => {
      window.location.href = "fc25.html";
    });

  document
    .querySelector('img[src="/static/photo/wukong.jpg"]')
    .addEventListener("click", () => {
      window.location.href = "wukong.html";
    });

  document
    .querySelector('img[src="/static/photo/Reseident Evil.jpg"]')
    .addEventListener("click", () => {
      window.location.href = "resident.html";
    });

  document
    .querySelector('img[src="/static/photo/gta5.jpg"]')
    .addEventListener("click", () => {
      window.location.href = "gta.html";
    });

  document
    .querySelector('img[src="/static/photo/Dota2.jpg"]')
    .addEventListener("click", () => {
      window.location.href = "Dota2.html";
    });

  document
    .querySelector('img[src="/static/photo/hogwarts.jpg"]')
    .addEventListener("click", () => {
      window.location.href = "hogwarts.html";
    });

  document
    .querySelector('img[src="/static/photo/takken 8.jpg"]')
    .addEventListener("click", () => {
      window.location.href = "takken8..html";
    });

  document
    .querySelector('img[src="/static/photo/PROJECT ZOMBOID.jpg"]')
    .addEventListener("click", () => {
      window.location.href = "PROJECTZOMBOID.html";
    });

  document
    .querySelector('img[src="/static/photo/sims4.jpg"]')
    .addEventListener("click", () => {
      window.location.href = "sims4.html";
    });

  document
    .querySelector('img[src="/static/photo/Read Dead.jpg"]')
    .addEventListener("click", () => {
      window.location.href = "ReadDead.html";
    });

  document
    .querySelector('img[src="/static/photo/2k 25.jpg"]')
    .addEventListener("click", () => {
      window.location.href = "NBA.html";
    });

  document
    .querySelector('img[src="/static/photo/RAFT.jpg"]')
    .addEventListener("click", () => {
      window.location.href = "RAFT.html";
    });

  document
    .querySelector('img[src="/static/photo/Among Us.jpg"]')
    .addEventListener("click", () => {
      window.location.href = "AMONGUS.html";
    });

  document
    .querySelector('img[src="/static/photo/Elden Ring.jpg"]')
    .addEventListener("click", () => {
      window.location.href = "EldenRing.html";
    });

  document
    .querySelector('img[src="/static/photo/Spider.jpg"]')
    .addEventListener("click", () => {
      window.location.href = "Spider.html";
    });

  document
    .querySelector('img[src="/static/photo/APEX.jpg"]')
    .addEventListener("click", () => {
      window.location.href = "APEX.html";
    });

  document
    .querySelector('img[src="/static/photo/Horizon.jpg"]')
    .addEventListener("click", () => {
      window.location.href = "Horizon.html";
    });

  document
    .querySelector('img[src="/static/photo/Subnatica.jpg"]')
    .addEventListener("click", () => {
      window.location.href = "Subnatica.html";
    });

  document
    .querySelector('img[src="/static/photo/God of War.jpg"]')
    .addEventListener("click", () => {
      window.location.href = "GodofWar.html";
    });

  document
    .querySelector('img[src="/static/photo/Big Ambitions.jpg"]')
    .addEventListener("click", () => {
      window.location.href = "Big Ambitions.html";
    });
});
