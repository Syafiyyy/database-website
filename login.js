document.querySelector('.login-btn').addEventListener('click', async () => {
    const email = document.getElementById('login-email').value.trim();
    const password = document.getElementById('login-password').value.trim();

    // Log input for debugging
    console.log("Email:", email);
    console.log("Password:", password);

    // Validate input
    if (!email || !password) {
        alert('Please enter both email and password');
        return;
    }

    try {
        // Send login request to the backend
        const response = await fetch('/backend/user_login.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email, password }),
        });

        const result = await response.json();

        if (result.status === 'success') {
            alert(result.message);
            // Redirect based on role
            window.location.href = result.redirect;
        } else {
            alert(result.message); // Display error message
        }
    } catch (error) {
        console.error('Error logging in:', error);
        alert('An error occurred while logging in. Please try again later.');
    }
});
