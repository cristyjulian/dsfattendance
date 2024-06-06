document.addEventListener('DOMContentLoaded', function() {
    // Query all elements with the class 'has-submenu'
    var dropdowns = document.querySelectorAll('.has-submenu');

    // Attach a click event listener to each dropdown
    dropdowns.forEach(function(dropdown) {
        dropdown.addEventListener('click', function(event) {
            // This line prevents the link from navigating anywhere
            event.preventDefault();
            
            // Close any other open sub-menus
            dropdowns.forEach(function(d) {
                if (d !== dropdown) {
                    d.classList.remove('active');
                }
            });
            
            // Toggle 'active' class on the current dropdown
            dropdown.classList.toggle('active');
        });
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.has-submenu')) {
            dropdowns.forEach(function(d) {
                d.classList.remove('active');
            });
        }
    });
});
