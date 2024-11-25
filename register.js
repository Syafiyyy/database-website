document.querySelector('.register-btn').addEventListener('click', async (event) => {
    event.preventDefault(); // Prevent the default form submission

    const username = document.getElementById('username').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm-password').value;

    // Basic validation
    if (!username || !email || !password || !confirmPassword) {
        alert('Please fill in all fields.');
        return;
    }
    if (password !== confirmPassword) {
        alert('Passwords do not match.');
        return;
    }

    // Log the data to the console before sending it
    console.log("Sending data:", { username, email, password });

    // Send the data to the backend
    try {
        const response = await fetch('/backend/user_register.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ username, email, password })
        });

        const result = await response.json();
        console.log(result); // Log the result from the server

        if (result.status === 'success') {
            alert('Registration successful!');
            window.location.href = 'index.html'; // Redirect to index.html
        } else {
            alert(result.message); // Show the error message from the backend
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    }
});
