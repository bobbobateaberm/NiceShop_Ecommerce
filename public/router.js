// Define a function to load the appropriate HTML page based on the URL path
function loadPage() {
    var path = window.location.pathname;
    if (path === '/admin.html') {
        // Load admin page
        fetch('admin.html')
            .then(response => response.text())
            .then(html => document.getElementById('content').innerHTML = html)
            .catch(error => console.error('Error loading admin page:', error));
    } else {
        // Default to shop page
        fetch('shop.html')
            .then(response => response.text())
            .then(html => document.getElementById('content').innerHTML = html)
            .catch(error => console.error('Error loading shop page:', error));
    }
}

// Load the appropriate page when the page loads
window.addEventListener('DOMContentLoaded', loadPage);