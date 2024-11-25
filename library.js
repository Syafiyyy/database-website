document.addEventListener('DOMContentLoaded', () => {
    const libraryContainer = document.getElementById('libraryContainer');
    const resetLibraryButton = document.getElementById('resetLibraryButton');
    const downloadButton = document.getElementById('DownloadButton');

    // Fetch purchased items from the backend
    async function fetchLibraryItems() {
        try {
            const response = await fetch('/backend/get_library.php');
            const result = await response.json();

            if (result.status === 'success') {
                return result.data;
            } else {
                alert(result.message);
                return [];
            }
        } catch (error) {
            console.error('Error fetching library items:', error);
            return [];
        }
    }

    // Function to render library items
    function renderLibrary(items) {
        libraryContainer.innerHTML = ''; // Clear the container before rendering

        if (!items || items.length === 0) {
            libraryContainer.innerHTML = `<p>No items found in your library.</p>`;
        } else {
            items.forEach((item) => {
                const itemDiv = document.createElement('div');
                itemDiv.classList.add('library-item');
                itemDiv.innerHTML = `
                    <div class="item-details">
                        <img src="${item.image}" alt="${item.product_name}" class="item-image" />
                        <h3>${item.product_name}</h3>
                        <p>Price: RM ${parseFloat(item.product_price).toFixed(2)}</p>
                    </div>
                    <div class="item-actions">
                        <a href="${item.url}" class="download-btn" target="_blank">Download</a>
                    </div>
                `;
                libraryContainer.appendChild(itemDiv);
            });
        }
    }

    // Function to reset the library
    async function resetLibrary() {
        if (confirm('Are you sure you want to reset your library?')) {
            try {
                const response = await fetch('/backend/reset_library.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ user_id: 1 }) // Replace with logged-in user ID
                });

                const result = await response.json();

                if (result.status === 'success') {
                    renderLibrary([]); // Clear the library UI
                    alert('Library has been reset successfully.');
                } else {
                    alert(result.message);
                }
            } catch (error) {
                console.error('Error resetting library:', error);
            }
        }
    }

    // Event listener for reset library button
    if (resetLibraryButton) {
        resetLibraryButton.addEventListener('click', resetLibrary);
    }

    // Event listener for download button
    if (downloadButton) {
        downloadButton.addEventListener('click', () => {
            alert('Downloading your purchased items...');
        });
    }

    // Initialize library on page load
    async function initializeLibrary() {
        const items = await fetchLibraryItems();
        renderLibrary(items);
    }

    initializeLibrary();
});
