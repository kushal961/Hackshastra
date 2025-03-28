document.getElementById("team-request-form").addEventListener("submit", function(event) {
    event.preventDefault(); // Prevent page reload on form submit
    
    // Get the form data
    const teamName = document.getElementById("teamName").value;
    const description = document.getElementById("description").value;

    if (teamName && description) {
        // For now, just log the data to the console
        console.log("Team Request Created:");
        console.log("Team Name: " + teamName);
        console.log("Description: " + description);
    }
});


// JavaScript to toggle open state for FAQ items
document.querySelectorAll('.faq-card').forEach(card => {
    card.addEventListener('click', () => {
        card.classList.toggle('open');
    });
});



